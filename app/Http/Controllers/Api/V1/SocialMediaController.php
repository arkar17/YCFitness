<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\Chat;
use App\Models\Post;
use App\Models\User;
use App\Models\Report;
use App\Models\Comment;
use App\Models\Profile;
use App\Events\Chatting;
use App\Models\ChatGroup;
use App\Models\Friendship;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Events\GroupChatting;
use App\Events\MakeAgoraCall;
use App\Events\MessageDelete;
use App\Models\UserReactPost;
use App\Models\UserSavedPost;
use App\Events\GroupAudioCall;
use App\Events\GroupVideoCall;
use App\Events\DeclineCallUser;
use App\Models\ChatGroupMember;
use App\Models\ChatGroupMessage;
use App\Events\MakeAgoraAudioCall;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Class\AgoraDynamicKey\RtcTokenBuilder;

class SocialMediaController extends Controller
{
    //for user search
    public function newFeeds()
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
            $posts = Post::select('users.name', 'profiles.profile_image', 'posts.*')
                ->whereIn('posts.user_id', $n)
                ->where('posts.shop_status',0)
                ->leftJoin('users', 'users.id', 'posts.user_id')
                ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
                ->orderBy('posts.created_at', 'DESC')
                ->paginate(30);
            $saved_post = UserSavedPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_saved_posts.post_id')
                ->whereIn('user_saved_posts.user_id', $n)
                ->get();
            $liked_post = UserReactPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_react_posts.post_id')
                ->where('user_react_posts.user_id', $user_id)->get();

            $liked_post_count = DB::select("SELECT COUNT(post_id) as like_count, post_id FROM user_react_posts GROUP BY post_id");

            $comment_post_count = DB::select("SELECT COUNT(post_id) as comment_count, post_id FROM comments GROUP BY post_id");
            //dd($comment_post_count);
            foreach ($posts as $key => $value) {
                $posts[$key]['is_save'] = 0;
                $posts[$key]['is_like'] = 0;
                $posts[$key]['like_count'] = 0;
                $posts[$key]['comment_count'] = 0;
                // dd($value->id);
                foreach ($saved_post as $saved_key => $save_value) {

                    if ($save_value->id === $value->id) {
                        $posts[$key]['is_save'] = 1;
                        break;
                    } else {
                        $posts[$key]['is_save'] = 0;
                    }
                }
                foreach ($liked_post as $liked_key => $liked_value) {
                    if ($liked_value->id === $value->id) {
                        $posts[$key]['is_like'] = 1;
                        break;
                    } else {
                        $posts[$key]['is_like'] = 0;
                    }
                }
                foreach ($liked_post_count as $like_count) {
                    if ($like_count->post_id === $value->id) {
                        $posts[$key]['like_count'] = $like_count->like_count;
                        break;
                    } else {
                        $posts[$key]['like_count'] = 0;
                    }
                }
                foreach ($comment_post_count as $comment_count) {
                    if ($comment_count->post_id === $value->id) {
                        $posts[$key]['comment_count'] = $comment_count->comment_count;
                        break;
                    } else {
                        $posts[$key]['comment_count'] = 0;
                    }
                }
            }
        } else {
            $posts = Post::select('users.name', 'profiles.profile_image', 'posts.*')
                ->where('posts.user_id', $user->id)
                ->where('posts.shop_status', 0)
                ->leftJoin('users', 'users.id', 'posts.user_id')
                ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
                ->orderBy('posts.created_at', 'DESC')
                ->paginate(30);

            $saved_post = UserSavedPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_saved_posts.post_id')
                ->where('user_saved_posts.user_id', $user->id)
                ->get();

            $liked_post = UserReactPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_react_posts.post_id')
                ->where('user_react_posts.user_id', $user_id)->get();

            $liked_post_count = DB::select("SELECT COUNT(post_id) as like_count, post_id FROM user_react_posts GROUP BY post_id");

            $comment_post_count = DB::select("SELECT COUNT(post_id) as comment_count, post_id FROM comments GROUP BY post_id");

            foreach ($posts as $key => $value) {
                $posts[$key]['is_save'] = 0;
                $posts[$key]['is_like'] = 0;
                $posts[$key]['like_count'] = 0;
                $posts[$key]['comment_count'] = 0;
                // dd($value->id);
                foreach ($saved_post as $saved_key => $save_value) {

                    if ($save_value->id === $value->id) {
                        $posts[$key]['is_save'] = 1;
                        break;
                    } else {
                        $posts[$key]['is_save'] = 0;
                    }
                }

                foreach ($liked_post as $liked_key => $liked_value) {
                    if ($liked_value->id === $value->id) {
                        $posts[$key]['is_like'] = 1;
                        break;
                    } else {
                        $posts[$key]['is_like'] = 0;
                    }
                }
                foreach ($liked_post_count as $like_count) {
                    if ($like_count->post_id === $value->id) {
                        $posts[$key]['like_count'] = $like_count->like_count;
                        break;
                    } else {
                        $posts[$key]['like_count'] = 0;
                    }
                }
                foreach ($comment_post_count as $comment_count) {
                    if ($comment_count->post_id === $value->id) {
                        $posts[$key]['comment_count'] = $comment_count->comment_count;
                        break;
                    } else {
                        $posts[$key]['comment_count'] = 0;
                    }
                }
            }
        }
        return response()
            ->json([
                'data' => $posts
            ]);
    }
    public function search_users(Request $request)
    {
        $users = User::select('users.id', 'users.name', 'profiles.profile_image')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->where('users.name', 'LIKE', '%' . $request->keyword . '%')
            ->orWhere('users.phone', 'LIKE', '%' . $request->keyword . '%')->get();
        $friends = DB::table('friendships')->get();
        return response()->json([
            'users' => $users,
            'friends' => $friends,
        ]);
    }
    //for add friends
    public function add_friends(Request $request)
    {
        $id = $request->id;
        $user_id = auth()->user()->id;
        $sender = User::where('id', $user_id)->first();

        $friendship = new Friendship();
        $friendship->sender_id = $user_id;
        $friendship->receiver_id = $id;
        $friendship->date = Carbon::Now()->toDateString();
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
        $pusher->trigger('friend_request.' . $id, 'friendRequest', $fri_noti);
        return response()
            ->json([
                'data' => $data
            ]);
    }

    // unfirend
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
        return response()->json([
            'message' => 'Unfriend Success!'
        ]);
    }
    //confirm request
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

        $pusher->trigger('friend_request.' . $request->id, 'friendRequest', $fri_noti);
        return response()->json([
            'message' => 'Accepted Success!'
        ]);
    }

    public function cancelRequest(Request $request)
    {
        $user_id = auth()->user()->id;
        $friend_ship_delete = Friendship::where('sender_id', $user_id)->where('receiver_id', $request->id);
        $friend_ship_delete->delete();
        $noti_delete = Notification::where('sender_id', $user_id)->where('receiver_id', $request->id);
        $noti_delete->delete();
        return response()->json([
            'message' => 'Cancled Success!'
        ]);
    }

    public function declineRequest(Request $request)
    {
        $user_id = auth()->user()->id;
        $friend_ship_delete = Friendship::where('sender_id', $request->id)->where('receiver_id', $user_id);
        $friend_ship_delete->delete();
        $noti_delete = Notification::where('sender_id', $request->id)->where('receiver_id', $user_id);
        $noti_delete->delete();
        return response()->json([
            'message' => 'Declined Success!'
        ]);
    }

    public function socialmedia_profile(Request $request)
    {
        $auth = Auth()->user()->id;
        $id = $request->id;

        $profile = DB::table('users')
            ->select('users.id', 'users.name', 'users.bio', 'profiles.profile_image', 'profiles.cover_photo')
            ->leftjoin('profiles', 'profiles.id', '=', 'users.profile_id')
            ->where('users.id', $id)
            ->get();

        $cover = DB::table('users')
            ->select('profiles.cover_photo')
            ->leftjoin('profiles', 'profiles.id', '=', 'users.cover_id')
            ->where('users.id', $id)
            ->get();

        foreach ($profile as $value) {
            foreach ($cover as $cover_index) {
                $value->cover_photo = $cover_index->cover_photo;
            }
        }
        $posts = Post::select('users.name', 'profiles.profile_image', 'posts.*')
            ->where('posts.user_id', $id)
            ->leftJoin('users', 'users.id', 'posts.user_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->orderBy('posts.created_at', 'DESC')
            ->where('posts.shop_status',0)
            ->paginate(30);

        $saved_post = UserSavedPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_saved_posts.post_id')
            ->where('user_saved_posts.user_id', $auth)
            ->get();

        $liked_post = UserReactPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_react_posts.post_id')
            ->where('user_react_posts.user_id', $auth)->get();
        $liked_post_count = DB::select("SELECT COUNT(post_id) as like_count, post_id FROM user_react_posts GROUP BY post_id");

        $comment_post_count = DB::select("SELECT COUNT(post_id) as comment_count, post_id FROM comments GROUP BY post_id");
        // dd($liked_post);

        foreach ($posts as $key => $value) {
            $posts[$key]['is_save'] = 0;
            $posts[$key]['is_like'] = 0;
            $posts[$key]['like_count'] = 0;
            $posts[$key]['comment_count'] = 0;
            // dd($value->id);
            foreach ($saved_post as $saved_key => $save_value) {

                if ($save_value->id === $value->id) {
                    $posts[$key]['is_save'] = 1;
                    break;
                } else {
                    $posts[$key]['is_save'] = 0;
                }
            }
            foreach ($liked_post as $liked_key => $liked_value) {
                if ($liked_value->id === $value->id) {
                    $posts[$key]['is_like'] = 1;
                    break;
                } else {
                    $posts[$key]['is_like'] = 0;
                }
            }
            foreach ($liked_post_count as $like_count) {
                if ($like_count->post_id === $value->id) {
                    $posts[$key]['like_count'] = $like_count->like_count;
                    break;
                } else {
                    $posts[$key]['like_count'] = 0;
                }
            }
            foreach ($comment_post_count as $comment_count) {
                if ($comment_count->post_id === $value->id) {
                    $posts[$key]['comment_count'] = $comment_count->comment_count;
                    break;
                } else {
                    $posts[$key]['comment_count'] = 0;
                }
            }
        }
        $friendships = DB::table('friendships')
            ->where('friend_status', 2)
            ->where(function ($query) use ($id) {
                $query->where('sender_id', $id)
                    ->orWhere('receiver_id', $id);
            })
            ->join('users as sender', 'sender.id', 'friendships.sender_id')
            ->join('users as receiver', 'receiver.id', 'friendships.receiver_id')
            ->get(['sender_id', 'receiver_id'])->toArray();

        $n = array();
        foreach ($friendships as $friend) {
            $f = (array)$friend;
            array_push($n, $f['sender_id'], $f['receiver_id']);
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
            ->take(6)->get();

        $friend_status = DB::select("SELECT * FROM `friendships` WHERE (receiver_id = $auth or sender_id = $auth )
            AND (receiver_id = $id or sender_id = $id)");

        return response()->json([
            'user' => $profile,
            'friend_status' => $friend_status,
            'friends' => $friends,
            'posts' => $posts
        ]);
    }

    public function cover_profile_photo(Request $request)
    {
        $id = $request->id;
        $cover_photo = Profile::select('cover_photo', 'id')
            ->where('user_id', $id)->where('profile_image', null)->get();
        $profile_photo = Profile::select('profile_image', 'id')
            ->where('user_id', $id)->where('cover_photo', null)->get();
        return response()->json([
            'cover_photo' => $cover_photo,
            'profile_photo' => $profile_photo,
        ]);
    }

    public function friends(Request $request)
    {
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
            ->paginate(3)->toArray();
        return response()->json([
            'friends' => $friends
        ]);
    }

    public function friends_for_mention(Request $request)
    {
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

    public function notification()
    {
        $notification = Notification::select(
            'users.id as user_id',
            'users.name',
            'notifications.*',
            'profiles.profile_image'
        )
            ->leftJoin('users', 'notifications.sender_id', '=', 'users.id')
            ->leftJoin('profiles', 'profiles.id', 'users.profile_id')
            ->where('receiver_id', auth()->user()->id)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        return response()->json([
            'notification' => $notification
        ]);
    }

    public function viewFriendRequestNoti(Request $request)
    {
        $auth = Auth()->user()->id;
        DB::table('notifications')->where('id', $request->noti_id)->update(['notification_status' => 2]);
        $user = User::where('id', $request->id)->first();
        Friendship::where('sender_id', auth()->user()->id)->orWhere('receiver_id', auth()->user()->id)->first();
        DB::select("SELECT * FROM `friendships` WHERE (receiver_id = $auth or sender_id = $auth )
        AND (receiver_id = $request->id or sender_id = $request->id)");
        return response()->json([
            'user' => $user,
        ]);
    }

    public function friend_request()
    {

        $auth = Auth()->user()->id;

        $friend_request = DB::table('friendships')
            ->where('friend_status', 1)
            ->where(function ($query) use ($auth) {
                $query->where('sender_id', $auth)
                    ->orWhere('receiver_id', $auth);
            })
            ->join('users as sender', 'sender.id', 'friendships.sender_id')
            ->join('users as receiver', 'receiver.id', 'friendships.receiver_id')
            ->get(['sender_id', 'receiver_id'])->toArray();

        // dd($friend_request);
        $request = array();
        foreach ($friend_request as $req) {
            $r = (array)$req;
            array_push($request, $r['sender_id'], $r['receiver_id']);
        }
        $request_profile_id = DB::table('profiles')
            ->groupBy('user_id')
            ->select(DB::raw('max(id) as id'))
            ->where('cover_photo', null)
            ->whereIn('user_id', $request)
            ->get()
            ->pluck('id')->toArray();

        if (empty($request_profile_id)) {
            $friend_requests = DB::select("SELECT u.name,u.id,f.date,p.profile_image FROM friendships f
                LEFT JOIN users u
                on (u.id = f.sender_id)
                LEFT JOIN profiles p on p.user_id = u.id
                where  (receiver_id = $auth)
                and f.friend_status = 1");
        } else {
            $ids = join(",", $request_profile_id);
            $friend_requests = DB::select("SELECT u.name,u.id,f.date,p.profile_image FROM friendships f
                LEFT JOIN users u
                on (u.id = f.sender_id)
                LEFT JOIN profiles p on p.user_id = u.id
                and p.id IN ($ids)
                where  (receiver_id = $auth)
                and f.friend_status = 1");
        }
        return response()->json([
            'friend_request' =>  $friend_requests
        ]);
    }

    public function post_store(Request $request)
    {
        $input = $request->all();
        $user = auth()->user();
        $post = new Post();
        if (empty($input['addPostInput'])  && $input['caption'] != null) {
            $caption = $input['caption'];
        } elseif ($input['caption'] == null) {
            $caption = null;

            if ($input['addPostInput']) {

                $images = $input['addPostInput'];
                $filenames = $input['filenames'];
                foreach ($images as $index => $file) {

                    $tmp = base64_decode($file);
                    $file_name = $filenames[$index];
                    Storage::disk('public')->put(
                        'post/' . $file_name,
                        $tmp
                    );
                    $imgData[] = $file_name;
                    $post->media = json_encode($imgData);
                }
            }
        } else {
            $caption = $input['caption'];
            $images = $input['addPostInput'];
            if ($input['addPostInput']) {

                $images = $input['addPostInput'];
                $filenames = $input['filenames'];
                foreach ($images as $index => $file) {

                    $tmp = base64_decode($file);
                    $file_name = $filenames[$index];
                    Storage::disk('public')->put(
                        'post/' . $file_name,
                        $tmp
                    );
                    $imgData[] = $file_name;
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
                return response()->json([
                    'message' => 'ban',
                ]);
            } elseif (str_contains($caption, $m_banword)) {
                return response()->json([
                    'message' => 'ban',
                ]);
            } elseif (str_contains($caption, $em_banword)) {
                return response()->json([
                    'message' => 'ban',
                ]);
            }
        }

        $post->user_id = $user->id;
        $post->caption = $caption;
        $post->save();

        $id = $post->id;

        $post_one = Post::select('users.name', 'profiles.profile_image', 'posts.*')
            ->where('posts.id', $id)
            ->leftJoin('users', 'users.id', 'posts.user_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->first();

        foreach ($post_one as $key => $value) {
            $post_one['is_save'] = 0;
            $post_one['is_like'] = 0;
            $post_one['like_count'] = 0;
            $post_one['comment_count'] = 0;
        }
        return response()->json([
            'data' => $post_one
        ]);
    }

    public function post_destroy(Request $request)
    {
        Post::find($request->id)->delete($request->id);

        return response()->json([
            'success' => 'Post deleted successfully!'
        ]);
    }

    public function post_edit(Request $request)
    {
        $post = Post::find($request->id);
        foreach ($post->media as $media) {
        }
        if ($post) {

            return response()->json([
                'status' => 200,
                'post' => $post,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Data Not Found',
            ]);
        }
    }

    public function post_update(Request $request)
    {
        $input = $request->all();
        // return $request->all();
        $edit_post = Post::findOrFail($input['edit_post_id']);
        $edit_post->caption = $input['caption'];

        if (empty($input['addPostInput'])  && $input['caption'] != null) {
            $caption = $input['caption'];
            $updateFilenames = $input['filenames'];
            $edit_post->media = json_encode($updateFilenames);
        } elseif ($input['caption'] == null) {
            $caption = null;
            if ($input['addPostInput']) {

                $images = $input['addPostInput'];

                $updateFilenames = $input['filenames'];
                $newFilenames = $input['newFileNames'];

                foreach ($images as $index => $file) {

                    $tmp = base64_decode($file);

                    $file_name = $newFilenames[$index];
                    Storage::disk('public')->put(
                        'post/' . $file_name,
                        $tmp
                    );
                    //  $imgData[] = $tmp;
                    //  $edit_post->media = json_encode($imgData);
                }
                $edit_post->media = json_encode($updateFilenames);
            }
        } elseif ($input['addPostInput'] == null && $input['caption'] == null) {
            $caption = $input['caption'];
            $updateFilenames = $input['filenames'];
            $edit_post->media = json_encode($updateFilenames);
        } else {
            $caption = $input['caption'];
            $images = $input['addPostInput'];
            if ($input['addPostInput']) {

                $images = $input['addPostInput'];

                $updateFilenames = $input['filenames'];
                $newFilenames = $input['newFileNames'];

                foreach ($images as $index => $file) {

                    $tmp = base64_decode($file);

                    $file_name = $newFilenames[$index];
                    Storage::disk('public')->put(
                        'post/' . $file_name,
                        $tmp
                    );
                    //  $imgData[] = $tmp;
                    //  $edit_post->media = json_encode($imgData);
                }
                $edit_post->media = json_encode($updateFilenames);
            }
        }
        $banwords = DB::table('ban_words')->select('ban_word_english', 'ban_word_myanmar', 'ban_word_myanglish')->get();

        foreach ($banwords as $b) {
            $e_banword = $b->ban_word_english;
            $m_banword = $b->ban_word_myanmar;
            $em_banword = $b->ban_word_myanglish;

            if (str_contains($caption, $e_banword)) {
                return response()->json([
                    'message' => 'ban',
                ]);
            } elseif (str_contains($caption, $m_banword)) {
                return response()->json([
                    'message' => 'ban',
                ]);
            } elseif (str_contains($caption, $em_banword)) {
                return response()->json([
                    'message' => 'ban',
                ]);
            }
        }
        $edit_post->caption = $caption;

        $edit_post->update();

        $id = $edit_post->id;

        $saved_post = UserSavedPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_saved_posts.post_id')
            ->where('user_saved_posts.post_id', $id)
            ->where('user_saved_posts.user_id', auth()->user()->id)
            ->first();
        //  dd($saved_post);
        $post = Post::select('users.name', 'profiles.profile_image', 'posts.*')
            ->where('posts.id', $id)
            ->leftJoin('users', 'users.id', 'posts.user_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->first();

        $liked_post = UserReactPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_react_posts.post_id')
            ->where('user_react_posts.post_id', $id)
            ->where('user_react_posts.user_id', auth()->user()->id)
            ->first();

        $liked_post_count = DB::select("SELECT COUNT(post_id) as like_count, post_id FROM user_react_posts WHERE post_id = $id");

        $comment_post_count = DB::select("SELECT COUNT(post_id) as comment_count, post_id FROM comments WHERE post_id = $id");
        // dd($comment_post_count);
        // dd($liked_post);

        foreach ($post as $key => $value) {
            // dd($post);
            $post['is_save'] = 0;
            $post['is_like'] = 0;
            $post['like_count'] = 0;
            $post['comment_count'] = 0;
            // dd($value->id);
            if (empty($saved_post)) {
                $post['is_save'] = 0;
            } else {
                $post['is_save'] = 1;
            }
            if (!empty($liked_post)) {
                $post['is_like'] = 1;
            } else {
                $post['like_count'] = 0;
            }
            if (!empty($liked_post_count)) {
                foreach ($liked_post_count as $like_count) {
                    $post['like_count'] = $like_count->like_count;
                }
            } else {
                $post['like_count'] = 0;
            }

            if (!empty($comment_post_count)) {
                foreach ($comment_post_count as $comment_count) {
                    $post['comment_count'] = $comment_count->comment_count;
                }
            } else {
                $post['comment_count'] = 0;
            }
        }
        return response()->json([
            'data' => $post,
        ]);
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
            $id = $request['post_id'];
            $auth = auth()->user()->id;
            $saved_post = UserSavedPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_saved_posts.post_id')
                ->where('user_saved_posts.post_id', $id)
                ->where('user_saved_posts.user_id', $auth)
                ->first();
            //  dd($saved_post);
            $post = Post::select('users.name', 'profiles.profile_image', 'posts.*')
                ->where('posts.id', $id)
                ->leftJoin('users', 'users.id', 'posts.user_id')
                ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
                ->first();

            $liked_post = UserReactPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_react_posts.post_id')
                ->where('user_react_posts.post_id', $id)
                ->where('user_react_posts.user_id', $auth)
                ->first();

            $liked_post_count = DB::select("SELECT COUNT(post_id) as like_count, post_id FROM user_react_posts WHERE post_id = $id");

            $comment_post_count = DB::select("SELECT COUNT(post_id) as comment_count, post_id FROM comments WHERE post_id = $id");
            foreach ($post as $key => $value) {
                $post['is_save'] = 0;
                $post['is_like'] = 0;
                $post['like_count'] = 0;
                $post['comment_count'] = 0;
                if (empty($saved_post)) {
                    $post['is_save'] = 0;
                } else {
                    $post['is_save'] = 1;
                }
                if (!empty($liked_post)) {
                    $post['is_like'] = 1;
                } else {
                    $post['like_count'] = 0;
                }
                if (!empty($liked_post_count)) {
                    foreach ($liked_post_count as $like_count) {
                        $post['like_count'] = $like_count->like_count;
                    }
                } else {
                    $post['like_count'] = 0;
                }

                if (!empty($comment_post_count)) {
                    foreach ($comment_post_count as $comment_count) {
                        $post['comment_count'] = $comment_count->comment_count;
                    }
                } else {
                    $post['comment_count'] = 0;
                }
            }

            return response()->json([
                'data' => $post,
            ]);
        } else {
            $user_save_post->user_id = $user->id;
            $user_save_post->post_id = $post_id;
            $user_save_post->saved_status = 1;
            $user_save_post->save();

            $id = $request['post_id'];
            $auth = auth()->user()->id;
            $saved_post = UserSavedPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_saved_posts.post_id')
                ->where('user_saved_posts.post_id', $id)
                ->where('user_saved_posts.user_id', $auth)
                ->first();
            //  dd($saved_post);
            $post = Post::select('users.name', 'profiles.profile_image', 'posts.*')
                ->where('posts.id', $id)
                ->leftJoin('users', 'users.id', 'posts.user_id')
                ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
                ->first();

            $liked_post = UserReactPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_react_posts.post_id')
                ->where('user_react_posts.post_id', $id)
                ->where('user_react_posts.user_id', $auth)
                ->first();

            $liked_post_count = DB::select("SELECT COUNT(post_id) as like_count, post_id FROM user_react_posts WHERE post_id = $id");

            $comment_post_count = DB::select("SELECT COUNT(post_id) as comment_count, post_id FROM comments WHERE post_id = $id");
            foreach ($post as $key => $value) {
                $post['is_save'] = 0;
                $post['is_like'] = 0;
                $post['like_count'] = 0;
                $post['comment_count'] = 0;
                if (empty($saved_post)) {
                    $post['is_save'] = 0;
                } else {
                    $post['is_save'] = 1;
                }
                if (!empty($liked_post)) {
                    $post['is_like'] = 1;
                } else {
                    $post['like_count'] = 0;
                }
                if (!empty($liked_post_count)) {
                    foreach ($liked_post_count as $like_count) {
                        $post['like_count'] = $like_count->like_count;
                    }
                } else {
                    $post['like_count'] = 0;
                }

                if (!empty($comment_post_count)) {
                    foreach ($comment_post_count as $comment_count) {
                        $post['comment_count'] = $comment_count->comment_count;
                    }
                } else {
                    $post['comment_count'] = 0;
                }
            }

            return response()->json([
                'data' => $post,
            ]);
        }
    }

    public function saved_post()
    {
        $posts = UserSavedPost::select('users.name', 'profiles.profile_image', 'posts.*')
            ->leftJoin('posts', 'posts.id', 'user_saved_posts.post_id')
            ->where('user_saved_posts.user_id', auth()->user()->id)
            ->leftJoin('users', 'users.id', 'posts.user_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->orderBy('posts.created_at', 'DESC')
            ->get();

        $liked_post = UserReactPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_react_posts.post_id')
            ->where('user_react_posts.user_id', auth()->user()->id)->get();

        $liked_post_count = DB::select("SELECT COUNT(post_id) as like_count, post_id FROM user_react_posts GROUP BY post_id");

        $comment_post_count = DB::select("SELECT COUNT(post_id) as comment_count, post_id FROM comments GROUP BY post_id");
        //dd($comment_post_count);
        foreach ($posts as $key => $value) {
            $posts[$key]['is_save'] = 1;
            $posts[$key]['is_like'] = 0;
            $posts[$key]['like_count'] = 0;
            $posts[$key]['comment_count'] = 0;
            // dd($value->id);

            foreach ($liked_post as $liked_key => $liked_value) {
                if ($liked_value->id === $value->id) {
                    $posts[$key]['is_like'] = 1;
                    break;
                } else {
                    $posts[$key]['is_like'] = 0;
                }
            }
            foreach ($liked_post_count as $like_count) {
                if ($like_count->post_id === $value->id) {
                    $posts[$key]['like_count'] = $like_count->like_count;
                    break;
                } else {
                    $posts[$key]['like_count'] = 0;
                }
            }
            foreach ($comment_post_count as $comment_count) {
                if ($comment_count->post_id === $value->id) {
                    $posts[$key]['comment_count'] = $comment_count->comment_count;
                    break;
                } else {
                    $posts[$key]['comment_count'] = 0;
                }
            }
        }

        return response()->json([
            'save' => $posts
        ]);
    }


    public function one_post(Request $request)
    {
        $id = $request->id;
        $auth = auth()->user()->id;
        $saved_post = UserSavedPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_saved_posts.post_id')
            ->where('user_saved_posts.post_id', $id)
            ->where('user_saved_posts.user_id', auth()->user()->id)
            ->first();
        //  dd($saved_post);
        $post = Post::select('users.name', 'profiles.profile_image', 'posts.*')
            ->where('posts.id', $id)
            ->leftJoin('users', 'users.id', 'posts.user_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->first();

        $liked_post = UserReactPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_react_posts.post_id')
            ->where('user_react_posts.post_id', $id)
            ->where('user_react_posts.user_id', auth()->user()->id)
            ->first();

        $liked_post_count = DB::select("SELECT COUNT(post_id) as like_count, post_id FROM user_react_posts WHERE post_id = $id");

        $comment_post_count = DB::select("SELECT COUNT(post_id) as comment_count, post_id FROM comments WHERE post_id = $id");
        // dd($comment_post_count);
        // dd($liked_post);

        foreach ($post as $key => $value) {
            // dd($post);
            $post['is_save'] = 0;
            $post['is_like'] = 0;
            $post['like_count'] = 0;
            $post['comment_count'] = 0;
            // dd($value->id);
            if (empty($saved_post)) {
                $post['is_save'] = 0;
            } else {
                $post['is_save'] = 1;
            }
            if (!empty($liked_post)) {
                $post['is_like'] = 1;
            } else {
                $post['like_count'] = 0;
            }
            if (!empty($liked_post_count)) {
                foreach ($liked_post_count as $like_count) {
                    $post['like_count'] = $like_count->like_count;
                }
            } else {
                $post['like_count'] = 0;
            }

            if (!empty($comment_post_count)) {
                foreach ($comment_post_count as $comment_count) {
                    $post['comment_count'] = $comment_count->comment_count;
                }
            } else {
                $post['comment_count'] = 0;
            }
        }
        return response()->json([
            'post' => $post
        ]);
    }

    public function profile_update_cover(Request $request)
    {
        $tmp = $request->cover;
        $file = base64_decode($tmp);
        $image_name = $request->name;
        Storage::disk('local')->put(
            'public/post/' . $image_name,
            $file
        );
        $profile = new Profile();
        $profile->cover_photo = $image_name;
        $profile->user_id = auth()->user()->id;
        $profile->save();
        $user = User::findOrFail(auth()->user()->id);
        $user->cover_id = $profile->id;
        $user->update();
        return response()->json([
            'message' => 'Success',
        ]);
    }

    public function profile_update_profile_img(Request $request)
    {
        $tmp = $request->profile;
        $file = base64_decode($tmp);
        $image_name = $request->name;
        Storage::disk('local')->put(
            'public/post/' . $image_name,
            $file
        );
        $profile = new Profile();
        $profile->profile_image = $image_name;
        $profile->user_id = auth()->user()->id;
        $profile->save();

        $user = User::findOrFail(auth()->user()->id);
        $user->profile_id = $profile->id;
        $user->update();
        return response()->json([
            'message' => 'Success',
        ]);
    }

    public function profile_update_bio(Request $request)
    {
        $user_id = auth()->user()->id;
        $user = User::findOrFail($user_id);
        $user->bio = $request->bio;
        $user->update();
        return response()->json([
            'message' => 'Success',
        ]);
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
            'success' => 'Success!'
        ]);
    }

    public function chatting(Request $request, User $user)
    {
        if ($request->text == null && $request->fileSend == null) {
        } else {
            $message = new Chat();
            $sendFile = $request->all();
            if ($request->totalFiles != 0) {
                $files = $sendFile['fileSend'];
                if ($sendFile['fileSend']) {
                    foreach ($files as $file) {
                        $extension = $file->extension();
                        $name = rand() . "." . $extension;
                        $file->storeAs('/public/customer_message_media/', $name);
                        $imgData[] = $name;
                        $message->media = json_encode($imgData);
                        $message->text = null;
                    }
                }
            } else {
                $message->text = $request->text;
                $message->media = null;
            }

            $message->from_user_id = auth()->user()->id;
            $message->to_user_id = $user->id;
            $message->save();

            broadcast(new Chatting($message, $request->sender));
        }
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true);
        $pusher = new Pusher(
                        env('PUSHER_APP_KEY'),
                        env('PUSHER_APP_SECRET'),
                        env('PUSHER_APP_ID'),
                        $options);
        $to_user_id = $user->id;
        $user_id = auth()->user()->id;
        $messages = DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date
        from
            chats
          join
            (select user, max(created_at) m
                from
                   (
                     (select id, to_user_id user, created_at
                       from chats
                       where from_user_id= $user_id  and delete_status <> 2 and deleted_by != $user_id )
                   union
                     (select id, from_user_id user, created_at
                       from chats
                       where to_user_id= $user_id  and delete_status <> 2 and deleted_by != $user_id)
                    ) t1
               group by user) t2
                on ((from_user_id= $user_id and to_user_id=user) or
                    (from_user_id=user and to_user_id= $user_id)) and
                    (created_at = m)
                left join users on users.id = user
                left join profiles on users.profile_id = profiles.id
                where deleted_by !=  $user_id  and delete_status != 2
            order by chats.created_at desc");

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

        //to user
        $messages_to =DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date
        from
            chats
          join
            (select user, max(created_at) m
                from
                   (
                     (select id, to_user_id user, created_at
                       from chats
                       where from_user_id= $to_user_id  and delete_status <> 2 and deleted_by != $to_user_id )
                   union
                     (select id, from_user_id user, created_at
                       from chats
                       where to_user_id= $to_user_id  and delete_status <> 2 and deleted_by != $to_user_id)
                    ) t1
               group by user) t2
                on ((from_user_id= $to_user_id and to_user_id=user) or
                    (from_user_id=user and to_user_id= $to_user_id)) and
                    (created_at = m)
                left join users on users.id = user
                left join profiles on users.profile_id = profiles.id
                where deleted_by !=  $to_user_id  and delete_status != 2
            order by chats.created_at desc");

            $groups_to = DB::table('chat_group_members')
                        ->select('group_id')
                        ->groupBy('group_id')
                        ->where('chat_group_members.member_id',$to_user_id)
                        ->get()
                        ->pluck('group_id')->toArray();

            $latest_group_message_to = DB::table('chat_group_messages')
                        ->groupBy('group_id')
                        ->whereIn('group_id',$groups_to)
                        ->select(DB::raw('max(id) as id'))
                        ->get()
                        ->pluck('id')->toArray();
            $latest_group_sms_to =ChatGroupMessage::
                    select('chat_group_messages.group_id as id','chat_groups.group_name as name',
                    'profiles.profile_image','chat_group_messages.text',
                    DB::raw('DATE_FORMAT(chat_group_messages.created_at, "%Y-%m-%d %H:%i:%s") as date'))
                    ->leftJoin('chat_groups','chat_groups.id','chat_group_messages.group_id')
                    ->leftJoin('users','users.id','chat_group_messages.sender_id')
                    ->leftJoin('profiles','users.profile_id','profiles.id')
                    ->whereIn('chat_group_messages.id',$latest_group_message_to)->get()->toArray();
                    //   $ids = json_encode($messages);
            $arr_to = json_decode(json_encode ( $messages_to ) , true);
            foreach($arr_to as $key=>$value){
                $arr_to[$key]['is_group'] = 0;
            }
            foreach($latest_group_sms_to as $key=>$value){
                $latest_group_sms_to[$key]['is_group'] = 1;
            }
                    $merged_to = array_merge($arr_to, $latest_group_sms_to);
                    $keys_to = array_column($merged_to, 'date');
                    array_multisort($keys_to, SORT_DESC, $merged_to);
                    $group_owner_to = ChatGroup::whereIn('chat_groups.id',$groups_to)->get();
                    foreach($merged_to as $key=>$value){
                           $merged_to[$key]['owner_id'] = 0;
                        foreach($group_owner_to as $owner){
                            if($value['id'] == $owner['id'] AND $value['is_group'] == 1)
                            $merged_to[$key]['owner_id'] = $owner->group_owner_id;
                           }
                    }

        $arr_six = array_reverse($merged);
        $arr_six = array_slice($arr_six, -6);
        $arr_six = array_reverse($arr_six);

        $arr_six_to = array_reverse($merged_to);
        $arr_six_to = array_slice($arr_six_to, -6);
        $arr_six_to = array_reverse($arr_six_to);



        $pusher->trigger('all_message.'. $to_user_id , 'all', $merged_to);
        $pusher->trigger('all_message.'. $user_id , 'all', $merged);
        $pusher->trigger('chat_message.' . $to_user_id, 'chat', $arr_six_to);
        $pusher->trigger('chat_message.' . $user_id, 'chat', $arr_six);
    }

    public function group_chatting(Request $request, $id)
    {
        if ($request->text == null && $request->fileSend == null) {
        } else {

            $message = new ChatGroupMessage();
            $sendFile = $request->all();
            if ($request->totalFiles != 0) {
                $files = $sendFile['fileSend'];
                if ($sendFile['fileSend']) {
                    foreach ($files as $file) {
                        $extension = $file->extension();
                        $name = rand() . "." . $extension;
                        $file->storeAs('/public/customer_message_media/', $name);
                        $imgData[] = $name;
                        // $message->media .= $name.',';
                        $message->media = json_encode($imgData);
                    }
                } else {
                    $message->text = $request->text;
                    $message->media = null;
                }
            } else {
                $message->text = $request->text;
                $message->media = null;
            }
            $message->group_id = $id;
            $message->sender_id = $request->senderId;
            $message->save();


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

            $group_message = ChatGroupMember::select('member_id')->where('group_id', $id)->get();
            for ($i = 0; count($group_message) > $i; $i++) {
                $user_id = $group_message[$i]['member_id'];
                //all
                $messages_all = DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date,chats.from_user_id as from_id,chats.to_user_id as to_id
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

                $groups_all = DB::table('chat_group_members')
                    ->select('group_id')
                    ->groupBy('group_id')
                    ->where('chat_group_members.member_id', $user_id)
                    ->get()
                    ->pluck('group_id')->toArray();

                $latest_group_message_all = DB::table('chat_group_messages')
                    ->groupBy('group_id')
                    ->whereIn('group_id', $groups_all)
                    ->select(DB::raw('max(id) as id'))
                    ->get()
                    ->pluck('id')->toArray();
                $latest_group_sms_all = ChatGroupMessage::select(
                        'chat_group_messages.group_id as id',
                        'chat_groups.group_name as name',
                        'profiles.profile_image',
                        'chat_group_messages.text',
                        DB::raw('DATE_FORMAT(chat_group_messages.created_at, "%Y-%m-%d %H:%i:%s") as date')
                    )
                    ->leftJoin('chat_groups', 'chat_groups.id', 'chat_group_messages.group_id')
                    ->leftJoin('users', 'users.id', 'chat_group_messages.sender_id')
                    ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
                    ->whereIn('chat_group_messages.id', $latest_group_message_all)
                    ->orderBy('chat_group_messages.created_at','DESC')
                    ->get()->toArray();
                //   $ids = json_encode($messages);
                $arr_all = json_decode(json_encode($messages_all), true);
                foreach ($arr_all as $key => $value) {
                    $arr_all[$key]['is_group'] = 0;
                }
                foreach ($latest_group_sms_all as $key => $value) {
                    $latest_group_sms_all[$key]['is_group'] = 1;
                }
                $merged_all = array_merge($arr_all, $latest_group_sms_all);
                $keys_all = array_column($merged_all, 'date');
                array_multisort($keys_all, SORT_DESC, $merged_all);
                $group_owner_all = ChatGroup::whereIn('chat_groups.id', $groups_all)->get();
                foreach ($merged_all as $key => $value) {
                    $merged_all[$key]['owner_id'] = 0;
                    foreach ($group_owner_all as $owner) {
                        if ($value['id'] == $owner['id'] and $value['is_group'] == 1)
                            $merged_all[$key]['owner_id'] = $owner->group_owner_id;
                    }
                }


                $merged_three = array_reverse($merged_all);
                $merged_three = array_slice($merged_three, -6);
                $merged_three = array_reverse($merged_three);

                $pusher->trigger('groupChatting.' . $group_message[$i]['member_id'], 'group-chatting-event', ["message" => $message, "senderImg" => $request->senderImg, "senderName" => $request->senderName]);
                $pusher->trigger('chat_message.' . $group_message[$i]['member_id'], 'chat', $merged_three);
                $pusher->trigger('all_message.' .  $group_message[$i]['member_id'] , 'all', $merged_all);
            }
        }
    }


    public function chat(Request $request)
    {
        $message = new Chat();
        $input = $request->all();
        $to_user_id = $request->receiver;
        if ($input['images']) {

            $images = $input['images'];
            $filenames = $input['filenames'];
            foreach ($images as $index => $file) {
                $tmp = base64_decode($file);
                $file_name = $filenames[$index];
                Storage::disk('public')->put(
                    'customer_message_media/' . $file_name,
                    $tmp
                );
                $imgData[] = $file_name;
                $message->media = json_encode($imgData);
            }
        } else {
                $message->media = null;
        }
        $message->from_user_id = auth()->user()->id;
        $message->to_user_id = $to_user_id;
        $message->text = $request->text == null ?  null : $request->text;
        $message->save();


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
        // $pusher->trigger('chatting.'.auth()->user()->id.'.'.$to_user_id, 'chatting-event', ['message'=>$message]);
        // $pusher->trigger('chatting.' . $to_user_id . '.' . auth()->user()->id, 'chatting-event', ['message' => $message]);
        $message_id = $message->id;
        $message = Chat::select('chats.*','profiles.profile_image')
                    ->leftJoin('users','users.id','chats.from_user_id')
                    ->leftJoin('profiles','users.profile_id','profiles.id')
                    ->where('chats.id',$message_id)
                    ->first();
         broadcast(new Chatting($message, $request->sender));

        $user_id = auth()->user()->id;
        $messages = DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date
        from
            chats
          join
            (select user, max(created_at) m
                from
                   (
                     (select id, to_user_id user, created_at
                       from chats
                       where from_user_id= $user_id  and delete_status <> 2 and deleted_by != $user_id )
                   union
                     (select id, from_user_id user, created_at
                       from chats
                       where to_user_id= $user_id  and delete_status <> 2 and deleted_by != $user_id)
                    ) t1
               group by user) t2
                on ((from_user_id= $user_id and to_user_id=user) or
                    (from_user_id=user and to_user_id= $user_id)) and
                    (created_at = m)
                left join users on users.id = user
                left join profiles on users.profile_id = profiles.id
                where deleted_by !=  $user_id  and delete_status != 2
            order by chats.created_at desc limit  3");
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

        //to user
        $messages_to =DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date
        from
            chats
          join
            (select user, max(created_at) m
                from
                   (
                     (select id, to_user_id user, created_at
                       from chats
                       where from_user_id= $to_user_id  and delete_status <> 2 and deleted_by != $to_user_id )
                   union
                     (select id, from_user_id user, created_at
                       from chats
                       where to_user_id= $to_user_id  and delete_status <> 2 and deleted_by != $to_user_id)
                    ) t1
               group by user) t2
                on ((from_user_id= $to_user_id and to_user_id=user) or
                    (from_user_id=user and to_user_id= $to_user_id)) and
                    (created_at = m)
                left join users on users.id = user
                left join profiles on users.profile_id = profiles.id
                where deleted_by !=  $to_user_id  and delete_status != 2
            order by chats.created_at desc limit  3");
      // dd($messages);
            $groups_to = DB::table('chat_group_members')
                        ->select('group_id')
                        ->groupBy('group_id')
                        ->where('chat_group_members.member_id',$to_user_id)
                        ->get()
                        ->pluck('group_id')->toArray();

            $latest_group_message_to = DB::table('chat_group_messages')
                        ->groupBy('group_id')
                        ->whereIn('group_id',$groups_to)
                        ->select(DB::raw('max(id) as id'))
                        ->get()
                        ->pluck('id')->toArray();
            $latest_group_sms_to =ChatGroupMessage::
                    select('chat_group_messages.group_id as id','chat_groups.group_name as name',
                    'profiles.profile_image','chat_group_messages.text',
                    DB::raw('DATE_FORMAT(chat_group_messages.created_at, "%Y-%m-%d %H:%m:%s") as date'))
                    ->leftJoin('chat_groups','chat_groups.id','chat_group_messages.group_id')
                    ->leftJoin('users','users.id','chat_group_messages.sender_id')
                    ->leftJoin('profiles','users.profile_id','profiles.id')
                    ->whereIn('chat_group_messages.id',$latest_group_message_to)->get()->toArray();
                    //   $ids = json_encode($messages);
            $arr_to = json_decode(json_encode ( $messages_to ) , true);
            foreach($arr_to as $key=>$value){
                $arr_to[$key]['is_group'] = 0;
            }
            foreach($latest_group_sms_to as $key=>$value){
                $latest_group_sms_to[$key]['is_group'] = 1;
            }
                    $merged_to = array_merge($arr_to, $latest_group_sms_to);
                    $keys_to = array_column($merged_to, 'date');
                    array_multisort($keys_to, SORT_DESC, $merged_to);
                    $group_owner_to = ChatGroup::whereIn('chat_groups.id',$groups_to)->get();
                    foreach($merged_to as $key=>$value){
                           $merged_to[$key]['owner_id'] = 0;
                        foreach($group_owner_to as $owner){
                            if($value['id'] == $owner['id'] AND $value['is_group'] == 1)
                            $merged_to[$key]['owner_id'] = $owner->group_owner_id;
                           }
                    }

        $arr_six = array_reverse($merged);
        $arr_six = array_slice($arr_six, -6);
        $arr_six = array_reverse($arr_six);
        $arr_six_to = array_reverse($merged_to);
        $arr_six_to = array_slice($arr_six_to, -6);
        $arr_six_to = array_reverse($arr_six_to);

        $pusher->trigger('chat_message.' . $user_id, 'chat', $arr_six);
        $pusher->trigger('chat_message.' . $to_user_id, 'chat', $arr_six_to);

        $pusher->trigger('all_message.'.$to_user_id , 'all', $merged_to);
        $pusher->trigger('all_message.'.$user_id , 'all', $merged);
        return response()->json([
            'success' =>  $message
        ]);

}

    public function chat_messages(Request $request)
    {
        $id = $request->id;
        $auth_user = auth()->user();
        if ($request->is_group == 0) {
            $messages = DB::select("SELECT * FROM chats where (from_user_id =  $auth_user->id or to_user_id =  $auth_user->id) and (from_user_id = $id or to_user_id = $id)
            and  deleted_by !=  $auth_user->id  and delete_status != 2 ");
            $receiver_user = User::select('users.id', 'users.name', 'profiles.profile_image')
                ->where('users.id', $id)
                ->leftjoin('profiles', 'profiles.id', 'users.profile_id')->first();
            foreach ($messages as $key => $value) {
                $messages[$key]->profile_image = $receiver_user->profile_image == null ?  null : $receiver_user->profile_image;
            }
        } else {
            $messages = ChatGroupMessage::select(
                    'profiles.profile_image',
                    'chat_group_messages.id',
                    'chat_group_messages.sender_id as from_user_id',
                    'chat_group_messages.text',
                    'chat_group_messages.media',
                    'chat_group_messages.created_at'
                )
                ->leftJoin('users', 'users.id', 'chat_group_messages.sender_id')
                ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
                ->where('chat_group_messages.group_id', $id)
                ->get();
            foreach ($messages as $key => $value) {
                $messages[$key]['to_user_id'] = 0;
            }
        }

        return response()->json([
            'messages' => $messages
        ]);
    }

    public function view_media_message(Request $request)
    {
        $auth_user = auth()->user();
        $id = $request->id;
        if ($request->is_group == 0) {
            $messages = Chat::select('id', 'media')->where(function ($query) use ($auth_user) {
                $query->where('from_user_id', $auth_user->id)->orWhere('to_user_id', $auth_user->id);
            })->where(function ($que) use ($id) {
                $que->where('from_user_id', $id)->orWhere('to_user_id', $id);
            })->where('media', '!=', null)->get();
        } else {
            $messages = ChatGroupMessage::select('id', 'media')->where('chat_group_messages.group_id', $id)
                ->where('chat_group_messages.media', '!=', null)
                ->get();
        }
        return response()->json([
            'messages' => $messages
        ]);
    }

    public function see_all_message()
    {
        $user_id = auth()->user()->id;

        $messages = DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date
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
            'all_messages' => $merged
        ]);
    }

    public function post_comment_store(Request $request)
    {
        $banwords = DB::table('ban_words')->select('ban_word_english', 'ban_word_myanmar', 'ban_word_myanglish')->get();
        foreach ($banwords as $b) {
            $e_banword = $b->ban_word_english;
            $m_banword = $b->ban_word_myanmar;
            $em_banword = $b->ban_word_myanglish;
            if (str_contains($request->comment, $e_banword)) {
                return response()->json([
                    'ban' => 'Ban',
                ]);
            } elseif (str_contains($request->comment, $m_banword)) {
                return response()->json([
                    'ban' => 'Ban',
                ]);
            } elseif (str_contains($request->comment, $em_banword)) {
                return response()->json([
                    'ban' => 'Ban',
                ]);
            }
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
            $pusher->trigger('friend_request.' . $post_owner->user_id, 'friendRequest', $fri_noti);
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
                    $pusher->trigger('friend_request.' . $fri_noti->receiver_id, 'friendRequest', $fri_noti);
                }
            }
        }
        return response()->json([
            'data' =>  $comments
        ]);
    }

    public function user_like_post(Request $request)
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
                $pusher->trigger('friend_request.' . $post_owner->user_id, 'friendRequest', $fri_noti);
            }
        }
        $total_likes = UserReactPost::where('post_id', $post_id)->count();
        return response()->json([
            'total_likes' => $total_likes,
        ]);
    }

    public function comment_edit(Request $request)
    {
        $banwords = DB::table('ban_words')->select('ban_word_english', 'ban_word_myanmar', 'ban_word_myanglish')->get();
        foreach ($banwords as $b) {
            $e_banword = $b->ban_word_english;
            $m_banword = $b->ban_word_myanmar;
            $em_banword = $b->ban_word_myanglish;
            if (str_contains($request->comment, $e_banword)) {
                return response()->json([
                    'ban' => 'Ban',
                ]);
            } elseif (str_contains($request->comment, $m_banword)) {
                return response()->json([
                    'ban' => 'Ban',
                ]);
            } elseif (str_contains($request->comment, $em_banword)) {
                return response()->json([
                    'ban' => 'Ban',
                ]);
            }
        }
        $comments_update = Comment::findOrFail($request->id);
        $comments_update->comment = $request->comment;
        $comments_update->mentioned_users = json_encode($request->mention);
        $comments_update->update();
        return response()->json([
            'success' =>  'Comment updated successfully!'
        ]);
    }

    public function social_media_likes(Request $request)
    {
        $auth = Auth()->user()->id;
        $post_id = $request->post_id;
        $post_likes = UserReactPost::select('users.name', 'profiles.profile_image', 'user_react_posts.*')
            ->leftJoin('users', 'users.id', 'user_react_posts.user_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->where('post_id', $post_id)
            ->get();

        $friends = DB::select("SELECT * FROM `friendships` WHERE (receiver_id = $auth or sender_id = $auth)
       ");

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

        return response()->json([
            'data' =>  $post_likes
        ]);
    }


    public function comment_delete(Request $request)
    {
        Comment::find($request->id)->delete($request->id);

        return response()->json([
            'success' => 'Comment deleted successfully!'
        ]);
    }

    public function post_viewer(Request $request){
        $id = $request->id;
        $post_count = Post::findOrFail($id);
        if(auth()->user()->id != $post_count->user_id){
            $post_count->viewers = $post_count->viewers + 1;
        }
        $post_count->update();
        return response()->json([
            'message' => 'Counted'
        ]);
    }

    public function comment_list(Request $request)
    {
        $id = $request->id;
        $comments = Comment::select('users.name', 'users.profile_id', 'profiles.profile_image', 'comments.*')
            ->leftJoin('users', 'users.id', 'comments.user_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->where('comments.post_id', $id)->orderBy('comments.created_at', 'DESC')->get();
        return response()->json([
            'comments' => $comments
        ]);
    }

    public function user_list()
    {
        $user = User::select('users.id', 'users.name')->get();

        return response()->json([
            'data' => $user
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
        if ($request->members) {
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
        else{
            $group_members = new ChatGroupMember();
            $group_members->group_id = $group->id;
            $group_members->member_id = $groupOwner;
            $group_members->save();
        }
        $message = "Hi All";
        ChatGroupMember::create(['group_id' => $group->id, 'member_id' => $groupOwner]);
        $chat_message = new ChatGroupMessage();
        $chat_message->group_id =  $group->id;
        $chat_message->sender_id = $groupOwner;
        $chat_message->text = $message;
        $chat_message->save();

        $group_message = ChatGroupMember::select('member_id')->where('group_id', $group->id)->get();
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

        $arr_six = array_reverse($merged);
        $arr_six = array_slice($arr_six, -6);
        $arr_six = array_reverse($arr_six);

        $pusher->trigger('chat_message.' . $user_id_to, 'chat', $arr_six);
        $pusher->trigger('all_message.'.$user_id_to , 'all', $merged);
    }
        return response()->json([
            'success' => 'Success',
            'group' => $group,
            'group_members' => $group_members
        ]);
    }
    public function members(Request $request)
    {
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
        $friend = User::select('users.id', 'users.name', 'profiles.profile_image')
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
        $group_id = $request->id;
        $group_members = ChatGroupMember::select('users.id', 'users.name', 'profiles.profile_image')
            ->leftJoin('users', 'users.id', 'chat_group_members.member_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->where('chat_group_members.group_id', $group_id)
            ->where('chat_group_members.member_id', '!=', $id)
            ->get()->toArray();

        foreach ($friend as $key => $fri) {
            foreach ($group_members as $value => $gp) {
                if ($fri['id'] == $gp['id']) {
                    unset($friend[$key]);
                }
            }
        }
        return response()->json([
            'success' => 'Success',
            'data' => $friend
        ]);
    }
    public function addmember(Request $request)
    {
        $members = $request->members;
        $id = $request->group_id;
        for ($i = 0; $i < count($members); $i++) {
            $memberId = $members[$i];
            $group_members = new ChatGroupMember();
            $group_members->group_id = $id;
            $group_members->member_id = $memberId;
            $group_members->save();
        }
        return response()->json([
            'success' => 'add',

        ]);
    }

    public function group_member_kick(Request $request)
    {
        $member = ChatGroupMember::where('group_id', $request->group_id)->where('member_id', $request->member_id)->first();
        $member->delete();
        return response()->json([
            'success' => 'Kicked!'
        ]);
    }

    public function leave_group(Request $request)
    {
        $member = ChatGroupMember::where('group_id', $request->group_id)->where('member_id', auth()->user()->id)->first();
        $member->delete();
        return response()->json([
            'success' => 'leaved!'
        ]);
    }


    public function delete_group(Request $request)
    {

        $group_message_delete = ChatGroupMessage::where('group_id', $request->id);
        $group_message_delete->delete();

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

            $group_message = ChatGroupMember::select('member_id')->where('group_id', $request->id)
            ->where('member_id','!=',auth()->user()->id)->get();
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
        $group_member_delete = ChatGroupMember::where('group_id', $request->id);
        $group_member_delete->delete();

        $group_delete = ChatGroup::where('id', $request->group_id);
        $group_delete->delete();

        return response()->json([
            'message' => 'Success Deleted!'
        ]);
    }

    public function all_group()
    {
        $user_id = auth()->user()->id;
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

        $chat_group =
            ChatGroupMessage::leftJoin('chat_groups', 'chat_groups.id', 'chat_group_messages.group_id')
            ->select('chat_group_messages.*', 'chat_groups.group_name')
            ->whereIn('chat_group_messages.id', $latest_group_message)
            ->orderBy('chat_group_messages.created_at', 'DESC')->get();
        return response()->json([
            'data' =>  $chat_group
        ]);
    }

    public function group_messages(Request $request)
    {
        $group_id = $request->id;
        $group_messages = ChatGroupMessage::select(
            'profiles.profile_image',
            'chat_group_messages.sender_id as from_user_id',
            'chat_group_messages.text',
            'chat_group_messages.media',
            'chat_group_messages.created_at'
        )
            ->leftJoin('users', 'users.id', 'chat_group_messages.sender_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->where('chat_group_messages.group_id', $group_id)
            ->orderBy('chat_group_messages.created_at', 'DESC')
            ->get();
        return response()->json([
            'data' =>  $group_messages
        ]);
    }

    public function group_members(Request $request)
    {
        $group_id = $request->id;
        $group_members = ChatGroupMember::select('users.id', 'users.name', 'profiles.profile_image')
            ->leftJoin('users', 'users.id', 'chat_group_members.member_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->where('chat_group_members.group_id', $group_id)
            ->get();
        return response()->json([
            'data' =>  $group_members
        ]);
    }

    public function group_media(Request $request)
    {
        $group_id = $request->id;
        $group_media = ChatGroupMessage::where('chat_group_messages.group_id', $group_id)
            ->where('chat_group_messages.media', '!=', null)
            ->get();
        return response()->json([
            'data' =>  $group_media
        ]);
    }

    public function send_message(Request $request)
    {
        $message = new ChatGroupMessage();
        $input = $request->all();
        $group_id = $request->group_id;
        if ($input['images']) {
            $images = $input['images'];
            $filenames = $input['filenames'];
            foreach ($images as $index => $file) {
                $tmp = base64_decode($file);
                $file_name = $filenames[$index];
                Storage::disk('public')->put(
                    'customer_message_media/' . $file_name,
                    $tmp
                );
                $imgData[] = $file_name;
                $message->media = json_encode($imgData);
            }
        } else {
            $message->media = null;
        }

        $message->group_id = $group_id;
        $message->sender_id = auth()->user()->id;
        $message->text = $request->text == null ?  null : $request->text;
        $message->save();
        $id = $message->id;
        $sms = ChatGroupMessage::select(
            'profiles.profile_image',
            'chat_group_messages.sender_id as from_user_id',
            'chat_group_messages.text',
            'chat_group_messages.media',
            'chat_group_messages.created_at',
            'chat_group_messages.id'
        )
            ->leftJoin('users', 'users.id', 'chat_group_messages.sender_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->where('chat_group_messages.id', $id)
            ->first()->toArray();
        foreach ($sms as $key => $value) {
            $sms['to_user_id'] = 0;
        }

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
        $user_id = auth()->user()->id;
        $group_message = ChatGroupMember::select('member_id')->where('group_id', $group_id)
        ->get();
        for ($i = 0; count($group_message) > $i; $i++) {
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
                           where to_user_id= $user_id_to  and delete_status <> 2 and deleted_by != $user_id_to)
                        ) t1
                   group by user) t2
                    on ((from_user_id= $user_id_to and to_user_id=user) or
                        (from_user_id=user and to_user_id= $user_id_to)) and
                        (created_at = m)
                    left join users on users.id = user
                    left join profiles on users.profile_id = profiles.id
                    order by chats.created_at desc limit  3");
        // dd($messages);


        $groups = DB::table('chat_group_members')
            ->select('group_id')
            ->groupBy('group_id')
            ->where('chat_group_members.member_id', $user_id_to)
            ->get()
            ->pluck('group_id')->toArray();

                $latest_group_message = DB::table('chat_group_messages')
                            ->groupBy('group_id')
                            ->whereIn('group_id',$groups)
                            ->select(DB::raw('max(id) as id'))
                            ->get()
                            ->pluck('id')->toArray();
                $latest_group_sms =ChatGroupMessage::
                        select('chat_group_messages.group_id as id','chat_groups.group_name as name',
                        'profiles.profile_image','chat_group_messages.text',
                        DB::raw('DATE_FORMAT(chat_group_messages.created_at, "%Y-%m-%d %H:%i:%s") as date'))
                        ->leftJoin('chat_groups','chat_groups.id','chat_group_messages.group_id')
                        ->leftJoin('users','users.id','chat_group_messages.sender_id')
                        ->leftJoin('profiles','users.profile_id','profiles.id')
                        ->whereIn('chat_group_messages.id',$latest_group_message)->get()->toArray();
                        //   $ids = json_encode($messages);
                $arr = json_decode(json_encode ( $messages ) , true);
                foreach($arr as $key=>$value){
                    $arr[$key]['is_group'] = 0;
                }
                foreach($latest_group_sms as $key=>$value){
                    $latest_group_sms[$key]['is_group'] = 1;
                }
                        $merged = array_merge($arr, $latest_group_sms);
                        $keys = array_column($merged, 'date');
                        array_multisort($keys, SORT_DESC, $merged);
                        $group_owner = ChatGroup::whereIn('chat_groups.id',$groups)->get();
                        foreach($merged as $key=>$value){
                               $merged[$key]['owner_id'] = 0;
                            foreach($group_owner as $owner){
                                if($value['id'] == $owner['id'] AND $value['is_group'] == 1)
                                $merged[$key]['owner_id'] = $owner->group_owner_id;
                            }
                        }
            $pusher->trigger('groupChatting.' . $group_message[$i]['member_id'], 'group-chatting-event', ["message" => $message, "senderImg" => $request->senderImg, "senderName" => $request->senderName]);
            $pusher->trigger('all_message.' . $group_message[$i]['member_id'], 'all', $merged);
        }
        return response()->json([
            'success' =>  $sms
        ]);
    }

    public function delete_message(Request $request)
    {
        $message = Chat::findOrFail($request->id);
        $message->delete_status = 1;
        $message->deleted_by = auth()->user()->id;
        $message->save();
        return response()->json([
            'success' => 'Deleted Success'
        ]);
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
                    Chat::where('id', $value->id)->update(['delete_status' => 1, 'deleted_by' => $auth_user]);
                } elseif ($value->delete_status == 1) {
                    Chat::where('id', $value->id)->update(['delete_status' => 2]);
                }
            }
        }
        return response()->json([
            'success' =>  'Deleted'
        ]);
    }


    public function unsend_message(Request $request)
    {
        $message = Chat::findOrFail($request->id);
        $message->delete_status = 2;
        $message->deleted_by = auth()->user()->id;
        $message->update();
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        );
        $to_user_id = Chat::select('to_user_id')->where('id', $request->id)->first();
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );
        $pusher->trigger('message-delete.' . $to_user_id->to_user_id . '.' . auth()->user()->id, 'message-delete-event', ['message' => $message]);

        $pusher->trigger('message-delete.' . auth()->user()->id . '.' . $to_user_id->to_user_id, 'message-delete-event', ['message' => $message]);
        // broadcast(new MessageDelete($message, $request->id));
        return response()->json([
            'success' => 'Deleted Success'
        ]);
    }

    public function post_report(Request $request)
    {
        //dd($request->all());
        $user_id = auth()->user()->id;
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
        $user_rp->notification_status = $admin_id;
        $user_rp->report_id = $report->id;
        $user_rp->save();

        $admin_rp = new Notification();
        $admin_rp->description = $new_data;
        $admin_rp->date = Carbon::Now()->toDateTimeString();
        $admin_rp->sender_id = auth()->user()->id;
        $admin_rp->receiver_id = $admin_id;
        $admin_rp->report_id = $report->id;
        $admin_rp->save();

        $pusher->trigger('friend_request.' . auth()->user()->id, 'friendRequest', $data);

        $pusher->trigger('friend_request.' . $admin_id, 'friendRequest', $new_data);

        return response()->json([
            'success' => 'Reported Success'
        ]);
    }


    public function video_token(Request $request)
    {
        $appID = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $channelName = $request->channelName;
        $user = Auth::user()->id;
        $role = RtcTokenBuilder::RoleAttendee;
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        $token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);

        $data['userToCall'] = $request->user_to_call;
        $data['channelName'] = $channelName;
        $data['from'] = Auth::id();

        broadcast(new MakeAgoraCall($data))->toOthers();
        return response()->json([
            'data' => $token
        ]);
    }
    public function audio_token(Request $request)
    {
        $appID = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $channelName = $request->channelName;
        $user = Auth::user()->id;
        $role = RtcTokenBuilder::RoleAttendee;
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        $token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);

        $data['userToCall'] = $request->user_to_call;
        $data['channelName'] = $channelName;
        $data['from'] = Auth::id();

        broadcast(new MakeAgoraAudioCall($data))->toOthers();

        return response()->json([
            'data' => $token
        ]);
    }

    public function gp_video_token(Request $request)
    {
        $appID = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $channelName = $request->channelName;
        $user = Auth::user()->id;
        $role = RtcTokenBuilder::RoleAttendee;
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        $token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);

        $members = ChatGroupMember::where('group_id', $request->group_id)->where('member_id','!=',auth()->user()->id)->get();
        $data['channelName'] = $channelName;

        foreach ($members as $member) {
            $data['memberId'] = $member->member_id;
            broadcast(new GroupVideoCall($data['memberId'], $data))->toOthers();
        }
        return response()->json([
            'data' => $token
        ]);
    }

    public function gp_audio_token(Request $request)
    {
        $appID = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $channelName = $request->channelName;
        $user = Auth::user()->id;
        $role = RtcTokenBuilder::RoleAttendee;
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        $token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);

        $members = ChatGroupMember::where('group_id', $request->group_id)->where('member_id','!=',auth()->user()->id)->get();
        $group_name = ChatGroup::select('group_name')->where('id',$request->group_id)->first();
        $data['channelName'] = $channelName;
        $data['groupName'] = $group_name;

        foreach ($members as $member) {
            $data['memberId'] = $member->member_id;
            broadcast(new GroupAudioCall($data['memberId'], $data))->toOthers();
        }
        return response()->json([
            'data' => $token
        ]);
    }


    public function accept_call(Request $request)
    {
        $appID = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $channelName = $request->channelName;
        $user = Auth::user()->id;
        $role = RtcTokenBuilder::RoleAttendee;
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        $token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);
        return response()->json([
            'data' => $token
        ]);
    }

    public function declineCallUser(Request $request) {
        $data['userFromCall'] = $request->user_from_call;
        broadcast(new DeclineCallUser($data))->toOthers();
    }
}
