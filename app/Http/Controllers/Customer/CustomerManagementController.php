<?php

namespace App\Http\Controllers\Customer;

use App\Models\Message;
use App\Models\TrainingUser;
use Illuminate\Http\Request;
use App\Models\TrainingGroup;
use App\Events\TrainingMessageEvent;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerManagementController extends Controller
{
    public function index(){
        return view('customer.groupchat.index');
    }

    public function view_media(){
        $id = auth()->user()->id;
        // dd($id);
        $group = TrainingUser::where('user_id',$id)->first();
        $photo_video = Message::where('training_group_id',$group->training_group_id)->where('media','!=',null)->get();
        return view('customer.groupchat.media',compact('photo_video','group'));
    }

    public function showchat(){
        $id = auth()->user()->id;
       // dd($id);
        $group = TrainingUser::where('user_id',$id)->first();
        
        if($group){
            $chats = Message::where('training_group_id',$group->training_group_id)->get();
            $medias = Message::where('training_group_id',$group->training_group_id)->where('media','!=',null)->get();
            $group_members = TrainingUser::where('training_group_id',$group->training_group_id)->get();
            return view('customer.groupchat.index', compact('chats','group','group_members','medias'));
        }
        else{
            Alert::warning('Warning', 'No Group Yet');
            return redirect()->back();
        }

    }
    public function showgroup(){
        $id = auth()->user()->id;
        $group = TrainingUser::where('user_id',$id)->first();
        if($group){
            return view('customer.training_center.groups',compact('group'));
        }
        else{
            Alert::warning('Warning', 'No Group Yet');
            return redirect()->back();
        }

    }
}
