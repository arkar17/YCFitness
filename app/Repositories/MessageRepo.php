<?php

namespace App\Repositories;

use Pusher\Pusher;
use App\Models\User;
use App\Models\BlockList;
use App\Models\ChatGroup;
use Illuminate\Http\Request;
use App\Models\ChatGroupMember;
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
        foreach ($latest_group_sms as $key => $value) {
            $latest_group_sms[$key]['isRead'] = 0; // Set initial value to 0
            if (count($read) > 0) {
                foreach ($read as $re) {
                    if (($re->message_id == $value['message_id'] && $re->user_id == $user_id)) {
                        $latest_group_sms[$key]['isRead'] = 1;
                        // break; // Exit the inner loop once isRead is set to 1
                    }
                }
            }
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
        // return $merged;
        return ($merged) ? $merged : FALSE;
    }

    public function to_chat(Request $request)
    {
        $id_admin = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'admin');
        })->first();
        $admin_id = $id_admin->id;
        $to_user_id = $request->receiver;
        $messages_to = DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date,chats.from_user_id, chats.read_or_not
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
                where users.id != $admin_id
            order by chats.created_at desc");
        // dd($messages);
        $groups_to = DB::table('chat_group_members')
        ->select('group_id')
        ->groupBy('group_id')
        ->where('chat_group_members.member_id', $to_user_id)
            ->get()
            ->pluck('group_id')->toArray();

        $latest_group_message_to = DB::table('chat_group_messages')
        ->groupBy('group_id')
        ->whereIn('group_id', $groups_to)
            ->select(DB::raw('max(id) as id'))
            ->get()
            ->pluck('id')->toArray();
        $latest_group_sms_to = ChatGroupMessage::select(
            'chat_group_messages.group_id as id',
            'chat_group_messages.id as message_id',
            'chat_groups.group_name as name',
            'chat_group_messages.sender_id',
            'profiles.profile_image',
            'chat_group_messages.text',
            DB::raw('DATE_FORMAT(chat_group_messages.created_at, "%Y-%m-%d %H:%m:%s") as date')
        )
            ->leftJoin('chat_groups', 'chat_groups.id', 'chat_group_messages.group_id')
            ->leftJoin('users', 'users.id', 'chat_group_messages.sender_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->whereIn('chat_group_messages.id', $latest_group_message_to)->get()->toArray();
        //   $ids = json_encode($messages);
        $read_to = GroupChatMessageReadStatus::where('user_id', $to_user_id)->get();
        $arr_to = json_decode(json_encode($messages_to), true);
        foreach ($arr_to as $key => $value) {
            $arr_to[$key]['is_group'] = 0;
        }

        
        foreach ($arr_to as $key => $value) {
            if ($value['from_user_id'] == $to_user_id)
                $arr_to[$key]['isRead'] = 1;
            else
                $arr_to[$key]['isRead'] = $value['read_or_not'];
        }

         foreach ($latest_group_sms_to as $key => $value) {
             $latest_group_sms_to[$key]['is_group'] = 1;
         }

        // foreach ($latest_group_sms_to as $key => $value) {
        //     $latest_group_sms_to[$key]['isRead'] = 0; // Set initial value to 0

        //     if (count($read_to) > 0) {
        //         foreach ($read_to as $re) {
        //             if (($re->message_id == $value['message_id'] && $re->user_id == $to_user_id)) {
        //                 $latest_group_sms_to[$key]['isRead'] = 1;
        //                 break; // Exit the inner loop once isRead is set to 1
        //             }
        //         }
        //     } elseif (
        //         $value['sender_id'] == $to_user_id
        //     ) {
        //         $latest_group_sms_to[$key]['isRead'] = 1;
        //     }
        // }

        foreach ($latest_group_sms_to as $key => $value) {
            $latest_group_sms_to[$key]['isRead'] = 0; // Set initial value to 0

            if (count($read_to) > 0) {
                foreach ($read_to as $re) {
                    if (($re->message_id == $value['message_id'] && $re->user_id == $to_user_id)) {
                        $latest_group_sms_to[$key]['isRead'] = 1;
                        // break; // Exit the inner loop once isRead is set to 1
                    }
                }
            }
        }
        $merged_to = array_merge($arr_to, $latest_group_sms_to);
        $keys_to = array_column($merged_to, 'date');
        array_multisort($keys_to, SORT_DESC, $merged_to);
        $group_owner_to = ChatGroup::whereIn('chat_groups.id', $groups_to)->get();
        foreach ($merged_to as $key => $value) {
            $merged_to[$key]['owner_id'] = 0;
            foreach ($group_owner_to as $owner) {
                if ($value['id'] == $owner['id'] and $value['is_group'] == 1)
                    $merged_to[$key]['owner_id'] = $owner->group_owner_id;
            }
        }
        return ($merged_to) ? $merged_to : FALSE;
    }
    public function six_message()
    {
        $merged =  $this->auth_chat();
        // dd($merged);
        $arr_six = array_reverse($merged);
        $arr_six = array_slice($arr_six, -6);
        $arr_six = array_reverse($arr_six);
        return $arr_six;
    }
    public function six_message_to(Request $request)
    {
        $merged_to =  $this->to_chat($request);
        $arr_six_to = array_reverse($merged_to);
        $arr_six_to = array_slice($arr_six_to, -6);
        $arr_six_to = array_reverse($arr_six_to);
        return ($arr_six_to) ? $arr_six_to : FALSE;
    }

    public function to_chat_user(User $user)
    {
        $to_user_id = $user->id;
        $id_admin = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'admin');
        })->first();
        $admin_id = $id_admin->id;
        $messages_to = DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date,chats.from_user_id, chats.read_or_not
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
                where users.id != $admin_id
            order by chats.created_at desc");
        // dd($messages_to);
        $groups_to = DB::table('chat_group_members')
        ->select('group_id')
        ->groupBy('group_id')
        ->where('chat_group_members.member_id', $to_user_id)
            ->get()
            ->pluck('group_id')->toArray();

        $latest_group_message_to = DB::table('chat_group_messages')
        ->groupBy('group_id')
        ->whereIn('group_id', $groups_to)
            ->select(DB::raw('max(id) as id'))
            ->get()
            ->pluck('id')->toArray();
        $latest_group_sms_to = ChatGroupMessage::select(
            'chat_group_messages.group_id as id',
            'chat_group_messages.id as message_id',
            'chat_groups.group_name as name',
            'chat_group_messages.sender_id',
            'profiles.profile_image',
            'chat_group_messages.text',
            DB::raw('DATE_FORMAT(chat_group_messages.created_at, "%Y-%m-%d %H:%m:%s") as date')
        )
            ->leftJoin('chat_groups', 'chat_groups.id', 'chat_group_messages.group_id')
            ->leftJoin('users', 'users.id', 'chat_group_messages.sender_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->whereIn('chat_group_messages.id', $latest_group_message_to)->get()->toArray();
        //   $ids = json_encode($messages);
        $read_to = GroupChatMessageReadStatus::where('user_id', $to_user_id)->get();
        $arr_to = json_decode(json_encode($messages_to), true);
        foreach ($arr_to as $key => $value) {
            $arr_to[$key]['is_group'] = 0;
        }

        foreach ($arr_to as $key => $value) {
            if ($value['from_user_id'] == $to_user_id)
                $arr_to[$key]['isRead'] = 1;
            else
                $arr_to[$key]['isRead'] = $value['read_or_not'];
        }

        foreach ($latest_group_sms_to as $key => $value) {
            $latest_group_sms_to[$key]['is_group'] = 1;
        }

        foreach ($latest_group_sms_to as $key => $value) {
            $latest_group_sms_to[$key]['isRead'] = 0; // Set initial value to 0
            if (count($read_to) > 0) {
                foreach ($read_to as $re) {
                    if (($re->message_id == $value['message_id'] && $re->user_id == $to_user_id)) {
                        $latest_group_sms_to[$key]['isRead'] = 1;
                        // break; // Exit the inner loop once isRead is set to 1
                    }
                }
            }
        }
        $merged_to = array_merge($arr_to, $latest_group_sms_to);
        $keys_to = array_column($merged_to, 'date');
        array_multisort($keys_to, SORT_DESC, $merged_to);
        $group_owner_to = ChatGroup::whereIn('chat_groups.id', $groups_to)->get();
        foreach ($merged_to as $key => $value) {
            $merged_to[$key]['owner_id'] = 0;
            foreach ($group_owner_to as $owner) {
                if ($value['id'] == $owner['id'] and $value['is_group'] == 1)
                    $merged_to[$key]['owner_id'] = $owner->group_owner_id;
            }
        }
        return ($merged_to) ? $merged_to : FALSE;
    }
}
