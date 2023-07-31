<?php

namespace App\Http\Controllers\Admin;

//use DataTables;
use App\Models\Meal;
use App\Models\MealPlan;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\MealRequest;
use Illuminate\Support\Facades\Validator;

class MealController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
        return view('admin.Meal.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $meal_plan_type = MealPlan::All();
        // dd($member);
        return view('admin.Meal.create',compact('meal_plan_type'));
    }
    public function getMeal() {
        $meal = Meal::query()->with(['meal_plans']);
        return Datatables::of($meal)
        ->addIndexColumn()
        ->addColumn('action', function ($each) {
            $edit_icon = '';
            $delete_icon = '';

            $edit_icon = '<a href=" ' . route('meal.edit', $each->id) . ' " class="text-success mx-1 " title="edit">
            <i class="fa-solid fa-edit fa-xl" data-id="' . $each->id . '"></i>
        </a>';
            $delete_icon = '<a href=" ' . route('meal.destroy', $each->id) . ' " class="text-danger mx-1" id="delete" title="delete">
            <i class="fa-solid fa-trash fa-xl delete-btn" data-id="' . $each->id . '"></i>
        </a>';

            return '<div class="d-flex justify-content-center">' . $edit_icon . $delete_icon. '</div>';
        })
        ->make(true);
            //    ->make(true);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MealRequest $request)
    {
        $meal_create = new Meal();
        $meal_create->name = $request->name;
        // $meal_create->gender = $request->gender;
        $meal_create->calories = $request->calories;
        //$meal_create->meal_plan_type = $request->meal_plan_id;
        $meal_create->carbohydrates = $request->carbohydrates;
        $meal_create->protein = $request->protein;
        $meal_create->fat = $request->fat;
        // $meal_create->meal_time = $request->meal_time;
        // $meal_create->day = $request->day;
        $meal_create->save();
        return redirect()->route('meal.index')->with('success', 'New Meal is created successfully!');
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
        // dd($id);
        $meal_plan_type = MealPlan::All();

        $meal = Meal::findOrFail($id);
        // dd($meal);
        return view('admin.Meal.edit', compact('meal','meal_plan_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MealRequest $request, $id)
    {
        //
        $meal_update = Meal::findOrFail($id);
        $meal_update->name = $request->name;
        // $meal_update->gender = $request->gender;
        $meal_update->calories = $request->calories;
        // $meal_update->meal_plan_type = $request->meal_plan_id;
        $meal_update->carbohydrates = $request->carbohydrates;
        $meal_update->protein = $request->protein;
        $meal_update->fat = $request->fat;
        $meal_update->update();
        return redirect()->route('meal.index')->with('success', 'Meal is updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $meal_delete=Meal::findOrFail($id);
        $meal_delete->delete();
        return 'success';
    }
}
