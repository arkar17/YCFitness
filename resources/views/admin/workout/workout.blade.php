@extends('layouts.app')
@section('workoutplan-active','active')

@section('content')
@if (Session::has('success'))
<div class="d-flex justify-content-center">
    <div class="alert alert-success alert-dismissible fade show " role="alert" style="width:300px;">
        <strong>{{Session::get('success')}}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif
<div class="container">

<div class="d-flex">
<h3 class="text-center mx-auto">Workouts</h3>
</div>
<a href="{{route('workout')}}" class="btn btn-sm btn-primary"><small>Create Workout</small></a>
    <div class="row my-3">
        @foreach ($workoutview as $workout)
        <div class="col-md-3">
            <div class="card shadow rounded text-capitalize">
                <div class="card-header">
                    {{$workout->workout_plan_type}}
                    <span class="bg-info text-white rounded-pill" style="font-size: 0.8rem; padding:3px; float: right;">{{$workout->day}}</span>
                </div>
                <div class="card-body" style="padding: 0;">
                        <div class="" width="100%">
                            {{-- <video width="100%" height="200px" controls>
                                Your browser does not support the video tag.
                            </video> --}}
                            <video id="videoElm" width="100%" height="100%" controls>
                                <source src="{{asset('/storage/upload/'.$workout->video)}}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        <div class="" style="padding: 0 0 10px 10px;">
                            <p class="card-text mb-1"> <b>Workout Name :</b> {{$workout->workout_name}}</p>
                            <p class="card-text mb-1"><b>Gender Type :</b> {{$workout->gender_type}}</p>
                            <p class="card-text mb-1"><b>Level :</b> {{$workout->workout_level}}</p>
                            <p class="card-text mb-1"><b>Member Type :</b> {{$workout->member_type}}</p>
                            <p class="card-text mb-1"><b>Place :</b> {{$workout->place}}</p>
                            <p class="card-text mb-1"><b>Burn Calories :</b> {{$workout->calories}} Calories</p>
                            <p class="card-text mb-1"><b>Estimate Time :</b> {{$workout->estimate_time}} minutes</p>
                            <p class="card-text mb-1"><b>Sets :</b> {{$workout->sets}} times</p>
                            <p class="card-text mb-1"><b>Category :</b> {{$workout->category}} </p>
                            <a href="{{route('workoutedit',[$workout->id])}}" class="btn btn-sm btn-primary">Edit</a>
                            <a href="{{route('workoutdelete',[$workout->id])}}" class="btn btn-sm btn-danger ms-2">Delete</a>
                        </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script>

    // $(document).ready(function(){
            // var videotag = document.querySelector("video");
            // var file = @json($workoutview);
            // // var arr=Object.entries(file);
            // var videofile = file[0];
            // console.log(videofile);
            // var blobURL = URL.createObjectURL(videofile);
            // videotag.src = blobURL.video;

    // })

        //   var blobURL = URL.createObjectURL(file);
        //   var video = document.querySelector("video");
        //   video.src = blobURL;

    // function playVideo() {

    //     var starttime = 10; // start at 0 seconds
    // var endtime = 15; // stop at 2 seconds
    // var video = document.getElementById('videoElm');


    //   video.addEventListener("timeupdate", function() {
    //     if (this.currentTime >= endtime) {
    //         this.pause();
    //         this.currentTime = 10;
    //         playVideo();

    //       //this.currentTime = 10; // change time index here
    //     }
    //   }, false);

    // //   video.load();
    // //   video.play();
    // }

    // playVideo();
    </script>



@endsection
