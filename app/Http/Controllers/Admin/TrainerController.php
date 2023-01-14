<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTrainerRequest;
use App\Http\Requests\UpdateTrainerRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TrainerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.trainers.index');
    }

    public function ssd()
    {
        $trainers = User::whereNotNull('training_type');

        return Datatables::of($trainers)
            ->addIndexColumn()
            ->editColumn('training_type', function ($each) {
                $training_type = "";
                if ($each->training_type == 'weight_loss') {
                    $training_type = 'Weight Loss';
                } elseif ($each->training_type == 'weight_gain') {
                    $training_type = 'Weight Gain';
                } else {
                    $training_type = 'Body Beauty';
                }
                return $training_type;
            })
            ->addColumn('role_name', function ($each) {
                $output = "";
                foreach ($each->roles as $role) {
                    $output .= '<span class="badge badge-pill badge-success m-1">' . $role->name  . '</span>';
                }
                return $output;
            })
            ->editColumn('updated_at', function ($each) {
                return Carbon::parse($each->updated_at)->format("Y-m-d H:i:s");
            })
            ->addColumn('action', function ($each) {
                $edit_icon = '';
                $detail_icon = '';
                $delete_icon = '';

                $edit_icon = '<a href=" ' . route('trainer.edit', $each->id) . ' " class="text-warning mx-1 " title="edit">
                                    <i class="fa-solid fa-edit fa-xl"></i>
                              </a>';
                $detail_icon = '<a href=" ' . route('trainer.show', $each->id) . ' " class="text-info mx-1" title="detail">
                                    <i class="fa-solid fa-circle-info fa-xl"></i>
                                </a>';

                $delete_icon = '<a href=" ' . route('trainer.destroy', $each->id) . ' " class="text-danger mx-1              delete-btn" title="delete"  data-id="' . $each->id . '" >
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
        $roles = Role::all();
        return view('admin.trainers.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTrainerRequest $request)
    {
        $trainer = new User();
        $trainer->name = $request->name;
        $trainer->phone = $request->phone;
        $trainer->training_type = $request->training_type;
        $trainer->address = $request->address;
        $trainer->password = Hash::make($request->password);

        $trainer->save();

        $trainer->syncRoles($request->role);

        return redirect()->route('trainer.index')->with('success', 'New Trainer is created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.trainers.show', compact(''));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trainer = User::findOrFail($id);

        $roles = Role::all();
        $old_roles = $trainer->roles->pluck('id')->toArray();
        return view('admin.trainers.edit', compact('trainer', 'roles', 'old_roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTrainerRequest $request, $id)
    {
        $trainer = User::findOrFail($id);
        $trainer->name = $request->name;
        $trainer->phone = $request->phone;
        $trainer->training_type = $request->training_type;
        $trainer->address = $request->address;

        $trainer->password = $request->password == null ? $trainer->password  : Hash::make($request->password) ;

        $trainer->update();
        $trainer->syncRoles($request->role);
        return redirect()->route('trainer.index')->with('success', 'Trainer is updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trainer = User::findOrFail($id);
        $trainer->delete();

        return 'success';
    }
}
