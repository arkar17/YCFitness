<?php

namespace App\Http\Controllers\Admin;

use App\Models\Meal;
use App\Models\Member;
use App\Models\MealPlan;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\MealPlanRequest;

class MealPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.MealPlan.index');
    }
    public function getmealplan()
    {
        $mealPlan = MealPlan::query()->with(['member']);
        $i = 1;
        return Datatables::of($mealPlan)
            ->addIndexColumn()
            ->addColumn('action', function ($each) {
                $edit_icon = '';
                $delete_icon = '';

                $edit_icon = '<a href=" ' . route('mealplan.edit', $each->id) . ' " class="text-success mx-1 " title="edit">
                <i class="fa-solid fa-edit fa-xl" data-id="' . $each->id . '"></i>
            </a>';
                $delete_icon = '<a href=" ' . route('mealplan.delete', $each->id) . ' " class="text-danger mx-1" id="delete" title="delete">
                <i class="fa-solid fa-trash fa-xl delete" data-id="' . $each->id . '"></i>
            </a>';

                return '<div class="d-flex justify-content-center">' . $edit_icon . $delete_icon . '</div>';
            })
            ->removeColumn('id')
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
        $member = Member::groupBy('member_type')->get();
        // dd($member);
        return view('admin.MealPlan.create', compact('member'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MealPlanRequest $request)
    {
        $mealPlan = new MealPlan();
        $mealPlan->member_type = $request->member_type;
        $mealPlan->plan_name = $request->meal_plan_name;
        $mealPlan->save();
        //dd($mealPlan);
        return redirect()->route('mealplan.index')->with('success', 'New Meal Plan is created successfully!');
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
        $member = Member::groupBy('member_type')->get();
        // $mealplan = MealPlan::where('meal_plan_id',$id)->first();
        $mealplan = MealPlan::findOrFail($id);
        return view('admin.MealPlan.edit', compact('mealplan', 'member'));
    }


    public function update(MealPlanRequest $request, $id)
    {
        // dd("df");
        $mealPlan_update = MealPlan::findOrFail($id);
        $mealPlan_update->member_type = $request->member_type;
        $mealPlan_update->plan_name = $request->meal_plan_name;
        $mealPlan_update->update();
        return redirect()->route('mealplan.index')->with('success', 'Meal Plan is updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $meal_delete = Meal::where('meal_plan_id', $id);
        $meal_delete->delete();
        $mealPlan_delete = MealPlan::findOrFail($id);
        $mealPlan_delete->delete();
        return redirect()->route('mealplan.index')->with('success', 'Meal Plan is Deleted successfully!');
    }
}
