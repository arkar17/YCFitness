@extends('customer.layouts.app_home')

@section('content')
@include('sweetalert::alert')
    <div class="social-media-right-container">
        <div class="social-media-noti-tabs-container">
            <p class="social-media-noti-likes-tab ">
                Notifications
            </p>
            <p class="social-media-noti-requests-tab">
                Friend Requests
            </p>
        </div>

        <div class="social-media-likes-container">

            <div class="social-media-likes-today-container">
                <p>Today</p>
                @forelse ($notification as $noti)

                <div class="social-media-likes-row">
                    <div class="social-media-likes-name">
                    @if($noti->report_status!=0)
                    <img src="{{asset('img/customer/imgs/report.png')}}"/>
                    @elseif($noti->profile_image == null)
                            <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$noti->profile_image}}"/>
                    @endif
                        <p>{{$noti->description}}</p>
                    </div>
                    @if($noti->report_status!=0)

                    @elseif($noti->comment_id == null)
                    <iconify-icon icon="ant-design:heart-filled" class="social-media-likes-icon"></iconify-icon>
                    @else
                    <iconify-icon icon="bi:chat-left-dots-fill" class="social-media-likes-icon"></iconify-icon>
                    @endif
                </div>
                @empty
                    <p class="text-secondary p-1">No notification</p>
                @endforelse
            </div>

            <div class="social-media-likes-earlier-container">
                <p>Earlier</p>
                @forelse ($notification_earlier as $noti_earli)

                <div class="social-media-likes-row">
                    <div class="social-media-likes-name">

                    @if($noti_earli->profile_image == null)
                            <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$noti_earli->profile_image}}"/>
                    @endif
                        <p>{{$noti_earli->description}}</p>
                    </div>
                    @if($noti_earli->comment_id == null)
                    <iconify-icon icon="ant-design:heart-filled" class="social-media-likes-icon"></iconify-icon>
                    @else
                    <iconify-icon icon="bi:chat-left-dots-fill" class="social-media-likes-icon"></iconify-icon>
                    @endif
                </div>
                @empty
                    <p class="text-secondary p-1">No notification</p>
                @endforelse
            </div>


        </div>

        <div class="social-media-requests-container">
            <div class="social-media-requests-today-container">
                <p>Today</p>
                @forelse($friend_requests as $requests)
                <div class="social-media-request-row">

                        <div class="social-media-request-name">
                            <img src="../imgs/trainer3.jpg">
                            <p>{{$requests->name}}</p>
                        </div>

                        <div class="social-media-btns-container">
                            <a href = {{route('confirmRequest',$requests->id)}} class="customer-primary-btn">
                                Accept
                            </a>
                            <a href = {{route('declineRequest',$requests->id)}} class="customer-red-btn">
                                Decline</a>
                        </div>

                    </div>
                @empty
                <p class="text-secondary p-1">No Friend Request</p>
                @endforelse
            </div>
            <div class="social-media-requests-earlier-container">
                <p>Earlier</p>
                @forelse($friend_requests_earlier as $earlier)
                <div class="social-media-request-row">
                    <div class="social-media-request-name">
                        <img src="../imgs/trainer3.jpg">
                        <p>{{$earlier->name}}</p>
                    </div>
                    <div class="social-media-btns-container">
                        <a href = {{route('confirmRequest',$earlier->id)}} class="customer-primary-btn">
                            Accept
                        </a>
                        <a href = {{route('declineRequest',$earlier->id)}} class="customer-red-btn">
                            Decline</a>
                    </div>
                </div>

                @empty
                <p class="text-secondary p-1">No Friend Request</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="../js/theme.js"></script>


<script>
    $(document).ready(function() {
        $(".social-media-noti-likes-tab").addClass("social-media-noti-active-tab")
        $(".social-media-likes-container").show()
        $(".social-media-requests-container").hide()

        $(".social-media-noti-likes-tab").click(function(){
            $(".social-media-noti-likes-tab").addClass("social-media-noti-active-tab")
            $(".social-media-noti-requests-tab").removeClass("social-media-noti-active-tab")

            $(".social-media-likes-container").show()
            $(".social-media-requests-container").hide()
        })
        $(".social-media-noti-requests-tab").click(function(){
            $(".social-media-noti-likes-tab").removeClass("social-media-noti-active-tab")
            $(".social-media-noti-requests-tab").addClass("social-media-noti-active-tab")

            $(".social-media-likes-container").hide()
            $(".social-media-requests-container").show()
        })



        $('.social-media-post-header-icon').click(function(){
            $(this).next().toggle()
        })

        $(".social-media-left-container-trigger").click(function(){
            $('.social-media-left-container').toggleClass("social-media-left-container-open")
            $('.social-media-overlay').toggle()
            $(".social-media-left-container-trigger .arrow-icon").toggleClass("rotate-arrow")
        })

    })
</script>
@endpush
