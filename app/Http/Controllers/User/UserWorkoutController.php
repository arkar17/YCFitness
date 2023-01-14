<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Member;
use App\Models\Workout;
use Illuminate\Http\Request;
use App\Models\TrainingCenter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserWorkoutController extends Controller
{
    
    public function video(){


         return view('admin.workout.video');
    }

    public function getstart(){

        $user = Auth::user();
        // $platinum ='Platinum';
        // $diamond = 'Diamond';
        // $level='Advance';

        //dd($checkMemberlevel->toArray());
        // dd($checkMembertype->toArray());
        //$checkGender = Workout::select('gender_type')->where('gender_type')

       // old
        // $checkuser = Member::where('members.user_id',$user->id)->where('workouts.workout_level','Advance')->where('workouts.gender_type',$user->gender)
        // ->join('workout_plans','workout_plans.id','members.workout_plan_id')
        // ->join('workouts','workouts.workout_plan_id','workout_plans.id')
        // ->get();

        //new
        // $checkuser = TrainingCenter::where('users.id',$user->id)->join('users','users.id','training_centers.user_id')->join('workout_plans','workout_plans.id','training_centers.workout_plan_id')->join('workouts','workouts.workout_plan_id','workout_plans.id')
        //             ->where('workouts.gender_type',$user->gender)->get();
        // dd($checkuser->toArray());

        $checkMembertype = User::select('member_type')->where('member_type',$user->member_type)->first();
        $checkMemberlevel = Workout::select('workout_level')->where('workout_level',$user->member_type_level)->first();
        $checkgender = Workout::select('gender_type')->where('workout_level',$user->member_type_level)->get();
//         $checkuser = TrainingCenter::where('users.id',$user->id)->join('users','users.id','training_centers.user_id')
//         ->join('workout_plans','workout_plans.id','training_centers.workout_plan_id')
//         ->join('workouts','workouts.workout_plan_id','workout_plans.id')
//         ->get();
//dd($checkgender->toArray());

        if($checkMembertype->member_type == 'Platinum' || $checkMembertype->member_type == 'Diamond'){
            if($checkMemberlevel == null){
                $checkuser = "dsajfjsdjkf";
                return view('user.workout.workoutstart')->with(['error','checkuser'=>null]);
            }
            else if($checkMemberlevel->workout_level == 'Beginner'){
                        $checkuser = TrainingCenter::where('users.id',$user->id)->join('users','users.id','training_centers.user_id')
                                ->join('workout_plans','workout_plans.id','training_centers.workout_plan_id')
                                ->join('workouts','workouts.workout_plan_id','workout_plans.id')
                                ->where('workouts.gender_type',$user->gender)
                                ->get();
                            return view('user.workout.workoutstart',compact('checkuser'));
            }
            else if($checkMemberlevel->workout_level == 'Advance'){
                        $checkuser = TrainingCenter::where('users.id',$user->id)->join('users','users.id','training_centers.user_id')
                        ->join('workout_plans','workout_plans.id','training_centers.workout_plan_id')
                        ->join('workouts','workouts.workout_plan_id','workout_plans.id')
                        ->where('workouts.gender_type',$user->gender)
                        ->get();
                        return view('user.workout.workoutstart',compact('checkuser'));

                // $videogroup = Member::where('members.user_id',$user->id)->where('workouts.workout_level','Beginner')->where('workouts.gender_type',$user->gender)
                // ->join('workout_plans','workout_plans.id','members.workout_plan_id')
                // ->join('workouts','workouts.workout_plan_id','workout_plans.id')
                // ->get();

                // $grouped = $videogroup->mapToGroups(function ($item, $key) {
                //     return [$item['workout_plan_id'] => $item['video']];
                // });
                // $videogrouped=$grouped->toArray();
                //dd($videogrouped);

            }
            else if($checkMemberlevel->workout_level == 'Professional'){
                        $checkuser = TrainingCenter::where('users.id',$user->id)->join('users','users.id','training_centers.user_id')
                                ->join('workout_plans','workout_plans.id','training_centers.workout_plan_id')
                                ->join('workouts','workouts.workout_plan_id','workout_plans.id')
                                ->where('workouts.gender_type',$user->gender)
                                ->get();
                        return view('user.workout.workoutstart',compact('checkuser'));
            }

        }
        else{
            return view('user.workout.workoutstart')->with(['error','checkuser'=>null]);
        }

    }
}
