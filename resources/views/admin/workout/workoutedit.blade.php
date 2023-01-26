@extends('layouts.app')
@section('workoutplan-active', 'active')

@section('content')

    <a href="{{ route('workoutview') }}" class="btn btn-sm btn-primary"><i class="fa-solid fa-arrow-left-long"></i>&nbsp;
        Back</a>

    <div class="container d-flex justify-content-center">


        <div class="card my-3 shadow rounded" style="max-width: 40%">
            <div class="card-header text-center">
                <h3>Edit Workouts</h3>
            </div>
            <div class="card-body">

                <form class="referee-remark-input-container" action="{{ route('workoutupdate', [$data->id]) }}"
                    enctype="multipart/form-data" method="POST">
                    @csrf

                    <div class="offset-1 col-md-10" class="previewvideo">
                        {{-- <video width="100%" height="200px" controls>
                            Your browser does not support the video tag.
                        </video> --}}
                        <video id="videoElm" width="100%" height="100%" controls>
                            <source src='https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/upload/{{ $data->video}}' type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>

                    <div class="row mb-3">
                        <div class="form-floating">
                            <select class="form-select" aria-label="Default select example"
                                placeholder="Select Workout Plan Type" name="plantype">
                                <option value="weight loss" id="weightloss">Weight Loss</option>
                                <option value="weight gain" id="weightgain">Weight Gain</option>
                                <option value="body beauty" id="bodybeauty">Body Beauty</option>
                            </select>
                            <label for="floatingInput">Select Workout Plan Type</label>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="form-floating col-md-6">
                            <input type="text" class="form-control" id="floatingInput" placeholder="Workout Name"
                                name="workoutname" value="{{ $data->workout_name }}">
                            <label for="floatingInput">Workout Name</label>
                        </div>
                        <div class="form-floating col-md-6">
                            <select class="form-select" aria-label="Default select example" placeholder="Member level"
                                name="memberType">
                                <option value="Platinum" id="platinum">Platinum</option>
                                <option value="Diamond" id="diamond">Diamond</option>
                                <option value="Gym Member" id="gymmerber">Gym Member</option>
                            </select>
                            <label for="floatingInput">Workout level select</label>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6 form-floating">
                            <input type="number" class="form-control" id="floatingPassword" placeholder="Calories"
                                name="calories" value="{{ $data->calories }}">
                            <label for="floatingPassword">Calories</label>
                        </div>
                        <div class="form-floating col-md-6">
                            <select class="form-select" aria-label="Default select example"
                                placeholder="Workout level select" name="workoutlevel">

                                <option value="beginner" id="beginner">Beginner</option>
                                <option value="advance" id="advance">Advance</option>
                                <option value="professional" id="professional">Professional</option>
                            </select>
                            <label for="floatingInput">Workout level select</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content mb-3">
                        <label for="">Gender Type : &nbsp;</label>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label"><input class="form-check-input" type="radio" name="gendertype"
                                    value="male" id="male">
                                Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label"><input class="form-check-input" type="radio" name="gendertype"
                                    id="female" value="female">Female</label>
                        </div>
                        <div class="form-check form-check-inline">

                            <label class="form-check-label" for="inlineRadio3"> <input class="form-check-input"
                                    type="radio" name="gendertype" id="both" value="both"> Both</label>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="form-floating col-md-6">
                            <select class="form-select" aria-label="Default select example" placeholder="Select workout day"
                                name="workoutday">
                                <option value=""></option>
                                <option value="Monday" id="Monday">Monday</option>
                                <option value="Tuesday" id="Tuesday">Tuesday</option>
                                <option value="Wednesday" id="Wednesday">Wednesday</option>
                                <option value="Thursday" id="Thursday">Thursday</option>
                                <option value="Friday" id="Friday">Friday</option>
                                <option value="Saturday" id="Saturday">Saturday</option>
                            </select>
                            <label for="floatingInput">Select Workout day</label>
                        </div>
                        <div class="form-floating col-md-6">
                            <select class="form-select" aria-label="Default select example"
                                placeholder="Select workout place" name="workoutplace">
                                <option value=""></option>
                                <option value="Gym" id="Gym">Gym</option>
                                <option value="Home" id="Home">Home</option>
                            </select>
                            <label for="floatingInput">Select Workout Place</label>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <input type="number" name="estimateTime" class="form-control"
                                    placeholder="Estimate time (minutes)" value="{{ $data->estimate_time }}">
                            </div>
                            <div class="col-md-6">
                                <input type="number" name="sets" class="form-control" placeholder="Sets"
                                    value="{{ $data->sets }}">
                            </div>
                        </div>
                    </div>


                    <div class="input-group mb-3">
                        <label class="input-group-text" for="inputGroupFile01">Upload photo</label>
                        <input type="file" class="form-control" id="inputGroupFile01" name="image">
                        <input type="input-group-text" value="{{ $data->image }}" disabled>
                    </div>

                    <div class="input-group mb-3">
                        <label class="input-group-text"> Upload video</label>
                        <input type="file" class="form-control" name="video" id="videoUpload">
                        <input type="input-group-text" value="{{ $data->video }}" disabled>
                        <input type="hidden" name="videoTime" value="" class="video-duration">
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" aria-label="Default select example" placeholder="Select Categories"
                            name="category">
                            <option value=""></option>
                            <option value="category1" id="category1">Category 1</option>
                            <option value="category2" id="category2">Category 2</option>
                            <option value="category3" id="category3">Category 3</option>
                            <option value="category4" id="category4">Category 4</option>
                            <option value="category5" id="category5">Category 5</option>
                            <option value="category6" id="category6">Category 6</option>
                            <option value="category7" id="category7">Category 7</option>
                            <option value="category8" id="category8">Category 8</option>
                            <option value="category9" id="category9">Category 9</option>
                            <option value="category10" id="category10">Category 10</option>
                        </select>
                        <label for="floatingInput">Select Categories</label>
                    </div>


                    <div class="referee-remark-input-btns-container">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('workoutview') }}" class="btn btn-secondary ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>



    </div>


@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var user = @json($data);

            if (user.workout_plan_type == 'weight loss') {
                var select = $("#weightloss");
                select.attr('selected', true);
            } else if (user.workout_plan_type == 'weight gain') {
                var select = $("#weightgain");
                select.attr('selected', true);
            } else if (user.workout_plan_type == 'body beauty') {
                var select = $("#bodybeauty");
                select.attr('selected', true);
            }

            if (user.gender_type == 'male') {
                $("#male").attr('checked', 'checked');
            } else if (user.gender_type == 'female') {
                $("#female").attr('checked', 'checked');
            } else {
                $("#both").attr('checked', 'checked');
            }

            if (user.member_type == 'Platinum') {
                $("#platinum").attr('selected', true);
            } else if (user.member_type == 'Diamond') {
                $("#diamond").attr('selected', true);
            } else {
                $('#gymmember').attr('selected', ture);
            }

            if (user.workout_level == 'beginner') {
                var select = $("#beginner");
                select.attr('selected', true);
            } else if (user.workout_level == 'advance') {
                var select = $("#advance");
                select.attr('selected', true);
            } else if (user.workout_level == 'professional') {
                var select = $("#professional");
                select.attr('selected', true);
            }
            if (user.day == 'Monday') {
                $("#Monday").attr('selected', true);
            } else if (user.day == 'Tuesday') {
                $("#Tuesday").attr('selected', true);
            } else if (user.day == 'Wednesday') {
                $("#Wednesday").attr('selected', true);
            } else if (user.day == 'Thursday') {
                $("#Thursday").attr('selected', true);
            } else if (user.day == 'Friday') {
                $("#Friday").attr('selected', true);
            } else if (user.day == 'Saturday') {
                $("#Saturday").attr('selected', true);
            }

            if (user.place == 'gym') {
                $("#Gym").attr('selected', true);
            } else {
                $("#Home").attr('selected', true);
            }

            if (user.category == 'category1') {
                $("#category1").attr('selected', true);
            } else if (user.category == 'category2') {
                $("#category2").attr('selected', true);
            } else if (user.category == 'category3') {
                $("#category3").attr('selected', true);
            } else if (user.category == 'category4') {
                $("#category4").attr('selected', true);
            } else if (user.category == 'category5') {
                $("#category5").attr('selected', true);
            } else if (user.category == 'category6') {
                $("#category6").attr('selected', true);
            } else if (user.category == 'category7') {
                $("#category7").attr('selected', true);
            } else if (user.category == 'category8') {
                $("#category8").attr('selected', true);
            } else if (user.category == 'category9') {
                $("#category9").attr('selected', true);
            } else if (user.category == 'category10') {
                $("#category10").attr('selected', true);
            }
        })

        document.getElementById("videoUpload")
            .onchange = function(event) {
                var file = event.target.files[0];
                console.log(file);
                var blobURL = URL.createObjectURL(file);
                var video = document.querySelector("video");
                video.src = blobURL;
                video.addEventListener('loadedmetadata', function() {
                    var minutes = Math.floor(video.duration / 60) % 60;
                    var seconds = Math.floor(video.duration % 60);
                    document.querySelector('.video-duration').value = minutes
                });

            }
    </script>
@endpush
