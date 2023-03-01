@extends('customer.training_center.layouts.app')

@section('content')
<a class="back-btn margin-top" href="{{ route('training_center.workout_plan') }}">
    <iconify-icon icon="bi:arrow-left" class="back-btn-icon"></iconify-icon>
</a>

    <div class="customer-workout-plan-header-container">
        <h1>Get Lean At Home</h1>
        <div class="customer-workout-plan-header-details-container">


            <div class="customer-workout-plan-header-detail-container">
                <iconify-icon icon="fluent-emoji-flat:fire" class="customer-workout-plan-detail-icon"></iconify-icon>
                <p>Calories : <span>{{$c_sum}}</span></p>
            </div>
            <div class="customer-workout-plan-header-detail-container">
                <iconify-icon icon="noto:alarm-clock" class="customer-workout-plan-detail-icon"></iconify-icon>
                <p>Minutes : <span>{{$t_sum}} Mins</span>
                    {{-- @if ($time_sum < 60)
                    <span>0:{{$sec}}</span>
                    @else
                    <span>{{$duration}}:{{$t_sum}}</span>
                    @endif --}}
            </div>
        </div>

        <div class="customer-workout-video-parent-container">
            <div class="customer-workout-video" >
                <video id="workoutVideo" controls>
                    <!-- <source src="../imgs/Y2Mate.is - 8 Best Bicep Exercises at Gym for Bigger Arms-3pm_L-H3Th4-720p-1655925997409.mp4" type="video/mp4"> -->
                </video>
            </div>

            <div class="customer-workout-video-progress">
                <!-- <div class="completed-workout"></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div> -->
            </div>

            <div class="customer-workout-btns-container">
                <button class="customer-workout-prev-btn">
                    <iconify-icon icon="material-symbols:skip-next" rotate="180deg" class="customer-workout-prev-icon"></iconify-icon>
                </button>
                <button style="display: none;" class="customer-workout-pause-btn">
                    <iconify-icon icon="ant-design:pause-circle-outlined" class="customer-workout-pause-icon"></iconify-icon>
                </button>
                <button  class="customer-workout-play-btn">
                    <iconify-icon icon="akar-icons:play" class="customer-workout-play-icon"></iconify-icon>
                </button>
                <button class="customer-workout-next-btn">
                    <iconify-icon icon="material-symbols:skip-next"  class="customer-workout-next-icon"></iconify-icon>
                </button>
            </div>




            <h1 class="customer-workout-name"></h1>

            <p class="customer-workout-counter">
                <span class="customer-workout-min">00 :</span>
                <span class="customer-workout-sec">00</span></p>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        // $(".customer-workout-next-btn").click(function(){
        //     console.log("hello")
        // })

        $(document).ready(function(){


            $("#workoutVideo").on(
                "timeupdate",
                function(event){
                onTrackedVideoFrame(this.currentTime, this.duration);
            });

            $("#workoutVideo").on("play",function(){
                $(".customer-workout-play-btn").hide();
                $(".customer-workout-pause-btn").show();
            })

            $("#workoutVideo").on("pause",function(){
                $(".customer-workout-play-btn").show();
                $(".customer-workout-pause-btn").hide();
            })



            $(".customer-workout-play-btn").click(function(){
                $('#workoutVideo').trigger('play');
                $(".customer-workout-play-btn").hide()
                $(".customer-workout-pause-btn").show()
            })

            $(".customer-workout-pause-btn").click(function(){
                $('#workoutVideo').trigger('pause');
                $(".customer-workout-pause-btn").hide()
                $(".customer-workout-play-btn").show()
            })
        });


        function onTrackedVideoFrame(currentTime, duration){
            const counter = parseInt(duration) - parseInt(currentTime)
            if(duration){
                const mins = Math.floor(counter/60)
                const secs = Math.ceil(counter % 60)
                // var counterText
                // if(mins < 10 && secs < 10)
                const minText = mins < 10 ? `0${mins}` : `${mins}`
                const secText = secs < 10 ? `0${secs}` : `${secs}`
                $(".customer-workout-counter").text(`${minText} : ${secText}`); //Change #current to currentTime
            }else{
                $(".customer-workout-counter").text("00 : 00")
            }

        }

        let videoSource = new Array();

        var workout_id = []
        let videoDuration=0;
        let calories=0;
        let t_sum=0;
        let cal_sum=0;

        var tc_workout_video = @json($tc_workouts);
        videoSource=tc_workout_video;

        for(var a = 0;a < videoSource.length;a++){

            videoDuration=@json($tc_workouts)[a].estimate_time;
            calories=@json($tc_workouts)[a].calories;
            t_sum+=videoDuration;
            cal_sum+=calories;
            videoSource[a] = 'https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/upload/'+videoSource[a].video;
            workout_id.push(@json($tc_workouts)[a].id);

        }

        // videoSource[0] = 'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4';
        // videoSource[1] = 'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4';
        let i = 0; // global
        if(i <= 0){
            $(".customer-workout-prev-btn").attr("disabled",true)
        }
        const videoCount = videoSource.length;
        const element = document.getElementById("workoutVideo");

        for(var k = 0;k < videoSource.length;k++){
            $(".customer-workout-video-progress").append(`<div></div>`)
        }


        // document.getElementById('workoutVideo').addEventListener('ended', myHandler, false);
        // document.querySelector('.customer-workout-next-btn').addEventListener('click', myHandler(i+1));
        $(".customer-workout-next-btn").click(function(){
            i++

            if(i <= 0){
                $(".customer-workout-prev-btn").attr("disabled",true)
            }else{
                $(".customer-workout-prev-btn").attr("disabled",false)
            }
            // console.log($(".customer-workout-prev-btn").attr("disabled"))
            myHandler()

        })

        $(".customer-workout-prev-btn").click(function(){
            i--
            console.log(i)
            if(i <= 0){
                $(".customer-workout-prev-btn").attr("disabled",true)
            }else{
                $(".customer-workout-prev-btn").attr("disabled",false)
            }

            // console.log($(".customer-workout-prev-btn").attr("disabled"))
            myHandler()

        })

        videoPlay(0); // load the first video
        // ensureVideoPlays(); // play the video automatically
        function myHandler() {
            // i++;
            // console.log(i)
            if (i == videoCount) {
                console.log(workout_id);
                Swal.fire({
                                    icon: 'success',
                                    title: 'Done',
                                    text: 'Workout session ended',
                                    timer: 3000,
                                    timerProgressBar: true,
                                    showClass: {
                                        popup: 'animate__animated animate__fadeInDown'
                                    },
                                    hideClass: {
                                        popup: 'animate__animated animate__fadeOutUp'
                                    }
                                    }).then(okay => {
                                    if (okay) {
                                        window.location.href = 'workout_complete/'+t_sum+'/'+cal_sum+'/'+videoSource.length;
                                    }
                                })
                    //window.location.href = "{{ route('workout_complete',"sum")}}";
            }
            else {
                videoPlay(i);
            }
        }

        function videoPlay(videoNum) {
            element.setAttribute("src", videoSource[videoNum]);
            // element.autoplay = true;
            element.load();
            // console.log(element)
            $(".customer-workout-video-progress div")[i].classList.add("completed-workout")
        }

            // function ensureVideoPlays() {
            //     const video = document.getElementById('workoutVideo');

            //     if(!video) return;

            //     const promise = video.play();
            //     if(promise !== undefined){
            //         promise.then(() => {
            //             // Autoplay started
            //         }).catch(error => {
            //             // Autoplay was prevented.
            //             video.muted = true;
            //             video.play();
            //         });
            //     }
            // }
    </script>
@endpush

