@extends('customer.layouts.app_home')

@section('content')
@include('sweetalert::alert')
<style>
    a{
        text-decoration: none !important;
    }
</style>
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

            <div class="social-media-likes-today-container" id="today_noti_pusher">
                <p>Today</p>
                @forelse ($notification as $noti)

                 @if($noti->report_id != 0 )
                  @if($noti->notification_status == 1)
                    <div class="social-media-likes-row notis-box-unread-noti" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @else
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @endif
                    @if($noti->profile_image == null)
                            <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$noti->profile_image}}"/>
                    @endif
                        <p>{{$noti->description}}</p>
                    </div>
                    <iconify-icon icon="bi:chat-left-dots-fill" class="social-media-likes-icon"></iconify-icon>
                </div>

                 @elseif($noti->notification_status == 1 AND $noti->post_id == null AND $noti->report_id==0)
                            <a href ="?id={{$noti->id}}"  class = "accept" id = {{$noti->sender_id}}>
                                @if($noti->notification_status == 1)
                        <div class="social-media-likes-row notis-box-unread-noti" style="padding:5px;">
                        <div class="social-media-likes-name">
                    @else
                        <div class="social-media-likes-row" style="padding:5px;">
                        <div class="social-media-likes-name">
                    @endif
                        @if($noti->profile_image == null)
                                <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                            @else
                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$noti->profile_image}}"/>
                        @endif
                            <p>{{$noti->description}}</p>
                        </div>
                        <iconify-icon icon="ant-design:heart-filled" class="social-media-likes-icon"></iconify-icon>
                    </div>
                            </a>

            @elseif($noti->notification_status == 2 AND $noti->post_id == null AND $noti->report_id==0)
                        <a href ="?id={{$noti->id}}"  class = "accept" id = {{$noti->sender_id}}>
                             @if($noti->notification_status == 1)
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @else
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @endif
                    @if($noti->profile_image == null)
                            <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$noti->profile_image}}"/>
                    @endif
                        <p>{{$noti->description}}</p>
                    </div>
                   <iconify-icon icon="ant-design:heart-filled" class="social-media-likes-icon"></iconify-icon>
                </div>
                        </a>

             @elseif($noti->notification_status == 1 AND $noti->post_id != null AND $noti->comment_id != null AND $noti->report_id==0)
                        <a href ="?id={{$noti->id}}"  class = "view_comment" id = {{$noti->post_id}}>
                             @if($noti->notification_status == 1)
                    <div class="social-media-likes-row notis-box-unread-noti" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @else
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @endif
                    @if($noti->profile_image == null)
                            <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$noti->profile_image}}"/>
                    @endif
                        <p>{{$noti->description}}</p>
                    </div>
                     <iconify-icon icon="bi:chat-left-dots-fill" class="social-media-likes-icon"></iconify-icon>
                </div>
                        </a>

            @elseif($noti->notification_status == 2 AND $noti->post_id != null AND $noti->comment_id != null AND $noti->report_id==0)
                        <a href ="?id={{$noti->id}}"  class = "view_comment" id = {{$noti->post_id}}>
                             @if($noti->notification_status == 1)
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @else
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @endif
                    @if($noti->profile_image == null)
                            <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$noti->profile_image}}"/>
                    @endif
                        <p>{{$noti->description}} {{ $noti->post_id }}</p>
                    </div>
                     <iconify-icon icon="bi:chat-left-dots-fill" class="social-media-likes-icon"></iconify-icon>
                </div>
                        </a>

                @elseif($noti->notification_status != 1 AND $noti->post_id != null AND $noti->comment_id == null AND $noti->report_id==0)
                        <a href ="?id={{$noti->id}}"  class = "view_like" id = {{$noti->post_id}}>
                             @if($noti->notification_status == 1)
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @else
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @endif
                    @if($noti->profile_image == null)
                            <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$noti->profile_image}}"/>
                    @endif
                        <p>{{$noti->description}}</p>
                    </div>
                     <iconify-icon icon="ant-design:heart-filled" class="social-media-likes-icon"></iconify-icon>
                </div>
                        </a>


                        @elseif($noti->notification_status != 2 AND $noti->post_id != null AND $noti->comment_id == null AND $noti->report_id==0)
                        <a href ="?id={{$noti->id}}"  class = "view_like" id = {{$noti->post_id}}>
                             @if($noti->notification_status == 1)
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @else
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @endif
                    @if($noti->profile_image == null)
                            <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$noti->profile_image}}"/>
                    @endif
                        <p>{{$noti->description}}</p>
                    </div>
                     <iconify-icon icon="ant-design:heart-filled" class="social-media-likes-icon"></iconify-icon>
                </div>
                        </a>                    
                @endif

                {{-- <div class="social-media-likes-row">
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
                </div> --}}
                @empty
                    <p class="text-secondary p-1">No notification</p>
                @endforelse
            </div>

            <div class="social-media-likes-earlier-container">
                <p>Earlier</p>
                @forelse ($notification_earlier as $noti_earli)
                @if($noti_earli->report_id != 0 )
                  @if($noti_earli->notification_status == 1)
                    <div class="social-media-likes-row notis-box-unread-noti" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @else
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @endif
                    @if($noti_earli->profile_image == null)
                            <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$noti_earli->profile_image}}"/>
                    @endif
                        <p>{{$noti_earli->description}}</p>
                    </div>
                    <iconify-icon icon="bi:chat-left-dots-fill" class="social-media-likes-icon"></iconify-icon>
                </div>

                 @elseif($noti_earli->notification_status == 1 AND $noti_earli->post_id == null AND $noti_earli->report_id==0)
                            <a href ="?id={{$noti_earli->id}}"  class = "accept" id = {{$noti_earli->sender_id}}>
                                @if($noti_earli->notification_status == 1)
                        <div class="social-media-likes-row notis-box-unread-noti" style="padding:5px;">
                        <div class="social-media-likes-name">
                    @else
                        <div class="social-media-likes-row" style="padding:5px;">
                        <div class="social-media-likes-name">
                    @endif
                        @if($noti_earli->profile_image == null)
                                <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                            @else
                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$noti_earli->profile_image}}"/>
                        @endif
                            <p>{{$noti_earli->description}}</p>
                        </div>
                        <iconify-icon icon="ant-design:heart-filled" class="social-media-likes-icon"></iconify-icon>
                    </div>
                            </a>

            @elseif($noti_earli->notification_status == 2 AND $noti_earli->post_id == null AND $noti_earli->report_id==0)
                        <a href ="?id={{$noti_earli->id}}"  class = "accept" id = {{$noti_earli->sender_id}}>
                             @if($noti_earli->notification_status == 1)
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @else
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @endif
                    @if($noti_earli->profile_image == null)
                            <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$noti_earli->profile_image}}"/>
                    @endif
                        <p>{{$noti_earli->description}}</p>
                    </div>
                   <iconify-icon icon="ant-design:heart-filled" class="social-media-likes-icon"></iconify-icon>
                </div>
                        </a>

             @elseif($noti_earli->notification_status == 1 AND $noti_earli->post_id != null AND $noti_earli->comment_id != null AND $noti_earli->report_id==0)
                        <a href ="?id={{$noti_earli->id}}"  class = "view_comment" id = {{$noti_earli->post_id}}>
                             @if($noti_earli->notification_status == 1)
                    <div class="social-media-likes-row notis-box-unread-noti" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @else
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @endif
                    @if($noti_earli->profile_image == null)
                            <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$noti_earli->profile_image}}"/>
                    @endif
                        <p>{{$noti_earli->description}}</p>
                    </div>
                     <iconify-icon icon="bi:chat-left-dots-fill" class="social-media-likes-icon"></iconify-icon>
                </div>
                        </a>

            @elseif($noti_earli->notification_status == 2 AND $noti_earli->post_id != null AND $noti_earli->comment_id != null AND $noti_earli->report_id==0)
                        <a href ="?id={{$noti_earli->id}}"  class = "view_comment" id = {{$noti_earli->post_id}}>
                             @if($noti_earli->notification_status == 1)
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @else
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @endif
                    @if($noti_earli->profile_image == null)
                            <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$noti_earli->profile_image}}"/>
                    @endif
                        <p>{{$noti_earli->description}}</p>
                    </div>
                     <iconify-icon icon="bi:chat-left-dots-fill" class="social-media-likes-icon"></iconify-icon>
                </div>
                        </a>

                @elseif($noti_earli->notification_status != 1 AND $noti_earli->post_id != null AND $noti_earli->comment_id == null AND $noti_earli->report_id==0)
                        <a href ="?id={{$noti_earli->id}}"  class = "view_like" id = {{$noti_earli->post_id}}>
                             @if($noti_earli->notification_status == 1)
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @else
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @endif
                    @if($noti_earli->profile_image == null)
                            <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$noti_earli->profile_image}}"/>
                    @endif
                        <p>{{$noti_earli->description}}</p>
                    </div>
                     <iconify-icon icon="ant-design:heart-filled" class="social-media-likes-icon"></iconify-icon>
                </div>
                        </a>


                        @elseif($noti_earli->notification_status != 2 AND $noti_earli->post_id != null AND $noti_earli->comment_id == null AND $noti_earli->report_id==0)
                        <a href ="?id={{$noti_earli->id}}"  class = "view_like" id = {{$noti_earli->post_id}}>
                             @if($noti_earli->notification_status == 1)
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @else
                    <div class="social-media-likes-row" style="padding:5px;">
                    <div class="social-media-likes-name">
                 @endif
                    @if($noti_earli->profile_image == null)
                            <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$noti_earli->profile_image}}"/>
                    @endif
                        <p>{{$noti_earli->description}}</p>
                    </div>
                     <iconify-icon icon="ant-design:heart-filled" class="social-media-likes-icon"></iconify-icon>
                </div>
                        </a>





                    
                @endif

                
                {{-- <div class="social-media-likes-row">
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
                </div> --}}
                @empty
                    <p class="text-secondary p-1">No notification</p>
                @endforelse
            </div>


        </div>

        <div class="social-media-requests-container">
            <div class="social-media-requests-today-container">
                <p>Today</p>
               
                @forelse($friend_requests as $requests)
                <div class="social-media-request-row" id="friend_today_pusher" >

                        <div class="social-media-request-name" >
                            @if ($requests->profile_image==null)
                            <img  src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                            @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $requests->profile_image}}">
                            @endif
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
                        @if ($earlier->profile_image==null)
                        <img  src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                        @else
                        <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $earlier>profile_image}}">
                        @endif
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
    var user_id = {{ auth()->user()->id }};
        console.log(user_id);
        var pusher = new Pusher('{{ env('MIX_PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });
        var channel = pusher.subscribe('friend_request.' + user_id);
        channel.bind('friendRequest', function(data) {
            $( "#today_noti_pusher" ).load(window.location.href + " #today_noti_pusher" );
            $( "#friend_today_pusher" ).load(window.location.href + " #friend_today_pusher" );
            // document.getElementById("testing").text = data
            $.notify(data, "success", {
                position: "left"
            });
        });
</script>
@endpush
