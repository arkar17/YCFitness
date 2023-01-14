<?php

namespace App\Http\Controllers;

use File;
use stdClass;
use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\Chat;
use App\Models\Post;
use App\Models\User;
use App\Models\Report;
use App\Models\BanWord;
use App\Models\Comment;
use App\Models\Profile;
use App\Events\Chatting;
use App\Models\ShopPost;
use App\Models\ChatGroup;
use App\Models\ShopReact;
use App\Models\Friendship;
use App\Models\ShopMember;
use App\Models\ShopRating;
use App\Models\NotiFriends;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Events\MessageDelete;
use App\Models\UserReactPost;
use App\Models\UserSavedPost;
use App\Models\ChatGroupMember;
use App\Models\ChatGroupMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use RealRashid\SweetAlert\Facades\Alert;

class SocialmediaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $user_id = $user->id;
        $friends = DB::table('friendships')
            ->where('friend_status', 2)
            ->where(function ($query) use ($user_id) {
                $query->where('sender_id', $user_id)
                    ->orWhere('receiver_id', $user_id);
            })
            ->get(['sender_id', 'receiver_id'])->toArray();

        if (!empty($friends)) {
            $n = array();
            foreach ($friends as $friend) {
                $f = (array)$friend;
                array_push($n, $f['sender_id'], $f['receiver_id']);
            }
            $posts = Post::whereIn('user_id', $n)
                ->where('report_status', 0)
                ->where('shop_status',0)
                ->orderBy('created_at', 'DESC')
                ->with('user')
                ->paginate(30);
        } else {
            $n = array();
            $posts = Post::where('user_id', $user->id)
                ->where('report_status', 0)
                ->where('shop_status',0)
                ->orderBy('created_at', 'DESC')
                ->with('user')
                ->paginate(30);
        }

        // $postdata ='Testing local storage';
        // $date =Carbon::now();
        // Storage::disk('local')->put('test/test.txt', $postdata);

        return view('customer.socialmedia', compact('posts'));
    }

    public function latest_messages()
    {
        $user_id = auth()->user()->id;

        $messages = DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date, chats.from_user_id as from_id,chats.to_user_id as to_id
            from
                chats
            join
                (select user, max(created_at) m
                    from
                    (
                        (select id, to_user_id user, created_at
                        from chats
                        where from_user_id= $user_id  and delete_status <> 2 and deleted_by != $user_id)
                    union
                        (select id, from_user_id user, created_at
                        from chats
                        where to_user_id= $user_id and delete_status <> 2 and deleted_by != $user_id)
                        ) t1
                group by user) t2
                    on ((from_user_id= $user_id and to_user_id=user) or
                        (from_user_id=user and to_user_id= $user_id)) and
                        (created_at = m)
                    left join users on users.id = user
                    left join profiles on users.profile_id = profiles.id
                    order by chats.created_at desc");
// dd($messages);


        $groups = DB::table('chat_group_members')
            ->select('group_id')
            ->groupBy('group_id')
            ->where('chat_group_members.member_id', $user_id)
            ->get()
            ->pluck('group_id')->toArray();

            $latest_group_message = DB::table('chat_group_messages')
                ->groupBy('group_id')
                ->whereIn('group_id', $groups)
                ->select(DB::raw('max(id) as id'))
                ->get()
                ->pluck('id')->toArray();
            $latest_group_sms = ChatGroupMessage::select(
                    'chat_group_messages.group_id as id',
                    'chat_groups.group_name as name',
                    'profiles.profile_image',
                    'chat_group_messages.text',
                    'chat_group_messages.created_at',
                    DB::raw('DATE_FORMAT(chat_group_messages.created_at, "%Y-%m-%d %H:%i:%s") as date')
                )
                ->leftJoin('chat_groups', 'chat_groups.id', 'chat_group_messages.group_id')
                ->leftJoin('users', 'users.id', 'chat_group_messages.sender_id')
                ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
                ->whereIn('chat_group_messages.id', $latest_group_message)->get()->toArray();
            //   $ids = json_encode($messages);
            $arr = json_decode(json_encode($messages), true);
            foreach ($arr as $key => $value) {
                $arr[$key]['is_group'] = 0;
            }
            foreach ($latest_group_sms as $key => $value) {
                $latest_group_sms[$key]['is_group'] = 1;
            }
            $merged = array_merge($arr, $latest_group_sms);
            $keys = array_column($merged, 'date');
            array_multisort($keys, SORT_DESC, $merged);
            $group_owner = ChatGroup::whereIn('chat_groups.id', $groups)->get();
            foreach ($merged as $key => $value) {
                $merged[$key]['owner_id'] = 0;
                foreach ($group_owner as $owner) {
                    if ($value['id'] == $owner['id'] and $value['is_group'] == 1)
                        $merged[$key]['owner_id'] = $owner->group_owner_id;
                }
            }


        return response()->json([
            'data' => $merged,
        ]);
    }
    public function user_react_post(Request $request)
    {
        $post_id = $request['post_id'];
        $isLike = $request['isLike'] === true;

        $update = false;
        $post = Post::findOrFail($post_id);

        if (!$post) {
            return null;
        }
        $user = auth()->user();
        $react = $user->user_reacted_posts()->where('post_id', $post_id)->first();

        if (!empty($react)) {
            $already_like = true;
            $update = true;
            $comment_noti_delete = Notification::where('sender_id', auth()->user()->id)
                ->where('receiver_id', $post->user_id)
                ->where('post_id', $post_id);
            $comment_noti_delete->delete();
            $react->delete();
        } else {
            $react = new UserReactPost();
        }
        $react->user_id = $user->id;
        $react->post_id = $post_id;
        $react->reacted_status = true;

        if ($update == true) {
            $react->update();
        } else {
            $react->save();
            $options = array(
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'encrypted' => true
            );
            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );

            $post_owner = Post::where('posts.id', $react->post_id)->first();
            if ($post_owner->user_id != auth()->user()->id) {
                $data = auth()->user()->name . ' liked your post!';
                $fri_noti = new Notification();
                $fri_noti->description = $data;
                $fri_noti->date = Carbon::Now()->toDateTimeString();
                $fri_noti->sender_id = auth()->user()->id;
                $fri_noti->receiver_id = $post_owner->user_id;
                $fri_noti->post_id = $request->post_id;
                $fri_noti->notification_status = 1;
                $fri_noti->save();
                $pusher->trigger('friend_request.' . $post_owner->user_id, 'friendRequest', $data);
            }
        }
        $total_likes = UserReactPost::where('post_id', $post_id)->count();
        return response()->json([
            'total_likes' => $total_likes,
        ]);
    }

    public function user_view_post(Request $request)
    {
        $post_id=$request->post_id;
        $post=Post::findOrFail($post_id);
        if(auth()->user()->id != $post->user_id){
            $post->viewers = $post->viewers + 1;
        }
        $post->update();
    }

    public function profile_photo_delete(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if ($user->profile_id == $request->profile_id) {
            $user->profile_id = null;
        } elseif ($user->cover_id == $request->profile_id) {
            $user->cover_id = null;
        }
        $user->update();

        Profile::find($request->profile_id)->delete($request->profile_id);
        return response()->json([
            'success' => 'Profile deleted successfully!'
        ]);
    }

    public function socialmedia_profile($id)
    {
        //dd($id);
        $auth = Auth()->user()->id;
        $user = User::where('id', $id)->first();
        $posts = Post::where('user_id', $id)
            ->orderBy('created_at', 'DESC')
            ->where('shop_status',0)
            ->with('user')
            ->paginate(30);

        $friendships = DB::table('friendships')
            ->where('friend_status', 2)
            ->where(function ($query) use ($id) {
                $query->where('sender_id', $id)
                    ->orWhere('receiver_id', $id);
            })
            ->join('users as sender', 'sender.id', 'friendships.sender_id')
            ->join('users as receiver', 'receiver.id', 'friendships.receiver_id')
            ->get(['sender_id', 'receiver_id'])->toArray();
        //dd($friends);
        $n = array();
        foreach ($friendships as $friend) {
            $f = (array)$friend;
            array_push($n, $f['sender_id'], $f['receiver_id']);
        }
        $friends = User::whereIn('id', $n)
            ->where('id', '!=', $user->id)
            ->paginate(6);

        $friend = DB::select("SELECT * FROM `friendships` WHERE (receiver_id = $auth or sender_id = $auth )
                        AND (receiver_id = $id or sender_id = $id)");
        return view('customer.socialmedia_profile', compact('user', 'posts', 'friends', 'friend'));
    }

    public function post_save(Request $request)
    {
        $post_id = $request['post_id'];
        $user = auth()->user();
        $user_save_post = new UserSavedPost();

        $already_save = $user->user_saved_posts()->where('post_id', $post_id)->first();

        if ($already_save) {
            $already_save->delete();
            $user_save_post->update();

            return response()->json([
                'unsave' => 'Unsaved Post Successfully',
            ]);
        } else {
            $user_save_post->user_id = $user->id;
            $user_save_post->post_id = $post_id;
            $user_save_post->saved_status = 1;
            $user_save_post->save();

            return response()->json([
                'save' => 'Saved Post Successfully',
            ]);
        }
    }

    public function profile(Request $request, $id)
    {
        $used_id = auth()->user()->id;
        if ($used_id == $id) {
            return redirect()->route('customer-profile');
        } else {
            $auth = Auth()->user()->id;
            $user = User::where('id', $id)->first();
            $posts = Post::where('user_id', $id)
                ->where('report_status',0)
                ->where('shop_status',0)
                ->orderBy('created_at', 'DESC')
                ->with('user')
                ->paginate(30);

            $friendships = DB::table('friendships')
                ->where('friend_status', 2)
                ->where(function ($query) use ($id) {
                    $query->where('sender_id', $id)
                        ->orWhere('receiver_id', $id);
                })
                ->join('users as sender', 'sender.id', 'friendships.sender_id')
                ->join('users as receiver', 'receiver.id', 'friendships.receiver_id')
                ->get(['sender_id', 'receiver_id'])->toArray();
            //dd($friends);
            $n = array();
            foreach ($friendships as $friend) {
                $f = (array)$friend;
                array_push($n, $f['sender_id'], $f['receiver_id']);
            }
            $friends = User::whereIn('id', $n)
                ->where('id', '!=', $user->id)
                ->paginate(6);
            $friend = DB::select("SELECT * FROM `friendships` WHERE (receiver_id = $auth or sender_id = $auth )
                            AND (receiver_id = $id or sender_id = $id)");
            return view('customer.socialmedia_profile', compact('user', 'posts', 'friends', 'friend'));
        }
    }

    public function social_media_profile(Request $request)
    {
        if (!empty($request->noti_id)) {
            $noti =  DB::table('notifications')->where('id', $request->noti_id)->update(['notification_status' => 2]);
        }

        $id = $request->id;
        $auth = Auth()->user()->id;
        $user = User::where('id', $id)->first();
        $posts = Post::where('user_id', $id)
            ->orderBy('created_at', 'DESC')
            ->with('user')
            ->paginate(30);

        $friendships = DB::table('friendships')
            ->where('friend_status', 2)
            ->where(function ($query) use ($id) {
                $query->where('sender_id', $id)
                    ->orWhere('receiver_id', $id);
            })
            ->join('users as sender', 'sender.id', 'friendships.sender_id')
            ->join('users as receiver', 'receiver.id', 'friendships.receiver_id')
            ->get(['sender_id', 'receiver_id'])->toArray();
        //dd($friends);
        $n = array();
        foreach ($friendships as $friend) {
            $f = (array)$friend;
            array_push($n, $f['sender_id'], $f['receiver_id']);
        }
        $friends = User::select('users.name', 'users.id')
            ->whereIn('id', $n)
            ->where('id', '!=', $user->id)
            ->paginate(6);
        $friend = DB::select("SELECT * FROM `friendships` WHERE (receiver_id = $auth or sender_id = $auth )
                        AND (receiver_id = $id or sender_id = $id)");
        return response()->json([
            'user' => $user,
            'friend_status' => $friend,
            'friends' => $friends,
            'posts' => $posts
        ]);
    }

    public function social_media_likes(Request $request, $post_id)
    {
        if (!empty($request->noti_id)) {
            $noti =  DB::table('notifications')->where('id', $request->noti_id)->update(['notification_status' => 2]);
        }
        $auth = Auth()->user()->id;
        $post_likes = UserReactPost::where('post_id', $post_id)
            ->with('user')
            ->get();
        // $post_likes=UserReactPost::select('users.id','users.name','profiles.profile_image','user_react_posts.*')
        //             ->leftJoin('users','users.id','user_react_posts.user_id')
        //             ->leftJoin('profiles','users.profile_id','profiles.id')
        //             ->where('post_id',$post_id)
        //             ->get();
        $post = Post::findOrFail($post_id);

        // $friends=DB::table('friendships')->get()->toArray();
        $friends = DB::select("SELECT * FROM `friendships` WHERE (receiver_id = $auth or sender_id = $auth)");

        foreach ($post_likes as $key => $value) {
            foreach ($friends as $fri) {
                if ($value->user_id == $fri->receiver_id and $fri->sender_id == $auth and $fri->friend_status == 1) {
                    $post_likes[$key]['friend_status'] = "cancel request";
                    break;
                } else if ($value->user_id == $fri->sender_id and $fri->receiver_id == $auth and $fri->friend_status == 1) {
                    $post_likes[$key]['friend_status'] = "response";
                    break;
                } else if ($value->user_id == $fri->receiver_id and $fri->sender_id == $auth and $fri->friend_status == 2) {
                    $post_likes[$key]['friend_status'] = "friend";
                    break;
                } else if ($value->user_id == $fri->sender_id and $fri->receiver_id == $auth and $fri->friend_status == 2) {
                    $post_likes[$key]['friend_status'] = "friend";
                    break;
                } else if ($value->user_id == $auth) {
                    $post_likes[$key]['friend_status'] = "myself";
                    break;
                } else {
                    $post_likes[$key]['friend_status'] = "add friend";
                }
            }
        }

        return view('customer.socialmedia_likes', compact('post_likes', 'post'));
    }

    public function socialmedia_profile_photos(Request $request,$id)
    {
        $user_id = $id;

        $user = User::findOrFail($user_id);

        $user_profile_cover = Profile::select('cover_photo')
            ->where('user_id', $user_id)
            ->where('profile_image', null)
            ->orderBy('created_at', 'DESC')
            ->get();

        $user_profile_image = Profile::select('profile_image')
            ->where('user_id', $user_id)
            ->where('cover_photo', null)
            ->orderBy('created_at', 'DESC')
            ->get();

        if ($user_profile_cover == null) {
            $user_profile_cover = null;
        } else {
            $user_profile_cover = $user_profile_cover;
        }

        if ($user_profile_image == null) {
            $user_profile_image = null;
        } else {
            $user_profile_image = $user_profile_image;
        }
        return view('customer.socialmedia_profile_photo', compact('user', 'user_id', 'user_profile_image', 'user_profile_cover'));
    }

    public function post_update(Request $request)
    {
        $input = $request->all();

        $edit_post = Post::findOrFail($input['edit_post_id']);
        $caption = $input['caption'];

        $banwords = DB::table('ban_words')->select('ban_word_english', 'ban_word_myanmar', 'ban_word_myanglish')->get();

        if ($caption) {
            foreach ($banwords as $b) {
                $e_banword = $b->ban_word_english;
                $m_banword = $b->ban_word_myanmar;
                $em_banword = $b->ban_word_myanglish;

                if (str_contains($caption, $e_banword)) {
                    // Alert::warning('Warning', 'Ban Ban Ban');
                    //return redirect()->back();
                    return response()->json([
                        'ban' => 'You used our banned words!',
                    ]);
                } elseif (str_contains($caption, $m_banword)) {
                    return response()->json([
                        'ban' => 'You used our banned words!',
                    ]);
                } elseif (str_contains($caption, $em_banword)) {
                    return response()->json([
                        'ban' => 'You used our banned words!',
                    ]);
                }
            }
        }

        if ($input['totalImages'] != 0 && $input['oldimg'] == null) {
            $images = $input['editPostInput'];
            foreach ($images as $file) {
                $extension = $file->extension();
                $name = rand() . "." . $extension;
                $file->storeAs('/public/post/', $name);
                $imgData[] = $name;
                $edit_post->media = json_encode($imgData);
            }
        } elseif ($input['oldimg'] != null && $input['totalImages'] == 0) {

            $imgData = $input['oldimg'];

            $myArray = explode(',', $imgData);

            $edit_post->media = json_encode($myArray);
        } elseif ($input['oldimg'] == null && $input['totalImages'] == 0) {
            $edit_post->media = null;
        } else {
            $oldimgData = $input['oldimg'];
            $myArray_data = explode(',', $oldimgData);
            $old_images = $myArray_data;

            $images = $input['editPostInput'];

            foreach ($images as $file) {
                $extension = $file->extension();
                $name = rand() . "." . $extension;
                $file->storeAs('/public/post/', $name);
                $imgData[] = $name;
                $new_images = $imgData;
            }
            $result = array_merge($old_images, $new_images);
            $edit_post->media = json_encode($result);
        }
        $edit_post->caption = $caption;
        $edit_post->update();

        return response()->json([
            'success' => 'Post Updated successfully!'
        ]);
    }

    public function post_edit(Request $request, $id)
    {
        $post = Post::find($id);

        if($post->media==null){
            $imageData=null;
        }else{
            $images=json_decode($post->media);
            $imageData=new stdClass();
            foreach($images as $key=>$value){
                     for($i=0;$i<count($images);$i++){

                        $img_size=File::size(public_path('storage/post/'.$value));

                        // $obj['size']=$img_size;
                        // $obj['name']=$images[$i];
                        $imageData->$key['size']=$img_size;
                        $imageData->$key['name']=$value;
                        }


                    }
            $imageData=(array)$imageData;
        }


        if ($post) {
            return response()->json([
                'status' => 200,
                'post' => $post,
                'imageData'=>$imageData,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Data Not Found',
            ]);
        }
    }

    public function viewFriendRequestNoti(Request $request)
    {
        $auth = Auth()->user()->id;
        $id = $request->id;
        $posts = Post::where('user_id', $id)
            ->orderBy('created_at', 'DESC')
            ->with('user')
            ->paginate(30);
        DB::table('notifications')->where('id', $request->noti_id)->update(['notification_status' => 2]);
        $user = User::where('id', $request->id)->first();
        $friendships = DB::table('friendships')
            ->where('friend_status', 2)
            ->where(function ($query) use ($id) {
                $query->where('sender_id', $id)
                    ->orWhere('receiver_id', $id);
            })
            ->join('users as sender', 'sender.id', 'friendships.sender_id')
            ->join('users as receiver', 'receiver.id', 'friendships.receiver_id')
            ->get(['sender_id', 'receiver_id'])->toArray();
        //dd($friends);
        $n = array();
        foreach ($friendships as $friend) {
            $f = (array)$friend;
            array_push($n, $f['sender_id'], $f['receiver_id']);
        }
        $friends = User::whereIn('id', $n)
            ->where('id', '!=', $user->id)
            ->paginate(6);

        $friend_status = Friendship::where('sender_id', auth()->user()->id)->orWhere('receiver_id', auth()->user()->id)->first();
        $friend = DB::select("SELECT * FROM `friendships` WHERE (receiver_id = $auth or sender_id = $auth )
        AND (receiver_id = $request->id or sender_id = $request->id)");
        return view('customer.socialmedia_profile', compact('user', 'friend_status', 'friend', 'friends', 'posts'));
    }

    public function showUser(Request $request)
    {
        $users = User::where('name', 'LIKE', '%' . $request->keyword . '%')
            ->orWhere('phone', 'LIKE', '%' . $request->keyword . '%')->get();
        $friends = DB::table('friendships')
            ->get();
        return response()->json([
            'users' => $users,
            'friends' => $friends,
        ]);
    }

    public function friendsList(Request $request)
    {
        //dd($request->user_id);
        $id = $request->id;
        $user = User::select('id', 'name')->where('id', $id)->first();

        return view('customer.friendlist', compact('user'));
    }
    public function friList(Request $request)
    {
        //dd($request->keyword);
        $id = $request->id;
        $friendships = DB::table('friendships')
            ->where('friend_status', 2)
            ->where(function ($query) use ($id) {
                $query->where('sender_id', $id)
                    ->orWhere('receiver_id', $id);
            })
            ->join('users as sender', 'sender.id', 'friendships.sender_id')
            ->join('users as receiver', 'receiver.id', 'friendships.receiver_id')
            ->get(['sender_id', 'receiver_id'])->toArray();
        //dd($friends);
        $n = array();
        foreach ($friendships as $friend) {
            $f = (array)$friend;
            array_push($n, $f['sender_id'], $f['receiver_id']);
        }
        if ($request->keyword != '') {
            $friends = User::select('users.id', 'users.name', 'friendships.date', 'profiles.profile_image')
                ->leftjoin('friendships', function ($join) {
                    $join->on('friendships.receiver_id', '=', 'users.id')
                        ->orOn('friendships.sender_id', '=', 'users.id');
                })
                ->leftJoin('profiles', 'profiles.id', 'users.profile_id')
                ->where('users.name', 'LIKE', '%' . $request->keyword . '%')
                ->where('users.id', '!=', $id)
                ->where('friendships.friend_status', 2)
                ->where('friendships.receiver_id', $id)
                ->orWhere('friendships.sender_id', $id)
                ->whereIn('users.id', $n)
                ->where('users.id', '!=', $id)
                ->where('users.name', 'LIKE', '%' . $request->keyword . '%')
                ->get();
            // dd($friends);
            return response()->json([
                'friends' => $friends
            ]);
        }
        $friends = User::select('users.id', 'users.name', 'friendships.date', 'profiles.profile_image')
            ->leftjoin('friendships', function ($join) {
                $join->on('friendships.receiver_id', '=', 'users.id')
                    ->orOn('friendships.sender_id', '=', 'users.id');
            })
            ->leftJoin('profiles', 'profiles.id', 'users.profile_id')
            ->where('users.id', '!=', $id)
            ->where('friendships.friend_status', 2)
            ->where('friendships.receiver_id', $id)
            ->orWhere('friendships.sender_id', $id)
            ->whereIn('users.id', $n)
            ->where('users.id', '!=', $id)
            ->get();

        return response()->json([
            'friends' => $friends
        ]);
    }

    public function notification_center()
    {
        $friend_requests = Friendship::select('sender.name', 'sender.id')
            ->join('users as receiver', 'receiver.id', '=', 'friendships.receiver_id')
            ->join('users as sender', 'sender.id', '=', 'friendships.sender_id')
            ->where('receiver.id', auth()->user()->id)
            ->where('friend_status', 1)
            ->where(DB::raw("(DATE_FORMAT(date,'%Y-%m-%d'))"), Carbon::Now()->toDateString())
            ->get();

        $friend_requests_earlier = Friendship::select('sender.name', 'sender.id')
            ->join('users as receiver', 'receiver.id', '=', 'friendships.receiver_id')
            ->join('users as sender', 'sender.id', '=', 'friendships.sender_id')
            ->where('receiver.id', auth()->user()->id)
            ->where('friend_status', 1)
            ->where(DB::raw("(DATE_FORMAT(date,'%Y-%m-%d'))"), '!=', Carbon::Now()->toDateString())
            ->get();

        $notification = Notification::select(
            'users.id as user_id',
            'users.name',
            'notifications.*',
            'profiles.profile_image'
        )
            ->leftJoin('users', 'notifications.sender_id', '=', 'users.id')
            ->leftJoin('profiles', 'profiles.id', 'users.profile_id')
            ->where('notifications.receiver_id', auth()->user()->id)
            ->where('notifications.post_id', '!=', null)
            ->orWhere(function ($query) {
                $query->where('notifications.report_id', '!=', null)
                    ->where('receiver_id', auth()->user()->id);
            })
            ->where(DB::raw("(DATE_FORMAT(date,'%Y-%m-%d'))"), Carbon::Now()->toDateString())
            ->get();

        $notification_earlier = Notification::select(
            'users.id as user_id',
            'users.name',
            'notifications.*',
            'profiles.profile_image'
        )
            ->leftJoin('users', 'notifications.sender_id', '=', 'users.id')
            ->leftJoin('profiles', 'profiles.id', 'users.profile_id')
            ->where('notifications.receiver_id', auth()->user()->id)
            ->where('notifications.post_id', '!=', null)
            ->orWhere(function ($query) {
                $query->where('notifications.report_id', '!=', null)
                    ->where('receiver_id', auth()->user()->id);
            })
            ->where(DB::raw("(DATE_FORMAT(date,'%Y-%m-%d'))"), '!=', Carbon::Now()->toDateString())
            ->get();
        // dd($notification_earlier);
        return view('customer.noti_center', compact('friend_requests', 'friend_requests_earlier', 'notification', 'notification_earlier'));
    }

    public function addUser(Request $request)
    {
        // dd("ok");
        $id = $request->id;
        $user_id = auth()->user()->id;
        $sender = User::where('id', $user_id)->first();

        $friendship = new Friendship();
        $friendship->sender_id = $user_id;
        $friendship->receiver_id = $id;
        $friendship->date =  Carbon::Now()->toDateTimeString();
        $friendship->friend_status = 1;
        $friendship->save();



        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        );
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $data = $sender->name . ' send you a friend request!';

        $fri_noti = new Notification();
        $fri_noti->description = $data;
        $fri_noti->date = Carbon::Now()->toDateTimeString();
        $fri_noti->sender_id = $user_id;
        $fri_noti->receiver_id = $id;
        $fri_noti->notification_status = 1;
        $fri_noti->save();

        $pusher->trigger('friend_request.' . $id, 'friendRequest', $data);
        return response()
            ->json([
                'data' => $data
            ]);
    }
    public function unfriend(Request $request)
    {
        $friend_ship_delete_receiver = Friendship::where('sender_id', auth()->user()->id)
            ->where('receiver_id', $request->id)
            ->where('friend_status', 2);
        $friend_ship_delete_receiver->delete();
        $friend_ship_delete_sender = Friendship::where('sender_id', $request->id)
            ->where('receiver_id', auth()->user()->id)
            ->where('friend_status', 2);
        $friend_ship_delete_sender->delete();
        $noti_delete_receiver = Notification::where('sender_id', $request->id)
            ->where('receiver_id', auth()->user()->id)
            ->where('post_id', null);
        $noti_delete_receiver->delete();
        $noti_delete_sender = Notification::where('sender_id', auth()->user()->id)
            ->where('receiver_id', $request->id)
            ->where('post_id', null);
        $noti_delete_sender->delete();
        return response()
            ->json([
                'data' => 'Success'
            ]);
    }

    public function confirmRequest(Request $request)
    {
        $user = auth()->user();
        DB::table('friendships')->where('receiver_id', $user->id)
            ->where('sender_id', $request->id)
            ->update(['friend_status' => 2, 'date' =>  Carbon::Now()->toDateTimeString()]);

        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        );
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $data = $user->name . ' accepted your friend request!';

        $fri_noti = new Notification();
        $fri_noti->description = $data;
        $fri_noti->date = Carbon::Now()->toDateTimeString();
        $fri_noti->sender_id = $user->id;
        $fri_noti->receiver_id = $request->id;
        $fri_noti->notification_status = 1;
        $fri_noti->save();

        $pusher->trigger('friend_request.' . $request->id, 'App\\Events\\Friend_Request', $data);
        return redirect()->back();
    }

    public function cancelRequest(Request $request)
    {
        $user_id = auth()->user()->id;
        $friend_ship_delete = Friendship::where('sender_id', $user_id)->where('receiver_id', $request->id);
        $friend_ship_delete->delete();
        $noti_delete = Notification::where('sender_id', $user_id)->where('receiver_id', $request->id);
        $noti_delete->delete();
    }

    public function declineRequest(Request $request)
    {
        $user_id = auth()->user()->id;
        $friend_ship_delete = Friendship::where('sender_id', $request->id)->where('receiver_id', $user_id);
        $friend_ship_delete->delete();
        $noti_delete = Notification::where('sender_id', $request->id)->where('receiver_id', $user_id);
        $noti_delete->delete();
        return redirect()->back();
    }

    public function post_store(Request $request)
    {
        $input = $request->all();

        $user = auth()->user();
        $post = new Post();

        if ($input['totalImages'] == 0 && $input['caption'] != null) {
            $caption = $input['caption'];
        } elseif ($input['caption'] == null && $input['totalImages'] != 0) {
            $caption = null;
            $images = $input['addPostInput'];
            if ($input['addPostInput']) {
                foreach ($images as $file) {
                    $extension = $file->extension();
                    $name = rand() . "." . $extension;
                    $file->storeAs('/public/post/', $name);
                    $imgData[] = $name;
                    $post->media = json_encode($imgData);
                }
            }
        } elseif ($input['totalImages'] != 0 && $input['caption'] != null) {
            $caption = $input['caption'];
            $images = $input['addPostInput'];
            if ($input['addPostInput']) {
                foreach ($images as $file) {
                    $extension = $file->extension();
                    $name = rand() . "." . $extension;
                    $file->storeAs('/public/post/', $name);
                    $imgData[] = $name;
                    $post->media = json_encode($imgData);
                }
            }
        }
        $banwords = DB::table('ban_words')->select('ban_word_english', 'ban_word_myanmar', 'ban_word_myanglish')->get();

        foreach ($banwords as $b) {
            $e_banword = $b->ban_word_english;
            $m_banword = $b->ban_word_myanmar;
            $em_banword = $b->ban_word_myanglish;

            if (str_contains($caption, $e_banword)) {
                // Alert::warning('Warning', 'Ban Ban Ban');
                //return redirect()->back();
                return response()->json([
                    'ban' => 'You used our banned words!',
                ]);
            } elseif (str_contains($caption, $m_banword)) {
                return response()->json([
                    'ban' => 'You used our banned words!',
                ]);
            } elseif (str_contains($caption, $em_banword)) {
                return response()->json([
                    'ban' => 'You used our banned words!',
                ]);
            }
        }

        $post->user_id = $user->id;
        $post->caption = $caption;

        $post->save();
        return response()->json([
            'message' => 'Post Created Successfully',
        ]);
        // Alert::success('Success', 'Post Created Successfully');
        // return redirect()->back();
    }

    public function post_destroy($id)
    {
        $post=Post::find($id);

        if ($post != null) {
            $post->delete();
            return response()->json([
                'success' => 'Post deleted successfully!'
            ]);
        }else{

        }


    }

    public function see_all_message()
    {
        $user_id = auth()->user()->id;
        $messages = DB::select("SELECT users.id,users.name,profiles.profile_image,chats.text,chats.created_at,chats.from_user_id as from_id,chats.to_user_id as to_id
            from
                chats
              join
                (select user, max(created_at) m
                    from
                       (
                         (select id, to_user_id user, created_at
                           from chats
                           where from_user_id= $user_id
                           and delete_status <> 2 and deleted_by != $user_id )
                       union
                         (select id, from_user_id user, created_at
                           from chats
                           where to_user_id= $user_id
                           and delete_status <> 2 and deleted_by != $user_id)
                        ) t1
                   group by user) t2
             on ((from_user_id= $user_id and to_user_id=user) or
                 (from_user_id=user and to_user_id= $user_id)) and
                 (created_at = m)
            left join users on users.id = user
            left join profiles on users.profile_id = profiles.id
            where deleted_by !=  $user_id  and delete_status != 2
           order by chats.created_at desc");


        //friends list
        $user = User::where('id', $user_id)->first();
        $friendships = DB::table('friendships')
            ->where('friend_status', 2)
            ->where(function ($query) use ($user_id) {
                $query->where('sender_id', $user_id)
                    ->orWhere('receiver_id', $user_id);
            })
            ->join('users as sender', 'sender.id', 'friendships.sender_id')
            ->join('users as receiver', 'receiver.id', 'friendships.receiver_id')
            ->get(['sender_id', 'receiver_id'])->toArray();

        $n = array();
        foreach ($friendships as $friend) {
            $f = (array)$friend;
            array_push($n, $f['sender_id'], $f['receiver_id']);
        }

        $friends = DB::table('users')->select('users.name', 'users.id')->whereIn('users.id', $n)
            ->where('users.id', '!=', $user->id)
            ->get();
        return view('customer.message_seeall', compact('messages', 'friends'));
    }

    public function chat_message($id)
    {
        $auth_user = auth()->user();

        $messages = Chat::where(function ($que) use ($id) {
            $que->where('from_user_id', $id)->orWhere('to_user_id', $id);
        })->where(function ($query) use ($auth_user) {
            $query->where('from_user_id', $auth_user->id)->orWhere('to_user_id', $auth_user->id);
        })->get();


        foreach ($messages as $mess) {

            if ($mess->delete_status == 1 && $mess->deleted_by == $auth_user->id) {
                $messages = Chat::where('delete_status', 0)->orWhere(function ($q) use ($auth_user) {
                    $q->where('delete_status', 1)->where('deleted_by', '!=', $auth_user->id);
                })->where(function ($que) use ($id) {
                    $que->where('from_user_id', $id)->orWhere('to_user_id', $id);
                })->where(function ($query) use ($auth_user) {
                    $query->where('from_user_id', $auth_user->id)->orWhere('to_user_id', $auth_user->id);
                })->get();
            }
            if ($mess->delete_status == 2) {
                $messages = Chat::where('delete_status', 0)->orWhere(function ($q) use ($auth_user) {
                    $q->where('delete_status', 1)->where('deleted_by', '!=', $auth_user->id);
                })->where(function ($que) use ($id) {
                    $que->where('from_user_id', $id)->orWhere('to_user_id', $id);
                })->where(function ($query) use ($auth_user) {
                    $query->where('from_user_id', $auth_user->id)->orWhere('to_user_id', $auth_user->id);
                })->get();
            }
        }


        $auth_user_name = auth()->user()->name;
        $receiver_user = User::where('users.id', $id)->with('user_profile')->first();

        $sender_user = User::where('id', $auth_user->id)->with('user_profile')->first();

        $auth = Auth()->user()->id;
        $user = User::where('id', $auth)->first();

        $friendships = DB::table('friendships')
            ->where('friend_status', 2)
            ->where(function ($query) use ($id) {
                $query->where('sender_id', $id)
                    ->orWhere('receiver_id', $id);
            })
            ->join('users as sender', 'sender.id', 'friendships.sender_id')
            ->join('users as receiver', 'receiver.id', 'friendships.receiver_id')
            ->get(['sender_id', 'receiver_id'])->toArray();
        //dd($friends);
        $n = array();
        foreach ($friendships as $friend) {
            $f = (array)$friend;
            array_push($n, $f['sender_id'], $f['receiver_id']);
        }
        $friends = User::select('users.name', 'users.id')
            ->whereIn('id', $n)
            ->where('id', '!=', $user->id)
            ->get();

        return view('customer.chat_message', compact('id', 'messages', 'auth_user_name', 'receiver_user', 'sender_user', 'friends'));
    }

    public function delete_allchat_message(Request $request)
    {
        $data = $request->all();
        $from_id = $data['from_id'];
        $to_id = $data['to_id'];
        $auth_user = auth()->user()->id;

        $messages = Chat::where('delete_status', '!=', 2)
            ->where(function ($query1) use ($from_id, $to_id) {
                $query1->where('from_user_id', $from_id)
                    ->orWhere('from_user_id', $to_id);
            })
            ->where(function ($query2) use ($from_id, $to_id) {
                $query2->where('to_user_id', $from_id)
                    ->orWhere('to_user_id', $to_id);
            })
            ->get();

        if (($messages)->count() > 0) {
            foreach ($messages as $key => $value) {
                if ($value->delete_status == 0) {
                    // $messages[$key]['delete_status']=1;
                    // $messages[$key]['deleted_by']=$auth_user;
                    Chat::where('id', $value->id)->update(['delete_status' => 1, 'deleted_by' => $auth_user]);
                } elseif ($value->delete_status == 1) {
                    // $messages[$key]['delete_status']=2;
                    Chat::where('id', $value->id)->update(['delete_status' => 2]);
                }
                return response()->json([
                    'success' =>  'Deleted'
                ]);
            }
        }
    }


    public function viewmedia_message($id)
    {
        $auth_user = auth()->user();

        $messages = Chat::where(function ($query) use ($auth_user) {
            $query->where('from_user_id', $auth_user->id)->orWhere('to_user_id', $auth_user->id);
        })->where(function ($que) use ($id) {
            $que->where('from_user_id', $id)->orWhere('to_user_id', $id);
        })->where('media', '!=', null)->get();


        foreach ($messages as $mess) {
            if ($mess->delete_status == 1 && $mess->deleted_by == $auth_user->id) {
                $messages = Chat::where('delete_status', 0)->orWhere(function ($q) use ($auth_user) {
                    $q->where('delete_status', 1)->where('deleted_by', '!=', $auth_user->id);
                })->where(function ($que) use ($id) {
                    $que->where('from_user_id', $id)->orWhere('to_user_id', $id);
                })->where(function ($query) use ($auth_user) {
                    $query->where('from_user_id', $auth_user->id)->orWhere('to_user_id', $auth_user->id);
                })->where('media', '!=', null)->get();
            }
            if ($mess->delete_status == 2) {
                $messages = Chat::where('delete_status', 0)->orWhere(function ($q) use ($auth_user) {
                    $q->where('delete_status', 1)->where('deleted_by', '!=', $auth_user->id);
                })->where(function ($que) use ($id) {
                    $que->where('from_user_id', $id)->orWhere('to_user_id', $id);
                })->where(function ($query) use ($auth_user) {
                    $query->where('from_user_id', $auth_user->id)->orWhere('to_user_id', $auth_user->id);
                })->where('media', '!=', null)->get();
            }
        }

        $auth_user_name = auth()->user()->name;
        $receiver_user = User::findOrFail($id);
        return view('customer.chat_view_media', compact('id', 'messages', 'auth_user_name', 'receiver_user'));
    }

    public function hide_message(Request $request)
    {
        $message = Chat::findOrFail($request->id);
        $message->delete_status = 1;
        $message->deleted_by = $request->delete_user;
        $message->save();
    }

    public function delete_message(Request $request)
    {
        $message = Chat::findOrFail($request->id);
        $message->delete_status = 2;
        $message->deleted_by = $request->delete_user;
        $message->save();

        broadcast(new MessageDelete($message, $request->id));
    }

    public function post_comment($id)
    {
        $post = Post::select('users.name', 'profiles.profile_image', 'posts.*')
            ->where('posts.id', $id)
            ->leftJoin('users', 'users.id', 'posts.user_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->first();
        $comments = Comment::select('users.name', 'users.profile_id', 'profiles.profile_image', 'comments.*')
            ->leftJoin('users', 'users.id', 'comments.user_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->where('post_id', $id)->orderBy('created_at', 'DESC')->get();

        $post_likes = UserReactPost::where('post_id', $post->id)
            ->with('user')
            ->get();



        return view('customer.comments', compact('post', 'comments', 'post_likes'));
    }

    public function users_for_mention(Request $request)
    {
        // dd($request->keyword);
        $id = auth()->user()->id;
        $friendships = DB::table('friendships')
            ->where('friend_status', 2)
            ->where(function ($query) use ($id) {
                $query->where('sender_id', $id)
                    ->orWhere('receiver_id', $id);
            })
            ->join('users as sender', 'sender.id', 'friendships.sender_id')
            ->join('users as receiver', 'receiver.id', 'friendships.receiver_id')
            ->get(['sender_id', 'receiver_id'])->toArray();
        //dd($friends);
        $n = array();
        foreach ($friendships as $friend) {
            $f = (array)$friend;
            array_push($n, $f['sender_id'], $f['receiver_id']);
        }
        $user = User::select('users.id', 'users.name', 'friendships.date', 'profiles.profile_image as avatar')
            ->leftjoin('friendships', function ($join) {
                $join->on('friendships.receiver_id', '=', 'users.id')
                    ->orOn('friendships.sender_id', '=', 'users.id');
            })
            ->leftJoin('profiles', 'profiles.id', 'users.profile_id')
            ->where('users.id', '!=', $id)
            ->where('friendships.friend_status', 2)
            ->where('friendships.receiver_id', $id)
            ->orWhere('friendships.sender_id', $id)
            ->whereIn('users.id', $n)
            ->where('users.id', '!=', $id)
            ->get()->toArray();
        return response()->json([
            'data' =>  $user
        ]);
    }

    public function post_comment_store(Request $request)
    {

        // dd($request->post_id);
        $banwords = DB::table('ban_words')->select('ban_word_english', 'ban_word_myanmar', 'ban_word_myanglish')->get();

        foreach ($banwords as $b) {
            $e_banword = $b->ban_word_english;
            $m_banword = $b->ban_word_myanmar;
            $em_banword = $b->ban_word_myanglish;

            if (str_contains($request->comment, $e_banword)) {
                return response()->json([
                    'ban' => 'You used our banned words!',
                ]);
            } elseif (str_contains($request->comment, $m_banword)) {
                return response()->json([
                    'ban' => 'You used our banned words!',
                ]);
            } elseif (str_contains($request->comment, $em_banword)) {
                return response()->json([
                    'ban' => 'You used our banned words!',
                ]);
            }
        }
        if($request->mention == null AND $request->comment == null){
            return response()->json([
                'message' => 'text something',
            ]);
        }
        $comments = new Comment();
        $comments->user_id = auth()->user()->id;
        $comments->post_id = $request->post_id;
        $comments->comment = $request->comment;
        $comments->mentioned_users = json_encode($request->mention);
        $comments->save();

        $post_owner = Post::where('posts.id', $comments->post_id)->first();
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        );
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );
        if ($post_owner->user_id != auth()->user()->id and $comments->mentioned_users == "null") {
            $data2 = auth()->user()->name . ' commented on your post!';
            $fri_noti = new Notification();
            $fri_noti->description = $data2;
            $fri_noti->date = Carbon::Now()->toDateTimeString();
            $fri_noti->sender_id = auth()->user()->id;
            $fri_noti->receiver_id = $post_owner->user_id;
            $fri_noti->post_id = $request->post_id;
            $fri_noti->comment_id = $comments->id;
            $fri_noti->notification_status = 1;
            $fri_noti->save();
            $pusher->trigger('friend_request.' . $post_owner->user_id, 'friendRequest', $data2);
        } elseif ($comments->mentioned_users != "null") {
            $data = auth()->user()->name . ' mentioned you in a comment!';
            $ids = json_decode($comments->mentioned_users);
            $arr = json_decode(json_encode($ids), true);
            foreach ($arr as $id) {
                if ($id['id'] != auth()->user()->id) {
                    $fri_noti = new Notification();
                    $fri_noti->description = $data;
                    $fri_noti->date = Carbon::Now()->toDateTimeString();
                    $fri_noti->sender_id = auth()->user()->id;
                    $fri_noti->post_id = $request->post_id;
                    $fri_noti->receiver_id = $id['id'];
                    $fri_noti->comment_id = $comments->id;
                    $fri_noti->notification_status = 1;
                    $fri_noti->save();
                    $pusher->trigger('friend_request.' . $fri_noti->receiver_id, 'friendRequest', $data);
                }
            }
        }


        return response()->json([
            'data' =>  $comments
        ]);
    }

    public function comment_delete(Request $request)
    {
        Comment::find($request->id)->delete($request->id);

        return response()->json([
            'success' => 'Comment deleted successfully!'
        ]);
    }

    public function comment_list(Request $request)
    {
        if (!empty($request->noti_id)) {
            $noti =  DB::table('notifications')->where('id', $request->noti_id)->update(['notification_status' => 2]);
        }
        $id = $request->id;
        $comments = Comment::select('users.name', 'users.profile_id', 'posts.user_id as post_owner', 'profiles.profile_image', 'comments.*')
            ->leftJoin('users', 'users.id', 'comments.user_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->leftJoin('posts', 'posts.id', 'comments.post_id')
            ->where('post_id', $id)->orderBy('created_at', 'DESC')->get();
        foreach ($comments as $key => $comm1) {
            $date = $comm1['created_at'];
            $comments[$key]['date'] = $date->toDayDateTimeString();
            $ids = json_decode($comm1->mentioned_users);
            if ($ids != null) {
                $count = count($ids);
                $main =  $comm1['comment'];
                $date = $comm1['created_at'];
                for ($i = 0; $i < $count; $i++) {
                    $arr_id = json_decode(json_encode($ids[$i]), true);
                    $mentioned_user_id = $arr_id['id'];

                    $url = route('socialmedia.profile', $mentioned_user_id);
                    $comments[$key]['Replace'] = sizeof($ids);
                    if (str_contains($main, '@' . $mentioned_user_id)) {
                        $replace =
                            str_replace(
                                ['@' . $mentioned_user_id],
                                "<a href=$url>" . $arr_id['name'] . '</a>',
                                $main
                            );
                        $main = $replace;
                        $comments[$key]['Replace'] = $main;
                    }
                    $comments[$key]['Replace'] = $main;
                }
            } else {
                $comments[$key]['Replace'] = $comm1->comment;
            }
        }


        return response()->json([
            'comment' => $comments
        ]);
    }

    public function comment_edit($id)
    {

        $comments = Comment::findOrFail($id);

        $ids = json_decode($comments->mentioned_users);
        if ($ids != null) {
            $count = count($ids);
            //   dd($count);
            $main =  $comments['comment'];
            for ($i = 0; $i < $count; $i++) {
                $arr_id = json_decode(json_encode($ids[$i]), true);
                $mentioned_user_id = $arr_id['id'];

                $url = route('socialmedia.profile', $mentioned_user_id);
                $comments['Replace'] = sizeof($ids);
                if (str_contains($main, '@' . $mentioned_user_id)) {
                    $replace =
                        str_replace(
                            ['@' . $mentioned_user_id],
                            "<a href=$url data-item-id = $mentioned_user_id class = 'mentiony-link'>" . $arr_id['name'] . '</a>',
                            $main
                        );
                    $main = $replace;
                    $comments['Replace'] = $main;
                }
                $comments['Replace'] = $main;
            }

        } else {
            $comments['Replace'] = $comments->comment;
        }
        return response()->json([
            'data' => $comments
        ]);
    }

    public function comment_update(Request $request)
    {
        $banwords = DB::table('ban_words')->select('ban_word_english', 'ban_word_myanmar', 'ban_word_myanglish')->get();
        foreach ($banwords as $b) {
            $e_banword = $b->ban_word_english;
            $m_banword = $b->ban_word_myanmar;
            $em_banword = $b->ban_word_myanglish;
            if (str_contains($request->comment, $e_banword)) {
                return response()->json([
                    'ban' => 'You used our banned words!',
                ]);
            } elseif (str_contains($request->comment, $m_banword)) {
                return response()->json([
                    'ban' => 'You used our banned words!',
                ]);
            } elseif (str_contains($request->comment, $em_banword)) {
                return response()->json([
                    'ban' => 'You used our banned words!',
                ]);
            }
        }
        $comments_update = Comment::findOrFail($request->post_id);
        $comments_update->comment = $request->comment;
        $comments_update->mentioned_users = json_encode($request->mention);
        $comments_update->update();
        return response()->json([
            'success' =>  'Comment updated successfully!'
        ]);
    }

    public function group_create(Request $request)
    {
        $groupName = $request->group_name;
        $groupOwner = auth()->user()->id;
        $group = new ChatGroup();
        $group->group_name = $groupName;
        $group->group_owner_id = $groupOwner;
        $group->save();

        if ($request->members != null) {
            $members = $request->members;
            $id = $group->id;
            for ($i = 0; $i < count($members); $i++) {
                $memberId = $members[$i];
                $group_members = new ChatGroupMember();
                $group_members->group_id = $id;
                $group_members->member_id = $memberId;
                $group_members->save();
            }
        }

        $message = "Hi All";
        ChatGroupMember::create(['group_id' => $group->id, 'member_id' => $groupOwner]);
        $chat_message = new ChatGroupMessage();
        $chat_message->group_id =  $group->id;
        $chat_message->sender_id = $groupOwner;
        $chat_message->text = $message;
        $chat_message->save();
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        );
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
            );
            $group_message = ChatGroupMember::select('member_id')->where('group_id', $group->id)
            ->where('member_id','!=',$groupOwner)->get();
            for ($i = 0; count($group_message) > $i; $i++)
            {
            $user_id_to = $group_message[$i]['member_id'];
            $messages = DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date
            from
                chats
              join
                (select user, max(created_at) m
                    from
                       (
                         (select id, to_user_id user, created_at
                           from chats
                           where from_user_id= $user_id_to  and delete_status <> 2 and deleted_by != $user_id_to)
                       union
                         (select id, from_user_id user, created_at
                           from chats
                           where to_user_id= $user_id_to and delete_status <> 2 and deleted_by != $user_id_to)
                        ) t1
                   group by user) t2
                    on ((from_user_id= $user_id_to and to_user_id=user) or
                        (from_user_id=user and to_user_id= $user_id_to)) and
                        (created_at = m)
                    left join users on users.id = user
                    left join profiles on users.profile_id = profiles.id
                    order by chats.created_at desc");
            // dd($messages);


            $groups = DB::table('chat_group_members')
                ->select('group_id')
                ->groupBy('group_id')
                ->where('chat_group_members.member_id', $user_id_to)
                ->get()
                ->pluck('group_id')->toArray();

            $latest_group_message = DB::table('chat_group_messages')
                ->groupBy('group_id')
                ->whereIn('group_id', $groups)
                ->select(DB::raw('max(id) as id'))
                ->get()
                ->pluck('id')->toArray();
            $latest_group_sms = ChatGroupMessage::select(
                'chat_group_messages.group_id as id',
                'chat_groups.group_name as name',
                'profiles.profile_image',
                'chat_group_messages.text',
                DB::raw('DATE_FORMAT(chat_group_messages.created_at, "%Y-%m-%d %H:%i:%s") as date')
            )
                ->leftJoin('chat_groups', 'chat_groups.id', 'chat_group_messages.group_id')
                ->leftJoin('users', 'users.id', 'chat_group_messages.sender_id')
                ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
                ->whereIn('chat_group_messages.id', $latest_group_message)->get()->toArray();
            //   $ids = json_encode($messages);
            $arr = json_decode(json_encode($messages), true);
            foreach ($arr as $key => $value) {
                $arr[$key]['is_group'] = 0;
            }
            foreach ($latest_group_sms as $key => $value) {
                $latest_group_sms[$key]['is_group'] = 1;
            }
            $merged = array_merge($arr, $latest_group_sms);
            $keys = array_column($merged, 'date');
            array_multisort($keys, SORT_DESC, $merged);
            $group_owner = ChatGroup::whereIn('chat_groups.id', $groups)->get();
            foreach ($merged as $key => $value) {
                $merged[$key]['owner_id'] = 0;
                foreach ($group_owner as $owner) {
                    if ($value['id'] == $owner['id'] and $value['is_group'] == 1)
                        $merged[$key]['owner_id'] = $owner->group_owner_id;
                }
            }
            $pusher->trigger('all_message.'.$user_id_to , 'all', $merged);
        }
        return back();
    }

    public function group($id)
    {
        try {
            $group = ChatGroup::where('id', $id)->first();
            if($group->id == $id){
                $gp_messages = ChatGroupMessage::where('group_id', $id)->with('user')->with('user.user_profile')->get();
            $auth_user_data = User::where('id', auth()->user()->id)->with('user_profile')->first();

            return view('customer.group_chat_message', compact('group', 'gp_messages', 'auth_user_data'));
            }

        } catch (\Throwable $th) {
            return view('customer.groupdeleterrorpage');
        }
    }

    public function addmember(Request $request, $id)
    {
        $members = $request->members;

        for ($i = 0; $i < count($members); $i++) {
            $memberId = $members[$i];
            $group_members = new ChatGroupMember();
            $group_members->group_id = $id;
            $group_members->member_id = $memberId;
            $group_members->save();
        }
        return back();
    }

    public function group_detail($id)
    {
        $auth = Auth()->user()->id;
        $user = User::where('id', $auth)->first();
        $friendships = DB::table('friendships')
            ->where('friend_status', 2)
            ->where(function ($query) use ($auth) {
                $query->where('sender_id', $auth)
                    ->orWhere('receiver_id', $auth);
            })
            ->join('users as sender', 'sender.id', 'friendships.sender_id')
            ->join('users as receiver', 'receiver.id', 'friendships.receiver_id')
            ->get(['sender_id', 'receiver_id'])->toArray();

        $n = array();
        foreach ($friendships as $friend) {
            $f = (array)$friend;
            array_push($n, $f['sender_id'], $f['receiver_id']);
        }

        $group = ChatGroup::findOrFail($id);

        $friends = DB::table('users')->select('users.name', 'users.id')->whereIn('users.id', $n)
            ->where('users.id', '!=', $user->id)
            ->get();

        $gp_admin = ChatGroup::where('id', $id)->with('user')->with('user.user_profile')->first();

        $members = ChatGroupMember::where('group_id', $id)->where('member_id', '!=', $gp_admin->group_owner_id)->with('user')->with('user.user_profile')->get();

        return view('customer.group_chat-detail', compact('friends', 'id', 'members', 'group', 'gp_admin'));
    }

    public function group_member_kick(Request $request)
    {
        $member = ChatGroupMember::where('group_id', $request->groupId)->where('member_id', $request->memberId)->first();
        $member->delete();
        return back();
    }

    public function group_viewmedia($id)
    {
        $group = ChatGroup::findOrFail($id);
        $messages_media = ChatGroupMessage::where('group_id', $id)->where('media', '!=', null)->get();

        return view('customer.group_chat_viewmedia', compact('id', 'messages_media', 'group'));
    }

    public function group_leave($gp_id, $id)
    {
        $member = ChatGroupMember::where('group_id', $gp_id)->where('member_id', $id)->first();
        $member->delete();
        return redirect()->route('socialmedia');
    }

    public function group_delete($id)
    {
        $member = ChatGroup::where('id', $id)->first();
        $member->delete();
        $message = ChatGroupMessage::where('group_id', $id)->get();
        foreach ($message as $mess) {
            $mess->delete();
        }

        return redirect()->route('socialmedia');
    }

    public function post_report(Request $request)
    {
        //dd($request->all());
        $user_id = $request->user_id;
        $post_id = $request->post_id;
        $admin_id = 1;
        $description = $request->report_msg;
        $report = new Report();
        $report->user_id = $user_id;
        $report->post_id = $post_id;
        $report->description = $description;
        $report->save();

        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        );
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),

            $options
        );

        $data = 'Thanks for your report,we will check this post.';
        $new_data = 'Post is Reported';

        $user_rp = new Notification();
        $user_rp->description = $data;
        $user_rp->date = Carbon::Now()->toDateTimeString();

        $user_rp->sender_id = $admin_id;
        $user_rp->receiver_id =  auth()->user()->id;
        $user_rp->notification_status = 1;
        $user_rp->report_id = $report->id;
        $user_rp->save();

        $admin_rp = new Notification();
        $admin_rp->description = $new_data;
        $admin_rp->date = Carbon::Now()->toDateTimeString();
        $admin_rp->sender_id = auth()->user()->id;
        $admin_rp->notification_status = 1;
        $admin_rp->receiver_id = $admin_id;
        $admin_rp->report_id = $report->id;
        $admin_rp->save();

        $pusher->trigger('friend_request.' . auth()->user()->id, 'friendRequest', $data);

        $pusher->trigger('friend_request.' . $admin_id, 'friendRequest', $new_data);
        // return response()->json([
        //     'success' => 'Reported Success'
        // ]);
    }
}
