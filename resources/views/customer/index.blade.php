@extends('customer.layouts.app')

@section('content')
@include('sweetalert::alert')


<section class="index-hero-section ">
    <div class="customer-main-content-container index-hero-text">
        <h1>Lorem ipsum dolor sit amet consectetur <span>adipiscing elit Ut et.</span></h1>
        <p>Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna. Pellentesque sit amet sapien.</p>
        <div class="index-hero-btns-container">
            @guest
            <a href="{{route('login')}}" class="customer-primary-btn">{{__('msg.log in')}}</a>
            <a href="{{route('customer_register')}}" class="customer-secondary-btn">{{__('msg.sign up')}}</a>
            @endguest
            @auth
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="customer-primary-btn customer-login-btn" type="submit">{{__('msg.log out')}}</button>
            </form>
            @endauth
        </div>
    </div>
</section>


<section class="index-aboutus-section">
    <div class="customer-main-content-container">
        <div class="section-header">
            <p>{{__('msg.about us')}}</p>
            <div class="section-header-underline">

            </div>
        </div>

        <div class="index-about-us-content-container">
            <div class="index-about-us-img-container">
                <img src="{{ asset('image/about-us.jpg')}}">
            </div>

            <div class="index-about-us-text-container">
                <p>Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna. Pellentesque sit amet sapien fringilla, mattis ligula consectetur, ultrices mauris. Maecenas vitae mattis tellus. Nullam quis imperdiet augue. Vestibulum auctor ornare leo, non suscipit magna interdum eu. Curabitur pellentesque nibh nibh, at maximus ante.</p>
                <a href="#" class="customer-secondary-btn">
                    Read More
                    <iconify-icon icon="akar-icons:arrow-right" class="readmore-icon"></iconify-icon>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="index-prices-section">
    <div class="customer-main-content-container">
        <div class="section-header">
            <p>{{__('msg.pricing details')}}</p>
            <div class="section-header-underline">

            </div>
        </div>

        <div class="index-price-details-container">
            @foreach($members as $m)
            <div class="index-price-detail-container">
                <h1>{{$m->member_type}}</h1>
                <p class="index-price-detail-price">MMK {{$m->price}} / month</p>

                <div class="index-price-detail-benefits">
                    @foreach(explode(",",$m->pros) as $pro)
                    <div class="index-price-detail-benefit">
                        <iconify-icon icon="akar-icons:check" class="index-price-detail-benefit-icon check"></iconify-icon>
                        <p>{{$pro}}</p>
                    </div>
                    @endforeach
                    @foreach(explode(",",$m->cons) as $con)
                    <div class="index-price-detail-benefit">
                        <iconify-icon icon="akar-icons:cross" class="index-price-detail-benefit-icon cross"></iconify-icon>
                        <p>{{$con}}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="index-trainers-section">
    <div class="customer-main-content-container">
        <div class="section-header">
            <p>{{__('msg.our trainers')}}</p>
            <div class="section-header-underline">

            </div>
        </div>
        <div class="index-trainer-container">
            <div class="index-trainer-img-container left-img">
                <img src="{{ asset('image/trainer1.jpg')}}">
            </div>

            <div class="index-trainer-text-container">
                <h1>Trainer Name</h1>
                <p>
                    <iconify-icon icon="ci:double-quotes-r" flip="horizontal" class="index-trainer-icon"></iconify-icon>
                    Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna. Pellentesque sit amet sapien fringilla, mattis ligula consectetur, ultrices mauris. Maecenas vitae mattis tellus. Nullam quis imperdiet augue. Vestibulum auctor ornare leo, non suscipit magna interdum eu. Curabitur pellentesque nibh nibh, at maximus ante.</p>
            </div>
        </div>
        <div class="index-trainer-container">
            <div class="index-trainer-img-container right-img">
                <img src="{{ asset('image/trainer2.jpg')}}">
            </div>

            <div class="index-trainer-text-container">
                <h1>Trainer Name</h1>
                <p>
                    <iconify-icon icon="ci:double-quotes-r" flip="horizontal" class="index-trainer-icon"></iconify-icon>
                    Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna. Pellentesque sit amet sapien fringilla, mattis ligula consectetur, ultrices mauris. Maecenas vitae mattis tellus. Nullam quis imperdiet augue. Vestibulum auctor ornare leo, non suscipit magna interdum eu. Curabitur pellentesque nibh nibh, at maximus ante.</p>
            </div>
        </div>
        <div class="index-trainer-container">
            <div class="index-trainer-img-container left-img">
                <img src="{{ asset('image/trainer3.jpg')}}">
            </div>

            <div class="index-trainer-text-container">
                <h1>Trainer Name</h1>
                <p>
                    <iconify-icon icon="ci:double-quotes-r" flip="horizontal" class="index-trainer-icon"></iconify-icon>
                    Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna. Pellentesque sit amet sapien fringilla, mattis ligula consectetur, ultrices mauris. Maecenas vitae mattis tellus. Nullam quis imperdiet augue. Vestibulum auctor ornare leo, non suscipit magna interdum eu. Curabitur pellentesque nibh nibh, at maximus ante.</p>
            </div>
        </div>
    </div>


</section>

<section class="index-milestone-section">
    <div class="customer-main-content-container">
        <div class="customer-milestone-parent-container">
            <!-- <div class="customer-milestone-path-container"> -->
                <div class="customer-milestone-path"></div>
                <div class="customer-milestone-stone">
                    <div class="customer-milestone-text-container">
                        <div class="customer-milestone-text">
                            <p>2017</p>
                            <span>Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et.</span>
                        </div>
                        <div class="customer-milestone-text-line"></div>
                    </div>
                </div>
                <div class="customer-milestone-path"></div>
                <div class="customer-milestone-stone">
                    <div class="customer-milestone-text-container">
                        <div class="customer-milestone-text">
                            <p>2018</p>
                            <span>Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et.</span>
                        </div>
                        <div class="customer-milestone-text-line"></div>
                    </div>
                </div>
                <div class="customer-milestone-path"></div>
                <div class="customer-milestone-stone">
                    <div class="customer-milestone-text-container">
                        <div class="customer-milestone-text">
                            <p>2019</p>
                            <span>Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et.</span>
                        </div>
                        <div class="customer-milestone-text-line"></div>
                    </div>
                </div>
                <div class="customer-milestone-path"></div>
                <div class="customer-milestone-stone">
                    <div class="customer-milestone-text-container">
                        <div class="customer-milestone-text">
                            <p>2020</p>
                            <span>Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et.</span>
                        </div>
                        <div class="customer-milestone-text-line"></div>
                    </div>
                </div>

            <!-- </div> -->
        </div>
    </div>

</section>

<section class="index-appad-section">
    <div class="customer-main-content-container">
        <div class="index-appad-content-container">
            <div class="index-appad-img-container">
                <img src="{{ asset('image/appad.png')}}">
            </div>
            <div class="index-appad-text-container">
                <h1>Lorem ipsum dolor sit amet consectetur adipiscing elit.</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna. Pellentesque sit amet sapien fringilla, mattis ligula consectetur, ultrices mauris. Maecenas vitae mattis tellus..</p>
                <div class="index-appad-btns-container">
                    <a href="#" class="index-appad-btn">
                        <iconify-icon icon="cib:google-play" class="index-appad-icon"></iconify-icon>
                        <div class="index-appad-text">
                            <span>Download from</span>
                            <p>Google Play</p>
                        </div>
                    </a>
                    <a href="#" class="index-appad-btn">
                        <iconify-icon icon="ant-design:apple-filled" class="index-appad-icon"></iconify-icon>
                        <div class="index-appad-text">
                            <span>Download from</span>
                            <p>App Store</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

</section>

<section class="index-contact-section">
    <div class="customer-main-content-container">
        <div class="section-header">
            <p>{{__('msg.contact us')}}</p>
            <div class="section-header-underline">

            </div>
        </div>

        <form class="index-contact-us-form-parent-container">
            <div class="index-contact-us-form-container">
                <div class="index-contact-us-inputs-container">
                    <input type="email" required placeholder="Email">
                    <textarea placeholder="Message"></textarea>
                </div>

                <div class="index-contact-us-details-container">
                    <div class="index-contact-us-detail">
                        <iconify-icon icon="akar-icons:phone" class="index-contact-us-detail-icon"></iconify-icon>
                        <p>09-12345678</p>
                    </div>
                    <div class="index-contact-us-detail">
                        <iconify-icon icon="akar-icons:envelope" class="index-contact-us-detail-icon"></iconify-icon>
                        <p>someEmail@gmail.com</p>
                    </div>
                    <div class="index-contact-us-detail">
                        <iconify-icon icon="akar-icons:location" class="index-contact-us-detail-icon"></iconify-icon>
                        <p>some street, some city, some country,some street, some city, some country</p>
                    </div>
                </div>

                <div class="index-contact-us-btns-container">
                    <button type="submit" class="customer-primary-btn">{{__('msg.send message')}}</button>
                    <button type="submit" class="customer-secondary-btn">{{__('msg.cancel')}}</button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
@push('scripts')
    <script>
            $(document).ready(function(){
        $(window).scroll(function(){
            var scroll = $(window).scrollTop()
            if(scroll>50){
                $('.index-page-header').addClass("sticky-state")
                // if($('.burger-icon').css('display') == 'block' || $('.close-nav-icon').css('display') == 'block'){
                //     $(".customer-navlinks-container a").css('color',"black")
                // }else{
                //     $(".customer-navlinks-container a").css('color',"")
                // }

                // $(".index-page-header .customer-logo").css("color","#ffffff")
                // $(".index-page-header .customer-navlinks-container a").css("color","#ffffff")
                // $(".index-page-header select").css("color","#ffffff")
                // $(".index-page-header select option").css("color","#000000")
            }else{
                $('.index-page-header').removeClass("sticky-state")
                // $(".index-page-header .customer-logo").css("color","#000000")
                // $(".index-page-header .customer-navlinks-container a").css("color","#000000")
                // $(".index-page-header select").css("color","#000000")
            }
        })

        $( window ).resize(function() {
            if($(window).width() > 1000){
                $(".customer-navlinks-container a").css('color',"")
            }else if($(window).width() <= 1000){
                $(".customer-navlinks-container a").css('color',"black")
            }
        })

        let member = @json($members);
        var memberPlanDurationCheckboxesList = document.getElementsByName("memberPlanDuration");
        for(var i = 0; i < memberPlanDurationCheckboxesList.length;i++){
            if(memberPlanDurationCheckboxesList.item(i).value === '1'){
                // console.log("check 1")
                memberPlanDurationCheckboxesList.item(i).checked = true
                checkedOnDurationClick(memberPlanDurationCheckboxesList.item(i),"memberPlanDuration")
            }
        }

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

    })
    </script>
@endpush
