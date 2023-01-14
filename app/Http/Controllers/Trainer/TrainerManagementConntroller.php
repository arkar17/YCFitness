<?php

namespace App\Http\Controllers\Trainer;


use App\Models\Meal;
use App\Models\User;
use App\Models\Member;

use App\Models\Message;


use App\Models\TrainingUser;

use Illuminate\Http\Request;
use App\Models\TrainingGroup;
use Illuminate\Support\Facades\DB;
use App\Events\TrainingMessageEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class TrainerManagementConntroller extends Controller
{
    //
    public function index()
    {
        $messages = Message::whereNotNull('text')->get();
        $members=Member::groupBy('member_type')
                        ->where('member_type','!=','Free')
                        ->where('member_type','!=','Platinum')
                        ->where('member_type','!=','Diamond')
                        ->where('member_type','!=','Gym Member')
                        ->get();
         $groups=TrainingGroup::where('trainer_id',auth()->user()->id)->get();
         $group=TrainingGroup::where('trainer_id',auth()->user()->id)->first();
         return view('trainer.index',compact('messages','members','groups'));
    }

    public function send(Request $request,$id)
    {

        if($request->text ==null && $request->fileInput == null ){

        }else{
            $path='';
            if($request->file('fileInput') !=null){
                $request->validate([
                    'fileInput' => 'required|mimes:png,jpg,jpeg,gif,mp4,mov,webm'
                    ],[
                        'fileInput.required' => 'You can send png,jpg,jpeg,gif,mp4,mov and webm extension'
                    ]);

                $file = $request->file('fileInput');
                $path =uniqid().'_'. $file->getClientOriginalName();
                $disk = Storage::disk('public');
                $disk->put(
                    'trainer_message_media/'.$path,file_get_contents($file)
                );

            }

            $message = new Message();
            $message->training_group_id = $id;
            $message->text = $request->text == null ?  null : $request->text;
            $message->media = $request->fileInput == null ? null : $path;
            $message->save();

            event(new TrainingMessageEvent($message,$path,$id));

        }
    }


    public function kick($id)
    {
        //dd($id);
        $member_kick=DB::table('training_users')
                        ->where('user_id',$id)
                        ->delete();

        $member_user=User::findOrFail($id);

        $member_user->ingroup=0;
        $member_user->update();

       // return response()->json(['message'=>'Kick Member Successfully']);
    //    return response()->json(['member'=>$member_user]);
       return redirect()->back()->with('success','Kick Member Successfully');

    }

    public function free()
    {
        return view('trainer.free_user');
    }

    public function view_member($id)
    {
        $group_members=DB::table('training_users')
                            ->select('users.name','users.id')
                            ->join('users','training_users.user_id','users.id')
                            ->where('training_users.training_group_id',$id)
                            ->where('users.ingroup',1)
                            ->get();

        $groups=TrainingGroup::where('trainer_id',auth()->user()->id)->get();
        $members=Member::groupBy('member_type')
                        ->where('member_type','!=','Free')
                        ->get();

        $group_id = $id;
        $selected_group = TrainingGroup::where('id',$group_id)->first();
        return response()
            ->json([
                'members' => $members,
                'groups'=>$groups,
                'group_members'=>$group_members,
                'selected_group'=>$selected_group
        ]);


        //return view('Trainer.view_member',compact('members','selected_group','group_members','groups'));
    }

    public function view_media($id)
    {
        $groups=TrainingGroup::where('trainer_id',auth()->user()->id)->get();
        $selected_group = TrainingGroup::where('id',$id)->first();
        $members=Member::groupBy('member_type')
        ->where('member_type','!=','Free')
        ->get();
        $message = Message::where('training_group_id',$id)->where('media','!=',null)->get();
        return response()
        ->json([
            'members' => $members,
            'groups'=>$groups,
            'messages'=>$message,
            'selected_group'=>$selected_group
    ]);
        //  return view('Trainer.view_media',compact('members','selected_group','message','groups'));
    }

    public function addMember(Request $request)
    {
        $id = $request->id;
        $group_id = $request->group_id;
        $member = User::findOrFail($id);
        $member->ingroup = 1;
        $member->update();
        $member->tainer_groups()->attach($group_id);
        return response()
            ->json([
                'status'=>200,
        ]);
        //return redirect()->back()->with('popup', 'open');
    }


    public function showMember(Request $request)
    {
        $group_id =  $request->id;

        $group = TrainingGroup::where('id',$group_id)->first();

        if($group->group_type === 'weight loss'){
            $members = User::where('ingroup' , '!=',1)
            ->where('active_status',2)
            ->where('member_type',$group->member_type)
            ->where('membertype_level',$group->member_type_level)
            ->where('gender',$group->gender)
            ->where('bmi','>=',25)
            ->get();
           }

           if($group->group_type === 'weight gain'){
            $members = User::where('ingroup' , '!=',1)
            ->where('active_status',2)
            ->where('member_type',$group->member_type)
            ->where('membertype_level',$group->member_type_level)
            ->where('gender',$group->gender)
            ->where('bmi','<=',18.5)
            ->get();
           }

           if($group->group_type === 'body beauty'){
           $members = User::where('ingroup' , '!=',1)
           ->where('active_status',2)
            ->where('member_type',$group->member_type)
            ->where('membertype_level',$group->member_type_level)
            ->where('gender',$group->gender)
            ->whereBetween('bmi', [18.5, 24.9])
            ->get();

           }
          // dd($members);
        //    return response()->json([
        //     'members' => $members
        //    ]);
        //dd($request->keyword);
        if($request->keyword != ''){
            if($group->group_type === 'weight loss'){
                            $members = User::where('ingroup' , '!=',1)
                                    ->where('name','LIKE','%'.$request->keyword.'%')
                                    ->where('active_status',2)
                                    ->where('member_type',$group->member_type)
                                    ->where('membertype_level',$group->member_type_level)
                                    ->where('gender',$group->gender)
                                    ->where('bmi','>=',25)
                                    ->get();
               }

               if($group->group_type === 'weight gain'){

                            $members = User::where('ingroup' , '!=',1)
                                    ->where('name','LIKE','%'.$request->keyword.'%')
                                    ->where('active_status',2)
                                    ->where('member_type',$group->member_type)
                                    ->where('membertype_level',$group->member_type_level)
                                    ->where('gender',$group->gender)
                                    ->where('bmi','<=',18.5)
                                    ->get();
               }

               if($group->group_type === 'body beauty'){
                $members = User::where('ingroup' , '!=',1)
                                ->where('name','LIKE','%'.$request->keyword.'%')
                                ->where('active_status',2)
                                ->where('member_type',$group->member_type)
                                ->where('membertype_level',$group->member_type_level)
                                ->where('gender',$group->gender)
                                ->whereBetween('bmi', [18.5, 24.9])
                                ->get();

               }

            //$members = User::where('name','LIKE','%'.$request->keyword.'%')->get();
        }
        //dd($members);
        return response()->json([
           'members' => $members
        ]);
    }
    public function destroy(Request $request)
    {
        // dd($request);
        $group_users = TrainingUser::where('training_group_id',$request->group_id)->get();
        foreach($group_users as $gu){
            User::where('id',$gu->user_id)->update(["ingroup" => 0]);
        }
        $group_user_delete = TrainingUser::where('training_group_id',$request->group_id);
        $group_user_delete->delete();
        $group_delete = TrainingGroup::where('id',$request->group_id);
        $group_delete->delete();
        $group_message_delete = Message::where('training_group_id',$request->group_id);
        $group_message_delete->delete();
        Alert::success('Success', 'Group Deleted!');
        return redirect('trainer');
    }
    public function platinum()
    {
        return view('trainer.platinum_user');
    }
    public function diamond()
    {
        return view('trainer.diamond_user');
    }
    public function gold()
    {
        return view('trainer.gold_user');
    }
    public function ruby()
    {
        return view('trainer.ruby_user');
    }
    public function ruby_premium()
    {
        return view('trainer.ruby_premium_user');
    }
    public function gym_member()
    {
        return view('trainer.gym_member_user');
    }
}
