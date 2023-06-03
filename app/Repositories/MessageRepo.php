<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\BlockList;
use App\Models\ChatGroup;
use Illuminate\Http\Request;
use App\Models\ChatGroupMessage;
use Illuminate\Support\Facades\DB;
use App\Models\GroupChatMessageReadStatus;

class MessageRepo
{
    public function auth_chat()
    {
        $user_id = auth()->user()->id;
        $block_list = BlockList::where('sender_id', $user_id)->orWhere('receiver_id', $user_id)->get(['sender_id', 'receiver_id'])->toArray();
        $b = array();
        foreach ($block_list as $block) {
            $f = (array)$block;
            array_push($b, $f['sender_id'], $f['receiver_id']);
        }
        $array =  join(",", $b,);
        $id_admin = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'admin');
        })->first();
        $admin_id = $id_admin->id;
        if ($array) {
            $messages = DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date,chats.from_user_id, chats.read_or_not
            from
                chats
              join
                (select user, max(created_at) m
                    from
                       (
                         (select id, to_user_id user, created_at
                           from chats
                           where from_user_id= $user_id  and delete_status <> 2 and deleted_by != $user_id)
                       union
                         (select id, from_user_id user, created_at
                           from chats
                           where to_user_id= $user_id and delete_status <> 2 and deleted_by != $user_id)
                        ) t1
                   group by user) t2
                    on ((from_user_id= $user_id and to_user_id=user) or
                        (from_user_id=user and to_user_id= $user_id)) and
                        (created_at = m)
                    left join users on users.id = user
                    left join profiles on users.profile_id = profiles.id
                    where users.id not in ($array)
                    and users.id != $admin_id
                    order by chats.created_at desc");
        } else {
            $messages = DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date,
             chats.read_or_not,chats.from_user_id
        from
            chats
          join
            (select user, max(created_at) m
                from
                   (
                     (select id, to_user_id user, created_at
                       from chats
                       where from_user_id= $user_id  and delete_status <> 2 and deleted_by != $user_id)
                   union
                     (select id, from_user_id user, created_at
                       from chats
                       where to_user_id= $user_id and delete_status <> 2 and deleted_by != $user_id)
                    ) t1
               group by user ) t2
                on ((from_user_id= $user_id and to_user_id=user) or
                    (from_user_id=user and to_user_id= $user_id)) and
                    (created_at = m)
                left join users on users.id = user
                left join profiles on users.profile_id = profiles.id
                where users.id != $admin_id
                order by chats.created_at desc");
        }

        // dd($messages);


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
            'chat_group_messages.id as message_id',
            'chat_group_messages.sender_id',
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
        foreach ($arr as $key => $value) {
            if ($value['from_user_id'] == $user_id)
                $arr[$key]['isRead'] = 1;
            else

                $arr[$key]['isRead'] = $value['read_or_not'];
        }
        foreach ($latest_group_sms as $key => $value) {
            $latest_group_sms[$key]['is_group'] = 1;
        }
        $read = GroupChatMessageReadStatus::where('user_id', $user_id)->get();
        // dd(count($read));
        foreach ($latest_group_sms as $key => $value) {
            if (count($read) > 0)
                foreach ($read as $re) {
                    if ($re->message_id == $value['message_id'] and $re->user_id == $user_id or $value['sender_id'] == $user_id)
                        $latest_group_sms[$key]['isRead'] = 1;

                    else
                        $latest_group_sms[$key]['isRead'] = 0;
                }
            elseif ($value['sender_id'] == $user_id)
                $latest_group_sms[$key]['isRead'] = 1;
            else
                $latest_group_sms[$key]['isRead'] = 0;
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
        return ($merged) ? $merged : FALSE;
    }
    
}
