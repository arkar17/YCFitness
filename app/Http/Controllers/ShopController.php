<?php

namespace App\Http\Controllers;

use File;
use stdClass;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\Member;
use App\Models\Comment;
use App\Models\ShopPost;
use App\Models\BlockList;
use App\Models\ChatGroup;
use App\Models\ShopReact;
use App\Models\ShopMember;
use App\Models\ShopRating;
use App\Models\BankingInfo;
use Illuminate\Http\Request;
use App\Models\UserReactPost;
use App\Models\UserSavedPost;
use App\Models\ChatGroupMessage;
use App\Models\UserSavedShoppost;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ShopController extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;
        $block_list = BlockList::where('sender_id',$user_id)->orWhere('receiver_id',$user_id)->get(['sender_id', 'receiver_id'])->toArray();
        $b = array();
        foreach ($block_list as $block) {
            $f = (array)$block;
            array_push($b, $f['sender_id'], $f['receiver_id']);
        }
        // dd($b);
        $shops=User::where('shopmember_type_id','!=',0)
                    ->whereNotIn('id',$b)
                    ->where('shop_request',2)
                    ->orWhere('shop_request',3)
                    ->with('posts')
                    ->first();
        $post = Post::find(17);
                   
                    // dd($imageData);
        return view('customer.shop.shop',compact('shops'));
    }

    public function shop_list(Request $request)
    {
        $user_id = auth()->user()->id;
        $block_list = BlockList::where('sender_id',$user_id)->orWhere('receiver_id',$user_id)->get(['sender_id', 'receiver_id'])->toArray();
        $b = array();
        foreach ($block_list as $block) {
            $f = (array)$block;
            array_push($b, $f['sender_id'], $f['receiver_id']);
        }
        $array = \array_filter($b, static function ($element) {
            $user_id = auth()->user()->id;
            return $element !== $user_id;
            //                   ↑
            // Array value which you want to delete
        });
        $shop_list = User::select('users.id','users.name','profiles.profile_image')
        ->leftJoin('profiles','users.profile_id','profiles.id')
        ->whereNotIn('users.id',$array)
        ->where('shop_request',2)
        ->orWhere('shop_request',3)
        ->get();

        $rating = DB::table('shop_ratings')
        ->select('shop_id', DB::raw('count(*) as rating'))
        ->groupBy('shop_id')
        ->get()->toArray();
        $sum = DB::table('shop_ratings')
                ->select('shop_id', DB::raw('SUM(rating) as sum'))
                ->groupBy('shop_id')
                ->get();

        foreach($rating as $key=>$total){
        $rating[$key]->Avg_rating = 0;
        foreach($sum as $value){
            $int = intval($value->sum);
            if($total->shop_id == $value->shop_id){
                $result =   $int / $total->rating;
                $rating[$key]->Avg_rating = $result;
            }
        }
        }

        $total_count = Post::select("user_id",DB::raw("Count('id') as total_count"))
                        ->where('shop_status',1)
                        ->groupBy('user_id')
                        ->get();
        foreach($shop_list as $key=>$value){
            $shop_list[$key]['total_post'] = 0;
            foreach($total_count as $count){
                if($count['user_id'] == $value['id']){
                    $shop_list[$key]['total_post'] = $count['total_count'];
                }
            }
        }
        foreach($shop_list as $key=>$value){
            $shop_list[$key]['avg_rating'] = 0;
            foreach($rating as $rat){
                if($rat->shop_id == $value->id){
                    $shop_list[$key]['avg_rating'] = $rat->Avg_rating;
                }
            }
        }

        return response()->json([
            'data' => $shop_list
        ]);
    }

    public function  shoppost($id)
    {
        $user=User::where('id',$id)
                    ->first();

        $posts=DB::table('posts')
                    ->select('users.name','profiles.profile_image','posts.*','posts.id as post_id','posts.created_at as post_date')
                    ->where('users.shopmember_type_id','!=',0)
                    ->where('users.shop_request',2)
                    ->where('posts.user_id',$id)
                    ->where('posts.report_status',0)
                    ->where('posts.shop_status',1)
                    ->where('posts.deleted_at',null)
                    ->leftJoin('users','users.id','posts.user_id')
                    ->leftJoin('profiles','users.profile_id','profiles.id')
                    ->orderBy('posts.created_at','DESC')
                    ->get();
            foreach($posts as $key=>$value){

            $saved=auth()->user()->user_saved_posts->where('post_id',$value->post_id)->first();

            $react = auth()->user()->user_reacted_posts()->where('post_id', $value->post_id)->first();
            if (!empty($react)) {
                $isLike=1;
            }else{
                $isLike=0;
            }

            if($saved==null){
                $already_saved=0;
            }else{
                $already_saved=1;
            }
            $date= Carbon::parse($value->post_date)
                            ->format('d M Y , g:i A');

            $total_likes=UserReactPost::where('post_id',$value->post_id)
                            ->get()->count();
            $user_id = auth()->user()->id;
            $block_list = BlockList::where('sender_id',$user_id)->orWhere('receiver_id',$user_id)->get(['sender_id', 'receiver_id'])->toArray();
            $b = array();
            foreach ($block_list as $block) {
                $f = (array)$block;
                array_push($b, $f['sender_id'], $f['receiver_id']);
            }
            $array = \array_filter($b, static function ($element) {
                $user_id = auth()->user()->id;
                return $element !== $user_id;
                //                   ↑
                // Array value which you want to delete
            });

            $comment_post_count =  DB::table('comments')
                ->select('post_id', DB::raw('count(*) as total'))
                ->where('report_status',0)
                ->where('deleted_at',null)
                ->whereNotIn('user_id',$array)
                ->groupBy('post_id')
                ->get();
            
            $posts[$key]->total_likes=$total_likes;
            // $posts[$key]->total_comments=$total_comments;
            foreach($comment_post_count as $comment){
                if($comment->post_id == $value->id){
                    $posts[$key]->comment_count= $comment->total;
                }
                else{
                    $posts[$key]->comment_count = 0;
                }
            }
           
            $posts[$key]->date= $date;
            $posts[$key]->isLike=$isLike;
            $posts[$key]->already_saved=$already_saved;
            }


        return view('customer.shop.shop_post',compact('posts','user'));
    }

    public function shoprequest()
    {
        $shop_levels=ShopMember::get();
        return view('customer.shop.shop_request',compact('shop_levels'));
    }

    public function payment(Request $request)
    {
        $user=auth()->user();
        if($user->shop_request==1){
            Alert::warning('Warning', 'Already requested!You will get a notification 24hrs later');
            return redirect()->back();
        }else{
            $shop_level_id=$request->shop_level_id;

            $user=User::findOrFail($user->id);
            $user->shopmember_type_id=$shop_level_id;
            $user->update();

            $member=ShopMember::findOrFail($shop_level_id);
            $banking_info = BankingInfo::all();

            return view('customer.payment',compact('banking_info','member'));
        }
    }

    public function shoppost_save(Request $request)
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

    public function shoppost_edit(Request $request, $id)
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
                'imageData'=>$imageData
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Data Not Found',
            ]);
        }
    }

    public function shoppost_update(Request $request)
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

    public function shoppost_store(Request $request)
    {
        $input = $request->all();
        $user = auth()->user();
        $user_role=$user->getRoleNames()->first();
        $useru=User::findOrFail($user->id);
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
        //
        $shop_member_level = ShopMember::select('member_type')->where('id',$user->shopmember_type_id)->first();
        if(($user->shop_post_count == 0 AND $shop_member_level != 'level3') OR
           ($user->shop_post_count == 0 AND $user_role != 'Ruby Premium') OR
           ($user->shop_post_count == 0 AND $user_role != 'Ruby')){

            $message='Cannot Post';
        }

        $post->user_id = $user->id;
        $post->shop_status =1;
        $post->caption = $caption;
        $post->save();

        if(($user->shop_post_count != 0 AND $shop_member_level != 'level3') OR
           ($user->shop_post_count != 0 AND $user_role != 'Ruby Premium') OR
           ($user->shop_post_count != 0 AND $user_role != 'Ruby')){
            $user = User::find(auth()->user()->id);
            $user->shop_post_count = $user->shop_post_count - 1;
            $user->update();
            $message='Post Created Successfully';
        }else{

            $message='Post Created Successfully';
        }

        return response()->json([
            'message' => $message,
        ]);


        // /////////////
        // if( $user->shop_request==2 || $user->shop_request==3){
        //     if($user->shopmember_type_id==null && $user_role== "Ruby" || $user_role=="Ruby Premium"){

        //     }elseif($user->shopmember_type_id!=null){
        //         $shopmember=ShopMember::findOrFail($user->shopmember_type_id);

        //         if($shopmember->member_type=="level3"){
        //             if ($input['totalImages'] == 0 && $input['caption'] != null) {
        //                 $caption = $input['caption'];
        //             } elseif ($input['caption'] == null && $input['totalImages'] != 0) {
        //                 $caption = null;
        //                 $images = $input['addPostInput'];
        //                 if ($input['addPostInput']) {
        //                     foreach ($images as $file) {
        //                         $extension = $file->extension();
        //                         $name = rand() . "." . $extension;
        //                         $file->storeAs('/public/post/', $name);
        //                         $imgData[] = $name;
        //                         $post->media = json_encode($imgData);
        //                     }
        //                 }
        //             } elseif ($input['totalImages'] != 0 && $input['caption'] != null) {
        //                 $caption = $input['caption'];
        //                 $images = $input['addPostInput'];
        //                 if ($input['addPostInput']) {
        //                     foreach ($images as $file) {
        //                         $extension = $file->extension();
        //                         $name = rand() . "." . $extension;
        //                         $file->storeAs('/public/post/', $name);
        //                         $imgData[] = $name;
        //                         $post->media = json_encode($imgData);
        //                     }
        //                 }
        //             }
        //             $banwords = DB::table('ban_words')->select('ban_word_english', 'ban_word_myanmar', 'ban_word_myanglish')->get();

        //             foreach ($banwords as $b) {
        //                 $e_banword = $b->ban_word_english;
        //                 $m_banword = $b->ban_word_myanmar;
        //                 $em_banword = $b->ban_word_myanglish;

        //                 if (str_contains($caption, $e_banword)) {
        //                     // Alert::warning('Warning', 'Ban Ban Ban');
        //                     //return redirect()->back();
        //                     return response()->json([
        //                         'ban' => 'You used our banned words!',
        //                     ]);
        //                 } elseif (str_contains($caption, $m_banword)) {
        //                     return response()->json([
        //                         'ban' => 'You used our banned words!',
        //                     ]);
        //                 } elseif (str_contains($caption, $em_banword)) {
        //                     return response()->json([
        //                         'ban' => 'You used our banned words!',
        //                     ]);
        //                 }
        //             }

        //             $post->user_id = $user->id;
        //             $post->shop_status =1;
        //             $post->caption = $caption;
        //             $post->save();
        //             return response()->json([
        //                 'message' => 'Post Created Successfully',
        //             ]);
        //         }elseif($user->shop_post_count!=0){
        //             if ($input['totalImages'] == 0 && $input['caption'] != null) {
        //                 $caption = $input['caption'];
        //             } elseif ($input['caption'] == null && $input['totalImages'] != 0) {
        //                 $caption = null;
        //                 $images = $input['addPostInput'];
        //                 if ($input['addPostInput']) {
        //                     foreach ($images as $file) {
        //                         $extension = $file->extension();
        //                         $name = rand() . "." . $extension;
        //                         $file->storeAs('/public/post/', $name);
        //                         $imgData[] = $name;
        //                         $post->media = json_encode($imgData);
        //                     }
        //                 }
        //             } elseif ($input['totalImages'] != 0 && $input['caption'] != null) {
        //                 $caption = $input['caption'];
        //                 $images = $input['addPostInput'];
        //                 if ($input['addPostInput']) {
        //                     foreach ($images as $file) {
        //                         $extension = $file->extension();
        //                         $name = rand() . "." . $extension;
        //                         $file->storeAs('/public/post/', $name);
        //                         $imgData[] = $name;
        //                         $post->media = json_encode($imgData);
        //                     }
        //                 }
        //             }
        //             $banwords = DB::table('ban_words')->select('ban_word_english', 'ban_word_myanmar', 'ban_word_myanglish')->get();

        //             foreach ($banwords as $b) {
        //                 $e_banword = $b->ban_word_english;
        //                 $m_banword = $b->ban_word_myanmar;
        //                 $em_banword = $b->ban_word_myanglish;

        //                 if (str_contains($caption, $e_banword)) {
        //                     return response()->json([
        //                         'ban' => 'You used our banned words!',
        //                     ]);
        //                 } elseif (str_contains($caption, $m_banword)) {
        //                     return response()->json([
        //                         'ban' => 'You used our banned words!',
        //                     ]);
        //                 } elseif (str_contains($caption, $em_banword)) {
        //                     return response()->json([
        //                         'ban' => 'You used our banned words!',
        //                     ]);
        //                 }
        //             }

        //             $post->user_id = $user->id;
        //             $post->shop_status =1;
        //             $post->caption = $caption;

        //             $postcount=0;
        //             $postcount=$useru->shop_post_count;
        //             $postcount=$postcount-1;

        //             $useru->shop_post_count=$postcount;
        //             $useru->update();
        //             $post->save();
        //             return response()->json([
        //                 'message' => 'Post Created Successfully',
        //             ]);
        //         }elseif($user->shop_post_count==0){
        //             return response()->json([
        //                 'message' => 'You reached post limit please upgrade you shop member level',
        //             ]);
        //         }
        //     }else{

        //     }
        // }else{
        //     return response()->json([
        //         'message' => 'You cannot post yet!please wait our response',
        //     ]);
        // }
        // Alert::success('Success', 'Post Created Successfully');
        // return redirect()->back();
    }

    public function shoppost_destroy($id)
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

    public function shop_rating(Request $request){
        //  dd($request->all());
        $rating=$request->rating;
        $user_id = auth()->user()->id;
        $shop_id = $request->post_user;
        $shop_rating = ShopRating::where('user_id', $user_id)->where('shop_id',$shop_id)->first();



        if($shop_rating){
            if($shop_rating->shop_id != auth()->user()->id){
                DB::table('shop_ratings')->where('user_id', $user_id)->where('shop_id',$shop_id)->update(['rating' => $request->rating]);
                $message=200;

            }
            else{
                $message=400;

            }
        }else{
            $shop_rating = new ShopRating();
            $shop_rating->user_id = $user_id;
            $shop_rating->shop_id = $shop_id;
            $shop_rating->rating = $request->rating;
            $shop_rating->save();
            $message=200;
        }

        return response()->json([
            'message' => $message
        ]);
    }
}
