<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Member;
use App\Models\Message;
use App\Models\TrainingUser;
use Illuminate\Http\Request;
use App\Models\TrainingGroup;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class TrainingCenterController extends Controller
{
    public function index(){
        // $messages = Message::whereNotNull('text')->get();
        // $members=Member::groupBy('member_type')
        //                 ->where('member_type','!=','Free')
        //                 ->where('member_type','!=','Platinum')
        //                 ->where('member_type','!=','Diamond')
        //                 ->where('member_type','!=','Gym Member')
        //                 ->get();
        //  $groups=TrainingGroup::all();

        return view('admin.trainingcenter.index');
    }


    public function storeGroup(Request $request){
        $validated = $request->validate([
            'member_type' => 'required',
            'group_name' => 'required',
            'group_type'=>'required',
            'member_type_level'=>'required',
            'gender'=>'required'
        ]);
        $training_group=New TrainingGroup();
        $training_group->trainer_id=auth()->user()->id;
        $training_group->member_type=$request->member_type;
        $training_group->group_name=$request->group_name;
        $training_group->group_type=$request->group_type;
        $training_group->member_type_level=$request->member_type_level;
        $training_group->gender=$request->gender;

        $training_group->save();
        $groups=TrainingGroup::where('trainer_id',auth()->user()->id)->get();
        $members=Member::groupBy('member_type')
                ->where('member_type','!=','Free')
                ->get();
        Alert::success('Success', 'New Training Group is created successfully');

        return redirect()->route('trainer',compact('groups','members'))->with(
        'success','');
    }

    public function entergroup(Request $request){
        $group_chat=TrainingGroup::where('id',$request->id)->first();
        $chat_messages = Message::where('training_group_id', $request->id)->get();
        $members = TrainingUser::where('training_group_id',$request->id)->with('user')->get();
        return response()
            ->json([
                'group_chat' => $group_chat,
                'group_members' => $members,
                'chat_message' => $chat_messages
        ]);
    }

    public function chat_message($id){
        $group_data=TrainingGroup::where('id',$id)->first();
        $groups=TrainingGroup::where('trainer_id',auth()->user()->id)->get();
        $chat_messages = Message::where('training_group_id', $id)->get();
        return view('admin.trainingcenter.chat_message', compact('chat_messages','groups','group_data'));
    }

    public function chat_message_admin($id){
        $group_data=TrainingGroup::where('id',$id)->first();
        $groups=TrainingGroup::where('trainer_id',auth()->user()->id)->get();
        $chat_messages = Message::where('training_group_id', $id)->get();
        return view('admin.trainingcenter.chat_message', compact('chat_messages','groups','group_data'));
    }

    public function view_media($id){
        $groups=TrainingGroup::where('trainer_id',auth()->user()->id)->get();
        $group_data=TrainingGroup::where('id',$id)->first();
        $medias = Message::select('media')->where('training_group_id', $id)->where('media','!=',null)->get();
        return view('admin.trainingcenter.view_media', compact('medias','groups','group_data'));
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
        return view('admin.trainingcenter.view_member', compact('group_members','groups','members','selected_group','group_id'));

    }

    public function show_member(Request $request)
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

    public function add_member($id, $gp_id)
    {
        $id = $id;
        $group_id = $gp_id;
        $member = User::findOrFail($id);
        $member->ingroup = 1;
        $member->update();
        $member->tainer_groups()->attach($group_id);
        return response()
            ->json([
                'status'=>200,
        ]);

    }

    public function kick_member($id)
    {
        $member_kick=DB::table('training_users')
                        ->where('user_id',$id)
                        ->delete();

        $member_user=User::findOrFail($id);

        $member_user->ingroup=0;
        $member_user->update();
       return redirect()->back()->with('success','Kick Member Successfully');

    }

    public function delete_gp(Request $request){
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
        return redirect()->route('traininggroup.create')->with('success','Group Deleted!');
    }
}
