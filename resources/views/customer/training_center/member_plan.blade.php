@extends('customer.training_center.layouts.app')
@section('content')
<div class="training-center-imgs-container margin-top">
<section class="training-center-plans-section">
    <div class="section-header">
        <p>Pricing Details</p>
        <div class="section-header-underline">

        </div>
    </div>
    <div class="training-center-plan-durations-container">
@foreach($durations as $duration)
        <div class="member-plan-duration-container">
            <label>
                <input type="checkbox" name = "memberPlanDuration" class=" customer-member-plan-duration-checkbox-input"  onclick="checkedOnDurationClick(this,'memberPlanDuration')" value="{{$duration->duration}}"/>
                <p class="customer-member-plan-duration-checkbox-title">{{$duration->duration}} month</p>
            </label>
        </div>
        {{-- <div class="member-plan-duration-container">
            <label>
                <input type="checkbox" name = "memberPlanDuration" class=" customer-member-plan-duration-checkbox-input"  onclick="checkedOnDurationClick(this,'memberPlanDuration')" value="{{$duration->duration}}"/>
                <p class="customer-member-plan-duration-checkbox-title">3 months</p>
            </label>
        </div>
        <div class="member-plan-duration-container">
            <label>
                <input type="checkbox" name = "memberPlanDuration" class=" customer-member-plan-duration-checkbox-input"  onclick="checkedOnDurationClick(this,'memberPlanDuration')" value="{{$duration->duration}}"/>
                <p class="customer-member-plan-duration-checkbox-title">6 months</p>
            </label>
        </div> --}}
        @endforeach
    </div>
    <div class="home-price-details-container">

    </div>
</section>
</div>
@endsection
@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>
    <script src="../js/theme.js"></script>
    <script>
    $(document).ready(function(){
        let member = @json($members);
        var memberPlanDurationCheckboxesList = document.getElementsByName("memberPlanDuration");
        for(var i = 0; i < memberPlanDurationCheckboxesList.length;i++){
            if(memberPlanDurationCheckboxesList.item(i).value === '1'){
                // console.log("check 1")
                memberPlanDurationCheckboxesList.item(i).checked = true
                checkedOnDurationClick(memberPlanDurationCheckboxesList.item(i),"memberPlanDuration")
            }
        }
        });
        window.onload = function() {
            let slider = document.querySelector('#slider');
            let move = document.querySelector('#move');
            let moveLi = Array.from(document.querySelectorAll('#slider #move li'));
            let forword = document.querySelector('#slider #forword');
            let back = document.querySelector('#slider #back');
            let counter = 1;
            let time = 5000;
            let line = document.querySelector('#slider #line');
            let dots = document.querySelector('#slider #dots');
            let dot;
            for (i = 0; i < moveLi.length; i++) {
                dot = document.createElement('li');
                dots.appendChild(dot);
                dot.value = i;
            }
            dot = dots.getElementsByTagName('li');
            line.style.animation = 'line ' + (time / 1000) + 's linear infinite';
            dot[0].classList.add('active');
            function moveUP() {
                if (counter == moveLi.length) {
                    moveLi[0].style.marginLeft = '0%';
                    counter = 1;
                } else if (counter >= 1) {
                    moveLi[0].style.marginLeft = '-' + counter * 100 + '%';
                    counter++;
                }
                if (counter == 1) {
                    dot[moveLi.length - 1].classList.remove('active');
                    dot[0].classList.add('active');
                } else if (counter > 1) {
                    dot[counter - 2].classList.remove('active');
                    dot[counter - 1].classList.add('active');
                }
            }
            function moveDOWN() {
                if (counter == 1) {
                    moveLi[0].style.marginLeft = '-' + (moveLi.length - 1) * 100 + '%';
                    counter = moveLi.length;
                    dot[0].classList.remove('active');
                    dot[moveLi.length - 1].classList.add('active');
                } else if (counter <= moveLi.length) {
                    counter = counter - 2;
                    moveLi[0].style.marginLeft = '-' + counter * 100 + '%';
                    counter++;
                    dot[counter].classList.remove('active');
                    dot[counter - 1].classList.add('active');
                }
            }
            for (i = 0; i < dot.length; i++) {
                dot[i].addEventListener('click', function(e) {
                    dot[counter - 1].classList.remove('active');
                    counter = e.target.value + 1;
                    dot[e.target.value].classList.add('active');
                    moveLi[0].style.marginLeft = '-' + (counter - 1) * 100 + '%';
                });
            }
            forword.onclick = moveUP;
            back.onclick = moveDOWN;
            let autoPlay = setInterval(moveUP, time);
            slider.onmouseover = function() {
                autoPlay = clearInterval(autoPlay);
                line.style.animation = '';
            }
            slider.onmouseout = function() {
                autoPlay = setInterval(moveUP, time);
                line.style.animation = 'line ' + (time / 1000) + 's linear infinite';
            }
        }
        const checkedOnDurationClick = (el,category) => {
            let member = @json($members);

            $(".home-price-details-container").empty()
            if(category === 'memberPlanDuration'){
                var memberPlanDurationCheckboxesList = document.getElementsByName("memberPlanDuration");
                for(var i = 0; i < memberPlanDurationCheckboxesList.length;i++){
                    memberPlanDurationCheckboxesList.item(i).checked = false
                }
            }
            if(el.checked){
                el.checked = false;
            }else{
                el.checked = true;
            }
            let pros=@json($pros);
            console.log(pros);
            let cons=@json($cons);
                $.each(member, function(index, value){
                    if( value.duration === "0" &&  el.value === '1'){
                        pros.forEach((pro)=>{
                                console.log(pro.pros?.split(','))
                            })
                            $(".home-price-details-container").append(`
                            <div class="home-price-detail-container">
                            <h1>${value.member_type}</h1>
                            <p class="home-price-detail-price">MMK ${value.price} / month</p>
                            <div class="home-price-detail-benefits">
                            ${
                                value.pros?.split(',').map((item) => (
                                    `
                                <div class="home-price-detail-benefit">
                                <iconify-icon icon="akar-icons:check" class="home-price-detail-benefit-icon check"></iconify-icon>
                                <p>${item}</p>
                                </div>
                            `
                                )).join('')
                            }
                            ${
                                value.cons?.split(',').map((item) => (
                                    `
                                <div class="home-price-detail-benefit">
                                    <iconify-icon icon="akar-icons:cross" class="home-price-detail-benefit-icon cross"></iconify-icon>
                                <p>${item}</p>
                                </div>
                            `
                                )).join('')
                            }
                            <form action="{{ url('/customer_payment_active_staus/${value.id}') }}" method="GET">
                                <button type="submit" class="customer-secondary-btn">Upgrade</button>
                            </form>
                        </div>
                            `)
                    }
                    else if(el.value === value.duration){
                            console.log(value)
                            pros.forEach((pro)=>{
                                console.log(pro.pros?.split(','))
                            })
                            $(".home-price-details-container").append(`
                            <div class="home-price-detail-container">
                            <h1>${value.member_type}</h1>
                            <p class="home-price-detail-price">MMK ${value.price} / month</p>
                            <div class="home-price-detail-benefits">
                            ${
                                value.pros?.split(',').map((item) => (
                                    `
                                <div class="home-price-detail-benefit">
                                <iconify-icon icon="akar-icons:check" class="home-price-detail-benefit-icon check"></iconify-icon>
                                <p>${item}</p>
                                </div>
                            `
                                )).join('')
                            }
                            ${
                                value.cons?.split(',').map((item) => (
                                    `
                                <div class="home-price-detail-benefit">
                                    <iconify-icon icon="akar-icons:cross" class="home-price-detail-benefit-icon cross"></iconify-icon>
                                <p>${item}</p>
                                </div>
                            `
                                )).join('')
                            }
                            <form action="{{ url('/customer_payment_active_staus/${value.id}') }}" method="GET">
                                <button type="submit" class="customer-secondary-btn">Upgrade</button>
                            </form>
                        </div>
                            `)
                    }
                });
        }
    </script>
@endpush
