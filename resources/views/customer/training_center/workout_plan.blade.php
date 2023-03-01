@extends('customer.training_center.layouts.app')

@section('content')

<a class="back-btn margin-top" href="{{route('training_center.index')}}">
    <iconify-icon icon="bi:arrow-left" class="back-btn-icon"></iconify-icon>
</a>
<div class="customer-workout-plan-place-container">
    <p class="customer-workout-plan-place-btn customer-workout-plan-home-btn" >
        {{__('msg.home1')}}
    </p>
    <p class="customer-workout-plan-place-btn customer-workout-plan-gym-btn">
        {{__('msg.gym')}}
    </p>
</div>
<div class="customer-workout-plan-home">
    <div class="customer-workout-plan-header-container">
        <h1>{{__('msg.get lean at home')}}</h1>
        <div class="customer-workout-plan-header-details-container">


            <div class="customer-workout-plan-header-detail-container">
                <iconify-icon icon="fluent-emoji-flat:fire" class="customer-workout-plan-detail-icon"></iconify-icon>
                <p>{{__('msg.calories')}} : <span>{{$c_sum_home}}</span></p>
            </div>
            <div class="customer-workout-plan-header-detail-container">
                <iconify-icon icon="noto:alarm-clock" class="customer-workout-plan-detail-icon"></iconify-icon>
                <p>{{__('msg.minutes')}} : <span>{{$time_sum_home}} Mins</span>
                </p>
            </div>
        </div>

        <a href="{{url('customer/training_center/workout/home')}}" class="customer-primary-btn customer-workout-letsgo-btn">
           {{__('msg.let\'s go')}}
        </a>

    </div>
    <div class="customer-workout-plan-details-parent-container">
        <div class="customer-workout-plan-details-equipment-container">
            <h1>{{__('msg.equipment')}}</h1>
            <div class="customer-workout-plan-equipments-container">
                <div class="customer-workout-plan-equipment-container">
                    <img src="{{asset('image/icons8-yoga-mat-96 (1).png')}}">
                    <p>{{__('msg.yoga mat')}}</p>
                </div>
                <div class="customer-workout-plan-equipment-container">
                    <img src="{{asset('image/icons8-bench-press-96.png')}}">
                    <p>{{__('msg.bench press')}}</p>
                </div>
                <div class="customer-workout-plan-equipment-container">
                    <img src="{{asset('image/icons8-dumbbell-64.png')}}">
                    <p>{{__('msg.dumbbells')}}</p>
                </div>
            </div>
        </div>

        <div class="customer-workout-plan-details-workouts-container">
            <h1>{{__('msg.workouts')}}</h1>
            <div class="customer-workout-plan-workouts-container">
                @forelse ($tc_home_workoutplans as $workout)
                    <div class="customer-workout-plan-workout-container">
                        <div class="customer-workout-plan-video-container">
                            <video>
                                <source src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/upload/{{$workout->video}}" type="video/mp4">
                            </video>
                        </div>

                        <div class="customer-workout-plan-name">
                            <p>{{$workout->workout_name}}</p>
                            <?php
                            if ($workout->time < 60) {
                                $sec=$workout->time;
                            }else {
                                $duration=floor($workout->time/60);
                                $sec=$workout->time%60;
                            }
                            ?>
                            @if ($workout->time < 60)
                            <span>0:{{$sec}}</span>
                            @else
                            <span>{{$duration}}:{{$sec}}</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-secondary p-1">No Video Found</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
<div class="customer-workout-plan-gym">
    <div class="customer-workout-plan-header-container">
        <h1>{{__('msg.get lean at gym')}}</h1>
        <div class="customer-workout-plan-header-details-container">


            <div class="customer-workout-plan-header-detail-container">
                <iconify-icon icon="fluent-emoji-flat:fire" class="customer-workout-plan-detail-icon"></iconify-icon>
                <p>{{__('msg.calories')}} : <span>{{$c_sum}}</span></p>
            </div>
            <div class="customer-workout-plan-header-detail-container">
                <iconify-icon icon="noto:alarm-clock" class="customer-workout-plan-detail-icon"></iconify-icon>
                <p>{{__('msg.minutes')}} : <span>{{$time_sum}} Mins</span>
                    {{-- @if ($time_sum < 60)
                    <span>0:{{$sec}}</span>
                    @elseif ($time_sum >= 60)
                    <span>{{$duration}}:{{$sec}}</span>
                    @endif --}}
                </p>
            </div>
        </div>

        <a href="{{route('training_center.workout.gym')}}" class="customer-primary-btn customer-workout-letsgo-btn">
           {{__('msg.let\'s go')}}
        </a>

    </div>
    <div class="customer-workout-plan-details-parent-container">
        <div class="customer-workout-plan-details-equipment-container">
            <h1>{{__('msg.equipment')}}</h1>
            <div class="customer-workout-plan-equipments-container">
                <div class="customer-workout-plan-equipment-container">
                    <img src="{{asset('image/icons8-yoga-mat-96 (1).png')}}">
                    <p>{{__('msg.yoga mat')}}</p>
                </div>
                <div class="customer-workout-plan-equipment-container">
                    <img src="{{asset('image/icons8-bench-press-96.png')}}">
                    <p>{{__('msg.bench press')}}</p>
                </div>
                <div class="customer-workout-plan-equipment-container">
                    <img src="{{asset('image/icons8-dumbbell-64.png')}}">
                    <p>{{__('msg.dumbbells')}}</p>
                </div>
            </div>
        </div>

        <div class="customer-workout-plan-details-workouts-container">
            <h1>{{__('msg.workouts')}}</h1>
            <div class="customer-workout-plan-workouts-container">
                @forelse ($tc_gym_workoutplans as $workout)
                <div class="customer-workout-plan-workout-container">
                    <div class="customer-workout-plan-video-container">
                        <video>
                            <source src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/upload/{{$workout->video}}" type="video/mp4">
                        </video>


                       

                    </div>

                    <div class="customer-workout-plan-name">
                        <p>{{$workout->workout_name}}</p>
                        <?php
                            if ($workout->time < 60) {
                                $sec=$workout->time;
                            }else {
                                $duration=floor($workout->time/60);
                                $sec=$workout->time%60;
                            }
                        ?>
                        @if ($workout->time < 60)
                        <span>0:{{$sec}}</span>
                        @else
                        <span>{{$duration}}:{{$sec}}</span>
                        @endif
                    </div>
                </div>
                @empty
                    <p class="text-secondary p-1">No Video Found</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    $(".customer-workout-plan-home").show()
    $(".customer-workout-plan-gym").hide()

    $(".customer-workout-plan-home-btn").addClass("active-place")
    $(".customer-workout-plan-gym-btn").removeClass("active-place")

    $(".customer-workout-plan-home-btn").click(function(){
        $(".customer-workout-plan-home").show()
        $(".customer-workout-plan-gym").hide()

        $(".customer-workout-plan-home-btn").addClass("active-place")
        $(".customer-workout-plan-gym-btn").removeClass("active-place")

    })

    $(".customer-workout-plan-gym-btn").click(function(){
        $(".customer-workout-plan-gym").show()
        $(".customer-workout-plan-home").hide()

        $(".customer-workout-plan-home-btn").removeClass("active-place")
        $(".customer-workout-plan-gym-btn").addClass("active-place")

    })

</script>
@endpush
