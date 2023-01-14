<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Member;
use App\Models\MealPlan;
use Illuminate\Http\Request;
use App\Models\MemberHistory;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use GrahamCampbell\ResultType\Success;
use Spatie\Permission\Models\Permission;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.member.index');
    }


    public function ssd() {
        $members = Member::query()->where('member_type','!=','Free');
        return Datatables::of($members)
        ->addIndexColumn()
        ->addColumn('action', function ($each) {
            $edit_icon = '';
            $delete_icon = '';

            $edit_icon = '<a href=" ' . route('member.edit', $each->id) . ' " class="text-success mx-1 " title="edit">
                        <i class="fa-solid fa-edit fa-xl" data-id="' . $each->id . '"></i>
                    </a>';
            $delete_icon = '<a href=" ' . route('member.destroy', $each->id) . ' " class="text-danger mx-1" id="delete" title="delete">
                        <i class="fa-solid fa-trash fa-xl delete" data-id="' . $each->id . '"></i>
                    </a>';

                        return '<div class="d-flex justify-content-center">' . $edit_icon . $delete_icon. '</div>';
                    })
               ->rawColumns(['action'])
               ->make(true);
    }

    public function user_member_ssd(Request $request ) {

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $members = User::query()
                    ->where('member_type','!=','Free')
                    ->where('active_status',2);
            if($startDate && $endDate) {
                $members->whereBetween('from_date', array($request->start_date, $request->end_date));
            }
        return Datatables::of($members)
        ->addIndexColumn()
        ->addColumn('action', function ($each) {
            $delete_icon = '';
            $edit_icon = '';
            $edit_icon = '<a href=" ' . route('member.user_member.edit', $each->id) . ' " class="text-success mx-1 " title="edit">
                        <i class="fa-solid fa-edit fa-xl" data-id="' . $each->id . '"></i>
                    </a>';
            $delete_icon = '<a href=" ' . route('user_member.ban', $each->id) . ' " class="text-danger mx-1" id="delete" title="ban">
                        <i class="fa-solid fa-xl fa-ban" data-id="' . $each->id . '"></i>
                    </a>';
                    return '<div class="d-flex justify-content-center">' . $edit_icon . $delete_icon. '</div>';
                    })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function user_member_decline_ssd(Request $request) {

        $startDate = $request->start_date_declined;
        $endDate = $request->end_date_declined;

        $users = User::query()
                    ->where('member_type','=','Free')
                    ->where('active_status',0);
        if($startDate && $endDate) {
            $users->whereBetween('from_date', array($request->start_date_declined, $request->end_date_declined));
        }

        return Datatables::of($users)
        ->addIndexColumn()
        ->addColumn('action', function ($each) {
            $delete_icon = '';
            $delete_icon = '<a href=" ' . route('user_member.destroy', $each->id) . ' " class="text-danger mx-1" id="delete" title="delete">
                        <i class="fa-solid fa-trash fa-xl delete" data-id="' . $each->id . '"></i>
                    </a>';
                    return '<div class="d-flex justify-content-center">' . $delete_icon . '</div>';
                    })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function create()
    {
        $roles=Role::all();
        return view('admin.member.create',compact('roles'));
    }

    public function user_member_show()
    {
        return view('admin.member.user_member_show');
    }

    public function user_member_edit($id)
    {
        $members=Member::all();
        $user=User::findOrFail($id);
        $user_member_history=MemberHistory::where('user_id',$id)->first();
        //$user_member_id=$user_member_history->member_id;
        return view('admin.member.user_member_edit',compact('members','user'));
    }


    public function store(Request $request)
    {
      //  dd($request->role_id);
       $member_store=New Member();
       $member_store->member_type=$request->member_type;
       $member_store->duration=$request->duration;
       $member_store->price=$request->price;
       $member_store->pros=$request->pros;
       $member_store->cons=$request->cons;
       $member_store->role_id=$request->role_id;
       $member_store->save();
       return redirect()->route('member.index')->with('success', 'New Member Type is created successfully!');
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


    public function edit($id)
    {
        $member_edit = Member::findOrFail($id);
        $roles=Role::where('name','!=','Free')->get();
        return view('admin.member.edit',compact('member_edit','roles'));
    }


    public function update(Request $request, $id)
    {
        $member_update=Member::findOrFail($id);
        $member_update->member_type=$request->member_type;
        $member_update->duration=$request->duration;
        $member_update->pros=$request->pros;
        $member_update->cons=$request->cons;
        $member_update->role_id=$request->role_id;
        $member_update->price=$request->price;
        $member_update->update();
        return redirect()->route('member.index')->with('success', 'Member Type is updated successfully!');
    }


    public function destroy($id)
    {
        $member=Member::findOrFail($id);
        $member->delete();
        return redirect()->route('member.index')->with('success', 'Member is Deleted successfully!');
    }

    public function user_member_destroy($id)
    {
        $user=User::findOrFail($id);
        $user->delete();
        $user->roles()->detach();
        return redirect()->back()->with('success', 'User is Deleted successfully!');
    }

    public function user_member_ban($id)
    {
        $user=User::findOrFail($id);
        $user->active_status=3;
        $user->update();
        return redirect()->back()->with('success', 'User is Ban successfully!');
    }

    public function user_member_update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|min:6|max:11',
            'email'=>'required|email',
            'address' => 'required',
            'height' => 'required',
            'age' => 'required',
            'weight' => 'required',
            'ideal_weight' => 'required',
            'gender' => 'required',
            'daily_life' => 'required',
            'diet_type' => 'required',
            'neck' => 'required',
            'waist' => 'required',
            'hip' => 'required',
            'shoulders' => 'required',
            'average_night' => 'required',
            'hydration' => 'required',
            'activities' => 'required',
            'physical_activity' => 'required',
            'goal' => 'required',
            'energy_level' => 'required',
            'body_type' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
        $member = Member::where('id',$request->member_type)->first();
        $role=Role::findOrFail($member->role_id);
        $user=User::findOrFail($id);
        $user->name=$request->name;
        $user->phone=$request->phone;
        $user->email=$request->email;
        $user->membertype_level=$request->membertype_level;
        $user->member_type=$member->member_type;
        $user->address=$request->address;
        $user->height =$request->height;
        $user->weight=$request->weight;
        $user->ideal_weight=$request->ideal_weight;
        $user->age =$request->age;
        $user->gender =$request->gender;
        $user->daily_life =$request->daily_life;
        $user->diet_type =$request->diet_type;
        $user->neck=$request->neck;
        $user->waist=$request->waist;
        $user->hip=$request->hip;
        $user->shoulders=$request->shoulders;
        $user->average_night=$request->average_night;
        $user->hydration=$request->hydration;
        $user->activities=$request->activities;
        $user->physical_activity=$request->physical_activity;
        $user->goal=$request->goal;
        $user->energy_level=$request->energy_level;
        $user->body_type=$request->body_type;
        $user->bmi=$request->bmi;
        $user->bfp=$request->bfp;
        $user->bmr=$request->bmr;
        $user->from_date=$request->from_date;
        $user->to_date=$request->to_date;

        $user->bad_habits=json_encode($request->bad_habits);
        $user->most_attention_areas=json_encode($request->most_attention_areas);
        $user->physical_limitation=json_encode($request->physical_limitation);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($role->name);
        $user->update();
        $user->members()->attach($member->id, ['member_type_level' => $request->membertype_level]);
        return redirect()->route('member.user_member')->with('success','Member Updated Successfully');
    }
}
