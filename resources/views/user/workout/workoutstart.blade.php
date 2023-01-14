@extends('layouts.app')

@section('content')

@if (Session::has('error'))
<p class="text-danger"> {{Session::get('error')}} </p>
@else

    @if ($checkuser != null)
        @foreach ($checkuser as $workoutuser)
            <div class="container" style="margin-top: 50px; max-width: 1000px;">
                <h3 class="text-center">Get Learn At Home</h3>
                <div class="d-flex flex-row justify-content-center">
                <div class="mx-3">
                    <i class="fa-solid fa-burger"></i>
                    {{-- <span>Calories:{{$workoutuser->calories}}</span> --}}
                </div>
                <div class="mx-3">
                    <i class="fa-regular fa-clock"></i>
                    {{-- <span>Minutes:{{$workoutuser->time}}</span> --}}
                </div>
                </div>
                <button class="btn btn-dark">Let's Go</button>
                <div class="card mb-3 mx-auto">
                <h4 class="mx-3">Equipments</h4>
                <div class="row g-0">
                    <div class="col-md-3 my-3" style="margin-left: 20px;">
                        <img src="gym1.jpg" class=" float-start" alt="" style="width: 100px; height: 100px; margin-right: 10px; border-radius: 50%;">
                        <p class="card-text my-5">Training 1</p>
                    </div>
                </div>

                <div class="row g-0">
                    <div class="col-md-3 my-3" style="margin-left: 20px;">
                        <img src="gym1.jpg" class=" float-start" alt="" style="width: 100px; height: 100px; margin-right: 10px; border-radius: 50%;">
                        <p class="card-text my-5">Training 1</p>
                    </div>
                </div>

                <div class="row g-0">
                    <div class="col-md-3 my-3" style="margin-left: 20px;">
                        <img src="gym1.jpg" class=" float-start" alt="" style="width: 100px; height: 100px; margin-right: 10px; border-radius: 50%;">
                        <p class="card-text my-5">Training 1</p>
                    </div>
                </div>
                <h4 class="mx-3">Workouts</h4>
                <div class="row">
                    <div class="col-md-6 my-3" style="margin-left: 20px;">
                        <div class="row">
                                <div class="col-md-7" style="border: solid 2px black;">
                                    {{-- @for ($i=0; $i<=count($videogrouped[$workoutuser->workout_plan_id])-1; $i++)
                                    <p>{{{ $videogrouped[$workoutuser->workout_plan_id][$i] }}}</p><br>
                                    @endfor --}}

                                    <video width="100%" height="100%" controls>
                                        <source src="{{asset('/upload/'.$workoutuser->video)}}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                <div class="col-md-4">
                                    <p class="card-text">{{$workoutuser->workout_name}}</p>
                                    <small class="text-muted video-duration"> </small>
                                </div>
                            </div>
                    </div>
                </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="container d-flex justify-content-center">
            <p class="bg-danger text-white text-center p-2 rounded my-5">Your member type is can not use this section.</p>
        </div>
    @endif

@endif

<script>

    var videoduration = document.querySelector('.video-duration');

    var videolength = document.querySelector('video');
    videolength.addEventListener('loadedmetadata', function () {
	var minutes = Math.floor(videolength.duration / 60) %60;
	var seconds = Math.floor(videolength.duration % 60);
	videoduration.innerHTML = "Duration : " + minutes +" : "+ seconds +" Minutes"
	});

</script>

@endsection
