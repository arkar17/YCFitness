<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\TrainingGroup;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\TrainingUser;

class TrainingGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trainingGroup = TrainingGroup::with('user')->get()->groupBy('trainer_id');
        $trainingGroup = $trainingGroup->values();
        //  dd($trainingGroup->toArray());

        return view('admin.trainingcenter.index' ,compact('trainingGroup'));
    }


    public function ssd($traininggroup){
        $training_members = TrainingUser::where('training_group_id',$traininggroup)->with('user');

        // dd($training_members->toArray());
        return Datatables::of($training_members)
        ->addIndexColumn()
        ->addColumn('member_name', function($each){
            return $each->user->name;
        })
        ->addColumn('member_phone', function($each){
            return $each->user->phone;
        })
        ->addColumn('member_address', function($each){
            return $each->user->address;
        })
        ->addColumn('membertype', function($each){
            return $each->user->member_type;
        })
        ->addColumn('member_level', function($each){
            return $each->user->membertype_level;
        })
        ->addColumn('member_gender', function($each){
            return $each->user->gender;
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
        $members = Member::where('member_type','!=','Free')->where('member_type','!=','Platinum')->where('member_type','!=','Diamond')
        ->where('member_type','!=','Gym Member')->get();

        $groups=TrainingGroup::where('trainer_id',auth()->user()->id)->get();
        return view('admin.trainingcenter.create', compact('members', 'groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_type' => 'required',
            'group_name' => 'required',
            'group_type'=>'required',
            'member_type_level'=>'required',
            'gender'=>'required'
        ]);
        $training_group=New TrainingGroup();
        $training_group->trainer_id=auth()->user()->id;
        $training_group->member_type=$request->member_type;
        $training_group->group_name=$request->group_name;
        $training_group->group_type=$request->group_type;
        $training_group->member_type_level=$request->member_type_level;
        $training_group->gender=$request->gender;

        $training_group->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     * .
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $training_group = TrainingGroup::findOrFail($id);
        return view('admin.trainingcenter.viewdetail', compact('training_group'));
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
        $trainingGroup = TrainingGroup::findOrFail($id);
        $trainingGroup->delete();
        return 'success';
    }
}
