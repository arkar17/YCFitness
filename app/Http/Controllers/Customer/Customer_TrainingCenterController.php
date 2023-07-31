<?php

namespace App\Http\Controllers\Customer;



use Carbon\Carbon;
use App\Models\Meal;
use App\Models\Post;
use App\Models\User;
use App\Models\Member;
use App\Models\Comment;
use App\Models\Profile;
use App\Models\Workout;
use App\Models\MealPlan;
use App\Models\BlockList;
use App\Models\WaterTracked;
use Illuminate\Http\Request;
use App\Models\UserReactPost;
use App\Models\UserSavedPost;
use App\Models\WeightHistory;
use App\Models\PersonalMealInfo;
use App\Models\UserReactComment;
use Illuminate\Support\Facades\DB;
use App\Models\PersonalWorkOutInfo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;



class Customer_TrainingCenterController extends Controller

{
    public function index()
    {
        $user = auth()->user();
        $bmi = $user->bmi;
        if ($bmi < 18.5) {
            $workout_plan = "weight gain";
        } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
            $workout_plan = "body beauty";
        } elseif ($bmi >= 25) {
            $workout_plan = "weight loss";
        }

        $tc_workouts = DB::table('workouts')
            ->where('workout_plan_type', $workout_plan)
            ->where('member_type', $user->member_type)
            ->where('gender_type', $user->gender)
            ->get();


        return view('customer.training_center.index', compact('workout_plan', 'tc_workouts'));
    }

    public function profile()
    {
        $user_id = auth()->user()->id;
        $user=User::findOrFail($user_id);

        $friends=DB::table('friendships')
                    ->where('friend_status',2)
                    ->where(function($query) use ($user_id){
                        $query->where('sender_id',$user_id)
                            ->orWhere('receiver_id',$user_id);
                    })
                    ->get(['sender_id','receiver_id'])->toArray();

        if(!empty($friends)){
            $n= array();
            foreach($friends as $friend){
                    $f=(array)$friend;
                    array_push($n, $f['sender_id'],$f['receiver_id']);
            }
        }else{
            $n= array();
        }

        $posts=Post::where('user_id',$user_id)
            ->where('report_status', 0)
                    ->orderBy('created_at','DESC')
                    ->with('user')
                    ->paginate(30);

        $id = auth()->user()->id;
        $block_list = BlockList::where('sender_id',$id)->orWhere('receiver_id',$id)->get(['sender_id', 'receiver_id'])->toArray();
        $b = array();
        foreach ($block_list as $block) {
            $f = (array)$block;
            array_push($b, $f['sender_id'], $f['receiver_id']);
        }
            
        $user_friends=User::whereIn('id',$n)
                        ->where('id','!=',$user_id)
                        ->whereNotIn('id',$b)
                        ->paginate(6);
   
        $user_profile_cover=Profile::select('cover_photo')
                                ->where('user_id',$user_id)
                                ->where('profile_image',null)
                                ->orderBy('created_at','DESC')
                                ->first();

        $user_profile_image=Profile::select('profile_image')
                                ->where('user_id',$user_id)
                                ->where('cover_photo',null)
                                ->orderBy('created_at','DESC')
                                ->first();

        if($user_profile_cover==null){
            $user_profile_cover=null;
        }else{
            $user_profile_cover=$user_profile_cover;
        }

        if($user_profile_image==null){
            $user_profile_image=null;
        }else{
            $user_profile_image=$user_profile_image;
        }

        $current_date = Carbon::now('Asia/Yangon')->toDateString();
        $year=Carbon::now()->subYear(10)->format("Y");
        $current_year=Carbon::now()->format("Y");

        $workouts = DB::table('personal_work_out_infos')
            ->where('user_id', $user_id)
            ->where('date', $current_date)
            ->join('workouts', 'workouts.id', 'personal_work_out_infos.workout_id')
            ->get();

        $workout_date = DB::table('personal_work_out_infos')
            ->select('date')
            ->where('user_id', $user_id)
            ->get();

        $weight_history = DB::table('weight_histories')
            ->where('user_id', $user_id)
            ->whereYear('date',$current_year)
            ->orderBy('date', 'ASC')
            ->get();

        if (sizeof($weight_history) == 1) {
            $weight_date = DB::table('weight_histories')
                ->select('date')
                ->where('user_id', $user_id)
                ->first();

            $newDate =\Carbon\Carbon::parse($weight_date->date)->addMonth(1)->format("j F, Y");
        }elseif(sizeof($weight_history) >1){
            $weight_date=DB::table('weight_histories')
                            ->where('user_id',$user_id)
                            ->orderBy('date','DESC')
                            ->first();

            $newDate =\Carbon\Carbon::parse($weight_date->date)->addMonth(1)->format("j F, Y");
        }else{
            $weight_date=null;
            $newDate=null;
        }

        $cal_sum = 0;
        $time_sum = 0;
        $time_min = 0;
        $time_sec = 0;
        foreach ($workouts as $s) {
            $cal_sum += $s->calories;
            $time_sum += $s->time;
            if ($time_sum >= 60) {
                $time_min = floor($time_sum / 60);
                $time_sec = $time_sum % 60;
            } else {
                $time_min = 0;
                $time_sec = $time_sum;
            }
        }
        return view('customer.training_center.profile', compact('user','posts','user_friends','user_profile_cover','user_profile_image','year','workouts', 'workout_date', 'cal_sum', 'time_min', 'time_sec', 'weight_history', 'newDate'));
    }

    public function saved_post(){

        $saved_posts = DB::table('user_saved_posts')
                        ->select('users.name','profiles.profile_image','posts.*','posts.id as post_id','posts.created_at as post_date')
                        ->leftJoin('posts','posts.id','user_saved_posts.post_id')
                        ->where('user_saved_posts.user_id',auth()->user()->id)
                        ->where('posts.report_status',0)
                        ->where('posts.shop_status',0)
                        ->leftJoin('users','users.id','posts.user_id')
                        ->leftJoin('profiles','users.profile_id','profiles.id')
                        ->orderBy('user_saved_posts.created_at','DESC')
                        ->get();

            foreach($saved_posts as $key=>$value){

            $react = auth()->user()->user_reacted_posts()->where('post_id', $value->post_id)->first();
            if (!empty($react)) {
                $isLike=1;
            }else{
                $isLike=0;
            }
            $date= Carbon::parse($value->post_date)
                            ->format('d M Y , g:i A');

            $total_likes=UserReactPost::where('post_id',$value->post_id)
                            ->get()->count();
            $total_comments=Comment::where('post_id',$value->post_id)
                            ->get()->count();

            $saved_posts[$key]->total_likes=$total_likes;
            $saved_posts[$key]->total_comments=$total_comments;
            $saved_posts[$key]->date= $date;
            $saved_posts[$key]->isLike=$isLike;
            }

        return response()->json([
            'save_posts' => $saved_posts
            ]);
    }

    public function shop_saved_post(){

        $saved_posts = DB::table('user_saved_posts')
                        ->select('users.name','profiles.profile_image','posts.*','posts.id as post_id','posts.created_at as post_date')
                        ->leftJoin('posts','posts.id','user_saved_posts.post_id')
                        ->where('user_saved_posts.user_id',auth()->user()->id)
                        ->where('posts.report_status',0)
                        ->where('posts.shop_status',1)
                        ->leftJoin('users','users.id','posts.user_id')
                        ->leftJoin('profiles','users.profile_id','profiles.id')
                        ->orderBy('user_saved_posts.created_at','DESC')
                        ->get();

            foreach($saved_posts as $key=>$value){

            $react = auth()->user()->user_reacted_posts()->where('post_id', $value->post_id)->first();
            if (!empty($react)) {
                $isLike=1;
            }else{
                $isLike=0;
            }
            $date= Carbon::parse($value->post_date)
                            ->format('d M Y , g:i A');

            $total_likes=UserReactPost::where('post_id',$value->post_id)
                            ->get()->count();
            $total_comments=Comment::where('post_id',$value->post_id)
                            ->get()->count();

            $saved_posts[$key]->total_likes=$total_likes;
            $saved_posts[$key]->total_comments=$total_comments;
            $saved_posts[$key]->date= $date;
            $saved_posts[$key]->isLike=$isLike;
            }

        return response()->json([
            'save_posts' => $saved_posts
            ]);
    }

    public function all_post(){

        $posts=DB::table('posts')
                    ->select('users.name','profiles.profile_image','posts.*','posts.id as post_id','posts.created_at as post_date')
                    ->where('posts.user_id',auth()->user()->id)
                    ->where('posts.report_status',0)
                    ->where('posts.shop_status',0)
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
            // $total_comments=Comment::where('post_id',$value->post_id)
            //                 ->get()->count();
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

            if ($array) {
                $comment_post_count =  DB::table('comments')
                ->select('post_id', DB::raw('count(*) as comment_count'))
                ->where('report_status', 0)
                ->where('deleted_at', null)
                ->whereNotIn('user_id', $array)
                    ->groupBy('post_id')
                    ->get();
            } else {
                $comment_post_count =  DB::table('comments')
                    ->select('post_id', DB::raw('count(*) as comment_count'))
                    ->where('report_status', 0)
                    ->where('deleted_at', null)
                    ->groupBy('post_id')
                    ->get();
            }

            $posts[$key]->total_likes=$total_likes;
            foreach($comment_post_count as $comment_count){
                if($value->post_id == $comment_count->post_id ){
                    $posts[$key]->total_comments = $comment_count->comment_count;
                    break;
                }
                else{
                    $posts[$key]->total_comments=0;
                }
               
            }
            $posts[$key]->date= $date;
            $posts[$key]->isLike=$isLike;
            $posts[$key]->already_saved=$already_saved;
            }
        return response()->json([
            'posts' => $posts
            ]);
    }

    public function shop_all_post(Request $request){
            $posts=DB::table('posts')
                    ->select('users.name','profiles.profile_image','posts.*','posts.id as post_id','posts.created_at as post_date')
                    ->where('posts.user_id',auth()->user()->id)
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
            $total_comments=Comment::where('post_id',$value->post_id)
                            ->get()->count();

            $posts[$key]->total_likes=$total_likes;
            $posts[$key]->total_comments=$total_comments;
            $posts[$key]->date= $date;
            $posts[$key]->isLike=$isLike;
            $posts[$key]->already_saved=$already_saved;

            $roles = DB::select("SELECT roles.name,model_has_roles.model_id FROM model_has_roles 
            left join roles on model_has_roles.role_id = roles.id where  model_has_roles.model_id = $value->user_id");

            foreach($roles as $r){
                                    
                if($r->model_id == $value->user_id){
                    $posts[$key]->roles = $r->name;
                    break;
                }
                else{
                        $posts[$key]->roles = null;
                    }
                }        

            }

            // dd($posts);
            return response()->json([
            'posts' => $posts
            ]);
    }


    public function shop_all_post_id(Request $request){
        $user_id = $request->post_id;
        $posts=DB::table('posts')
                ->select('users.name','profiles.profile_image','posts.*','posts.id as post_id','posts.created_at as post_date')
                ->where('posts.user_id', $user_id)
                ->where('posts.report_status',0)
                ->where('posts.shop_status',1)
                ->where('posts.deleted_at',null)
                ->leftJoin('users','users.id','posts.user_id')
                ->leftJoin('profiles','users.profile_id','profiles.id')
                ->orderBy('posts.created_at','DESC')
                ->get();
        if($request->keyword != null){
            $posts=DB::table('posts')
            ->select('users.name','profiles.profile_image','posts.*','posts.id as post_id','posts.created_at as post_date')
            ->where('posts.user_id', $user_id)
            ->where('posts.report_status',0)
            ->where('posts.shop_status',1)
            ->where('posts.deleted_at',null)
            ->leftJoin('users','users.id','posts.user_id')
            ->leftJoin('profiles','users.profile_id','profiles.id')
            ->where('posts.caption', 'LIKE', '%' . $request->keyword . '%')
            ->orderBy('posts.created_at','DESC')
            ->get();
        }

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
        $total_comments=Comment::where('post_id',$value->post_id)
                        ->get()->count();

        $posts[$key]->total_likes=$total_likes;
        $posts[$key]->total_comments=$total_comments;
        $posts[$key]->date= $date;
        $posts[$key]->isLike=$isLike;
        $posts[$key]->already_saved=$already_saved;
        
        $roles = DB::select("SELECT roles.name,model_has_roles.model_id FROM model_has_roles 
        left join roles on model_has_roles.role_id = roles.id where  model_has_roles.model_id = $value->user_id");

        foreach($roles as $r){
                                
            if($r->model_id == $value->user_id){
                $posts[$key]->roles = $r->name;
                break;
            }
            else{
                    $posts[$key]->roles = null;
                }
            } 
        }

    return response()->json([
        'posts' => $posts
        ]);
}

    public function profile_post_likes($post_id)
    {
        $auth=auth()->user()->id;
        $post_likes=UserReactPost::select('users.name','profiles.profile_image','user_react_posts.*')
                    ->leftJoin('users','users.id','user_react_posts.user_id')
                    ->leftJoin('profiles','users.profile_id','profiles.id')
                    ->where('post_id',$post_id)
                    ->get();
        $friends = DB::select("SELECT * FROM `friendships` WHERE (receiver_id = $auth or sender_id = $auth)");

                    foreach($post_likes as $key=>$value){
                        foreach($friends as $fri){
                            if($value->user_id == $fri->receiver_id AND $fri->sender_id == $auth AND $fri->friend_status == 1    ){
                                $post_likes[$key]['friend_status'] = "cancel request";
                                break;
                            }
                            else if($value->user_id == $fri->sender_id AND $fri->receiver_id == $auth AND $fri->friend_status == 1    ){
                                $post_likes[$key]['friend_status'] = "response";
                                break;
                            }
                            else if($value->user_id == $fri->receiver_id AND $fri->sender_id == $auth AND $fri->friend_status == 2){
                                $post_likes[$key]['friend_status'] = "friend";
                                break;
                            }
                            else if($value->user_id == $fri->sender_id AND $fri->receiver_id == $auth AND $fri->friend_status == 2){
                                $post_likes[$key]['friend_status'] = "friend";
                                break;
                            }
                            else if($value->user_id == $auth){
                                $post_likes[$key]['friend_status'] = "myself";
                                break;
                            }
                            else{
                                $post_likes[$key]['friend_status'] = "add friend";
                            }
                        }
                    }

        return response()
        ->json([
            'post_likes'=>$post_likes
                    ]);
    }


    public function comment_likes($id)
    {
        // dd("okk");
        $auth=auth()->user()->id;
        $post_likes=UserReactComment::select('users.name','profiles.profile_image','user_react_comments.*')
                    ->leftJoin('users','users.id','user_react_comments.user_id')
                    ->leftJoin('profiles','users.profile_id','profiles.id')
                    ->where('comment_id',$id)
                    ->get();
        $friends = DB::select("SELECT * FROM `friendships` WHERE (receiver_id = $auth or sender_id = $auth)");

                    foreach($post_likes as $key=>$value){
                        foreach($friends as $fri){
                            if($value->user_id == $fri->receiver_id AND $fri->sender_id == $auth AND $fri->friend_status == 1    ){
                                $post_likes[$key]['friend_status'] = "cancel request";
                                break;
                            }
                            else if($value->user_id == $fri->sender_id AND $fri->receiver_id == $auth AND $fri->friend_status == 1    ){
                                $post_likes[$key]['friend_status'] = "response";
                                break;
                            }
                            else if($value->user_id == $fri->receiver_id AND $fri->sender_id == $auth AND $fri->friend_status == 2){
                                $post_likes[$key]['friend_status'] = "friend";
                                break;
                            }
                            else if($value->user_id == $fri->sender_id AND $fri->receiver_id == $auth AND $fri->friend_status == 2){
                                $post_likes[$key]['friend_status'] = "friend";
                                break;
                            }
                            else if($value->user_id == $auth){
                                $post_likes[$key]['friend_status'] = "myself";
                                break;
                            }
                            else{
                                $post_likes[$key]['friend_status'] = "add friend";
                            }
                        }
                    }

        return response()
        ->json([
            'post_likes'=>$post_likes
                    ]);
    }

    public function member_plan()
    {
        $members = Member::orderBy('price', 'ASC')->get();
        $pros=DB::table('members')->select('pros')->get()->toArray();
        $cons=DB::table('members')->select('cons')->get()->toArray();
        $durations = Member::groupBy('duration')->where('duration', '!=', 0)->get();
        return view('customer.training_center.member_plan',compact('members','durations','pros','cons'));
    }

    public function workout_plan(Request $request)
    {
        $user = auth()->user();
        $bmi = $user->bmi;
        if ($bmi < 18.5) {
            $workout_plan = "weight gain";
        } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
            $workout_plan = "body beauty";
        } elseif ($bmi >= 25) {
            $workout_plan = "weight loss";
        }

        $current_day = Carbon::now()->format('l');
        // $current_day = Carbon::now()->isoFormat('dddd');
        // $random_category =  Workout::get()->random()->category;
        $workout = Workout::count();
        // dd($workout);
        if($workout > 0){
            $random_category = Cache::remember('random_category', 60*24, function (){
                return Workout::get()->random()->category;
            });
        }
        else{
            $random_category = null;
        }
        // dd($random_category, "dddd");

        //Storage::disk('local')->put('aa', $random_category);
        if($random_category){
            $tc_gym_workoutplans = DB::table('workouts')
            ->where('workout_plan_type', $workout_plan)
                ->where('place', 'Gym')
            ->where('member_type', $user->member_type)
            ->where('gender_type', $user->gender)
            ->where('workout_level', $user->membertype_level)
            ->where('day', $current_day)
            // ->where('category',  $random_category)
            ->get();

        $tc_home_workoutplans = DB::table('workouts')
            ->where('workout_plan_type', $workout_plan)
                ->where('place', 'Home')
            ->where('member_type', $user->member_type)
            ->where('gender_type', $user->gender)
            ->where('workout_level', $user->membertype_level)
            ->where('day', $current_day)
            // ->where('category',  $random_category)
                ->get();

           // dd($tc_home_workoutplans);
            $time_sum = 0;

            $c_sum = 0;
            foreach ($tc_gym_workoutplans as $s) {
                $c_sum += $s->calories;
            }
            // // home
             $time_sum_home = 0;

            $c_sum_home = 0;
            foreach ($tc_home_workoutplans as $s) {
                $c_sum_home += $s->calories;
            }
            foreach ($tc_gym_workoutplans as $s) {
                $time_sum+=$s->estimate_time;
            }

            foreach ($tc_home_workoutplans as $home) {
                $time_sum_home+=$home->estimate_time;
            }
            //dd($tc_home_workoutplans);
        }
        else{
            $tc_gym_workoutplans = array();
            $tc_home_workoutplans = array();
            $time_sum = 0;
            $c_sum = 0;
            $time_sum_home = 0;
            $c_sum_home = 0;
        }
            


        return view('customer.training_center.workout_plan', compact('tc_gym_workoutplans', 'tc_home_workoutplans', 'time_sum', 'c_sum', 'time_sum_home', 'c_sum_home',));
    }

    public function workout_filter($from, $to)
    {
        $user_id = auth()->user()->id;

        $from_date = Carbon::createFromFormat('Y-m-d', $from)->format('d,M,Y');
        $to_date = Carbon::createFromFormat('Y-m-d', $to)->format('d,M,Y');
        $workouts = DB::table('personal_work_out_infos')
            ->where('user_id', $user_id)
            ->whereBetween('date', [$from, $to])
            ->join('workouts', 'workouts.id', 'personal_work_out_infos.workout_id')
            ->get();
        $cal_sum = 0;
        $time_sum = 0;
        $time_min = 0;
        $time_sec = 0;
        foreach ($workouts as $s) {
            $cal_sum += $s->calories;
            $time_sum += $s->time;
            if ($time_sum >= 60) {
                $time_min = floor($time_sum / 60);
                $time_sec = $time_sum % 60;
            } else {
                $time_min = 0;
                $time_sec = $time_sum;
            }
        }
        return response()
            ->json([
                'workouts' => $workouts,
                'from' => $from_date,
                'to' => $to_date,
                'cal_sum' => $cal_sum,
                'time_min' => $time_min,
                'time_sec' => $time_sec
            ]);
    }

    public function profile_update(Request $request)
    {
        $user_id=auth()->user()->id;
        $current_date = Carbon::now('Asia/Yangon')->toDateString();
        $user=User::findOrFail($user_id);
        $user->weight=$request->weight;
        $user->neck=$request->neck;
        $user->hip=$request->hip;
        $user->waist=$request->waist;
        $user->shoulders=$request->shoulders;
        $user->thigh = $request->thigh;
        $user->calf = $request->calf;
        $user->arm = $request->arm;
        $user->wrist = $request->wrist;
        $user->age=$request->age;

        $height_ft = $request->height_ft;
        $height_in = $request->height_in;
        $height = ($height_ft * 12) + $height_in;

        $user->height = $height;
        $bmi = number_format((float)$request->weight / ($height * $height) * 703, 1);
        $user->bmi = $bmi;

        if (auth()->user()->gender == 'male') {

            $bmr = (($request->weight) * 4.536) + (($height) * 15.88) + - (($request->age) * 5) + 5;
            $bfp = round((86.010 * (log($request->waist * 1 - $request->neck * 1) / log(10)) - 70.041 * (log($height) / log(10)) + 36.76 * 1) * 100) / 100;
        } else {
            $bmr = (($request->weight) * 4.536) + (($height) * 15.88) + - (($request->age) * 5) - 161;
            $bfp = round((163.205 * (log($request->waist * 1.0 + $request->hip * 1.0 - $request->neck * 1.0) / log(10)) - 97.684 * (log($height) / log(10)) - 78.387 * 1.0) * 100) / 100;
        }
        $user->bmr = $bmr;
        $user->bfp = $bfp;

        $weight_history = new WeightHistory();
        $weight_history->weight = $request->weight;
        $weight_history->user_id = $user_id;
        $weight_history->date = $current_date;
        $weight_history->save();

        $user->update();
        Alert::success('Success', 'Profile Updated Successfully');
        return redirect()->back();
    }

    public function profile_update_name(Request $request)
    {
        $user_id=auth()->user()->id;
        $user=User::findOrFail($user_id);
        $user->name=$request->name;
        $user->update();
        Alert::success('Success', 'Name Updated Successfully');
        return redirect()->back();
    }

    public function profile_update_bio(Request $request)
    {
        $user_id=auth()->user()->id;
        $user=User::findOrFail($user_id);
        $user->bio=$request->bio;
        $user->update();
        Alert::success('Success', 'Bio Updated Successfully');
        return redirect()->back();
    }

    public function profile_update_cover(Request $request)
    {

        if($request->hasFile('cover')){
            $file = $request->file('cover');
            $extension = $file->extension();
            $name = rand().".".$extension;
            // $file->storeAs('/public/post/', $name);
            Storage::put(
                'public/post/'.$name,
                file_get_contents($file),'public'
               );
            $imgData = $name;

        }
        $profile=new Profile();
        $profile->cover_photo=$imgData;
        $profile->user_id=auth()->user()->id;
        $profile->save();

        $user = User::findOrFail(auth()->user()->id);
        $user->cover_id = $profile->id;
        $user->update();

        Alert::success('Success', 'Cover Photo Updated Successfully');
        return redirect()->back();
    }

    public function profile_update_profile_img(Request $request)
    {
        // dd($request);
        if($request->hasFile('profile_image')){
            $file = $request->file('profile_image');
            $extension = $file->extension();
            $name = rand().".".$extension;
            // $file->storeAs('/public/post/', $name);
            Storage::put('public/post/'.$name,file_get_contents($file),'public');

            // Storage::disk('do_spaces')->put('uploads', $request->file('profile_image'), 'public');


            
            // $file->storeAs('/public/post/', $name);

            $imgData = $name;

        }
        $profile=new Profile();
        $profile->profile_image=$imgData;
        $profile->user_id=auth()->user()->id;
        $profile->save();


        $user = User::findOrFail(auth()->user()->id);
        $user->profile_id = $profile->id;
        $user->update();

        Alert::success('Success', 'Profile Photo Updated Successfully');
        return redirect()->back();
    }

    public function year_filter($year)
    {
        $user_id = auth()->user()->id;

        $weight_history = DB::table('weight_histories')
            ->where('user_id', $user_id)
            ->whereYear('date',$year)
            ->orderBy('date', 'ASC')
            ->get();

        return response()
            ->json([
                'weight_history' => $weight_history
            ]);
    }

    public function workout_sevenday()
    {
        $user_id = auth()->user()->id;
        $current_date = Carbon::now('Asia/Yangon')->subDays(1)->toDateString();
        $sevenday = Carbon::now('Asia/Yangon')->subDays(7)->toDateString();

        $current = Carbon::now('Asia/Yangon')->subDays(1)->format('d,M,Y');
        $seven = Carbon::now('Asia/Yangon')->subDays(7)->format('d,M,Y');

        $workouts = DB::table('personal_work_out_infos')
            ->where('user_id', $user_id)
            ->whereBetween('date', [$sevenday, $current_date])
            ->join('workouts', 'workouts.id', 'personal_work_out_infos.workout_id')
            ->get();
        $cal_sum = 0;
        $time_sum = 0;
        $time_min = 0;
        $time_sec = 0;
        foreach ($workouts as $s) {
            $cal_sum += $s->calories;
            $time_sum += $s->time;
            if ($time_sum >= 60) {
                $time_min = floor($time_sum / 60);
                $time_sec = $time_sum % 60;
            } else {
                $time_min = 0;
                $time_sec = $time_sum;
            }
        }

        return response()
            ->json([
                'workouts' => $workouts,
                'current' => $current,
                'seven' => $seven,
                'cal_sum' => $cal_sum,
                'time_min' => $time_min,
                'time_sec' => $time_sec
            ]);
    }

    public function meal_sevendays($date)
    {
        $user_id = auth()->user()->id;
        //$formateddate = Carbon::parse($date)->format('M d');

        // $daymeal_breafast=DB::table('personal_meal_infos')
        //             ->where('personal_meal_infos.client_id',$user_id)
        //             ->join('meals','meals.id','personal_meal_infos.meal_id')
        //             ->where('meals.meal_plan_type','Breakfast')
        //             ->where('personal_meal_infos.date',$date)
        //             ->get();
        $daymeal_breafast = PersonalMealInfo::leftJoin('meals', 'meals.id', 'personal_meal_infos.meal_id')
            ->select(
                'meals.id',
                'meals.name',
                'personal_meal_infos.serving',
                DB::raw('( personal_meal_infos.serving * meals.calories) As calories'),
                DB::raw('( personal_meal_infos.serving * meals.protein) As protein'),
                DB::raw('( personal_meal_infos.serving * meals.carbohydrates) As carbohydrates'),
                DB::raw('( personal_meal_infos.serving * meals.fat) As fat'),
            )
            ->where('personal_meal_infos.client_id', $user_id)
            ->where('personal_meal_infos.date', $date)
            ->get()
            ->toArray();
        // dd($meal_personal_info);
        $total_calories_breakfast = 0;
        $total_protein_breakfast = 0;
        $total_carbohydrates_breakfast = 0;
        $total_fat_breakfast = 0;
        $total_serving_breakfast = 0;
        if ($daymeal_breafast) {
            foreach ($daymeal_breafast as $meal_personal) {
                // $meal = Meal::where('id',$meal_personal->meal_id)->get()->toArray();
                $total_calories_breakfast += $meal_personal['calories'];
                $total_protein_breakfast += $meal_personal['protein'];
                $total_carbohydrates_breakfast += $meal_personal['carbohydrates'];
                $total_fat_breakfast += $meal_personal['fat'];
                $total_serving_breakfast += $meal_personal['serving'];
            }
        }
        // $daymeal_lunch=DB::table('personal_meal_infos')
        //             ->where('personal_meal_infos.client_id',$user_id)
        //             ->join('meals','meals.id','personal_meal_infos.meal_id')
        //             ->where('meals.meal_plan_type','Lunch')
        //             ->where('personal_meal_infos.date',$date)
        //             ->get();
        $daymeal_lunch = PersonalMealInfo::leftJoin('meals', 'meals.id', 'personal_meal_infos.meal_id')
            ->select(
                'meals.id',
                'meals.name',
                'personal_meal_infos.serving',
                DB::raw('( personal_meal_infos.serving * meals.calories) As calories'),
                DB::raw('( personal_meal_infos.serving * meals.protein) As protein'),
                DB::raw('( personal_meal_infos.serving * meals.carbohydrates) As carbohydrates'),
                DB::raw('( personal_meal_infos.serving * meals.fat) As fat'),
            )
            ->where('personal_meal_infos.client_id', $user_id)
            ->where('personal_meal_infos.date', $date)
            ->where('meals.meal_plan_type', 'Lunch')
            ->get()
            ->toArray();
        // dd($meal_personal_info);
        $total_calories_lunch = 0;
        $total_protein_lunch = 0;
        $total_carbohydrates_lunch = 0;
        $total_fat_lunch = 0;
        $total_serving_lunch = 0;
        if ($daymeal_lunch) {
            foreach ($daymeal_lunch as $meal_personal) {
                // $meal = Meal::where('id',$meal_personal->meal_id)->get()->toArray();
                $total_calories_lunch += $meal_personal['calories'];
                $total_protein_lunch += $meal_personal['protein'];
                $total_carbohydrates_lunch += $meal_personal['carbohydrates'];
                $total_fat_lunch += $meal_personal['fat'];
                $total_serving_lunch += $meal_personal['serving'];
            }
        }

        // $daymeal_snack=DB::table('personal_meal_infos')
        //             ->where('personal_meal_infos.client_id',$user_id)
        //             ->join('meals','meals.id','personal_meal_infos.meal_id')
        //             ->where('meals.meal_plan_type','Snack')
        //             ->where('personal_meal_infos.date',$date)
        //             ->get();

        $daymeal_snack = PersonalMealInfo::leftJoin('meals', 'meals.id', 'personal_meal_infos.meal_id')
            ->select(
                'meals.id',
                'meals.name',
                'personal_meal_infos.serving',
                DB::raw('( personal_meal_infos.serving * meals.calories) As calories'),
                DB::raw('( personal_meal_infos.serving * meals.protein) As protein'),
                DB::raw('( personal_meal_infos.serving * meals.carbohydrates) As carbohydrates'),
                DB::raw('( personal_meal_infos.serving * meals.fat) As fat'),
            )
            ->where('personal_meal_infos.client_id', $user_id)
            ->where('personal_meal_infos.date', $date)
            ->where('meals.meal_plan_type', 'Snack')
            ->get()
            ->toArray();
        // dd($meal_personal_info);
        $total_calories_snack = 0;
        $total_protein_snack = 0;
        $total_carbohydrates_snack = 0;
        $total_fat_snack = 0;
        $total_serving_snack = 0;
        if ($daymeal_snack) {
            foreach ($daymeal_snack as $meal_personal) {
                // $meal = Meal::where('id',$meal_personal->meal_id)->get()->toArray();
                $total_calories_snack += $meal_personal['calories'];
                $total_protein_snack += $meal_personal['protein'];
                $total_carbohydrates_snack += $meal_personal['carbohydrates'];
                $total_fat_snack += $meal_personal['fat'];
                $total_serving_snack += $meal_personal['serving'];
            }
        }

        // $daymeal_dinner=DB::table('personal_meal_infos')
        //             ->where('personal_meal_infos.client_id',$user_id)
        //             ->join('meals','meals.id','personal_meal_infos.meal_id')
        //             ->where('meals.meal_plan_type','Dinner')
        //             ->where('personal_meal_infos.date',$date)
        //             ->get();
        $daymeal_dinner = PersonalMealInfo::leftJoin('meals', 'meals.id', 'personal_meal_infos.meal_id')
            ->select(
                'meals.id',
                'meals.name',
                'personal_meal_infos.serving',
                DB::raw('( personal_meal_infos.serving * meals.calories) As calories'),
                DB::raw('( personal_meal_infos.serving * meals.protein) As protein'),
                DB::raw('( personal_meal_infos.serving * meals.carbohydrates) As carbohydrates'),
                DB::raw('( personal_meal_infos.serving * meals.fat) As fat'),
            )
            ->where('personal_meal_infos.client_id', $user_id)
            ->where('personal_meal_infos.date', $date)
            ->where('meals.meal_plan_type', 'Dinner')
            ->get()
            ->toArray();
        // dd($meal_personal_info);
        $total_calories_dinner = 0;
        $total_protein_dinner = 0;
        $total_carbohydrates_dinner = 0;
        $total_fat_dinner = 0;
        $total_serving_dinner = 0;
        if ($daymeal_dinner) {
            foreach ($daymeal_dinner as $meal_personal) {
                // $meal = Meal::where('id',$meal_personal->meal_id)->get()->toArray();
                $total_calories_dinner += $meal_personal['calories'];
                $total_protein_dinner += $meal_personal['protein'];
                $total_carbohydrates_dinner += $meal_personal['carbohydrates'];
                $total_fat_dinner += $meal_personal['fat'];
                $total_serving_dinner += $meal_personal['serving'];
            }
        }


        return response()
            ->json([
                'meal_breafast' => $daymeal_breafast,
                'meal_lunch' => $daymeal_lunch,
                'meal_snack' => $daymeal_snack,
                'meal_dinner' => $daymeal_dinner,
                'total_calories_lunch' => $total_calories_lunch,
                'total_protein_lunch' => $total_protein_lunch,
                'total_carbohydrates_lunch' => $total_carbohydrates_lunch,
                'total_fat_lunch' => $total_fat_lunch,
                'total_calories_snack' => $total_calories_snack,
                'total_protein_snack' => $total_protein_snack,
                'total_carbohydrates_snack' => $total_carbohydrates_snack,
                'total_fat_snack' => $total_fat_snack,
                'total_calories_dinner' => $total_calories_dinner,
                'total_protein_dinner' => $total_protein_dinner,
                'total_carbohydrates_dinner' => $total_carbohydrates_dinner,
                'total_fat_dinner' => $total_fat_dinner,
                'total_calories_breakfast' => $total_calories_breakfast,
                'total_protein_breakfast' => $total_protein_breakfast,
                'total_carbohydrates_breakfast' => $total_carbohydrates_breakfast,
                'total_fat_breakfast' => $total_fat_breakfast,
                'total_serving_breakfast' => $total_serving_breakfast,
                'total_serving_lunch' => $total_serving_lunch,
                'total_serving_snack' => $total_serving_snack,
                'total_serving_dinner' => $total_serving_dinner,
            ]);
    }

    public function workout_complete_store(Request $request)
    {
        $groups_id = $request->workout_id;
        $groups =  json_decode(json_encode($groups_id));
        $date = Carbon::Now()->toDateString();
        $user = auth()->user()->id;
        if ($user) {
            foreach ($groups as $gp) {
                $personal_workout_info = new PersonalWorkOutInfo();
                $personal_workout_info->user_id = $user;
                $personal_workout_info->workout_id = $gp;
                $personal_workout_info->date = $date;
                $personal_workout_info->save();
            }
        }
        return response()
            ->json([
                'status' => 200,
                'message' => "Good Job!"
            ]);

        return redirect()->back();
    }
    public function workout_complete(Request $request, $t_sum, $cal_sum = null, $count_video)
    {
        $total_time = $t_sum;
        $sec = 0;
        $duration = 0;
        if ($total_time < 60) {
            $sec = $t_sum;
        } else {
            $duration = floor($t_sum / 60);
            $sec = $t_sum % 60;
        }
        $total_calories = $cal_sum;
        $total_video = $count_video;

        $user = auth()->user();
        $bmi = $user->bmi;
        if ($bmi < 18.5) {
            $workout_plan = "weight gain";
        } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
            $workout_plan = "body beauty";
        } elseif ($bmi >= 25) {
            $workout_plan = "weight loss";
        }

        $current_day = Carbon::now()->format('l');
        $tc_workouts = DB::table('workouts')
            ->where('workout_plan_type', $workout_plan)
            ->where('place', 'home')
            ->where('member_type', $user->member_type)
            ->where('gender_type', $user->gender)
            ->where('workout_level', $user->membertype_level)
            ->where('day', $current_day)
            ->get();

        return view('customer.training_center.workout_complete', compact('total_time','t_sum', 'sec', 'duration', 'total_calories', 'total_video', 'tc_workouts'));
    }
    public function workout_complete_gym(Request $request, $t_sum, $cal_sum = null, $count_video)
    {
        $total_time = $t_sum;

        // $sec = 0;
        // $duration = 0;

        // if ($total_time < 60) {
        //     $sec = $t_sum;
        // } else {
        //     $duration = round($t_sum / 60);
        //     $sec = $t_sum % 60;
        // }

        $total_calories = $cal_sum;
        $total_video = $count_video;

        $user = auth()->user();
        $bmi = $user->bmi;
        if ($bmi < 18.5) {
            $workout_plan = "weight gain";
        } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
            $workout_plan = "body beauty";
        } elseif ($bmi >= 25) {
            $workout_plan = "weight loss";
        }

        $current_day = Carbon::now()->format('l');
        $tc_workouts = DB::table('workouts')
            ->where('workout_plan_type', $workout_plan)
            ->where('place', 'gym')
            ->where('member_type', $user->member_type)
            ->where('gender_type', $user->gender)
            ->where('workout_level', $user->membertype_level)
            ->where('day', $current_day)
            ->get();
        return view('customer.training_center.workout_complete', compact('total_time','total_calories', 'total_video', 'tc_workouts'));
    }

    public function meal()
    {
        $user = auth()->user();
        $date = Carbon::now()->toDateString();
        $bmr  = User::select('bmr')->where('id', $user->id)->first();
        $meal_personal_info = PersonalMealInfo::leftJoin('meals', 'meals.id', 'personal_meal_infos.meal_id')
            ->select(
                'meals.id',
                DB::raw('( personal_meal_infos.serving * meals.calories) As calories'),
                DB::raw('( personal_meal_infos.serving * meals.protein) As protein'),
                DB::raw('( personal_meal_infos.serving * meals.carbohydrates) As carbohydrates'),
                DB::raw('( personal_meal_infos.serving * meals.fat) As fat'),
            )
            ->where('personal_meal_infos.client_id', $user->id)
            ->where('personal_meal_infos.date', $date)
            ->get()
            ->toArray();
        // dd($meal_personal_info);
        $total_calories = 0;
        $total_protein = 0;
        $total_carbohydrates = 0;
        $total_fat = 0;
        if ($meal_personal_info) {
            foreach ($meal_personal_info as $meal_personal) {
                // $meal = Meal::where('id',$meal_personal->meal_id)->get()->toArray();
                $total_calories += $meal_personal['calories'];
                $total_protein += $meal_personal['protein'];
                $total_carbohydrates += $meal_personal['carbohydrates'];
                $total_fat += $meal_personal['fat'];
            }
        }

        // dd($total_carbohydrates);
        return view('customer.training_center.meal', compact('bmr', 'total_calories', 'total_protein', 'total_carbohydrates', 'total_fat'));
    }

    public function showbreakfast(Request $request)
    {

        $meals = Meal::get();

        if ($request->keyword != '') {
            $meals = Meal::where('name', 'LIKE', '%' . $request->keyword . '%')->get();
        }
        //dd($members);
        return response()->json([
            'breakfast' => $meals
        ]);
    }
    public function showlunch(Request $request)
    {

        $meals = Meal::where('meal_plan_type', 'Lunch')->get();

        if ($request->keyword != '') {
            $meals = Meal::where('name', 'LIKE', '%' . $request->keyword . '%')->where('meal_plan_type', 'Lunch')->get();
        }
        //dd($members);
        return response()->json([
            'lunch' => $meals
        ]);
    }
    public function showdinner(Request $request)
    {

        $meals = Meal::where('meal_plan_type', 'Dinner')->get();

        if ($request->keyword != '') {
            $meals = Meal::where('name', 'LIKE', '%' . $request->keyword . '%')->where('meal_plan_type', 'Dinner')->get();
        }
        //dd($members);
        return response()->json([
            'dinner' => $meals
        ]);
    }
    public function showsnack(Request $request)
    {

        $meals = Meal::where('meal_plan_type', 'Snack')->get();

        if ($request->keyword != '') {
            $meals = Meal::where('name', 'LIKE', '%' . $request->keyword . '%')->where('meal_plan_type', 'Snack')->get();
        }
        //dd($members);
        return response()->json([
            'snack' => $meals
        ]);
    }



    public function foodList(Request $request)
    {
        $food_lists = $request->foodList; // json string
        $food_lists =  json_decode(json_encode($food_lists));
        $date = Carbon::now()->toDateString();
        $user = auth()->user()->id;
        if ($user) {
            $current_date = Carbon::now()->toDateString();
            $current_data = PersonalMealInfo::where('personal_meal_infos.client_id', auth()->user()->id)
                ->where('personal_meal_infos.date', $current_date)->get();
            foreach ($food_lists as $meal_info) {
                $personal_meal_info = new PersonalMealInfo();
                $personal_meal_info->meal_id = $meal_info->id;
                $personal_meal_info->client_id = auth()->user()->id;
                $personal_meal_info->date = $current_date;
                // foreach ($current_data as $data) {
                if ($current_data) {
                    // dd("current_data");
                    $existing_data = $current_data->where('meal_id', $meal_info->id)->first();
                    if ($existing_data) {
                        if ($existing_data->meal_id == $meal_info->id) {
                            $personal_meal_info = PersonalMealInfo::findOrFail($existing_data->id);
                            $personal_meal_info->serving = $meal_info->servings + $existing_data->serving;
                            $personal_meal_info->update();
                        }
                    } else {
                        $personal_meal_info->serving = $meal_info->servings;
                        $personal_meal_info->save();
                    }
                } else {
                    // dd("noData");
                    $personal_meal_info->serving = $meal_info->servings;
                    $personal_meal_info->save();
                }

                $personal_meal_info->save();
            }
        }
        return response()
            ->json([
                'status' => 200,
                'message' => "Good Job!"
            ]);
    }


    public function water()
    {
        $user = auth()->user();
        $current_date = Carbon::now()->toDateString();
        $water = WaterTracked::where('date', $current_date)->where('user_id', $user->id)->first();
        // dd($water);
        return view('customer.training_center.water', compact('water'));
    }

    public function todaywater()
    {
        $user = auth()->user();
        $current_date = Carbon::now()->toDateString();
        $water = WaterTracked::where('date', $current_date)->where('user_id', $user->id)->first();
        // dd($water);
        return response()
            ->json([
                'status' => 200,
                'water' => $water
            ]);
    }

    public function lastsevenDay($date)
    {
        // dd($date);
        $user = auth()->user();
        // $current_date = $date;
        $water = WaterTracked::where('date', $date)->where('user_id', $user->id)->first();
        // dd($water);
        return response()
            ->json([
                'status' => 200,
                'water' => $water
            ]);
    }


    public function water_track()
    {
        $current_date = Carbon::now()->toDateString();

        $user = auth()->user();

        $water = WaterTracked::where('user_id', $user->id)->where('date', $current_date)->first();


        if (!$water) {
            $water = new WaterTracked();
            $water->user_id = $user->id;
            $water->update_water = 250;
            $water->date = $current_date;
            $water->save();

            return response()->json([
                'success' => 200,
                'water' => $water
            ]);
        } else {
            $water = WaterTracked::findOrFail($water->id);
            if ($water->update_water == 3000) {
                return response()->json([
                    "message" => "You cant drink anymore!"
                ]);
            }
            $water->update_water += 250;
            $water->update();

            return response()->json([
                'success' => 200,
                'water' => $water
            ]);
        }
    }

    public function workout_home()
    {
        $user = auth()->user();
        $bmi = $user->bmi;
        if ($bmi < 18.5) {
            $workout_plan = "weight gain";
        } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
            $workout_plan = "body beauty";
        } elseif ($bmi >= 25) {
            $workout_plan = "weight loss";
        }

        $current_day = Carbon::now()->format('l');
        $category = Cache::get('random_category');
        $tc_workouts = DB::table('workouts')
            ->where('place', 'Home')
            ->where('workout_plan_type', $workout_plan)
            ->where('member_type', $user->member_type)
            ->where('gender_type', $user->gender)
            ->where('workout_level', $user->membertype_level)
            ->where('day', $current_day)
            ->where('category',$category)
            ->get();


        $time_sum = 0;
        $t_sum = 0;
        // $duration = 0;
        // $sec = 0;
        // foreach ($tc_workouts as $s) {
        //     $time_sum += $s->time;
        //     if ($time_sum < 60) {
        //         $sec = $time_sum;
        //     } else {
        //         $duration = floor($time_sum / 60);
        //         $t_sum = $time_sum % 60;
        //     }
        // }

        foreach ($tc_workouts as $s){
            $t_sum+= $s->estimate_time;
        }


        $c_sum = 0;
        foreach ($tc_workouts as $s) {
            $c_sum += $s->calories;
        }

        return view('customer.training_center.workout', compact('tc_workouts', 'c_sum', 't_sum',));
    }

    public function workout_gym()
    {
        $user = auth()->user();
        $bmi = $user->bmi;
        if ($bmi < 18.5) {
            $workout_plan = "weight gain";
        } elseif ($bmi >= 18.5 && $bmi <= 24.9) {
            $workout_plan = "body beauty";
        } elseif ($bmi >= 25) {
            $workout_plan = "weight loss";
        }

        $current_day = Carbon::now()->format('l');
        $category = Cache::get('random_category');
        $tc_workouts = DB::table('workouts')
            ->where('place', 'Gym')
            ->where('workout_plan_type', $workout_plan)
            ->where('member_type', $user->member_type)
            ->where('gender_type', $user->gender)
            ->where('workout_level', $user->membertype_level)
            ->where('day', $current_day)
            ->where('category',$category)
            ->get();

        //$time_sum = 0;
        $t_sum = 0;
        //$duration = 0;
        //$sec = 0;
        // foreach ($tc_workouts as $s) {
        //     $time_sum += $s->time;
        //     if ($time_sum < 60) {
        //         $sec = $time_sum;
        //     } else {
        //         $duration = floor($time_sum / 60);
        //         $t_sum = $time_sum % 60;
        //     }
        // }
        foreach ($tc_workouts as $s){
            $t_sum+= $s->estimate_time;
        }

        $c_sum = 0;
        foreach ($tc_workouts as $s) {
            $c_sum += $s->calories;
        }

        return view('customer.training_center.workout_gym', compact('tc_workouts', 'c_sum', 't_sum'));
    }
}
