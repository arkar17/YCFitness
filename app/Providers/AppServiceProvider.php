<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use App\Models\Profile;
use App\Models\BlockList;
use App\Models\ChatGroup;
use App\Models\Friendship;
use App\Models\ShopMember;
use App\Models\ChatGroupMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    view()->composer('*', function ($view)
    {
        if (Auth::check()) {
        $user_id=Auth::user()->id;
        // $left_friends = User::where('id',  Auth::user()->id)->get();
        $friends=DB::table('friendships')
                    ->where('friend_status',2)
                    ->where(function($query) use ($user_id){
                        $query->where('sender_id',$user_id)
                            ->orWhere('receiver_id',$user_id);
                    })
                    ->get(['sender_id','receiver_id'])->toArray();

        if(!empty($friends)){
            $n= array();
            foreach($friends as $friend){
                    $f=(array)$friend;
                    array_push($n, $f['sender_id'],$f['receiver_id']);
            }
            $posts=Post::whereIn('user_id',$n)
                        ->orderBy('created_at','DESC')
                        ->with('user')
                        ->paginate(30);
        }else{
            $n= array();
        }
        $id = auth()->user()->id;
        $block_list = BlockList::where('sender_id',$id)->orWhere('receiver_id',$id)->get(['sender_id', 'receiver_id'])->toArray();
        $b = array();
        foreach ($block_list as $block) {
            $f = (array)$block;
            array_push($b, $f['sender_id'], $f['receiver_id']);
        }
        if($b){
            $left_friends=User::whereIn('id',$n)
            ->where('id','!=',$user_id)
            ->whereNotIn('id',$b)
            ->paginate(6);
        }
        else{
            $left_friends=User::whereIn('id',$n)
                        ->where('id','!=',$user_id)
                        ->paginate(6);
        }
        $groups = DB::table('chat_group_members')
                                ->select('group_id')
                                ->groupBy('group_id')
                                ->where('chat_group_members.member_id',$user_id)
                                ->get()
                                ->pluck('group_id')->toArray();

        $latest_group_message = DB::table('chat_group_messages')
                                ->groupBy('group_id')
                                ->whereIn('group_id',$groups)
                                ->select(DB::raw('max(id) as id'))
                                ->get()
                                ->pluck('id')->toArray();

        $chat_group =
        ChatGroupMessage::leftJoin('chat_groups','chat_groups.id','chat_group_messages.group_id')
        ->select('chat_group_messages.*','chat_groups.group_name','chat_groups.id')
        ->whereIn('chat_group_messages.id',$latest_group_message)
        ->orderBy('chat_group_messages.created_at','DESC')
        ->take(3)
        ->get();
        //...with this variable
        $view->with(['left_friends'=> $left_friends, 'chat_group'=>$chat_group]);
        }
    });

    view()->composer('*', function ($view)
    {
        if (Auth::check()) {
        $user_id=Auth::user()->id;
        // $left_friends = User::where('id',  Auth::user()->id)->get();
        $friends=DB::table('friendships')
                    ->where('friend_status',2)
                    ->where(function($query) use ($user_id){
                        $query->where('sender_id',$user_id)
                            ->orWhere('receiver_id',$user_id);
                    })
                    ->get(['sender_id','receiver_id'])->toArray();

        if(!empty($friends)){
            $n= array();
            foreach($friends as $friend){
                    $f=(array)$friend;
                    array_push($n, $f['sender_id'],$f['receiver_id']);
            }
            $posts=Post::whereIn('user_id',$n)
                        ->orderBy('created_at','DESC')
                        ->with('user')
                        ->paginate(30);
        }else{
            $n= array();
        }
        $id = auth()->user()->id;
        $block_list = BlockList::where('sender_id',$id)->orWhere('receiver_id',$id)->get(['sender_id', 'receiver_id'])->toArray();
        $b = array();
        foreach ($block_list as $block) {
            $f = (array)$block;
            array_push($b, $f['sender_id'], $f['receiver_id']);
        }
        $left_friends=User::whereIn('id',$n)
                        ->where('id','!=',$user_id)
                        ->whereNotIn('id',$b)
                        ->take(3)
                        ->get();

        //...with this variable
        $view->with('left_friends', $left_friends);
        }

    });
    // View::share('Auth',Auth::user()->id);

    view()->composer('*',function($v){
        if (Auth::check()) {
            $user_id=auth()->user()->id;

            $user_profileimage=DB::table('users')
                                    ->select('users.*','profiles.profile_image as profile_image')
                                    ->join('profiles','profiles.id','users.profile_id')
                                    ->where('users.id',$user_id)
                                    ->first();

            $v->with('user_profileimage', $user_profileimage);
        }

    });


    view()->composer('*',function($message){
        if (Auth::check()) {
            $user_id=auth()->user()->id;
                $block_list = BlockList::where('sender_id',$user_id)->orWhere('receiver_id',$user_id)
                ->get(['sender_id', 'receiver_id'])
                ->toArray();
                $b = array();
                foreach ($block_list as $block) {
                    $f = (array)$block;
                    array_push($b, $f['sender_id'], $f['receiver_id']);
                }
                $array =  join(",",$b);

                $id_admin = User::whereHas('roles', function ($query) {
                    $query->where('name', '=', 'admin');
                })->first();
                // if($id_admin){
                  $admin_id = $id_admin->id;
                // }
                // else{
                //     $admin_id = null;
                // }
               
                // dd($array);
            if($array){
                $messages = DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date, chats.from_user_id as from_id,chats.to_user_id as to_id
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
            }
            else{
                $messages = DB::select("SELECT users.id as id,users.name,profiles.profile_image,chats.text,chats.created_at as date, chats.from_user_id as from_id,chats.to_user_id as to_id
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
                        where users.id != $admin_id
                        order by chats.created_at desc");
            }
           


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
                    'chat_group_messages.created_at',
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
            $arr = array_reverse($merged);
            $merged = array_slice($arr, -6);
            $merged = array_reverse($merged);

            $message->with('latest_messages', $merged);
        }

    });

    view()->composer('*',function($count){
        if (Auth::check()) {
            $memberRequest =  DB::table('users')
                                ->where('users.active_status',1)
                                ->get();
            $memberRequest_count=$memberRequest->count();
            $count->with('memberRequest_count', $memberRequest_count);
        }
    });
    view()->composer('*',function($count){
        if (Auth::check()) {
            $shopRequest =  DB::table('users')
                                ->where('users.shop_request',1)
                                ->orWhere('users.shop_request',3)
                                ->get();
            $shopRequest_count=$shopRequest->count();
            $count->with('shopRequest_count', $shopRequest_count);
        }
    });

    view()->composer('*',function($count){
        if (Auth::check()) {
            $shop_levels=ShopMember::get();
    
    $count->with('shop_levels', $shop_levels);
                }
                });
    

    }
}
