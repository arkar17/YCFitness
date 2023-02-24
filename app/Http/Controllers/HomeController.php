<?php

namespace App\Http\Controllers;


use File;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\Member;
use Carbon\CarbonPeriod;
use App\Models\BlockList;
use App\Models\BankingInfo;
use App\Models\Comment;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use App\Models\MemberHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Contracts\Role;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;

class HomeController extends Controller
{
    public function store(Request $request)
    {
        $user = new User();
        $user_member_type_id = $request->member_id;
        $user_member_type = Member::findOrFail($user_member_type_id);

        $user_member_type_level = $request->member_type_level;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->membertype_level = $request->member_type_level;
        $user->member_type = $user_member_type->member_type;
        $user->save();
        $user->members()->attach($request->member_id, ['member_type_level' => $user_member_type_level]);
        return redirect()->back();

        $user_member_type_level = $request->member_type_level;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->membertype_level = $request->member_type_level;
        $user->member_type = $user_member_type->member_type;
        $user->save();
        $user->members()->attach($request->member_id, ['member_type_level' => $user_member_type_level]);
    }

    public function lang(Request $request)
    {
        App::setLocale($request->lang);
        session()->put('locale',$request->lang);
        return redirect()->back();
    }

    public function requestlist(Request $request)
    {
        $users = User::where('active_status', 1)->get();
        return view('admin.requestlist', compact('users'));
    }

    public function index()
    {
        $year = Carbon::now()->year;
        $current_month = Carbon::Now()->toDateString();
        $subSix = Carbon::Now()->subMonth(6)->toDateString();
        $result = CarbonPeriod::create($subSix, '1 month',  $current_month);

        $aa = DB::select("SELECT Month(date) as Month,Count(user_id) as member_count From member_histories
                    Group By Month(date)");

        $mon = [];
        $monNum = [];
        foreach ($result as $dt) {
            $mm = $dt->format("F");
            $monthNumber = $dt->format("m");

            array_push($mon,$mm);
            array_push($monNum,$monthNumber);
            //dd($mon);
        }

        $member_plan_filter = MemberHistory::select('date')->whereYear('date', '=', $year)->get()->groupBy(function ($members) {
            return Carbon::parse($members->date)->format('F');
        });
        $months_filter = [];
        $monthCount_filter = [];
        foreach ($member_plan_filter as $month => $values) {
            $months_filter[] = $month;
            $monthCount_filter[] = count($values);
        }

        $free_user = DB::table('users')->where('member_type', 'Free')->count();
        $platinum_user = DB::table('users')->where('member_type', 'Platinum')->count();
        $gold_user = DB::table('users')->where('member_type', 'Gold')->count();
        $diamond_user = DB::table('users')->where('member_type', 'Diamond')->count();
        $ruby_user = DB::table('users')->where('member_type', 'Ruby')->count();
        $rubyp_user = DB::table('users')->where('member_type', 'Ruby Premium')->count();
        if (Auth::check()) {
            if (Auth::user()->hasRole('System_Admin')) {
                $member_plans = Member::where('member_type', '!=', 'Gym Member')->get();
                return view('admin.home', compact('member_plans', 'free_user', 'platinum_user', 'gold_user', 'diamond_user', 'ruby_user', 'rubyp_user','mon','monNum','aa','months_filter','monthCount_filter'));
            } elseif (Auth::user()->hasRole('King')) {
                $member_plans = Member::where('member_type', '!=', 'Gym Member')->get();
                return view('admin.home', compact('member_plans','free_user', 'platinum_user', 'gold_user', 'diamond_user', 'ruby_user', 'rubyp_user','mon','monNum','aa','months_filter','monthCount_filter'));
            } elseif (Auth::user()->hasRole('Queen')) {
                $member_plans = Member::where('member_type', '!=', 'Gym Member')->get();
                return view('admin.home', compact('member_plans','free_user', 'platinum_user', 'gold_user', 'diamond_user', 'ruby_user', 'rubyp_user','mon','monNum','aa','months_filter','monthCount_filter'));
            } else {
                    $user = auth()->user();
                    $user_id = $user->id;
                    $id = auth()->user()->id;
                    $block_list = BlockList::where('sender_id',$id)->orWhere('receiver_id',$id)->get(['sender_id', 'receiver_id'])->toArray();
                    $b = array();
                    foreach ($block_list as $block) {
                        $f = (array)$block;
                        array_push($b, $f['sender_id'], $f['receiver_id']);
                    }            
                    $friends = DB::table('friendships')
                                    ->where('friend_status', 2)
                                    ->where(function ($query) use ($user_id) {
                                        $query->where('sender_id', $user_id)
                                            ->orWhere('receiver_id', $user_id);
                                    })
                                    ->whereNotIn('sender_id',$b)
                                    ->orWhereNotIn('receiver_id',$b)
                                    ->get(['sender_id', 'receiver_id'])->toArray();
                                    //  dd($friends);
                if (!empty($friends)) {
                    $n = array();
                    foreach ($friends as $friend) {
                        $f = (array)$friend;
                        array_push($n, $f['sender_id'], $f['receiver_id']);
                    }
                   
                    // $posts = Post::
                    //     whereIn('user_id', $n)
                    //     ->where('report_status', 0)
                    //     ->where('shop_status',0)
                    //     ->orderBy('created_at', 'DESC')
                    //     ->with('user')
                    //     ->paginate(30);
                    $posts = Post::select('users.name', 'profiles.profile_image', 'posts.*')
                    ->whereIn('posts.user_id', $n)
                    ->whereNotIn('posts.user_id', $b)
                    ->where('posts.shop_status',0)
                    ->where('report_status','!=' ,1)
                    ->leftJoin('users', 'users.id', 'posts.user_id')
                    ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
                    ->orderBy('posts.created_at', 'DESC')
                    ->paginate(30);
                    $roles = DB::select("SELECT roles.name,model_has_roles.model_id FROM model_has_roles 
                    left join roles on model_has_roles.role_id = roles.id");
                                  $array = \array_filter($b, static function ($element) {
                                    $user_id = auth()->user()->id;
                                    return $element !== $user_id;
                                    //                   ↑
                                    // Array value which you want to delete
                                });
            
                                $total_count =  DB::table('comments')
                                    ->select('post_id', DB::raw('count(*) as total'))
                                    ->where('report_status',0)
                                    ->where('deleted_at',null)
                                    ->whereNotIn('user_id',$array)
                                    ->groupBy('post_id')
                                    ->get();
                                foreach ($posts as $key=>$post){
                                    $posts[$key]['roles'] = null;
                                    $posts[$key]['total_comments'] = 0;
                                    foreach($roles as $r){
                                        if($r->model_id == $post->user_id){
                                            $posts[$key]['roles'] = $r->name;
                                            break;
                                        }
                                        else{
                                            $posts[$key]['roles'] = null;
                                        }
                                        foreach($total_count as $k=>$v){
                                            if($v->post_id == $post->id){
                                                $posts[$key]['total_comments'] = $v->total;
                                                break;
                                            }
                                            else{
                                                $posts[$key]['total_comments'] = 0;
                                            }
                                        }
                                       
                                }
                }
                } else {
                    $id = auth()->user()->id;
                    $block_list = BlockList::where('sender_id',$id)->orWhere('receiver_id',$id)->get(['sender_id', 'receiver_id'])->toArray();
                    $b = array();
                    foreach ($block_list as $block) {
                        $f = (array)$block;
                        array_push($b, $f['sender_id'], $f['receiver_id']);
                    }
                    $n = array();
                    // $posts = Post::where('user_id', $user->id)
                    //     ->where('report_status', 0)
                    //     ->where('shop_status',0)
                    //     ->orderBy('created_at', 'DESC')
                    //     ->with('user')
                    //     ->paginate(30);
                    $posts = Post::select('users.name', 'profiles.profile_image', 'posts.*')
                    ->where('posts.shop_status',0)
                    ->where('report_status','!=' ,1)
                    ->leftJoin('users', 'users.id', 'posts.user_id')
                    ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
                    ->orderBy('posts.created_at', 'DESC')
                    ->paginate(30);
                       
                    $roles = DB::select("SELECT roles.name,model_has_roles.model_id FROM model_has_roles 
                    left join roles on model_has_roles.role_id = roles.id");

                    $array = \array_filter($b, static function ($element) {
                        $user_id = auth()->user()->id;
                        return $element !== $user_id;
                        //                   ↑
                        // Array value which you want to delete
                    });

                    $total_count =  DB::table('comments')
                        ->select('post_id', DB::raw('count(*) as total'))
                        ->where('report_status',0)
                        ->where('deleted_at',null)
                        ->whereNotIn('user_id',$array)
                        ->groupBy('post_id')
                        ->get();
                    foreach ($posts as $key=>$post){
                        $posts[$key]['roles'] = null;
                        $posts[$key]['total_comments'] = 0;
                        foreach($roles as $r){
                            if($r->model_id == $post->user_id){
                                $posts[$key]['roles'] = $r->name;
                                break;
                            }
                            else{
                                $posts[$key]['roles'] = null;
                            }
                            foreach($total_count as $k=>$v){
                                if($v->post_id == $post->id){
                                    $posts[$key]['total_comments'] = $v->total;
                                    break;
                                }
                                else{
                                    $posts[$key]['total_comments'] = 0;
                                }
                            }
                           
                    }
                }
                }
                //  dd($posts);
                        $member_plans = Member::where('member_type', '!=', 'Free')->where('member_type', '!=', 'Gym Member')->get();
                        return view('customer.socialmedia', compact('member_plans','posts'));
            }
        } else {
            // not logged-in
            $members = Member::orderBy('price', 'ASC')->where('duration', 1)->get();
            $durations = Member::groupBy('duration')->where('duration', '!=', 0)->get();
            $pros = DB::table('members')->select('pros')->get()->toArray();
            $cons = DB::table('members')->select('cons')->get()->toArray();
            return view('customer.index', compact('members', 'durations', 'pros', 'cons'));
        }
    }




    public function memberUpgradedHistory(Request $request)
    {
        DB::unprepared(DB::raw("CREATE TEMPORARY TABLE from_member_id(user_id INT ,member_id INT,date date)")); //true

        DB::statement(DB::raw("INSERT INTO from_member_id(user_id,member_id,date) SELECT user_id, member_id ,date FROM member_histories WHERE member_id  = $request->from_member;"));
        //true
        DB::unprepared(DB::raw("
        CREATE TEMPORARY TABLE to_member_id(
            user_id INT ,
            member_id INT,
            date date)
        ")); //true
        $current_month = Carbon::Now()->toDateString();
        $subSix = Carbon::Now()->subMonth(6)->toDateString();

        DB::statement(DB::raw("INSERT INTO to_member_id(user_id,member_id,date)
        SELECT user_id, member_id ,date
        FROM member_histories
        WHERE member_id  = $request->to_member;"));

        $aa = DB::select("SELECT Month(a.date) as Month,Count(a.user_id) as member_count from to_member_id a , from_member_id b
                    WHERE a.user_id = b.user_id
                    and a.date > b.date
                    and a.date BETWEEN '$subSix' and '$current_month'
                    Group By Month(a.date)");

        $result = CarbonPeriod::create($subSix, '1 month',  $current_month);

        $mon = [];
        $monNum = [];
        foreach ($result as $dt) {
            $mm = $dt->format("F");
            $monthNumber = $dt->format("m");

            array_push($mon,$mm);
            array_push($monNum,$monthNumber);
            //dd($mon);
        }

        return response()->json([
            'mon'=>$mon,
            'monNum'=>$monNum,
            'aa'=>$aa,
        ]);

    }
    public function memberUpgradedHistory_monthly(Request $request){
        $year = Carbon::now()->year;

        $member_plan_filter = MemberHistory::select('date')->whereYear('date', '=', $year)->where('member_id',$request->member_type)->get()
        ->groupBy(function ($members){
                        return Carbon::parse($members->date)->format('F');
                    });
        $months_filter = [];
        $monthCount_filter = [];
        foreach($member_plan_filter as $month=>$values){
            $months_filter[]= $month;
            $monthCount_filter[]= count($values);
        }

        return response()->json([
            'member_plan_filter'=>$member_plan_filter,
            'months_filter'=>$months_filter,
            'monthCount_filter'=>$monthCount_filter
        ]);
    }

    public function home()
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
                $member_plans = Member::where('member_type', '!=', 'Free')->where('member_type', '!=', 'Gym Member')->get();
                return view('customer.socialmedia', compact('member_plans','posts'));

        // $member_plans = Member::where('member_type', '!=', 'Free')->where('duration', '=', 1)->get();
        // return view('customer.home', compact('member_plans'));

    }

    public function customerregister()
    {
        $user = User::find(1);
        $banking_info = BankingInfo::all();
        // $mem = $user->members()->get();
        $users = User::with('members')->orderBy('created_at', 'DESC')->get();

        $members = Member::orderBy('price', 'ASC')->get();

        $durations = Member::groupBy('duration')->where('duration', '!=', 0)->get();

        return view('customer.customer_registration', compact('durations', 'members', 'banking_info'));
    }

    public function customer_register()
    {
        // $user = User::find(1);
        // $banking_info = BankingInfo::all();
        // // $mem = $user->members()->get();
        // $users = User::with('members')->orderBy('created_at', 'DESC')->get();

        // $members = Member::orderBy('price', 'ASC')->get();

        // $durations = Member::groupBy('duration')->where('duration', '!=', 0)->get();
        // return view('customer.register', compact('durations', 'members', 'banking_info'));
        return view('customer.register');
    }

    public function getRegister()
    {
        $members = Member::orderBy('price', 'ASC')->get();

        $durations = Member::groupBy('duration')->where('duration', '!=', 0)->get();

        $data = [
            'members' => $members,
            'durations' => $durations
        ];

        return view('auth.register')->with($data);
    }
}
