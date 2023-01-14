@extends('layouts.app')

@section('content')

<div class="container">
    <form class="workout-plan-input-container" action="{{route('updateworkoutplan',[$workoutplanEdit->id])}}" method = "POST">
        @csrf
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingPassword" placeholder="Workout Plan Type" name="plantype" value="{{$workoutplanEdit->plan_type}}" required>
            <label for="floatingPassword">Workout Plan Type</label>
        </div>
        <div class="workout-plan-input-btns-container">
            <button type ="submit" class="btn btn-primary">Update</button>
            <a class="btn btn-secondary" href="{{route('workoutplane')}}">Cancel</a>
        </div>
    </form>
</div>
@endsection
