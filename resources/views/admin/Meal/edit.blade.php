@extends('layouts.app')
@section('content')
    <h3 class="text-center pt-3 pb-2">Edit Meal</h3>
    <div class="col-md-8 mx-auto">
        <div class="card shadow p-4">
            <form action="{{ route('meal.update', $meal->id) }}" method="POST" id="edit-meal">
                @csrf
                @method('PUT')
                {{-- <div class="mb-4">
                    <label class="" for="meal_plan_id">Meal Plan Type</label> <br>
                    <select class="form-control " name="meal_plan_id" id="meal_plan_id">
                            <option value="Breakfast" @if ($meal->meal_plan_type == 'Breakfast') selected @endif>
                                Breakfast
                            </option>
                            <option value="Lunch" @if ($meal->meal_plan_type == 'Lunch') selected @endif>
                                Lunch
                            </option>
                            <option value="Snack" @if ($meal->meal_plan_type == 'Snack') selected @endif>
                                Snack
                            </option>
                            <option value="Dinner" @if ($meal->meal_plan_type == 'Dinner') selected @endif>
                                Dinner
                            </option>
                        </select>
                </div> --}}

                <div class="mt-4">
                    <label for="name">Meal Name</label>
                    <input type="text" class="form-control" name="name" value="{{$meal->name}}">
                </div>

                {{-- <div class="mt-4">
                    <label for="name">Meal Time</label>
                    <input type="text" class="form-control" value="{{$meal->meal_time}}" name="meal_time">
                </div>

                <div class="mt-4">
                    <label for="name">Day</label>
                    <input type="date" class="form-control" value="{{$meal->day}}" name="day">
                </div> --}}

                <div class="mt-4">
                    <label for="calories">Calories</label>
                    <input type="number" class="form-control" name="calories" value={{$meal->calories}}>
                </div>

                <div class="mt-4">
                    <label for="calories">Carbohydrates</label>
                    <input type="number" class="form-control" name="carbohydrates" value={{$meal->carbohydrates}}>
                </div>
                <div class="mt-4">
                    <label for="calories">Fat</label>
                    <input type="number" class="form-control" name="fat" value={{$meal->fat}}>
                </div>
                <div class="mt-4">
                    <label for="calories">Protein</label>
                    <input type="number" class="form-control" name="protein" value={{$meal->protein}}>
                </div>

                <div class="float-end mt-4">
                    <button type="submit" class="btn btn-primary" >Update</button>
                    <a href="{{ route('meal.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

@endsection


@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\MealRequest', '#edit-meal') !!}
@endpush
