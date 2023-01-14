@extends('customer.training_center.layouts.app')

@section('content')

<a class="back-btn margin-top" href="{{route('training_center.index')}}">
    <iconify-icon icon="bi:arrow-left" class="back-btn-icon"></iconify-icon>
</a>

{{-- <div class="card-chart">
    <div class="card-donut card-goalchart" data-size="300" data-thickness="18"></div>
    <div class="card-center">
      <span class="card-value">0</span>
      <div class="card-label">of 92 oz</div>
    </div>

</div>

<div class="customer-water-track-details-container">
    <div class="customer-water-track-intake-container">
        <span>Total Water Intake</span>
        <p>5 oz</p>
    </div>
    <div class="customer-water-track-days-container">
        <span>Days that reached goal</span>
        <p>0</p>
    </div>
</div>

<button class="customer-primary-btn customer-water-track-btn">Drink 5 oz</button> --}}
<div class="customer-water-tracker-container">
    <div class="customer-water-tracker-total-container">
        @if(empty($water))
            <h1 class="drinked_water">0</h1>
            @else
            <h1 class="drinked_water">{{$water->update_water}}</h1>
        @endif
        <p>of 3000 ml</p>
    </div>

    <div class="glass">
        <span class="top"></span>
        <span class="left"></span>
        <span class="right"></span>
        <span class="bottom"></span>
        <span class="water"></span>
        <span class="handle"></span>
    </div>

    <div class="customer-water-track-text-container">


    </div>

        <button class="customer-water-add-btn">
            {{-- <iconify-icon icon="akar-icons:plus" class="customer-water-add-icon"></iconify-icon> --}}
            +
        </button>



    <div class="customer-water-add-text">

    </div>
</div>

<script>
    $(document).ready(function(){
        var total = 3000
        var taken =  parseInt($(".drinked_water").text());
        console.log(taken)
        var left  = total - taken
        fillWater(taken,left,total)



        $(".customer-water-add-btn").click(function(){

            $.ajax({
                        url : 'water',
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success   : function(data) {
                            var taken =  data.water.update_water;
                            var left  = 3000 - taken
                            fillWater(taken,left,total)
                            $(".drinked_water").text(data.water.update_water);

                        },

                    });



        })

    })

    function fillWater(taken,left,total){
        if(taken == 0){
            html = `<p>You didn't drink water today</p>
            <h1>Let's drink</h1>`
            $('.customer-water-track-text-container').html(html);

        }
        else if(taken == 3000){
            html = `<p>You complete today’s mission.</p>
            <h1>Yay! You are hydrated.</h1>`
            $('.customer-water-track-text-container').html(html);
            $(".customer-water-add-btn").hide()
        }
        else{
            html = `<p>You left <span style="color:aqua">  ${left} </span> of today's mission.</p>
            <h1>Let's drink</h1>`
            $('.customer-water-track-text-container').html(html);

        }

        var fill = (taken / total) * 100
        console.log(fill)
        if(fill > 100){
            html = `<p>You complete today’s mission.</p>
            <h1>Yay! You are hydrated.</h1>`
            $('.customer-water-track-text-container').html(html);
            $(".customer-water-add-btn").hide()
            // return
        }
        $('.water').animate({height:`${fill}%`}, 300)

    }
</script>

@endsection
