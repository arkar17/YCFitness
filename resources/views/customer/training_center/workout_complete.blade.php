@extends('customer.training_center.layouts.app')

@section('content')

    <a class="back-btn margin-top" href="{{ url()->previous() }}">
        <iconify-icon icon="bi:arrow-left" class="back-btn-icon"></iconify-icon>
    </a>

   <div class="customer-workout-completed-container">
    <div class="customer-workout-completed-header">
        <h1>Completed</h1>
        <span>You Rock!</span>
    </div>

    <div class="customer-workout-completed-details-container">
        <div class="customer-workout-completed-detail">
            <h1>{{$total_calories}}</h1>
            <span>Calories</span>
        </div>
        <div class="customer-workout-completed-detail">
            {{-- @if ($t_sum < 60)
            <h1>0:{{$sec}}</h1>
            @else
            <h1>{{$duration}}:{{$sec}}</h1>
            @endif --}}
            <h1>{{$total_time}}</h1>
            <span>Minutes</span>
        </div>
        <div class="customer-workout-completed-detail">
            <h1>{{$total_video}}</h1>
            <span>Exercises</span>
        </div>
    </div>
    <button class="customer-primary-btn customer-workout-completed-save-btn" id="save">Save And Continue</button>
   </div>

@endsection
@push('scripts')
<script>
    console.log( @json($tc_workouts));
     $(document).on('click', '#save', function(e) {

        let videoSource = new Array();

        var workout_id = []

        var tc_workout_video = @json($tc_workouts);

        for(var a = 0;a < tc_workout_video.length;a++){

            workout_id.push(@json($tc_workouts)[a].id);

        }



        var add_url = "{{ route('workout_complete.store') }}";
       // add_url = add_url.replace(':workout_id', workout_id);
        $.ajax({
                    type: "POST",
                    url: add_url,
                    datatype: "json",
                    headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    data:  {"workout_id":workout_id},
                    success: function(data) {
                        Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Great Job!',
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
                                        window.location.href = "{{ route('training_center.index')}}";

                                    }
                                })
                    }
        })

     });

</script>
@endpush
