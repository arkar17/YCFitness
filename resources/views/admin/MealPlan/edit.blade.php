@extends('layouts.app')
@section('content')
    <h3 class="text-center pt-3 pb-2">Edit Meal Plan</h3>
    <div class="col-md-8 mx-auto">
        <div class="card shadow p-4">
            <form action="{{ route('mealplan.update', $mealplan->id) }}" method="POST" id="create-mealplan">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="" for="member_type_id">Member Type</label> <br>
                    <select class="form-control " name="member_type_id" id="member_type_id">
                        @foreach($member as $memb)
                        <option value="{{$memb->member_type}}" {{ $memb->member_type == $mealplan->member_type_id ? "selected":"" }}>{{$memb->member_type}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <label for="name">Meal Plan Name</label>
                    <input type="text" class="form-control" name="meal_plan_name"  value = "{{$mealplan->plan_name}}">
                </div>



                <div class="float-end mt-4">
                    <button type="submit" class="btn btn-primary" >Update</button>
                    <a href="{{ route('mealplan.index') }}" class="btn btn-secondary">Cancel</a>

                </div>
            </form>
        </div>
    </div>

@endsection


@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\MealPlanRequest', '#create-mealplan') !!}
@endpush
