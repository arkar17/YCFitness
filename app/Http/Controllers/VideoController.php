<?php

namespace App\Http\Controllers;

use Pusher\Pusher;
use App\Models\ChatGroup;
use Illuminate\Http\Request;
use App\Events\MakeAgoraCall;
use App\Events\GroupAudioCall;
use App\Events\GroupVideoCall;
use App\Events\DeclineCallUser;
use App\Models\ChatGroupMember;
use App\Models\ChatGroupMessage;
use App\Events\MakeAgoraAudioCall;
use Illuminate\Support\Facades\Auth;
use App\Class\AgoraDynamicKey\RtcTokenBuilder;


class VideoController extends Controller
{
    public function callUser(Request $request)
    {
        $data['userToCall'] = $request->user_to_call;

        $data['channelName'] = $request->channel_name;
        $data['from'] = Auth::id();

        broadcast(new MakeAgoraCall($data))->toOthers();
    }

    // MakeAgoraAudioCall
    public function callAudioUser(Request $request)
    {
        $data['userToCall'] = $request->user_to_call;
        $data['channelName'] = $request->channel_name;
        $data['from'] = Auth::id();

        broadcast(new MakeAgoraAudioCall($data))->toOthers();
    }
    public function declineCallUser(Request $request) {
        $data['userFromCall'] = $request->user_from_call;
        broadcast(new DeclineCallUser($data))->toOthers();
    }

    public function callGpuser(Request $request)
    {
        $members = ChatGroupMember::where('group_id', $request->group_id)->where('member_id','!=',auth()->user()->id)->get();
        $data['channelName'] = $request->channel_name;

        foreach ($members as $member) {
            $data['memberId'] = $member->member_id;
            broadcast(new GroupVideoCall($data['memberId'], $data))->toOthers();
        }
    }

    public function callGpAudioUser(Request $request)
    {
        $members = ChatGroupMember::where('group_id', $request->group_id)->where('member_id','!=',auth()->user()->id)->get();
        $data['channelName'] = $request->channel_name;
        $group_name = ChatGroup::select('group_name')->where('id',$request->group_id)->first();
        $data['groupName'] = $group_name;

        foreach ($members as $member) {
            $data['memberId'] = $member->member_id;
            broadcast(new GroupAudioCall($data['memberId'], $data))->toOthers();
        }
    }



    public function token(Request $request)
    {

        $appID = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $channelName = $request->channelName;
        $user = Auth::user()->name;
        $role = RtcTokenBuilder::RoleAttendee;
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        $token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);

        return $token;
    }

}
