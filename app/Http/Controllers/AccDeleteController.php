<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Post;
use App\Models\User;
use App\Models\Report;
use App\Models\Comment;
use App\Models\Payment;
use App\Models\Profile;
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
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AccDeleteController extends Controller
{
    //
    public function acc_delete(){
        return view('customer.account_delete');
    }
    public function acc_del(Request $request){
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
            Auth::logout();
            Alert::success('Success', 'Account Deleted Successfully!');
            return redirect('/');
}
}