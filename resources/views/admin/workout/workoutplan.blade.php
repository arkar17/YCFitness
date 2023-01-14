@extends('layouts.app')

@section('content')
@section('workoutplan-active','active')

<div class="container">
    {{-- create workout plan --}}
    <div class="d-flex justify-content-end"><button class="btn btn-primary create-workoutplan"><i
        class="fa-solid fa-circle-plus me-2 fa-lg align-middle"></i>Create Workout Plan</button></div>

    <div class="workout-plan-popup-parent-container">
        <div class="workout-plan-popup-container ">
            <div class="workout-plan-popup">
                <div class="workout-plan-popup-header">
                    <p>Create Workout</p>
                    <i class="fa-sharp fa-solid fa-xmark close-workoutplan" title="close"></i>
                </div>

                <form class="workout-plan-input-container" action="{{route('createworkout')}}" method = "POST" id="workplan-create" enctype="multipart/form-data">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="floatingPassword" placeholder="Workout Plan Type" name="plantype">
                        <label for="floatingPassword">Workout Plan Type</label>
                    </div>
                    <div class="workout-plan-input-btns-container">
                        <button type ="submit" class="btn btn-primary">Create</button>
                        {{-- <button class="referee-remark-cancel-btn">Cancel</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>





    <div class="row">
        @foreach ($workoutplan as $plan)

            <div class="col">

                <div class="container" style="margin-top: 50px;">

                    <!-- Button trigger modal -->
                    <input type="hidden" name="workoutplanId" value="{{$plan->id}}">
                    <h3 class="text-center text-capitalize">{{$plan->plan_type}} Plan</h3>
                    {{-- <p class="text-center">Monday OCT 3,2022</p> --}}

                    <div class="card mb-3 mx-auto shadow rounded" style="max-width: 540px;">
                    <div class="row g-0">
                        {{-- <div class="col-md-4">
                        <img src="{{asset('image/bodybuild.jpg')}}" class="float-start" alt="" style="width: 100px; height: 100px; border-radius: 50%; margin: 10px;">
                        </div> --}}
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Work out</h5>
                                    <a class="btn btn-sm btn-primary" href="{{route('editworkoutplan',[$plan->id])}}">Edit plan</a>
                                    <a class="btn btn-sm btn-danger" href="{{route('deleteworkoutplan',[$plan->id])}}">Delete</a>

                                <p class="text-muted">Core + Arms $ Legs work out.</p>
                                <p class="card-text"><small>35 mins</small></p>
                            </div>
                            <div class="d-flex justify-content-start mb-2">
                                <div class="ms-3">
                                    <a href="{{route('workout',[$plan->id])}}" class="btn btn-sm btn-primary"><small>Create Workout</small></a>
                                </div>
                                <div class="mx-3">
                                    <a href="{{route('workoutview')}}" class="btn btn-sm btn-primary"><small>View</small></a>
                                </div>
                            </div>
                        </div>


                    </div>
                    </div>


                </div>
            </div>
        @endforeach
    </div>
</div>



@endsection

@push('scripts')
{!! JsValidator::formRequest('App\Http\Requests\WorkoutplanRequest', '#workplan-create') !!}
<script>
    $(document).ready(function(){
       $(".create-workoutplan").click(function(){
            $(".workout-plan-popup-parent-container").show()
       })

       $(".close-workoutplan").click(function(){
            $(".workout-plan-popup-parent-container").hide()
       })

       $(".create-workout").click(function(){
            $(".referee-remark-popup-parent-container").show()
       })

       $(".close-workout").click(function(){
            $(".referee-remark-popup-parent-container").hide()
       })
   })

 </script>
  @endpush

