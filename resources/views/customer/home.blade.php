@extends('customer.layouts.app_home1')

@section('content')
@include('sweetalert::alert')

<section class="home-aboutus-section margin-top" >

    <div class="section-header">
        <p>About Us</p>
        <div class="section-header-underline">

        </div>
    </div>

    <div class="home-about-us-content-container">
        <div class="home-about-us-img-container">
            <img src="{{asset('image/about-us.jpg')}}">
        </div>

        <div class="home-about-us-text-container">
            <p>Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna. Pellentesque sit amet sapien fringilla, mattis ligula consectetur, ultrices mauris. Maecenas vitae mattis tellus. Nullam quis imperdiet augue. Vestibulum auctor ornare leo, non suscipit magna interdum eu. Curabitur pellentesque nibh nibh, at maximus ante.</p>
            <a href="#" class="customer-secondary-btn">
                Read More
                <iconify-icon icon="akar-icons:arrow-right" class="readmore-icon"></iconify-icon>
            </a>
        </div>
    </div>

</section>

<section class="home-prices-section">
    <div class="section-header">
        <p>Pricing Details</p>
        <div class="section-header-underline">

        </div>
    </div>

    <div class="home-price-details-container">
        @foreach ($member_plans as $member_plan)
        <div class="home-price-detail-container">
            <h1>{{$member_plan->member_type}}</h1>
            <p class="home-price-detail-price">MMK {{$member_plan->price}} / month</p>

            <div class="home-price-detail-benefits">

                <div class="home-price-detail-benefit">
                    <iconify-icon icon="akar-icons:check" class="home-price-detail-benefit-icon check"></iconify-icon>
                    <p>Benefit 1</p>
                </div>
                <div class="home-price-detail-benefit">
                    <iconify-icon icon="akar-icons:check" class="home-price-detail-benefit-icon check"></iconify-icon>
                    <p>Benefit 1</p>
                </div>
                <div class="home-price-detail-benefit">
                    <iconify-icon icon="akar-icons:check" class="home-price-detail-benefit-icon check"></iconify-icon>
                    <p>Benefit 1</p>
                </div>
                <div class="home-price-detail-benefit">
                    <iconify-icon icon="akar-icons:cross" class="home-price-detail-benefit-icon cross"></iconify-icon>
                    <p>Benefit 1</p>
                </div>
            </div>

            {{-- <form action="{{ route('customer_upgrade', $member_plan->id) }}" method="GET">
                @csrf
                <button type="submit" class="customer-secondary-btn">Upgrade</button>
            </form> --}}
            <button type="button" class="customer-secondary-btn">Upgrade</button>
        </div>
        @endforeach

    </div>

</section>

<section class="home-trainers-section">

    <div class="section-header">
        <p>Our Trainers</p>
        <div class="section-header-underline">

        </div>
    </div>
    <div class="home-trainer-container">
        <div class="home-trainer-img-container left-img">
            <img src="{{asset('image/trainer1.jpg')}}">
        </div>

        <div class="home-trainer-text-container">
            <h1>Trainer Name</h1>
            <p>
                <iconify-icon icon="ci:double-quotes-r" flip="horizontal" class="home-trainer-icon"></iconify-icon>
                Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna. Pellentesque sit amet sapien fringilla, mattis ligula consectetur, ultrices mauris. Maecenas vitae mattis tellus. Nullam quis imperdiet augue. Vestibulum auctor ornare leo, non suscipit magna interdum eu. Curabitur pellentesque nibh nibh, at maximus ante.</p>
        </div>
    </div>
    <div class="home-trainer-container">
        <div class="home-trainer-img-container right-img">
            <img src="{{asset('image/trainer2.jpg')}}">
        </div>

        <div class="home-trainer-text-container">
            <h1>Trainer Name</h1>
            <p>
                <iconify-icon icon="ci:double-quotes-r" flip="horizontal" class="home-trainer-icon"></iconify-icon>
                Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna. Pellentesque sit amet sapien fringilla, mattis ligula consectetur, ultrices mauris. Maecenas vitae mattis tellus. Nullam quis imperdiet augue. Vestibulum auctor ornare leo, non suscipit magna interdum eu. Curabitur pellentesque nibh nibh, at maximus ante.</p>
        </div>
    </div>
    <div class="home-trainer-container">
        <div class="home-trainer-img-container left-img">
            <img src="{{asset('image/trainer3.jpg')}}">
        </div>

        <div class="home-trainer-text-container">
            <h1>Trainer Name</h1>
            <p>
                <iconify-icon icon="ci:double-quotes-r" flip="horizontal" class="home-trainer-icon"></iconify-icon>
                Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna. Pellentesque sit amet sapien fringilla, mattis ligula consectetur, ultrices mauris. Maecenas vitae mattis tellus. Nullam quis imperdiet augue. Vestibulum auctor ornare leo, non suscipit magna interdum eu. Curabitur pellentesque nibh nibh, at maximus ante.</p>
        </div>
    </div>



</section>

<section class="home-milestone-section">

    <div class="customer-milestone-parent-container">

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

    </div>


</section>

<section class="home-appad-section">

    <div class="home-appad-content-container">
        <div class="home-appad-img-container">
            <img src="{{asset('image/appad.png')}}">
        </div>
        <div class="home-appad-text-container">
            <h1>Lorem ipsum dolor sit amet consectetur adipiscing elit.</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna. Pellentesque sit amet sapien fringilla, mattis ligula consectetur, ultrices mauris. Maecenas vitae mattis tellus..</p>
            <div class="home-appad-btns-container">
                <a href="#" class="home-appad-btn">
                    <iconify-icon icon="cib:google-play" class="home-appad-icon"></iconify-icon>
                    <div class="home-appad-text">
                        <span>Download from</span>
                        <p>Google Play</p>
                    </div>
                </a>
                <a href="#" class="home-appad-btn">
                    <iconify-icon icon="ant-design:apple-filled" class="home-appad-icon"></iconify-icon>
                    <div class="home-appad-text">
                        <span>Download from</span>
                        <p>App Store</p>
                    </div>
                </a>
            </div>
        </div>
    </div>


</section>

<section class="home-contact-section">

    <div class="section-header">
        <p>Contact Us</p>
        <div class="section-header-underline">

        </div>
    </div>

    <form class="home-contact-us-form-parent-container">
        <div class="home-contact-us-form-container">
            <div class="home-contact-us-inputs-container">
                <input type="email" required placeholder="Email">
                <textarea placeholder="Message"></textarea>
            </div>

            <div class="home-contact-us-details-container">
                <div class="home-contact-us-detail">
                    <iconify-icon icon="akar-icons:phone" class="home-contact-us-detail-icon"></iconify-icon>
                    <p>09-12345678</p>
                </div>
                <div class="home-contact-us-detail">
                    <iconify-icon icon="akar-icons:envelope" class="home-contact-us-detail-icon"></iconify-icon>
                    <p>someEmail@gmail.com</p>
                </div>
                <div class="home-contact-us-detail">
                    <iconify-icon icon="akar-icons:location" class="home-contact-us-detail-icon"></iconify-icon>
                    <p>some street, some city, some country,some street, some city, some country</p>
                </div>
            </div>

            <div class="home-contact-us-btns-container">
                <button type="submit" class="customer-primary-btn">Send Message</button>
                <button type="submit" class="customer-secondary-btn">Cancel</button>
            </div>
        </div>
    </form>

</section>
@endsection
