<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.request.member_request');
    }

    public function ssd()
    {
        $memberRequest =  DB::table('users')->select('users.id','users.name','users.gender','users.membertype_level','members.member_type','users.phone')
                        ->leftJoin('members','members.id','users.request_type')
                        ->where('users.active_status',1)
                        ->get();
        // dd($memberRequest)->toArray();

        return Datatables::of($memberRequest)
            ->addIndexColumn()
            ->addColumn('action', function ($each) {
                $edit_icon = '';
                $delete_icon = '';
                $detail_icon = '';

                $detail_icon = '<a href=" ' . route('payment.detail', $each->id) . ' " class="text-warning mx-1 mt-1" title="payment">
                                        <i class="fa-solid fa-circle-info fa-xl"></i>
                              </a>';

                $edit_icon = '<a href=" ' . route('requestaccept', $each->id) . ' " class="mx-1 btn btn-sm btn-success">
                                    Accept
                              </a>';

                $delete_icon = '<a href=" ' . route('requestdecline', $each->id) . ' " class="mx-1 btn btn-sm delete-btn btn-danger" data-id="' . $each->id . '" >
                                    Decline
                                </a>';

                return '<div class="d-flex justify-content-center">' .$detail_icon. $edit_icon . $delete_icon . '</div>';
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
