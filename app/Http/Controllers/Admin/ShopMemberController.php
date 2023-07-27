<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\ShopMember;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTrainerRequest;
use App\Http\Requests\CreateShopMemberRequest;
use App\Http\Requests\UpdateShopMemberRequest;

class ShopMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.shop_member.index');
    }

    public function ssd()
    {
        $shop_members = ShopMember::all();

        return Datatables::of($shop_members)
            ->addIndexColumn()
            ->editColumn('duration', function($each) {
                if($each->duration == 1) {
                    return $each->duration . " month";
                }else {
                    return $each->duration . " months";
                }

            })
            ->editColumn('updated_at', function ($each) {
                return Carbon::parse($each->updated_at)->format("Y-m-d H:i:s");
            })
            ->addColumn('action', function ($each) {
                $edit_icon = '';
                $detail_icon = '';
                $delete_icon = '';

                $edit_icon = '<a href=" ' . route('shop-member.edit', $each->id) . ' " class="text-warning mx-1 " title="edit">
                                    <i class="fa-solid fa-edit fa-xl"></i>
                              </a>';
                $detail_icon = '<a href=" ' . route('shop-member.show', $each->id) . ' " class="text-info mx-1" title="detail">
                                    <i class="fa-solid fa-circle-info fa-xl"></i>
                                </a>';

            $delete_icon = '<a href=" ' . route('shop-member.destroy', $each->id) . ' " class="text-danger mx-1 delete-btn" title="delete"  data-id="' . $each->id . '" >
                                    <i class="fa-solid fa-trash fa-xl"></i>
                                </a>';

                return '<div class="d-flex justify-content-center">' . $edit_icon . $delete_icon . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.shop_member.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateShopMemberRequest $request)
    {
        $shop_member = new ShopMember();
        $shop_member->member_type = $request->member_type;
        $shop_member->duration = $request->duration;
        $shop_member->price = $request->price;
        $shop_member->cons = $request->cons;
        $shop_member->post_count = $request->count;
        $shop_member->pros = $request->pros;

        $shop_member->save();

        return redirect()->route('shop-member.index')->with('success', 'New ShopMember is created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $user = User::findOrFail($id);
        // return view('admin.trainers.show', compact(''));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shop_member = ShopMember::findOrFail($id);
        return view('admin.shop_member.edit', compact('shop_member'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShopMemberRequest $request, $id)
    {
        $shop_member = ShopMember::findOrFail($id);
        $shop_member->member_type = $request->member_type;
        $shop_member->duration = $request->duration;
        $shop_member->price = $request->price;
        $shop_member->post_count = $request->count;
        $shop_member->pros = $request->pros;
        $shop_member->cons = $request->cons;

        $shop_member->update();

        return redirect()->route('shop-member.index')->with('success', 'New Shop Member is updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $shop_member = ShopMember::findOrFail($id);
        $shop_member->delete();

        return 'success';
    }
}
