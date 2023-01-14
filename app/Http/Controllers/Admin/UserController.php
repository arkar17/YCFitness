<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // $user = auth()->user();
        // foreach($user->roles as $role) {
        //     echo $role->name;
        // }
        return view('admin.users.index');
    }

    public function ssd()
    {
        $users = User::query();

        return Datatables::of($users)
            ->addIndexColumn()
            ->filterColumn('role_name', function ($query, $keyword) {
                $query->whereHas('roles', function ($q1) use ($keyword) {
                    $q1->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->editColumn('created_at', function ($each) {
                return Carbon::parse($each->created_at)->format("Y-m-d H:i:s");
            })
            ->addColumn('role_name', function ($each) {
                $output = "";
                foreach ($each->roles as $role) {
                    $output .= '<span class="badge badge-pill badge-success m-1">' . $role->name  . '</span>';
                }
                return $output;
            })
            ->addColumn('action', function ($each) {
                $edit_icon = '';
                $detail_icon = '';
                $delete_icon = '';

                $edit_icon = '<a href=" ' . route('user.edit', $each->id) . ' " class="text-warning mx-1 " title="edit">
                                    <i class="fa-solid fa-edit fa-xl"></i>
                              </a>';
                $detail_icon = '<a href=" ' . route('user.show', $each->id) . ' " class="text-info mx-1" title="detail">
                                    <i class="fa-solid fa-circle-info fa-xl"></i>
                                </a>';

                $delete_icon = '<a href=" ' . route('user.destroy', $each->id) . ' " class="text-danger mx-1              delete-btn" title="delete"  data-id="' . $each->id . '" >
                                    <i class="fa-solid fa-trash fa-xl"></i>
                                </a>';

                return '<div class="d-flex justify-content-center">' . $edit_icon . $delete_icon . '</div>';
            })
            ->rawColumns(['role_name', 'action'])
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
        //
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
        $user = User::findOrFail($id);
        $roles = Role::all();
        $members = Member::all();
        $old_roles = $user->roles->pluck('id')->toArray();
        $old_members = $user->members->pluck('id')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'members', 'old_roles', 'old_members'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        $user->password = $request->password == null ? $user->password : Hash::make($request->password);

        // Thandar style start
        $user_member_type_id = $request->member_id;
        $member = Member::findOrFail($user_member_type_id);

        $user->member_type = $member->member_type;

        $user->syncRoles($request->roles);

        $user->save();
        $user->members()->attach($request->member_id);
        // Thandar style end



        return redirect()->route('user.index')->with('success', 'User is updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return "success";
    }
}
