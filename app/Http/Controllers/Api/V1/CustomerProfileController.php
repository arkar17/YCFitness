<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\Chat;
use App\Models\Post;
use App\Models\User;
use App\Models\Report;
use App\Models\Comment;
use App\Models\Payment;
use App\Models\Profile;
use App\Models\Feedback;
use App\Models\ChatGroup;
use App\Models\Friendship;
use App\Models\ShopRating;
use App\Models\Notification;
use App\Models\TrainingUser;
use App\Models\WaterTracked;
use Illuminate\Http\Request;
use App\Models\MemberHistory;
use App\Models\TrainingGroup;
use App\Models\UserReactPost;
use App\Models\UserSavedPost;
use App\Models\WeightHistory;
use App\Models\TrainingCenter;
use App\Models\ChatGroupMember;
use App\Models\PersonalMealInfo;
use Illuminate\Support\Facades\DB;
use App\Models\PersonalWorkOutInfo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

class CustomerProfileController extends Controller
{

    public function userOnlineStatus(Request $request)
    {
        $users = User::select('id','name','last_seen as last')->where('id',$request->id)->first();
       
            // if (Cache::has('user-is-online-' . $users->id)){
                      
            //     $users['online'] = "online";
            //     $users['last_seen'] = null;
            // }
            // else{
            //     if($users->last){
            //         $last_seen = Carbon::parse($users->last)->diffForHumans();
            //     }
            //     else{
            //         $last_seen = null;
            //     }
            //     $users['online'] = "offline";
            //     $users['last_seen'] = $last_seen;
            // }
                
        
            // if (auth()->check()) {
            //     $user = auth()->user();
                
                if ($users->online) {
                    // User is online
                    $users['online'] = "online";
                } else {
                    // User is offline
                    $users['online'] = "offline";
                }
            // } else {
            //     // User is not authenticated
            // }
         return response()->json([
            'data' => $users,
        ]);
    }

    public function customerProfile()
    {
        $auth_user = auth()->user();

        $user = User::findOrFail($auth_user->id);

        // $weight_history = WeightHistory::where('user_id', $user->id)->latest()->first();

        return response()->json([
            'message' => 'success',
            'user' => $user,
        ]);
    }

    public function feedback_send(Request $request ){
        // dd($request->description);
        $user_id = Auth::user()->id;
        $feedback_store = New Feedback();
        $feedback_store->user_id = $user_id;
        $feedback_store->description = $request->description;
        $feedback_store->save();
        
        return response()->json([
            'message' => 'success',
        ]);
    }

    public function customerNameUpdate(Request $request)
    {
        $auth_user = auth()->user();
        $user = User::findOrFail($auth_user->id);

        $user->name = $request->name;
        $user->update();

        return response()->json([
            'message' => 'success',
            'user' => $user
        ]);
    }

    public function customerProfileUpdate(Request $request)
    {
        $auth_user = auth()->user();
        $current_date = Carbon::now('Asia/Yangon')->toDateString();

        $user = User::find($auth_user->id);

        $weight_history = new WeightHistory();
        $weight_history->user_id = $auth_user->id;
        $weight_history->weight = $request->weight;
        $weight_history->date = $current_date;

        $user->update($request->all());
        $weight_history->save();
        return response()->json([
            'message' => 'success',
            'user' => $user
        ]);
    }

    // water track
    public function customerWaterTrackForToday()
    {
        $auth_user = auth()->user();
        $current_date = Carbon::now('Asia/Yangon')->toDateString();
        $water = WaterTracked::where('user_id', $auth_user->id)->where('date', $current_date)->first();
        return response()->json([
            'water' => $water
        ]);
    }

    public function customerWaterTrackForLast7Days()
    {
        $auth_user = auth()->user();
        $water_levels = [];
        for ($i = 1; $i < 8; $i++) {
            $current_date = Carbon::now('Asia/Yangon')->subDays($i)->toDateString();
            $water = WaterTracked::where('user_id', $auth_user->id)->where('date', $current_date)->first();
            if ($water != null) {
                array_push($water_levels, $water);
            }
        }
        return response()->json($water_levels);
    }

    public function customerRequestWaterLevel($date)
    {
        $auth_user = auth()->user();
        $water = WaterTracked::where('user_id', $auth_user->id)->where('date', $date)->first();

        return response()->json([
            'water' => $water
        ]);
    }


    // meal

    public function customerRequestBreakfastMealTrack($date)
    {
        $auth_user = auth()->user();
        $data = PersonalMealInfo::where('client_id', $auth_user->id)->where('date', $date)->with('meal')
            ->whereHas('meal', function (Builder $query) {
                $query->where('meal_plan_type', 'Breakfast');
            })
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function customerRequestLunchMealTrack($date)
    {
        $auth_user = auth()->user();
        $data = PersonalMealInfo::where('client_id', $auth_user->id)->where('date', $date)->with('meal')
            ->whereHas('meal', function (Builder $query) {
                $query->where('meal_plan_type', 'Lunch');
            })
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function customerRequestSnackMealTrack($date)
    {
        $auth_user = auth()->user();
        $data = PersonalMealInfo::where('client_id', $auth_user->id)->where('date', $date)->with('meal')
            ->whereHas('meal', function (Builder $query) {
                $query->where('meal_plan_type', 'Snack');
            })
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function customerRequestDinnerMealTrack($date)
    {
        $auth_user = auth()->user();
        $data = PersonalMealInfo::where('client_id', $auth_user->id)->where('date', $date)->with('meal')
            ->whereHas('meal', function (Builder $query) {
                $query->where('meal_plan_type', 'Dinner');
            })
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }


    public function customerMealTrackForTodayBreakfast()
    {
        $auth_user = auth()->user();
        $current_date = Carbon::now('Asia/Yangon')->toDateString();
        $data = PersonalMealInfo::where('client_id', $auth_user->id)->where('date', $current_date)->with('meal')
            ->whereHas('meal', function (Builder $query) {
                $query->where('meal_plan_type', 'Breakfast');
            })
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function customerMealTrackForTodayLunch()
    {
        $auth_user = auth()->user();
        $current_date = Carbon::now('Asia/Yangon')->toDateString();
        $data = PersonalMealInfo::where('client_id', $auth_user->id)->where('date', $current_date)->with('meal')
            ->whereHas('meal', function (Builder $query) {
                $query->where('meal_plan_type', 'Lunch');
            })
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function customerMealTrackForTodaySnack()
    {
        $auth_user = auth()->user();
        $current_date = Carbon::now('Asia/Yangon')->toDateString();
        $data = PersonalMealInfo::where('client_id', $auth_user->id)->where('date', $current_date)->with('meal')
            ->whereHas('meal', function (Builder $query) {
                $query->where('meal_plan_type', 'Snack');
            })
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function customerMealTrackForTodayDinner()
    {
        $auth_user = auth()->user();
        $current_date = Carbon::now('Asia/Yangon')->toDateString();
        $data = PersonalMealInfo::where('client_id', $auth_user->id)->where('date', $current_date)->with('meal')
            ->whereHas('meal', function (Builder $query) {
                $query->where('meal_plan_type', 'Dinner');
            })
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function customerMealTrackForLast7DaysBreakfast()
    {
        $user = auth()->user();
        $meals = [];

        for ($i = 1; $i < 8; $i++) {
            $current_date = Carbon::now('Asia/Yangon')->subDays($i)->toDateString();

            $meal = PersonalMealInfo::where('client_id', $user->id)->where('date', $current_date)->with('meal')
                ->whereHas('meal', function (Builder $query) {
                    $query->where('meal_plan_type', 'Breakfast');
                })
                ->get();

            if (!$meal->isEmpty()) {
                array_push($meals, $meal);
            }
        }
        return response()->json([
            'meals' => $meals
        ]);
    }

    public function customerMealTrackForLast7DaysLunch()
    {
        $user = auth()->user();
        $meals = [];

        for ($i = 1; $i < 8; $i++) {
            $current_date = Carbon::now('Asia/Yangon')->subDays($i)->toDateString();

            $meal = PersonalMealInfo::where('client_id', $user->id)->where('date', $current_date)->with('meal')
                ->whereHas('meal', function (Builder $query) {
                    $query->where('meal_plan_type', 'Lunch');
                })
                ->get();

            if (!$meal->isEmpty()) {
                array_push($meals, $meal);
            }
        }
        return response()->json([
            'meals' => $meals
        ]);
    }

    public function customerMealTrackForLast7DaysSnack()
    {
        $user = auth()->user();
        $meals = [];

        for ($i = 1; $i < 8; $i++) {
            $current_date = Carbon::now('Asia/Yangon')->subDays($i)->toDateString();
            $meal = PersonalMealInfo::where('client_id', $user->id)->where('date', $current_date)->with('meal')
                ->whereHas('meal', function (Builder $query) {
                    $query->where('meal_plan_type', 'Snack');
                })
                ->get();

            if (!$meal->isEmpty()) {
                array_push($meals, $meal);
            }
        }
        return response()->json([
            'meals' => $meals
        ]);
    }

    public function customerMealTrackForLast7DaysDinner()
    {
        $user = auth()->user();
        $meals = [];

        for ($i = 1; $i < 8; $i++) {
            $current_date = Carbon::now('Asia/Yangon')->subDays($i)->toDateString();

            $meal = PersonalMealInfo::where('client_id', $user->id)->where('date', $current_date)->with('meal')
                ->whereHas('meal', function (Builder $query) {
                    $query->where('meal_plan_type', 'Dinner');
                })
                ->get();

            if (!$meal->isEmpty()) {
                array_push($meals, $meal);
            }
        }
        return response()->json([
            'meals' => $meals
        ]);
    }


    // workout
    public function customerTodayWorkout()
    {
        $auth_user = auth()->user();
        $current_date = Carbon::now('Asia/Yangon')->toDateString();
        $workouts = PersonalWorkOutInfo::where('user_id', $auth_user->id)
            ->where('date', $current_date)->with('workout')
            ->get();

        return response()->json([
            'workouts' => $workouts
        ]);
    }

    public function customerLast7daysWorkout()
    {
        // $auth_user = auth()->user();
        // $workouts = [];

        // for ($i = 1; $i < 8; $i++) {
        //     $current_date = Carbon::now('Asia/Yangon')->subDays($i)->toDateString();

        //     $workout = PersonalWorkOutInfo::where('user_id', $auth_user->id)->where('date', $current_date)
        //         ->with('workout')->get();

        //     if (!$workout->isEmpty()) {
        //         array_push($workouts, $workout);
        //     }
        // }
        // return response()->json([
        //     'workouts' => $workout
        // ]);

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

    public function customerBetweenDaysWrokout($start_date, $end_date)
    {
        //return $start_date;
        $auth_user = auth()->user();
        $workouts = PersonalWorkOutInfo::where('user_id', $auth_user->id)->whereDate('date', '>=', $start_date)
            ->whereDate('date', '<=', $end_date)
            ->with('workout')->get();
        return response()->json([
            'workouts' => $workouts
        ]);
    }

    public function weightHistory($current_year)
    {
        $auth_user = auth()->user();

        $weight_histories = WeightHistory::where('user_id', $auth_user->id)->whereYear('date', $current_year)->get();

        return response()->json([
            'weight_histories' => $weight_histories
        ]);
    }


    public function customerWorkoutHistoryDates() {
        $auth_user = auth()->user();
        $workout_infos = PersonalWorkOutInfo::where('user_id', $auth_user->id)->get();

        return response()->json([
            'workout_infos' => $workout_infos
        ]);
    }

    public function acc_del(){
        $user_id = Auth::user()->id;
        Chat::where('from_user_id', $user_id)->orWhere('to_user_id',$user_id)->delete();
        ChatGroup::where('group_owner_id', $user_id)->delete();
        ChatGroupMember::where('member_id', $user_id)->delete();
        Friendship::where('sender_id', $user_id)->orWhere('receiver_id',$user_id)->delete();
        MemberHistory::where('user_id', $user_id)->delete();
        Notification::where('sender_id', $user_id)->orWhere('receiver_id',$user_id)->delete();
        Payment::where('user_id', $user_id)->delete();
        Post::where('user_id', $user_id)->delete();
        Profile::where('user_id', $user_id)->delete();
        Report::where('user_id', $user_id)->delete();
        ShopRating::where('user_id', $user_id)->delete();
        TrainingCenter::where('user_id', $user_id)->delete();
        TrainingGroup::where('trainer_id', $user_id)->delete();
        TrainingUser::where('user_id', $user_id)->delete();
        UserReactPost::where('user_id', $user_id)->delete();
        UserSavedPost::where('user_id', $user_id)->delete();
        WaterTracked::where('user_id', $user_id)->delete();
        WeightHistory::where('user_id', $user_id)->delete();
        Comment::where('user_id', $user_id)->delete();
        User::where('id', $user_id)->delete();

        return response()->json([
            'message' => 'Account Deleted Successully!'
        ]);
    }
}
