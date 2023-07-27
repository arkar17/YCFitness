<?php

namespace App\Http\Controllers\Api\V1;

use Pusher\Pusher;
use App\Models\Chat;
use App\Models\Post;
use App\Models\User;
use App\Models\ShopPost;
use App\Models\BlockList;
use App\Models\FreeVideo;
use App\Models\ShopReact;
use App\Models\ShopMember;
use App\Models\ShopRating;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\UserReactPost;
use App\Models\UserSavedPost;
use App\Models\ChatGroupMember;
use App\Models\ChatGroupMessage;
use App\Models\UserReactShoppost;
use App\Models\UserSavedShoppost;
use Illuminate\Support\Facades\DB;
use App\Models\UserReactedShoppost;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\GroupChatMessageReadStatus;
use App\Models\Report;

class ShopController extends Controller
{
    //
    public function shop_status(){
        $user = User::select('shopmember_type_id','shop_request')->where('id',auth()->user()->id)->first();
        return response()->json([
            'data' => $user
        ]);
    }
    public function message()
    {
        $one = Chat::all();
        $gp = ChatGroupMessage::all();
        $gp_read = GroupChatMessageReadStatus::all();
        return response()->json([
            'one' => $one,
            'gp' => $gp,
            'gp_read' => $gp_read
        ]);
    }

    public function notification()
    {
        $noti = Notification::get();
        return response()->json([
            'noti' => $noti
        ]);
    }

    public function reports()
    {
        $report = Report::get();
        return response()->json([
            'report' => $report
        ]);
    }

    public function users()
    {
        $user = User::get();
        return response()->json([
            'user' => $user
        ]);
    }
    
    public function shop_member_plan_list(){
        $member_plan = ShopMember::get();
        return response()->json([
            'data' => $member_plan
        ]);
    }

    public function shop_list()
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
        if($array){
            $shop_list = User::select('users.id','users.name','profiles.profile_image')
            ->leftJoin('profiles','users.profile_id','profiles.id')
            ->where('shop_request',2)
            ->orWhere('shop_request',3)
            ->whereNotIn('users.id',$array)
            ->get();
        }
        else{
         $shop_list = User::select('users.id','users.name','profiles.profile_image')
        ->leftJoin('profiles','users.profile_id','profiles.id')
        ->where('shop_request',2)
        ->orWhere('shop_request',3)
        ->get();
        }
        

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

    public function shop_list_one()
    {
        $shop_list = User::select('users.id','users.name','profiles.profile_image')
        ->leftJoin('profiles','users.profile_id','profiles.id')
        ->where('users.id',auth()->user()->id)
        ->where(function($query) {
            $query->where('shop_request',2)
                ->orWhere('shop_request',3);
        })
        // ->where('shop_request',2)
        // ->orWhere('shop_request',3)
        ->first();
    //    dd($shop_list);
        $total_count = Post::select("user_id",DB::raw("Count('id') as total_count"))
                        ->where('user_id',auth()->user()->id)
                        ->where('shop_status',1)
                        ->first();
        if(!empty($shop_list)){
            foreach($shop_list as $value){
                if(!empty($total_count)){
                    $shop_list['total_post'] = $total_count['total_count'];
                }
                else{
                    $shop_list['total_post'] = 0;
                }
        }
        $rating = DB::table('shop_ratings')
                ->select('shop_id', DB::raw('count(*) as rating'))
                ->where('shop_id',$shop_list->id)
                ->first();

        $sum = DB::table('shop_ratings')
                ->select('shop_id', DB::raw('SUM(rating) as sum'))
                ->where('shop_id',$shop_list->id)
                ->first();

            if($rating->rating != 0 and $sum->sum != 0){
                if($rating->shop_id == $sum->shop_id){
                    $result =   $sum->sum / $rating->rating;
                    $rating->Avg_rating = $result;
                }
            }
            else{
                $rating->Avg_rating = 0;
            }


            foreach($shop_list as $value){
                if(!empty($rating)){
                    $shop_list->avg_rating = $rating->Avg_rating;
                }
                else{
                    $shop_list->avg_rating = 0;
                }
            }
        }
        return response()->json([
            'data' => $shop_list
        ]);
    }

    public function shop_post_store(Request $request)
    {
        $input = $request->all();
        $user = auth()->user();
        $user_role = $user->getRoleNames()->first();
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
                    Storage::put('public/post/' . $file_name,$tmp,'public'
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
                    Storage::put('public/post/' . $file_name, $tmp,'public'
                    );
                    $imgData[] = $file_name;
                    $post->media = json_encode($imgData);
                }
            }
        }

        $banwords = DB::table('ban_words')->select('ban_word_english', 'ban_word_myanmar', 'ban_word_myanglish')
                    ->get();

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

        // $shop_member_level = ShopMember::select('member_type')->where('id', $user->shopmember_type_id)->first();
        // if (($user->shop_post_count == 0 and $shop_member_level != 'level3') or
        //     ($user->shop_post_count == 0 and $user_role != 'Ruby Premium') or
        //     ($user->shop_post_count == 0 and $user_role != 'Ruby')
        // ) {

        //     $message = 'Cannot Post';
        // }


        $shop_member_level = ShopMember::select('member_type')->where('id',$user->shopmember_type_id)->first();
        // if(($user->shop_post_count == 0 AND $shop_member_level != 'level3') OR
        //    ($user->shop_post_count == 0 AND $user->member_type != 'Ruby Premium') OR
        //    ($user->shop_post_count == 0 AND $user->member_type != 'Ruby')){
        if (($user->shop_post_count == 0 and $shop_member_level != 'level3') or
            ($user->shop_post_count == 0 and $user_role != 'Ruby Premium') or
            ($user->shop_post_count == 0 and $user_role != 'Ruby')
        ) {
            return response()->json([
                'message' => 'cannot post',
                'user' => $user,
                'shop' => $shop_member_level,
                'role' => $user_role
            ]);
        }

        $post->user_id = $user->id;
        $post->caption = $caption;
        $post->shop_status = 1;
        $post->save();

        if($user->member_type != 'Ruby Premium' OR $user->member_type != 'Ruby' OR $shop_member_level != 'level3'){
            $user = User::find(auth()->user()->id);
            $user->shop_post_count = $user->shop_post_count - 1;
            $user->update();
        }


        $id = $post->id;

        $post_one = Post::select('users.name', 'profiles.profile_image', 'posts.*')
            ->where('posts.id', $id)
            ->leftJoin('users', 'users.id', 'posts.user_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->first();
        $roles = DB::select("SELECT roles.name,model_has_roles.model_id FROM model_has_roles 
            left join roles on model_has_roles.role_id = roles.id");

        foreach ($post_one as $key => $value) {
            $post_one['is_save'] = 0;
            $post_one['is_like'] = 0;
            $post_one['like_count'] = 0;
            $post_one['comment_count'] = 0;
            $post_one['roles'] = null;
            foreach($roles as $r){
                if($r->model_id == $post_one->user_id){
                    $post_one['roles'] = $r->name;
              }
              else{
                    $post_one['roles'] = null;
              }
            }
        }
        return response()->json([
            'data' => $post_one
        ]);
    }

    public function shop_posts(Request $request)
    {
        $auth = Auth()->user()->id;
        $id = $request->id;
        $posts = Post::select('users.name', 'profiles.profile_image', 'posts.*')
            ->where('posts.user_id', $id)
            ->where('posts.shop_status', 1)
            ->leftJoin('users', 'users.id', 'posts.user_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->orderBy('posts.created_at', 'DESC')
            ->paginate(30);

        $saved_post = UserSavedPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_saved_posts.post_id')
            ->where('user_saved_posts.user_id', $auth)
            ->get();

        $liked_post = UserReactPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_react_posts.post_id')
            ->where('user_react_posts.user_id', $auth)->get();
        $liked_post_count = DB::select("SELECT COUNT(post_id) as like_count, post_id FROM user_react_posts GROUP BY post_id");
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

        // $comment_post_count = DB::select("SELECT COUNT(post_id) as comment_count, post_id FROM comments GROUP BY post_id");
        if($array){
            $comment_post_count =  DB::table('comments')
            ->select('post_id', DB::raw('count(*) as comment_count'))
            ->where('report_status',0)
            ->where('deleted_at',null)
            ->whereNotIn('user_id',$array)
            ->groupBy('post_id')
            ->get();
        }
        else{
            $comment_post_count =  DB::table('comments')
            ->select('post_id', DB::raw('count(*) as comment_count'))
            ->where('report_status',0)
            ->where('deleted_at',null)
            ->groupBy('post_id')
            ->get();
        }
       
        
        $roles = DB::select("SELECT roles.name,model_has_roles.model_id FROM model_has_roles 
            left join roles on model_has_roles.role_id = roles.id");

        foreach ($posts as $key => $value) {
            $posts[$key]['is_save'] = 0;
            $posts[$key]['is_like'] = 0;
            $posts[$key]['like_count'] = 0;
            $posts[$key]['comment_count'] = 0;
            $posts[$key]['roles'] = 0;
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
            foreach($roles as $r){
                if($r->model_id == $value->user_id){
                    $posts[$key]['roles'] = $r->name;
                    break;
              }
              else{
                    $posts[$key]['roles'] = null;
              }
            }
        }
        return response()->json([
            'posts' => $posts
        ]);
    }

    public function shop_rating(Request $request){
        
        $rating=$request->rating;
        $user_id = auth()->user()->id;
        $shop_id = $request->shop_id;
        $shop_rating = ShopRating::where('user_id', $user_id)->where('shop_id',$shop_id)->first();



        if($shop_rating){
            if($shop_rating->shop_id != auth()->user()->id){
                DB::table('shop_ratings')->where('user_id', $user_id)->where('shop_id',$shop_id)->update(['rating' => $request->rating]);
                $message='rated';

            }
            else{
                $message='error';

            }
        }else{
            $shop_rating = new ShopRating();
            $shop_rating->user_id = $user_id;
            $shop_rating->shop_id = $shop_id;
            $shop_rating->rating = $request->rating;
            $shop_rating->save();
            $message='rated';
        }

        return response()->json([
            'success' => $message
        ]);
    }

    public function admin_id(){
        $id = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'admin');
        })->first();
        $to_user_id = $id->id;
        return response()->json([
            'receiver_id' => $to_user_id
        ]);
    }

    public function free_video(){
        $videos = FreeVideo::get();
        return response()->json([
            'data' =>  $videos
        ]);
    }

    public function shop_post_count(){
        $post_count = User::select('shop_post_count')->where('id',Auth::user()->id)->first();
        return response()->json([
            'data' =>  $post_count
        ]);
    }

    public function cancel(Request $request)
    {
        $id = $request->id;
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options = array(
                'cluster' => 'eu',
                'encrypted' => true
            )
        );
        if ($request->isGroup == 1) {
            $group_member = ChatGroupMember::select('member_id')->where('group_id', $id)->get();
            for ($i = 0; count($group_member) > $i; $i++) {
                $to_user_id = $group_member[$i]['member_id'];
                $pusher->trigger('cancel-call.' . $group_member[$i]['member_id'], 'cancel-event', 'Call Canceled!');
            }
        } else {
            $pusher->trigger('cancel-call.' . $id, 'cancel-event', 'Call Canceled!');
        }
        return response()->json([
            'message' =>  'success'
        ]);
    }
}
