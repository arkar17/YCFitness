<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrainingGroup;
use App\Models\Message;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChatWithAdminController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function user_list()
    {
        $id = 7;
        $user_id = Auth()->user()->id;
       
        // $messages = DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date, chats.from_user_id as from_id,chats.to_user_id as to_id
        //         from
        //             chats
        //         join
        //             (select user, max(created_at) m
        //                 from
        //                 (
        //                     (select id, to_user_id user, created_at
        //                     from chats
        //                     where from_user_id= $user_id  and delete_status <> 2 and deleted_by != $user_id)
        //                 union
        //                     (select id, from_user_id user, created_at
        //                     from chats
        //                     where to_user_id= $user_id and delete_status <> 2 and deleted_by != $user_id)
        //                     ) t1
        //             group by user) t2
        //                 on ((from_user_id= $user_id and to_user_id=user) or
        //                     (from_user_id=user and to_user_id= $user_id)) and
        //                     (created_at = m)
        //                 left join users on users.id = user
        //                 left join profiles on users.profile_id = profiles.id
        //                 order by chats.created_at desc");
        $auth_user = auth()->user();

        $messages = Chat::where(function ($que) use ($id) {
            $que->where('from_user_id', $id)->orWhere('to_user_id', $id);
        })->where(function ($query) use ($auth_user) {
            $query->where('from_user_id', $auth_user->id)->orWhere('to_user_id', $auth_user->id);
        })->get();
        foreach ($messages as $mess) {
            if ($mess->delete_status == 1 && $mess->deleted_by == $auth_user->id) {
                $messages = Chat::where('delete_status', 0)->orWhere(function ($q) use ($auth_user) {
                    $q->where('delete_status', 1)->where('deleted_by', '!=', $auth_user->id);
                })->where(function ($que) use ($id) {
                    $que->where('from_user_id', $id)->orWhere('to_user_id', $id);
                })->where(function ($query) use ($auth_user) {
                    $query->where('from_user_id', $auth_user->id)->orWhere('to_user_id', $auth_user->id);
                })->get();
            }
            if ($mess->delete_status == 2) {
                $messages = Chat::where('delete_status', 0)->orWhere(function ($q) use ($auth_user) {
                    $q->where('delete_status', 1)->where('deleted_by', '!=', $auth_user->id);
                })->where(function ($que) use ($id) {
                    $que->where('from_user_id', $id)->orWhere('to_user_id', $id);
                })->where(function ($query) use ($auth_user) {
                    $query->where('from_user_id', $auth_user->id)->orWhere('to_user_id', $auth_user->id);
                })->get();
            }
        }


        $auth_user_name = auth()->user()->name;
        $receiver_user = User::where('users.id', $id)->with('user_profile')->first();

        $sender_user = User::where('id', $auth_user->id)->with('user_profile')->first();

        $auth = Auth()->user()->id;
        $user = User::where('id', $auth)->first();

        $friendships = DB::table('friendships')
            ->where('friend_status', 2)
            ->where(function ($query) use ($id) {
                $query->where('sender_id', $id)
                    ->orWhere('receiver_id', $id);
            })
            ->join('users as sender', 'sender.id', 'friendships.sender_id')
            ->join('users as receiver', 'receiver.id', 'friendships.receiver_id')
            ->get(['sender_id', 'receiver_id'])->toArray();
        //dd($friends);
        $n = array();
        foreach ($friendships as $friend) {
            $f = (array)$friend;
            array_push($n, $f['sender_id'], $f['receiver_id']);
        }
        $friends = User::select('users.name', 'users.id')
            ->whereIn('id', $n)
            ->where('id', '!=', $user->id)
            ->get();

        return view('admin.chat_admin', compact('id', 'messages', 'auth_user_name', 'receiver_user', 'sender_user', 'friends'));
    }

    public function user_list_one($id){
        $user_id = Auth()->user()->id;
       
        // $messages = DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date, chats.from_user_id as from_id,chats.to_user_id as to_id
        //         from
        //             chats
        //         join
        //             (select user, max(created_at) m
        //                 from
        //                 (
        //                     (select id, to_user_id user, created_at
        //                     from chats
        //                     where from_user_id= $user_id  and delete_status <> 2 and deleted_by != $user_id)
        //                 union
        //                     (select id, from_user_id user, created_at
        //                     from chats
        //                     where to_user_id= $user_id and delete_status <> 2 and deleted_by != $user_id)
        //                     ) t1
        //             group by user) t2
        //                 on ((from_user_id= $user_id and to_user_id=user) or
        //                     (from_user_id=user and to_user_id= $user_id)) and
        //                     (created_at = m)
        //                 left join users on users.id = user
        //                 left join profiles on users.profile_id = profiles.id
        //                 order by chats.created_at desc");
        $auth_user = auth()->user();

        $messages = Chat::where(function ($que) use ($id) {
            $que->where('from_user_id', $id)->orWhere('to_user_id', $id);
        })->where(function ($query) use ($auth_user) {
            $query->where('from_user_id', $auth_user->id)->orWhere('to_user_id', $auth_user->id);
        })->get();
        foreach ($messages as $mess) {
            if ($mess->delete_status == 1 && $mess->deleted_by == $auth_user->id) {
                $messages = Chat::where('delete_status', 0)->orWhere(function ($q) use ($auth_user) {
                    $q->where('delete_status', 1)->where('deleted_by', '!=', $auth_user->id);
                })->where(function ($que) use ($id) {
                    $que->where('from_user_id', $id)->orWhere('to_user_id', $id);
                })->where(function ($query) use ($auth_user) {
                    $query->where('from_user_id', $auth_user->id)->orWhere('to_user_id', $auth_user->id);
                })->get();
            }
            if ($mess->delete_status == 2) {
                $messages = Chat::where('delete_status', 0)->orWhere(function ($q) use ($auth_user) {
                    $q->where('delete_status', 1)->where('deleted_by', '!=', $auth_user->id);
                })->where(function ($que) use ($id) {
                    $que->where('from_user_id', $id)->orWhere('to_user_id', $id);
                })->where(function ($query) use ($auth_user) {
                    $query->where('from_user_id', $auth_user->id)->orWhere('to_user_id', $auth_user->id);
                })->get();
            }
        }


        $auth_user_name = auth()->user()->name;
        $receiver_user = User::where('users.id', $id)->with('user_profile')->first();

        $sender_user = User::where('id', $auth_user->id)->with('user_profile')->first();

        $auth = Auth()->user()->id;
        $user = User::where('id', $auth)->first();

        $friendships = DB::table('friendships')
            ->where('friend_status', 2)
            ->where(function ($query) use ($id) {
                $query->where('sender_id', $id)
                    ->orWhere('receiver_id', $id);
            })
            ->join('users as sender', 'sender.id', 'friendships.sender_id')
            ->join('users as receiver', 'receiver.id', 'friendships.receiver_id')
            ->get(['sender_id', 'receiver_id'])->toArray();
        //dd($friends);
        $n = array();
        foreach ($friendships as $friend) {
            $f = (array)$friend;
            array_push($n, $f['sender_id'], $f['receiver_id']);
        }
        $friends = User::select('users.name', 'users.id')
            ->whereIn('id', $n)
            ->where('id', '!=', $user->id)
            ->get();

        return view('admin.chat_messages_admin', compact('id', 'messages', 'auth_user_name', 'receiver_user', 'sender_user', 'friends'));
    }

    public function viewmedia_message($id)
    {
        $auth_user = auth()->user();

        $messages = Chat::where(function ($query) use ($auth_user) {
            $query->where('from_user_id', $auth_user->id)->orWhere('to_user_id', $auth_user->id);
        })->where(function ($que) use ($id) {
            $que->where('from_user_id', $id)->orWhere('to_user_id', $id);
        })->where('media', '!=', null)->get();


        foreach ($messages as $mess) {
            if ($mess->delete_status == 1 && $mess->deleted_by == $auth_user->id) {
                $messages = Chat::where('delete_status', 0)->orWhere(function ($q) use ($auth_user) {
                    $q->where('delete_status', 1)->where('deleted_by', '!=', $auth_user->id);
                })->where(function ($que) use ($id) {
                    $que->where('from_user_id', $id)->orWhere('to_user_id', $id);
                })->where(function ($query) use ($auth_user) {
                    $query->where('from_user_id', $auth_user->id)->orWhere('to_user_id', $auth_user->id);
                })->where('media', '!=', null)->get();
            }
            if ($mess->delete_status == 2) {
                $messages = Chat::where('delete_status', 0)->orWhere(function ($q) use ($auth_user) {
                    $q->where('delete_status', 1)->where('deleted_by', '!=', $auth_user->id);
                })->where(function ($que) use ($id) {
                    $que->where('from_user_id', $id)->orWhere('to_user_id', $id);
                })->where(function ($query) use ($auth_user) {
                    $query->where('from_user_id', $auth_user->id)->orWhere('to_user_id', $auth_user->id);
                })->where('media', '!=', null)->get();
            }
        }

        $auth_user_name = auth()->user()->name;
        $receiver_user = User::findOrFail($id);
        return view('admin.chat_with_admin_view_media', compact('id', 'messages', 'auth_user_name', 'receiver_user'));
    }
}
