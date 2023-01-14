<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\CreateTrainerRequest;
use App\Http\Requests\UpdateTrainerRequest;
use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.permission.index');
    }

    public function ssd()
    {
        $permissions = Permission::query();
        return Datatables::of($permissions)
            ->editColumn('created_at', function ($each) {
                return Carbon::parse($each->created_at)->format("Y-m-d H:i:s");
            })
            ->addColumn('action', function ($each) {
                $edit_icon = '';
                $detail_icon = '';
                $delete_icon = '';

                $edit_icon = '<a href=" ' . route('permission.edit', $each->id) . ' " class="text-warning mx-1 " title="edit">
                <i class="fa-solid fa-edit fa-xl"></i>
            </a>';
                // $detail_icon = '<a href=" ' . route('permission.show', $each->id) . ' " class="text-info mx-1 " title="detail">
                //     <i class="fa-solid fa-circle-info fa-xl"></i>
                // </a>';

                $delete_icon = '<a href=" ' . route('permission.destroy', $each->id) . ' " class="text-danger mx-1 delete-btn" data-id="' . $each->id . '" title="delete">
                <i class="fa-solid fa-trash fa-xl"></i>
            </a>';

                return '<div class="d-flex justify-content-center">' .   $edit_icon . $delete_icon . '</div>';
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
        return view('admin.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePermissionRequest $request)
    {
        $permission = new Permission();
        $permission->name = $request->name;

        $permission->save();
        return redirect()->route('permission.index')->with('success', 'New Permission is created successfully!');
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
        $permission = Permission::findOrFail($id);
        return view('admin.permission.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePermissionRequest $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->name = $request->name;

        $permission->update();
        return redirect()->route('permission.index')->with('success', 'Permission is updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return 'success';
    }
}
