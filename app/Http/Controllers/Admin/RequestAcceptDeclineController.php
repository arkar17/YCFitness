<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\User;
use App\Models\Member;
use App\Models\ShopMember;
use Illuminate\Http\Request;
use App\Models\MemberHistory;
use App\Models\ShopmemberHistory;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RequestAcceptDeclineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function group()
    {
       // dd("dd");
    }
    public function accept(Request $request, $id)
    {

        $u = User::findOrFail($id);
        $member_history = MemberHistory::where('user_id', $id)->first();
        $member = Member::findOrFail($u->request_type);

        $date  = Carbon::Now()->toDateString();

        $shop_member = ShopMember::where('member_type', 'level3')->first();
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options = array(
                'cluster' => 'eu',
                'encrypted' => true
            )
        );
        if ($member_history != null && $member_history->user_id == $id) {

            $member_history->create([
                'user_id' => $id,
                'member_id' => $member->id,
                'member_type_level' => $member_history->member_type_level,
                'date' => $date
            ]);

            if ($member->member_type == "Ruby" || $member->member_type == "Ruby Premium") {
                //dd($member->member_type);
                ShopmemberHistory::create([
                    'user_id' => $id,
                    'shopmember_type_id' => $shop_member->id,
                    'date' => $date
                ]);

                $u->shopmember_type_id = 0;
                $u->shop_request = 2;
                $u->active_status = 2;
                // $u->request_type = 0;
                $u->shop_post_count = 0;
                $u->member_type = $member->member_type;
                $role = Role::findOrFail($member->role_id);
                $u->syncRoles($role->name);
                $u->save();

                $pusher->trigger('channel-accept.'. $id , 'accept', 'accepted');
                return back()->with('success', 'Upgraded Success');
            } else {
                $u->active_status = 2;
                $u->request_type = 0;
                $u->member_type = $member->member_type;
                $role = Role::findOrFail($member->role_id);
                $u->syncRoles($role->name);
                $u->save();
                $pusher->trigger('channel-accept.'. $id , 'accept', 'accepted');
                return back()->with('success', 'Upgraded Success');
            }
        } else {
            $current_date = Carbon::now()->toDateString();
            $member_role = Member::where('id', $member->id)->first();
            $role = Role::findOrFail($member_role->role_id);
            DB::table('model_has_roles')->where('model_id', $id)->delete();
            if ($member->member_type == "Ruby" || $member->member_type == "Ruby Premium") {
                //dd($member->member_type);
                ShopmemberHistory::create([
                    'user_id' => $id,
                    'shopmember_type_id' => $shop_member->id,
                    'date' => $date
                ]);

                $u->shopmember_type_id = 0;
                $u->shop_request = 2;
                $u->active_status = 2;
                // $u->request_type = 0;
                $u->shop_post_count = 0;
                $u->member_type = $member->member_type;
                $role = Role::findOrFail($member->role_id);
                $u->syncRoles($role->name);
                $u->save();
                $u->members()->attach($u->request_type, ['member_type_level' => $u->membertype_level, 'date' => $current_date,'member_id' =>$member->id]);
                $pusher->trigger('channel-accept.'. $id , 'accept', 'accepted');
                return back()->with('success', 'Upgraded Success');
            } else {
                $u->active_status = 2;
                $u->request_type = 0;
                $u->member_type = $member->member_type;
                $role = Role::findOrFail($member->role_id);
                $u->syncRoles($role->name);
                $u->save();
                $u->members()->attach($u->request_type, ['member_type_level' => $u->membertype_level, 'date' => $current_date,'member_id' =>$member->id]);
                $pusher->trigger('channel-accept.'. $id , 'accept', 'accepted');
                return back()->with('success', 'Upgraded Success');
            }

            // $current_date = Carbon::now()->toDateString();
            // $member_role = Member::where('id', $member->id)->first();
            // $role = Role::findOrFail($member_role->role_id);
            // DB::table('model_has_roles')->where('model_id', $id)->delete();

            // $u->assignRole($role->name);
            // $u->member_type = $member->member_type;
            // $u->active_status = 2;
            // $u->request_type = 0;
            // $u->save();
            // $u->members()->attach($u->request_type, ['member_type_level' => $u->membertype_level, 'date' => $current_date,'member_id' =>$member->id]);
            // $pusher->trigger('channel-accept.'. $id , 'accept', 'accepted');
            // return back()->with('success', 'Accepted');
        }
    }

    public function decline($id)
    {
        $user = User::findOrFail($id);
        $user->active_status = 0;
        $user->member_type = 'Free';
        $user->update();
        return back()->with('success', 'Declined');
    }
}
