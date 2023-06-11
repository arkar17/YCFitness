<?php

namespace App\Http\Controllers\Customer;

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
use Illuminate\Http\Request;
use App\Events\GroupChatting;
use App\Events\MakeAgoraCall;
use App\Events\MessageDelete;
use App\Models\UserReactPost;
use App\Models\UserSavedPost;
use App\Events\GroupAudioCall;
use App\Events\GroupVideoCall;
use App\Repositories\ChatRepo;
use App\Events\DeclineCallUser;
use App\Models\ChatGroupMember;
use App\Models\ChatGroupMessage;
use App\Repositories\MessageRepo;
use App\Events\MakeAgoraAudioCall;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\GroupChatMessageReadStatus;
use App\Class\AgoraDynamicKey\RtcTokenBuilder;

class ChattingController extends Controller
{
    //
    public $page = 'Messages';

    protected $messageRepo;
    public function __construct(MessageRepo $messageRepo)
    {
        $this->messageRepo = $messageRepo;
        $this->middleware('auth');
    }
    public function chatting(Request $request, User $user)
    {
        // dd($user->id);
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

            $message_id = $message->id;
            $message = Chat::select('chats.*', 'chats.created_at as date', 'profiles.profile_image', 'users.name')
            ->leftJoin('users', 'users.id', 'chats.from_user_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->where('chats.id', $message_id)
            ->first();
            foreach ($message as $key => $value) {
                $message['isGroup'] = 0;
            }
            // $pusher->trigger('chat_message.' . auth()->user()->id, 'message', $message);

            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options = array(
                    'cluster' => 'eu',
                    'encrypted' => true
                )
            );
            $pusher->trigger('channel-one2one.' . $user->id, 'one2one-event', ['message' => $message]);
            // $pusher->trigger('channel-one2one.' . Auth::user()->id, 'one2one-event', ['message' => $message]);
            broadcast(new Chatting($message, $request->sender));

            $user_id = auth()->user()->id;

            $merged = $this->messageRepo->auth_chat();


            $to_user_id = $user->id;
            $user_id = auth()->user()->id;

            $merged = $this->messageRepo->auth_chat();
            $merged_to = $this->messageRepo->to_chat_user($user);
            $arr_six = $this->messageRepo->six_message();
            $merged_to =  $merged_to;
            $arr_six_to = array_reverse($merged_to);
            $arr_six_to = array_slice($arr_six_to, -6);
            $arr_six_to = array_reverse($arr_six_to);

            $pusher->trigger('chat_message.' . $user_id, 'chat', $arr_six);
            $pusher->trigger('chat_message.' . $to_user_id, 'chat', $arr_six_to);

            $pusher->trigger('all_message.' . $to_user_id, 'all', $merged_to);
            $pusher->trigger('all_message.' . $user_id, 'all', $merged);
        }
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

            $message_id = $message->id;
            $message = Chat::select('chats.*', 'chats.created_at as date', 'profiles.profile_image', 'users.name')
            ->leftJoin('users', 'users.id', 'chats.from_user_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->where('chats.id', $message_id)
                ->first();
            foreach ($message as $key => $value) {
                $message['isGroup'] = 3;
            }
            // $pusher->trigger('chat_message.' . auth()->user()->id, 'message', $message);

            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options = array(
                    'cluster' => 'eu',
                    'encrypted' => true
                )
            );
            $pusher->trigger('channel-one2one.' . $to_user_id, 'one2one-event', ['message' => $message]);
            // $pusher->trigger('channel-one2one.' . Auth::user()->id, 'one2one-event', ['message' => $message]);
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
            $message_id = $message->id;
            $message = Chat::select('chats.*',  'chats.created_at as date', 'users.name', 'profiles.profile_image')
            ->leftJoin('users', 'users.id', 'chats.from_user_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->where('chats.id', $message_id)
            ->first();
            foreach ($message as $key => $value) {
                $message['isGroup'] = 3;
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
            $pusher->trigger('channel-one2one.' . $to_user_id, 'one2one-event', ['message' => $message]);
            broadcast(new Chatting($message, $request->sender));
        }
    }
}
