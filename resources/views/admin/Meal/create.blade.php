@extends('layouts.app')
@section('content')
    <div class="col-md-8 mx-auto">
        <div class="card shadow p-4">
            <h3 class="text-center mb-2">Create Meal</h3>
            <form action="{{ route('meal.store') }}" method="POST" id="create-meal">
                @csrf

                <div class="mb-4">
                    <label class="" for="meal_plan_id">Meal Plan Type</label> <br>
                    <select class="form-control " name="meal_plan_id" id="meal_plan_id">
                        {{-- @foreach($meal_plan_type as $meal_plan)
                        <option value="{{$meal_plan->id}}">{{$meal_plan->plan_name}}</option>
                        @endforeach --}}
                        <option value="Breakfast">Breakfast</option>
                        <option value="Lunch">Lunch</option>
                        <option value="Snack">Snack</option>
                        <option value="Dinner">Dinner</option>

                    </select>
                </div>

                <div class="mt-4">
                    <label for="name">Meal Name</label>
                    <input type="text" class="form-control" name="name">
                </div>

                {{-- <div class="mt-4">
                    <label for="name">Meal Time</label>
                    <input type="text" class="form-control" name="meal_time">
                </div>

                <div class="mt-4">
                    <label for="name">Day</label>
                    <input type="date" class="form-control" name="day">
                </div> --}}

                <div class="mt-4">
                    <label for="calories">Calories</label>
                    <input type="number" class="form-control" name="calories">
                </div>
                <div class="mt-4">
                    <label for="calories">Carbohydrates</label>
                    <input type="number" class="form-control" name="carbohydrates">
                </div>
                <div class="mt-4">
                    <label for="calories">Fat</label>
                    <input type="number" class="form-control" name="fat">
                </div>
                <div class="mt-4">
                    <label for="calories">Protein</label>
                    <input type="number" class="form-control" name="protein">
                </div>
                <div class="float-end mt-4">
                    <button type="submit" class="btn btn-primary" onclick="submitForm(this);">Confirm</button>
                    <a href="{{ route('meal.index') }}" class="btn btn-secondary">Cancel</a>

                </div>
            </form>
        </div>
    </div>

@endsection


@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\MealRequest', '#create-meal') !!}
@endpush
