@extends('layouts.app')
@section('content')
    <div class="col-md-8 mx-auto">
        <div class="card shadow p-4">
            <h3 class="text-center mb-2">Create Meal Plan</h3>
            <form action="{{ route('mealplan.store') }}" method="POST" id="create-mealplan">
                @csrf
                <div class="mb-4">
                    <label class="" for="member_type">Member Type</label> <br>
                    <select class="form-control " name="member_type" id="member_type">
                        @foreach($member as $memb)
                        <option value="{{$memb->member_type}}">{{$memb->member_type}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-4">
                    <label for="name">Meal Plan Name</label>
                    <input type="text" class="form-control" name="meal_plan_name">
                </div>

                <div class="float-end mt-4">
                    <button type="submit" class="btn btn-primary" onclick="submitForm(this);">Confirm</button>
                    <a href="{{ route('mealplan.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    {{-- <script>
        function submitForm(btn) {
            // disable the button
            btn.disabled = true;
            // submit the form
            btn.form.submit();
        }
    </script> --}}
@endsection


@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\MealPlanRequest', '#create-mealplan') !!}
@endpush
