<?php

namespace App\Http\Controllers\Admin;

use App\Models\Member;
use App\Models\Workout;
use App\Models\WorkoutPlan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\WorkoutRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\WorkoutplanRequest;


class WorkoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        return view('admin.workout.workout');
    }

    public function createworkoutplan(WorkoutplanRequest $request){

        $data = new WorkoutPlan();
        $data->plan_type = $request->plantype;

        $data->save();
        return back();
    }

    public function editworkoutplan($id){
        $workoutplanEdit = WorkoutPlan::where('id',$id)->first();
        return view('admin.workout.workoutplanedit', compact('workoutplanEdit'));
    }

    public function updateworkoutplan($id, Request $request){
        $data =  WorkoutPlan::findOrFail($id);
        $data->plan_type = $request->plantype;
        $data->update();
        return redirect()->route('workoutplane');
    }

    public function deleteworkoutplan($id){
        $data =  WorkoutPlan::findOrFail($id);
        $data->delete();
        return back();
    }

    public function workoutindex(){
        // $workoutplanId = $request->id;
        $member = Member::groupBy('member_type')->orWhere('member_type','Platinum')->orWhere('member_type','Diamond')->orWhere('member_type','Gym Member')->get();
        //dd($workoutplanId);
        return view('admin.workout.workoutcreate', compact('member'));
    }

    public function createworkout(WorkoutRequest $request){

        if($request->hasFile('video')) {
            $video = $request->file('video');
            $video_name =uniqid().'_'. $video->getClientOriginalName();
            Storage::disk('local')->put(
                'public/upload/'.$video_name,
                file_get_contents($video)
            );
        }

        if($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name =uniqid().'_'. $image->getClientOriginalName();
            Storage::disk('local')->put(
                'public/upload/'.$image_name,
                file_get_contents($image)
            );
        }

        if($request->gendertype == 'both'){
            $data = new Workout();
        $data->workout_plan_type = $request->plantype;
        $data->member_type = $request->memberType;
        $data->workout_name = $request->workoutname;
        $data->gender_type = 'male';
        $data->workout_level = $request->workoutlevel;
        $data->calories = $request->calories;
        $data->time = $request->videoTime;
        $data->workout_periods =0;
        $data->day = $request->workoutday;
        $data->place = $request->workoutplace;
        $data->image = $image_name;
        $data->video=$video_name;
        $data->estimate_time = $request->estimateTime;
        $data->sets = $request->sets;
        $data->category = $request->category;
        $data->save();

        $data = new Workout();
        $data->workout_plan_type = $request->plantype;
        $data->member_type = $request->memberType;
        $data->workout_name = $request->workoutname;
        $data->gender_type = 'female';
        $data->workout_level = $request->workoutlevel;
        $data->calories = $request->calories;
        $data->time = $request->videoTime;
        $data->workout_periods =0;
        $data->day = $request->workoutday;
        $data->place = $request->workoutplace;
        $data->image = $image_name;
        $data->video=$video_name;
        $data->estimate_time = $request->estimateTime;
        $data->sets = $request->sets;
        $data->category = $request->category;
        $data->save();
        return redirect('admin/workout');
        }
        else{
            $data = new Workout();
        $data->workout_plan_type = $request->plantype;
        $data->member_type = $request->memberType;
        $data->workout_name = $request->workoutname;
        $data->gender_type = $request->gendertype;
        $data->workout_level = $request->workoutlevel;
        $data->calories = $request->calories;
        $data->time = $request->videoTime;
        $data->workout_periods =0;
        $data->day = $request->workoutday;
        $data->place = $request->workoutplace;
        $data->image = $image_name;
        $data->video=$video_name;
        $data->estimate_time = $request->estimateTime;
        $data->sets = $request->sets;       
        $data->category = $request->category;
        $data->save();
        return redirect('admin/workout');
        }


    }

    public function workoutview(){
        $workoutview = Workout::all();
        return view('admin.workout.workout')->with(['workoutview'=>$workoutview]);
    }

    public function workoutdelete($id){
        $data = Workout::findOrFail($id);
        $image_name = $data['image'];
        $video_name =$data['video'];

        $data->delete();

        if(File::exists(storage_path()."/app/public/upload/".$image_name)){
            File::delete(storage_path()."/app/public/upload/".$image_name);
        }
        if(File::exists(storage_path()."/app/public/upload/".$video_name)){
            File::delete(storage_path()."/app/public/upload/".$video_name);
        }
        return back()->with(['success'=>'Workout delete success.']);
    }

    public function workoutedit($id){
        $data = Workout::where('id',$id)->first();
        $member = Member::groupBy('member_type')->orWhere('member_type','Platinum')->orWhere('member_type','Diamond')->orWhere('member_type','Gym Member')->get();
        //dd($data->toArray());
        return view('admin.workout.workoutedit')->with(['data'=>$data, 'member'=>$member]);
    }

    public function workoutupdate($id,Request $request){

        $check = Workout::findOrFail($id);

        if($request->hasFile('video')) {
            $video = $request->file('video');
            $video_name =uniqid().'_'. $video->getClientOriginalName();
            Storage::disk('local')->put(
                'public/upload/'.$video_name,
                file_get_contents($video)
            );
        }else{
            $video_name = $check->video;
        }
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name =uniqid().'_'. $image->getClientOriginalName();
            Storage::disk('local')->put(
                'public/upload/'.$image_name,
                file_get_contents($image)
            );
        }else{
            $image_name = $check->image;
        }

        $check->workout_plan_type = $request->plantype ?? $check->workout_plan_type;
        $check->member_type = $request->memberType ?? $check->member_type;
        $check->workout_name = $request->workoutname ?? $check->workout_name;
        $check->gender_type = $request->gendertype ?? $check->gender_type;
        $check->workout_level = $request->workoutlevel ?? $check->workout_level;
        $check->time = $request->videoTime ?? $check->time;
        $check->calories = $request->calories ?? $check->calories;
        $check->day = $request->workoutday ?? $check->day;
        $check->place = $request->workoutplace ?? $check->place;
        $check->image = $image_name;
        $check->video = $video_name;
        $check->estimate_time = $request->estimateTime ?? $check->estimate_time;
        $check->sets = $request->sets ?? $check->sets;
        $check->category = $request->category ?? $check->category;
        $check->update();
        return redirect('admin/workout');
    }

    public function getVideo(){
        $video = Workout::select('video')->get();
        //dd(count($video));
        //$videoView = Storage::disk('local')->get("{$video}");
        // $response = Response::make($videoView,200);
        // $response->header('Content-Type','video/mp4');
        // $response->header('Accept-Ranges','bytes');
        // for ($i=1; $i<=count($video); $i++) {
            foreach($video as $vd){
                return response()->file(storage_path()."/app/public/upload/".$vd->video);
            }
        // }
    }
}
