<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\Chat;
use App\Models\Post;
use App\Models\User;
use App\Models\Report;
use App\Models\Comment;
use App\Models\Profile;
use App\Events\Chatting;
use App\Models\BlockList;
use App\Models\ChatGroup;
use App\Models\Friendship;
use App\Models\Notification;
use App\Events\GroupChatting;
use App\Events\MakeAgoraCall;
use App\Events\MessageDelete;
use App\Models\UserReactPost;
use App\Models\UserSavedPost;
use App\Events\GroupAudioCall;
use App\Events\GroupVideoCall;
use App\Events\DeclineCallUser;
use App\Models\ChatGroupMember;
use App\Models\ChatGroupMessage;
use App\Events\MakeAgoraAudioCall;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Class\AgoraDynamicKey\RtcTokenBuilder;

class ChattingController extends Controller
{
    //
    public function chatting(Request $request, User $user)
    {
        // dd(auth()->user()->id);
        if ($request->text == null && $request->fileSend == null) {
        } else {
            $message = new Chat();
            $sendFile = $request->all();
            if ($request->totalFiles != 0) {
                $files = $sendFile['fileSend'];
                if ($sendFile['fileSend']) {
                    foreach ($files as $file) {
                        $extension = $file->extension();
                        $name = rand() . "." . $extension;
                        $file->storeAs('/public/customer_message_media/', $name);
                        $imgData[] = $name;
                        $message->media = json_encode($imgData);
                        $message->text = null;
                    }
                }
            } else {
                $message->text = $request->text;
                $message->media = null;
            }

            $message->from_user_id = auth()->user()->id;
            $message->to_user_id = $user->id;
            $message->save();

            broadcast(new Chatting($message, $request->sender));
        }
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options = array(
                'cluster' => 'eu',
                'encrypted' => true
            )
        );
        $to_user_id = $user->id;
        $user_id = auth()->user()->id;
        $id_admin = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'admin');
        })->first();
        $admin_id = $id_admin->id;
        $messages = DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date
        from
            chats
          join
            (select user, max(created_at) m
                from
                   (
                     (select id, to_user_id user, created_at
                       from chats
                       where from_user_id= $user_id  and delete_status <> 2 and deleted_by != $user_id )
                   union
                     (select id, from_user_id user, created_at
                       from chats
                       where to_user_id= $user_id  and delete_status <> 2 and deleted_by != $user_id)
                    ) t1
               group by user) t2
                on ((from_user_id= $user_id and to_user_id=user) or
                    (from_user_id=user and to_user_id= $user_id)) and
                    (created_at = m)
                left join users on users.id = user
                left join profiles on users.profile_id = profiles.id
                where deleted_by !=  $user_id  and delete_status != 2
                and users.id != $admin_id
            order by chats.created_at desc");

        $groups = DB::table('chat_group_members')
            ->select('group_id')
            ->groupBy('group_id')
            ->where('chat_group_members.member_id', $user_id)
            ->get()
            ->pluck('group_id')->toArray();

        $latest_group_message = DB::table('chat_group_messages')
            ->groupBy('group_id')
            ->whereIn('group_id', $groups)
            ->select(DB::raw('max(id) as id'))
            ->get()
            ->pluck('id')->toArray();

        $latest_group_sms = ChatGroupMessage::select(
                'chat_group_messages.group_id as id',
                'chat_groups.group_name as name',
                'profiles.profile_image',
                'chat_group_messages.text',
                DB::raw('DATE_FORMAT(chat_group_messages.created_at, "%Y-%m-%d %H:%i:%s") as date')
            )
            ->leftJoin('chat_groups', 'chat_groups.id', 'chat_group_messages.group_id')
            ->leftJoin('users', 'users.id', 'chat_group_messages.sender_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->whereIn('chat_group_messages.id', $latest_group_message)->get()->toArray();
        //   $ids = json_encode($messages);

        $arr = json_decode(json_encode($messages), true);
        foreach ($arr as $key => $value) {
            $arr[$key]['is_group'] = 0;
        }
        foreach ($latest_group_sms as $key => $value) {
            $latest_group_sms[$key]['is_group'] = 1;
        }
        $merged = array_merge($arr, $latest_group_sms);
        $keys = array_column($merged, 'date');
        array_multisort($keys, SORT_DESC, $merged);
        $group_owner = ChatGroup::whereIn('chat_groups.id', $groups)->get();
        foreach ($merged as $key => $value) {
            $merged[$key]['owner_id'] = 0;
            foreach ($group_owner as $owner) {
                if ($value['id'] == $owner['id'] and $value['is_group'] == 1)
                    $merged[$key]['owner_id'] = $owner->group_owner_id;
            }
        }
        //to user
        $messages_to =DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date
        from
            chats
          join
            (select user, max(created_at) m
                from
                   (
                     (select id, to_user_id user, created_at
                       from chats
                       where from_user_id= $to_user_id  and delete_status <> 2 and deleted_by != $to_user_id )
                   union
                     (select id, from_user_id user, created_at
                       from chats
                       where to_user_id= $to_user_id  and delete_status <> 2 and deleted_by != $to_user_id)
                    ) t1
               group by user) t2
                on ((from_user_id= $to_user_id and to_user_id=user) or
                    (from_user_id=user and to_user_id= $to_user_id)) and
                    (created_at = m)
                left join users on users.id = user
                left join profiles on users.profile_id = profiles.id
                where deleted_by !=  $to_user_id  and delete_status != 2
                and users.id != $admin_id
            order by chats.created_at desc");

            $groups_to = DB::table('chat_group_members')
                        ->select('group_id')
                        ->groupBy('group_id')
                        ->where('chat_group_members.member_id',$to_user_id)
                        ->get()
                        ->pluck('group_id')->toArray();

            $latest_group_message_to = DB::table('chat_group_messages')
                        ->groupBy('group_id')
                        ->whereIn('group_id',$groups_to)
                        ->select(DB::raw('max(id) as id'))
                        ->get()
                        ->pluck('id')->toArray();
            $latest_group_sms_to =ChatGroupMessage::
                    select('chat_group_messages.group_id as id','chat_groups.group_name as name',
                    'profiles.profile_image','chat_group_messages.text',
                    DB::raw('DATE_FORMAT(chat_group_messages.created_at, "%Y-%m-%d %H:%i:%s") as date'))
                    ->leftJoin('chat_groups','chat_groups.id','chat_group_messages.group_id')
                    ->leftJoin('users','users.id','chat_group_messages.sender_id')
                    ->leftJoin('profiles','users.profile_id','profiles.id')
                    ->whereIn('chat_group_messages.id',$latest_group_message_to)->get()->toArray();
                    //   $ids = json_encode($messages);
            $arr_to = json_decode(json_encode ( $messages_to ) , true);
            foreach($arr_to as $key=>$value){
                $arr_to[$key]['is_group'] = 0;
            }
            foreach($latest_group_sms_to as $key=>$value){
                $latest_group_sms_to[$key]['is_group'] = 1;
            }
                    $merged_to = array_merge($arr_to, $latest_group_sms_to);
                    $keys_to = array_column($merged_to, 'date');
                    array_multisort($keys_to, SORT_DESC, $merged_to);
                    $group_owner_to = ChatGroup::whereIn('chat_groups.id',$groups_to)->get();
                    foreach($merged_to as $key=>$value){
                           $merged_to[$key]['owner_id'] = 0;
                        foreach($group_owner_to as $owner){
                            if($value['id'] == $owner['id'] AND $value['is_group'] == 1)
                            $merged_to[$key]['owner_id'] = $owner->group_owner_id;
                           }
                    }

        $arr_six = array_reverse($merged);
        $arr_six = array_slice($arr_six, -6);
        $arr_six = array_reverse($arr_six);

        $arr_six_to = array_reverse($merged_to);
        $arr_six_to = array_slice($arr_six_to, -6);
        $arr_six_to = array_reverse($arr_six_to);



        $pusher->trigger('all_message.'. $to_user_id , 'all', $merged_to);
        $pusher->trigger('all_message.'. $user_id , 'all', $merged);
        $pusher->trigger('chat_message.' . $to_user_id, 'chat', $arr_six_to);
        $pusher->trigger('chat_message.' . $user_id, 'chat', $arr_six);
    }

    public function chatting_admin(Request $request, User $user)
    {
     $id = User::whereHas('roles', function ($query) {
        $query->where('name', '=', 'admin');
    })->first();
    $to_user_id = $id->id;
        if ($request->text == null && $request->fileSend == null) {
        } else {
            $message = new Chat();
            $sendFile = $request->all();
            if ($request->totalFiles != 0) {
                $files = $sendFile['fileSend'];
                if ($sendFile['fileSend']) {
                    foreach ($files as $file) {
                        $extension = $file->extension();
                        $name = rand() . "." . $extension;
                        $file->storeAs('/public/customer_message_media/', $name);
                        $imgData[] = $name;
                        $message->media = json_encode($imgData);
                        $message->text = null;
                    }
                }
            } else {
                $message->text = $request->text;
                $message->media = null;
            }

            $message->from_user_id = auth()->user()->id;
            $message->to_user_id = $to_user_id;
            $message->save();
           // dd($request->sender);
            broadcast(new Chatting($message, $request->sender));
        }
    }


    public function chatting_admin_side(Request $request, User $user)
    { 
    $to_user_id = $request->to_user_id;
        if ($request->text == null && $request->fileSend == null) {
        } else {
            $message = new Chat();
            $sendFile = $request->all();
            if ($request->totalFiles != 0) {
                $files = $sendFile['fileSend'];
                if ($sendFile['fileSend']) {
                    foreach ($files as $file) {
                        $extension = $file->extension();
                        $name = rand() . "." . $extension;
                        $file->storeAs('/public/customer_message_media/', $name);
                        $imgData[] = $name;
                        $message->media = json_encode($imgData);
                        $message->text = null;
                    }
                }
            } else {
                $message->text = $request->text;
                $message->media = null;
            }

            $message->from_user_id = auth()->user()->id;
            $message->to_user_id = $to_user_id;
            $message->save();
           // dd($request->sender);
            broadcast(new Chatting($message, $request->sender));
        }
    }
}
