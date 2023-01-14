<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ShopMember;
use App\Models\ShopmemberHistory;
use SebastianBergmann\Type\NullType;

class ShopRequestController extends Controller
{
    public function index()
    {
        return view('admin.shop_request.index');
    }

    public function ssd()
    {
        $shop_request =  DB::table('users')->select('users.id as user_id', 'users.name', 'shop_members.id', 'shop_members.member_type', 'shop_members.price', 'shop_members.duration')->where('shop_request', 1)
        ->orWhere('shop_request', 3)
            ->join('shop_members', 'shop_members.id', 'users.shopmember_type_id')->get();

        return Datatables::of($shop_request)
            ->addIndexColumn()
            ->addColumn('action', function ($each) {
                $edit_icon = '';
                $delete_icon = '';
                $detail_icon = '';

                $detail_icon = '<a href=" ' . route('payment.detail', $each->user_id) . ' " class="text-warning mx-1 mt-1" title="payment">
                                        <i class="fa-solid fa-circle-info fa-xl"></i>
                              </a>';

                $edit_icon = '<a href=" ' . route('admin.shop_request.accept', $each->user_id) . ' " class="mx-1 btn btn-sm btn-success accept-btn" data-id="' . $each->user_id . '">
                                    Accept
                              </a>';

                $delete_icon = '<a href=" ' . route('admin.shop_request.decline', $each->user_id) . ' " class="mx-1 btn btn-sm delete-btn btn-danger" data-id="' . $each->user_id . '" >
                                    Decline
                                </a>';

                return '<div class="d-flex justify-content-center">' . $detail_icon . $edit_icon . $delete_icon . '</div>';
            })
            ->make(true);
    }

    public function request_accept($id)
    {

        $check_user = User::select('users.*', 'shop_members.id as shopmemberId', 'shop_members.member_type', 'shop_members.post_count')->where('users.id', $id)->join('shop_members', 'shop_members.id', 'users.shopmember_type_id')->first();

        $checkmember = User::select('users.id as user_id', 'shop_members.*')->where('users.id', $id)->join('shop_members', 'shop_members.id', 'users.shopmember_type_id')->first();

        $date  = Carbon::Now()->toDateString();

        ShopmemberHistory::create([
            'user_id' => $checkmember->user_id,
            'shopmember_type_id' => $checkmember->id,
            'date' => $date
        ]);

        $user = User::findOrFail($id);
        $user->shop_request = 2;
        $shopmember=ShopMember::findOrFail($user->shopmember_type_id);
        if($shopmember->member_type=='level3'){
            $user->shop_post_count=0;
        }else{
            $shoppost=0;
            $shoppost=$user->shop_post_count;
            $shoppost+=$check_user->post_count;
            $user->shop_post_count =$shoppost;
        }
        // $user->shopmember_type_id = 0;

        $from_date = Carbon::now();
        $to_date = Carbon::now()->addMonths($shopmember->duration);
        $user->shopfrom_date = $from_date;
        $user->shopto_date = $to_date;

        $user->update();
        return back();
    }

    public function request_decline($id)
    {

        $user = User::findOrFail($id);
        $user->shop_request = 0;
        $user->update();
        return back()->with('success', 'Declined');
    }
}
