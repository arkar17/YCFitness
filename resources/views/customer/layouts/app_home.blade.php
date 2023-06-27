<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--iconify-->
    <script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!--global css-->
    {{-- <link href={{ asset('css/customer/css/globals.css')}} rel="stylesheet"/> --}}
    <link href={{ asset('css/globals.css') }} rel="stylesheet" />
    <link href={{ asset('css/aos.css') }} rel="stylesheet" />
    <link href={{ asset('css/home.css') }} rel="stylesheet" />
    <!--customer registeration-->
    <link href={{ asset('css/customer/css/customerRegisteration.css') }} rel="stylesheet" />

    <!--customer login-->
    <link href="{{ asset('css/customer/css/customerLogin.css') }}" rel="stylesheet" />

    <link href="{{ asset('css/customer/css/transactionChoice.css') }}" rel="stylesheet" />
    <!--social media -->
    <link href="{{ asset('css/shop.css') }}" rel="stylesheet" />
    <!--social media -->
    <link href="{{ asset('css/socialMedia.css') }}" rel="stylesheet" />

    <!--comment mention--->
    <link href="{{ asset('css/customer/jquery.mentiony.css') }}" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!--comment emoji-->
    <link href="{{ asset('css/customer/emojis.css') }}" rel="stylesheet" />

    @yield('styles')


    <style>
        .chat-backdrop {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 20;
            display: none
        }

        .modal2 {
            width: 400px;
            padding: 20px;
            margin: 100px auto;
            background: white;
            border-radius: 10px;
        }

        .backdrop {
            top: 0;
            position: fixed;
            background: rgba(0, 0, 0, 0.5);
            width: 100%;
            height: 100%;
            z-index: 999999 !important;
        }

        #video-container,
        #audio-container {
            width: 100%;
            height: 100%;
            /* max-width: 90vw;
                                                                                                                            max-height: 50vh; */
            margin: 0 auto;
            border-radius: 0.25rem;
            position: relative;
            box-shadow: 1px 1px 11px #9e9e9e;
            background-color: #fff;
        }

        #audio-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }


        #local-video {
            width: 30%;
            height: 30%;
            position: absolute;
            left: 10px;
            bottom: 10px;
            border: 1px solid #fff;
            border-radius: 6px;
            z-index: 5;
            cursor: pointer;
        }

        #local-audio {
            width: 30%;
            height: 30%;
            position: absolute;
            left: 10px;
            bottom: 10px;
            border: 1px solid #fff;
            border-radius: 6px;
            z-index: 5;
            cursor: pointer;
        }

        #video-main-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 700px;
            height: 500px;
            z-index: 21;
            display: none;
        }

        #remote-video {
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            top: 0;
            z-index: 3;
            margin: 0;
            padding: 0;
            cursor: pointer;
        }

        #remote-audio {
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            top: 0;
            z-index: 3;
            margin: 0;
            padding: 0;
            cursor: pointer;
        }

        .action-btns {
            position: absolute;
            bottom: 20px;
            left: 50%;
            margin-left: -50px;
            z-index: 4;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
        }

        #incomingCallContainer {
            position: absolute;
            top: 100px;
        }

        #incoming_call {
            position: relative;
            z-index: 99;
        }
    </style>

    <title>YC-fitness</title>
</head>

<body class="customer-loggedin-bg">

  
    <script>
        const theme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', theme);
    </script>

    @include('customer.training_center.layouts.header')
    <!--theme-->


    <script src="{{ asset('js/theme.js') }}"></script>
    <script src="{{ asset('js/aos.js') }}"></script>
<!-- Report Modal -->
<div class="modal fade " id="reportmodal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="reportLabel">{{__('msg.report')}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div>
                <h6 class="text-bold">Please select a problem</h6>
                <form id="report_form" value="">
                    <label class="form-label text-secondary" style="font-size:0.75em">Your report is anonymous,except if you're reporting an intellectual property infringement.</label>
                    <input type="hidden" value="" id="post_id">
                    <input type="hidden" value="" id="comment_id">
                    <label class="report-option">
                        <input type="radio" id="nudity" name="report_msg" value="nudity">
                        <label for="nudity">Nudity</label>
                    </label>
                    <label class="report-option">
                        <input type="radio" id="violence" name="report_msg" value="violence">
                        <label for="violence">Violence</label>
                    </label>

                    <label class="report-option">
                        <input type="radio" id="harassment" name="report_msg" value="harassment">
                        <label for="harassment">Harassment</label>
                    </label>

                    <label class="report-option">
                        <input type="radio" id="suicide" name="report_msg" value="suicide or self-injury">
                        <label for="suicide">Suicide or self-injury</label>
                    </label>

                    <label class="report-option">
                        <input type="radio" id="false" name="report_msg" value="false information">
                        <label for="false">false information</label>
                    </label>


                    <label class="report-option">
                        <input type="radio" id="spam" name="report_msg" value="spam">
                        <label for="spam">Spam</label>
                    </label>


                    <label class="report-option">
                        <input type="radio" id="Hate speech" name="report_msg" value="hate speech">
                        <label for="Hate speech">Hate speech</label>
                    </label>

                    <label class="report-option">
                        <input type="radio" id="terrorism" name="report_msg" value="terrorism">
                        <label for="terrorism">Terrorism</label>
                    </label>

                    <label class="report-option">
                        <input type="radio" id="other" name="report_msg" value="other">
                        <label for="other">Other</label>
                    </label>
                    <textarea id="other_msg" name="other_report_msg"></textarea>

                    <button type="submit" class="btn btn-primary disabled" id="report_submit">Submit</button>
                </form>
            </div>
        </div>
      </div>
    </div>
</div>
{{-- report modal end --}}
    {{-- For video call start --}}
    <div class="chat-backdrop"></div>
    <div id="incomingCallContainer">

    </div>

    <div id="video-main-container">

    </div>


    {{-- For video call end --}}


    <div class="nav-overlay">
    </div>

    <div class="modal fade" id="addPostModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('msg.create a post')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" id="form">
                    {{-- <form class="modal-body" method="POST" action="{{route('post.store')}}" enctype= multipart/form-data>
                        @csrf
                        @method('POST') --}}
                    <div class="addpost-caption">
                        <p>{{ __('msg.post caption') }}</p>
                        <textarea placeholder="Caption goes here..." name="caption" id="addPostCaption" class="addpost-caption-input"></textarea>
                    </div>

                    <div class="addpost-photovideo">

                        <span class="selectImage">

                            <div class="addpost-photovideo-btn">
                                <iconify-icon icon="akar-icons:circle-plus" class="addpst-photovideo-btn-icon">
                                </iconify-icon>
                                <p>Photo/Video</p>
                                <input type="file" id="addPostInput" name="addPostInput[]" multiple
                                    enctype="multipart/form-data">
                            </div>

                            <button class="addpost-photovideo-clear-btn" type="button"
                                onclick="clearAddPost()">Clear</button>

                        </span>

                        <div class="addpost-photo-video-imgpreview-container">
                        </div>


                    </div>
                    <button type="submit" class="customer-primary-btn addpost-submit-btn">{{__('msg.post')}}</button>
                    {{-- <button type="submit" class="customer-primary-btn addpost-submit-btn">Post</button> --}}
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('msg.edit post') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" id="edit_form" enctype=multipart/form-data>


                    {{-- <form class="modal-body" method="POST" action="{{route('post.store')}}" enctype= multipart/form-data>
                        @csrf
                        @method('POST') --}}
                    <input type="hidden" id="edit_post_id">

                    <div class="addpost-caption">
                        <p>{{__('msg.post caption')}}</p>
                        <textarea placeholder="Caption goes here..." name="caption" id="editPostCaption" class="addpost-caption-input"></textarea>
                    </div>

                    <div class="addpost-photovideo">

                        <span class="selectImage">

                            <div class="addpost-photovideo-btn">
                                <iconify-icon icon="akar-icons:circle-plus" class="addpst-photovideo-btn-icon">
                                </iconify-icon>
                                <p>{{ __('msg.photo/video') }}</p>
                                <input type="file" id="editPostInput" name="editPostInput[]" multiple
                                    enctype="multipart/form-data">
                            </div>

                            <button class="addpost-photovideo-clear-btn" type="button"
                                onclick="clearEditPost()">{{ __('msg.clear') }}</button>

                        </span>

                        <div class="editpost-photo-video-imgpreview-container">
                        </div>


                    </div>
                    {{-- <input type="submit" class="customer-primary-btn addpost-submit-btn" value="Update"> --}}
                    {{-- <button type="button" class="customer-primary-btn addpost-submit-btn "  id="editpost-submit-btn">Update</button> --}}
                    <button type="submit" class="customer-primary-btn addpost-submit-btn">{{__('msg.post')}}</button>
                </form>

            </div>
        </div>
    </div>

    <div class="customer-main-content-container">
        <div class="social-media-header-btns-container margin-top">
            {{-- <a class="back-btn" href="{{route("socialmedia")}}"> --}}
            <a class="back-btn" href="javascript:history.back()">
                <iconify-icon icon="bi:arrow-left" class="back-btn-icon"></iconify-icon>
            </a>
            <button class="social-media-addpost-btn customer-primary-btn" data-bs-toggle="modal"
                data-bs-target="#addPostModal">
                <iconify-icon icon="akar-icons:circle-plus" class="addpost-icon"></iconify-icon>
                <p>{{ __('msg.add post') }}</p>
            </button>
            
        </div>

        <div class="social-media-left-container-trigger">
            {{__('msg.friends')}}
            <iconify-icon icon="bi:arrow-right" class="arrow-icon"></iconify-icon>
        </div>

        <div class="social-media-overlay"></div>

        <div class="social-media-parent-container">
            <div class="social-media-left-container">
                <div class="social-media-left-search-cancel-container">
                    <div class="social-media-left-search-container">
                        <input type="text" id="search">
                        <iconify-icon icon="akar-icons:search" class="search-icon"></iconify-icon>
                    </div>
                    <div class="cancel">
                        <p class="customer-secondary-btn cancel">{{__('msg.cancel')}}</p>
                    </div>
                </div>
                <div class="social-media-left-infos-container">
                    <div class="social-media-left-friends-container">
                        <div class="social-media-left-container-header">
                            <p>{{__('msg.friends')}}</p>
                            <a href="{{ route('friendsList',auth()->user()->id) }}">{{__('msg.see all')}} <iconify-icon
                                    icon="bi:arrow-right" class="arrow-icon"></iconify-icon></a>
                        </div>
                        <div class="social-media-left-friends-rows-container">

                            @forelse ($left_friends as $friend)
                                <a href="{{ route('socialmedia.profile', $friend->id) }}"
                                    class="social-media-left-friends-row">
                                    <?php $profile = $friend->profiles->first();
                                    $profile_id = $friend->profile_id;
                                    $img = $friend->profiles->where('id', $profile_id)->first();
                                    ?>

                                    @if ($img == null)
                                        <img class="nav-profile-img"
                                            src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                                    @else
                                        <img class="nav-profile-img"
                                            src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$img->profile_image}}" />
                                    @endif
                                    <p>{{ $friend->name }}</p>
                                </a>
                            @empty
                                <p class="text-secondary p-1">No Friend</p>
                            @endforelse
                        </div>

                    </div>


                    <div class="social-media-left-messages-container">
                        <div class="social-media-left-container-header">
                            <p id="messages">{{__('msg.messages')}}</p>
                            <a href="{{ route('message.seeall') }}">{{__('msg.see all')}}<iconify-icon icon="bi:arrow-right"
                                    class="arrow-icon"></iconify-icon></a>
                        </div>

                        <div class="social-media-left-messages-rows-container">
                            {{-- @forelse ($latest_messages as $friend)
                                    <a href="{{route('message.chat',$friend->id)}}" class="social-media-left-messages-row unread-msg">
                                        @if ($friend->profile_image == null)
                                            <img  class="nav-profile-img" src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                                        @else
                                            <img  class="nav-profile-img" src="{{asset('storage/post/'.$friend->profile_image)}}"/>
                                        @endif

                                        <p>
                                            {{$friend->name}}<br>
                                            <span>{{$friend->text}} </span>
                                        </p>
                                    </a>
                                    @empty
                                    <p class="text-secondary p-1">No Messages</p>
                                    @endforelse --}}

                        </div>
                    </div>

                    {{-- <div class="social-media-left-gpmessages-container">
                                <div class="social-media-left-container-header">
                                    <p id = "testing">Group Messages</p>
                                    <a href="#">See All <iconify-icon icon="bi:arrow-right" class="arrow-icon"></iconify-icon></a>
                                </div>

                                <div class="social-media-left-gpmessages-rows-container"> --}}
                    {{-- @forelse ($chat_group as $group)
                                        <a href="{{route('socialmedia.group',$group->id)}}"             class="social-media-left-gpmessages-row">
                                    @if ($group->group_id != null)
                                        <a href="{{route('socialmedia.group',$group->group_id)}}" class="social-media-left-gpmessages-row">
                                            <img src="{{asset('img/customer/imgs/group_default.png')}}" class="w-25">
                                            <p>
                                            {{$group->group_name}}<br>
                                                <span>{{$group->text}} </span>
                                            </p>
                                        </a>
                                    @else
                                        <a href="{{route('socialmedia.group',$group->id)}}" class="social-media-left-gpmessages-row">
                                            <img src="{{asset('img/customer/imgs/group_default.png')}}" class="w-25">
                                            <p>
                                            {{$group->group_name}}<br>
                                                <span>{{$group->text}}</span>
                                            </p>
                                        </a>
                                        @endif
                                 @empty
                                        <p class="text-secondary p-1">No Group Messages</p>
                                @endforelse --}}


                    {{-- </div> --}}
                    {{-- </div> --}}
                </div>

                <div class="social-media-left-searched-items-container">

                </div>

            </div>

            @yield('content')
        </div>
    </div>
    {{-- <div class="customer-main-content-container"> --}}

    {{-- </div> --}}

    <script src="{{ asset('js/AgoraRTCSDK.js') }}"></script>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Sweet Alert -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
    <script src={{ asset('js/customer/js/customerRegisteration.js') }}></script>

    <!--nav bar-->
    <script src={{ asset('js/navBar.js') }}></script>
    <script src={{ asset('js/notify.js') }}></script>


    {{-- axios && Echo --}}
    <script src={{ asset('js/app.js') }}></script>
    {{-- pusher --}}
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    {{-- emoji --}}
    <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- comment emoji --}}
    <script src="https://twemoji.maxcdn.com/v/latest/twemoji.min.js"></script>
    <script src="{{ asset('js/customer/DisMojiPicker.js') }}"></script>



    <script src="{{ asset('js/customer/jquery.mentiony.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>

    <script>
        var user_id = {{ auth()->user()->id }};
        console.log(user_id);
        var pusher = new Pusher('{{ env('MIX_PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });
        var channel = pusher.subscribe('friend_request.' + user_id);
        channel.bind('friendRequest', function(data) {
            console.log(data, "noti_center");
            count = document.getElementById('noti_count').innerHTML;
            // alert(count);
            $( "#noti_count" ).load(window.location.href + " #noti_count" );
            $( ".notis-box-container" ).load(window.location.href + " .notis-box-container>*" );
            // document.getElementById("testing").text = data
            $.notify(data, "success", {
                position: "left"
            });
        });
        var channel = pusher.subscribe('chat_message.' + user_id);
        channel.bind('chat', function(data) {
            console.log("chat",data)
            let htmlView = '';
            for (let i = 0; i < data.length; i++) {
                var id = data[i].id;
                var url = "{{ route('message.chat', ':id') }}";
                url = url.replace(':id', id);
                var group_url = "{{ route('socialmedia.group', ':id') }}";
                group_url = group_url.replace(':id', id);
                text = data[i].text == null ? "" : data[i].text;
                console.log(data[i])
                if (data[i].is_group == 0) {
                    if (data[i].profile_image != null) {
                        if(Number(data[i].isRead) === 0){
                            htmlView += `<a href=` + url + ` class="social-media-left-messages-row unread-msg" id="0" data-id= `+id+`>
                                            <img  class="nav-profile-img" src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+data[i].profile_image+`"/>
                                        <p>
                                            ` + data[i].name + `<br>
                                            <span>` + text + ` </span>
                                        </p>
                                    </a>
                            `
                        }else{
                            htmlView += `<a href=` + url + ` class="social-media-left-messages-row " id="0" data-id= `+id+`>
                                            <img  class="nav-profile-img" src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+data[i].profile_image+`"/>
                                        <p>
                                            ` + data[i].name + `<br>
                                            <span>` + text + ` </span>
                                        </p>
                                    </a>
                            `
                        }
                        
                    } else {
                        if(Number(data[i].isRead) === 0){
                            htmlView += `<a href=` + url + ` class="social-media-left-messages-row unread-msg" id="0" data-id= `+id+`>
                                            <img  class="nav-profile-img" src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                                        <p>
                                            ` + data[i].name + `<br>
                                            <span>` + text + ` </span>
                                        </p>
                                    </a>
                            `
                        }else{
                            htmlView += `<a href=` + url + ` class="social-media-left-messages-row" id="0" data-id= `+id+`>
                                            <img  class="nav-profile-img" src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                                        <p>
                                            ` + data[i].name + `<br>
                                            <span>` + text + ` </span>
                                        </p>
                                    </a>
                            `
                        }
                        
                    }

                } else {
                    if(Number(data[i].isRead) === 0){
                        htmlView += `
                                    <a href=` + group_url + ` class="social-media-left-messages-row unread-msg" id="0" data-id= `+id+`>
                                            <img  class="nav-profile-img" src="{{ asset('img/customer/imgs/group_default.png') }}" />
                                        <p>
                                            ` + data[i].name + `<br>
                                            <span>` + text + ` </span>
                                        </p>
                                    </a>
                            `
                    }else{
                        htmlView += `
                                    <a href=` + group_url + ` class="social-media-left-messages-row" id="0" data-id= `+id+`>
                                            <img  class="nav-profile-img" src="{{ asset('img/customer/imgs/group_default.png') }}" />
                                        <p>
                                            ` + data[i].name + `<br>
                                            <span>` + text + ` </span>
                                        </p>
                                    </a>
                            `
                    }
                    
                }
            }
            $('.social-media-left-messages-rows-container').html(htmlView);
        });

        table()

        function table() {
            // alert("send");
            let htmlView = '';
            var latest_messages = @json($latest_messages);
            console.log(latest_messages, 'latest msg')
            if (latest_messages.length <= 0) {
                htmlView += `
                        No Messages.
                        `;
            }
            for (let i = 0; i < latest_messages.length; i++) {
                var id = latest_messages[i].id;
                var url = "{{ route('message.chat', ':id') }}";
                url = url.replace(':id', id);

                var group_url = "{{ route('socialmedia.group', ':id') }}";
                group_url = group_url.replace(':id', id);
                text = latest_messages[i].text == null ? "" : latest_messages[i].text;
                console.log(latest_messages[i].isRead)
                if (latest_messages[i].is_group == 0) {
                    if (latest_messages[i].profile_image === null) {
                        if(Number(latest_messages[i].isRead) === 0){
                            htmlView += `
                                    <a href=` + url + ` class="social-media-left-messages-row unread-msg" id="0" data-id= `+id+`>
                                            <img  class="nav-profile-img" src="{{ asset('img/customer/imgs/user_default.jpg') }}"/>
                                        <p>
                                            ` + latest_messages[i].name + `<br>
                                            <span>` + text + ` </span>
                                        </p>
                                    </a>
                            `
                        }else{
                            htmlView += `
                                    <a href=` + url + ` class="social-media-left-messages-row" id="0" data-id= `+id+`>
                                            <img  class="nav-profile-img" src="{{ asset('img/customer/imgs/user_default.jpg') }}"/>
                                        <p>
                                            ` + latest_messages[i].name + `<br>
                                            <span>` + text + ` </span>
                                        </p>
                                    </a>
                            `
                        }
                        
                    
                    } else {
                        if(Number(latest_messages[i].isRead) === 0){
                            htmlView += `
                                    <a href=` + url + ` class="social-media-left-messages-row unread-msg" id="0" data-id= `+id+`">
                                            <img  class="nav-profile-img" src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+latest_messages[i].profile_image+`"/>
                                        <p>
                                            ` + latest_messages[i].name + `<br>
                                            <span>` + text + ` </span>
                                        </p>
                                    </a>
                            `
                        }else{
                            htmlView += `
                                    <a href=` + url + ` class="social-media-left-messages-row" id="0" data-id= `+id+`">
                                            <img  class="nav-profile-img" src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+latest_messages[i].profile_image+`"/>
                                        <p>
                                            ` + latest_messages[i].name + `<br>
                                            <span>` + text + ` </span>
                                        </p>
                                    </a>
                            `
                        }
                        
                    }
                        
                

                } else {
                    if(Number(latest_messages[i].isRead) === 0){
                        htmlView += `
                                    <a href=` + group_url + ` class="social-media-left-messages-row unread-msg" id="1" data-id= `+id+`>
                                            <img  class="nav-profile-img" src="{{ asset('img/customer/imgs/group_default.png') }}"/>
                                        <p>
                                            ` + latest_messages[i].name + `<br>
                                            <span>` + text + ` </span>
                                        </p>
                                    </a>
                            `
                    }else{
                        htmlView += `
                                    <a href=` + group_url + ` class="social-media-left-messages-row" id="1" data-id= `+id+`>
                                            <img  class="nav-profile-img" src="{{ asset('img/customer/imgs/group_default.png') }}"/>
                                        <p>
                                            ` + latest_messages[i].name + `<br>
                                            <span>` + text + ` </span>
                                        </p>
                                    </a>
                            `
                    }
                    
                }
                    
                

            }
            $('.social-media-left-messages-rows-container').html(htmlView);
        }
        $(document).ready(function() {
            //report start
            $('#other_msg').hide();
        $('#report_submit').attr("class",'btn btn-primary disabled')
        console.log($("input[name='report_msg']:checked").val());

        $('input[name="report_msg"]').on('click', function() {
                if ($(this).val() == 'other') {
                    $('#other_msg').show();
                    $('#other_msg').keydown(function() {
                        if(!$('#other_msg').val()){
                            $('#report_submit').attr("class",'btn btn-primary disabled')
                        }else{
                            $('#report_submit').attr("class",'btn btn-primary')
                        }
                    })

                }else if ($(this).val() == 'nudity'){
                    $('#other_msg').hide();
                    $('#other_msg').val('');
                    $('#report_submit').attr("class",'btn btn-primary')

                }else if ($(this).val() == 'violence'){
                    $('#other_msg').hide();
                    $('#other_msg').val('')
                    $('#report_submit').attr("class",'btn btn-primary')
                }else if ($(this).val() == 'harassment'){
                    $('#other_msg').hide();
                    $('#other_msg').val('')
                    $('#report_submit').attr("class",'btn btn-primary')
                }else if ($(this).val() == 'suicide or self-injury'){
                    $('#other_msg').hide();
                    $('#other_msg').val('')
                    $('#report_submit').attr("class",'btn btn-primary')
                }else if ($(this).val() == 'false information'){
                    $('#other_msg').hide();
                    $('#other_msg').val('');
                    $('#report_submit').attr("class",'btn btn-primary')
                }else if ($(this).val() == 'spam'){
                    $('#other_msg').hide();
                    $('#other_msg').val('')
                    $('#report_submit').attr("class",'btn btn-primary')
                }else if ($(this).val() == 'hate speech'){
                    $('#other_msg').hide();
                    $('#other_msg').val('')
                    $('#report_submit').attr("class",'btn btn-primary')
                }else if ($(this).val() == 'terrorism'){
                    $('#other_msg').hide();
                    $('#other_msg').val('')
                    $('#report_submit').attr("class",'btn btn-primary')
                }
        });
        $(document).on('click', '#report', function(e){
            var post_id=$(this).data('id')
            $('#post_id').val(post_id)
            $('input[name="report_msg"]').prop('checked', false);
            $('#other_msg').hide();
            $('#other_msg').val('');
            $('#report_submit').attr("class",'btn btn-primary disabled')
            $('#reportmodal').modal('show');

        })

         $(document).on('click', '.social-media-left-messages-row', function(e){
            //e.preventDefault();
            var user_id =$(this).data('id')
            var isGroup = $(this).attr('id');
            var url = "{{ route('read.unread')}}";
            //alert(isGroup);
             $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                        $.ajax({
                            method: "POST",
                            url: url,
                            data:{ isGroup : isGroup , user_id: user_id },
                            success:function(data){

                            }
        })
        })

        

        $(document).on('click', '#comment_report', function(e){
            var comment_id=$(this).data('id')
            $('#comment_id').val(comment_id)
            $('input[name="report_msg"]').prop('checked', false);
            $('#other_msg').hide();
            $('#other_msg').val('');
            $('#report_submit').attr("class",'btn btn-primary disabled')
            $('#reportmodal').modal('show');

        })

        $(document).on('submit','#report_form',function(e){
            e.preventDefault()
            $('#reportmodal').modal('hide');
            var report_msg
            var post_id=$('#post_id').val();
            var comment_id=$('#comment_id').val();
            $('#post_id').val('')
            $('#comment_id').val('')
            var user_id={{auth()->user()->id}}

            if($('input[name="report_msg"]:checked').val()=='other'){
                report_msg=$("#other_msg").val();

            } else{
                 report_msg=$("input[name='report_msg']:checked").val();
            }

            var add_url = "{{ route('socialmedia.report')}}";
            $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                    $.ajax({
                        method: "POST",
                        url: add_url,
                        data:{ post_id : post_id ,comment_id : comment_id, user_id:user_id ,report_msg:report_msg},
                        success:function(data){
                            if(data.success){
                                Swal.fire({
                                        text: data.success,
                                        timerProgressBar: true,
                                        timer: 3000,
                                        icon: 'success',
                                    }).then((result) => {
                                        $('input[name="report_msg"]').prop('checked', false);
                                        $('#reportmodal').modal('hide');
                                        $('#other_msg').hide();
                                        $('#other_msg').val('');
                                    })
                            }
                        }
                    })

        })

            //report end

            //image slider start
            console.log($(".image-slider"))

            $.each($(".ul-image-slider"), function() {
                console.log($(this).children('li').length)

                $(this).children('li:first').addClass("active-img")
            })

            $.each($(".img-slider-thumbnails ul"), function() {
                console.log($(this).children('li').length)

                $(this).children('li:first').addClass("active")
            })

            $(function() {

                $('.img-slider-thumbnails li').click(function() {
                    var thisIndex = $(this).index()
                    // console.log(thisIndex,$(this).siblings("li.active").index())
                    if ($(this).siblings(".active").index() === -1) {
                        return
                    }


                    if (thisIndex < $(this).siblings(".active").index()) {
                        prevImage(thisIndex, $(this).parents(".img-slider-thumbnails").prev(
                            "#image-slider"));
                    } else if (thisIndex > $(this).siblings(".active").index()) {
                        nextImage(thisIndex, $(this).parents(".img-slider-thumbnails").prev(
                            "#image-slider"));
                    }


                    $(this).siblings('.active').removeClass('active');
                    $(this).addClass('active');

                });

            });

            var width = $('#image-slider').width();
            console.log(width)

            function nextImage(newIndex, parent) {
                parent.find('li').eq(newIndex).addClass('next-img').css('left', width).animate({
                    left: 0
                }, 600);
                parent.find('li.active-img').removeClass('active-img').css('left', '0').animate({
                    left: '-100%'
                }, 600);
                parent.find('li.next-img').attr('class', 'active-img');
            }

            function prevImage(newIndex, parent) {
                parent.find('li').eq(newIndex).addClass('next-img').css('left', -width).animate({
                    left: 0
                }, 600);
                parent.find('li.active-img').removeClass('active-img').css('left', '0').animate({
                    left: '100%'
                }, 600);
                parent.find('li.next-img').attr('class', 'active-img');
            }

            /* Thumbails */
            // var ThumbailsWidth = ($('#image-slider').width() - 18.5)/7;
            // $('#thumbnail li').find('img').css('width', ThumbailsWidth);

            $('.social-media-media-slider').hide()

            $(document).on('click', '#photo_view_count', function(e) {
                // alert("ok");
                $(this).siblings(".social-media-media-slider").show()
                $(this).hide()
                var post_id=$(this).data('id');
                var add_url = "{{ route('user.view.post1') }}";
                $.ajax({
                        method: "GET",
                        url: add_url,
                        data:{ post_id : post_id},
                        success: function(data) {
                            
                            document.querySelector(`#viewers`+post_id).innerHTML = data.data
                                // alert();
                                console.log(data.data);
                                
                    }
                    })

            })

            $(".slider-close-icon").click(function() {
                $(this).closest('.social-media-media-slider').hide()
                $(this).closest('.social-media-media-slider').siblings('.social-media-media-container')
                    .show()
            })
            //image slider end




            $(".cancel").hide();
            $(".social-media-left-search-container input").focus(function() {
                // alert( "Handler for .focus() called." );
                $(".social-media-left-infos-container").hide()
                $(".social-media-left-searched-items-container").show()
                $(".cancel").show();
            });

            $(document).on('click', '.cancel', function(e) {
                // alert( "Handler for .focus() called." );
                $(".social-media-left-infos-container").show()
                $(".social-media-left-searched-items-container").hide()
                $(".cancel").hide()
                $('.social-media-left-search-container input').val('')
            });

            $(document).on('click', '#delete_post', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                Swal.fire({
                    text: 'Are you sure to delete this post?',
                    timerProgressBar: true,
                    showCloseButton: true,
                    showCancelButton: true,
                    icon: 'warning',
                }).then((result) => {
                    if (result.isConfirmed) {
                        var add_url = "{{ route('post.destroy', [':id']) }}";
                        add_url = add_url.replace(':id', id);

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            method: "POST",
                            url: add_url,
                            datatype: "json",
                            success: function(data) {
                                window.location.reload();
                            }
                        })
                    } else {

                    }
                })

            })

            $(document).on('click', '#AddFriend', function(e) {
                e.preventDefault();
                $('.social-media-left-searched-items-container').empty();
                var url = new URL(this.href);

                var id = url.searchParams.get("id");
                var group_id = $(this).attr("id");

                var add_url = "{{ route('addUser', [':id']) }}";
                add_url = add_url.replace(':id', id);
                $(".add-member-btn").attr('href', '');
                $.ajax({
                    type: "GET",
                    url: add_url,
                    datatype: "json",
                    success: function(data) {
                        console.log(data)
                        search();
                    }
                })
            });

            $(document).on('click', '#cancelRequest', function(e) {
                e.preventDefault();
                Swal.fire({
                    text: "Are you sure?",
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    },
                    showCancelButton: true,
                    timerProgressBar: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',

                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        var url = new URL(this.href);
                        var id = url.searchParams.get("id");
                        var url = "{{ route('cancelRequest', [':id']) }}";
                        url = url.replace(':id', id);
                        $(".cancel-request-btn").attr('href', '');
                        $.ajax({
                            type: "GET",
                            url: url,
                            datatype: "json",
                            success: function(data) {
                                console.log(data)
                                search();
                            }
                        })

                    }
                })


                $('.social-media-left-searched-items-container').empty();
            });


            $('.social-media-left-search-container input').on('keyup', function() {
                search();
            });

            function search() {

                var keyword = $('#search').val();
                //console.log(keyword);
                var search_url = "{{ route('search_users') }}";
                $.post(search_url, {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        keyword: keyword
                    },
                    function(data) {
                        table_post_row(data);
                        console.log(data);
                    });
            }
            // table row with ajax
            function table_post_row(res) {
                var auth_id = {{ auth()->user()->id }}
                let htmlView = '';
                if (res.users.length <= 0) {
                    htmlView += `
                                No data found.
                                `;
                } else if (res.friends.length <= 0 && res.users.length != 0) {
                    console.log("myself");
                    for (let i = 0; i < res.users.length; i++) {
                        id = res.users[i].id;
                        var url = "{{ route('socialmedia.profile', [':id']) }}";
                        url = url.replace(':id', id);
                        if (res.users[i].id === auth_id) {
                            htmlView += `
                                            <div class="social-media-left-searched-item">
                                            <a href=` + url + ` class = "profiles">
                                                <p>` + res.users[i].name + `</p>
                                            </a>
                                            </div>
                                    `
                        } else {
                            console.log("no friends");
                            htmlView += `
                                            <div class="social-media-left-searched-item">
                                            <a href=` + url + ` class = "profiles">
                                                <p>` + res.users[i].name + `</p>
                                            </a>
                                            <a href="?id=` + res.users[i].id + `"  id = "AddFriend"><iconify-icon icon="bi:person-add" class="search-item-icon"></iconify-icon></a>
                                            </div>
                                    `
                        }
                    }
                } else {
                    for (let i = 0; i < res.users.length; i++) {
                        var status = ''
                        for (let f = 0; f < res.friends.length; f++) {
                            id = res.users[i].id;
                            var url = "{{ route('socialmedia.profile', [':id']) }}";
                            url = url.replace(':id', id);
                            console.log(auth_id)

                            if (res.users[i].id === res.friends[f].receiver_id &&
                                res.friends[f].sender_id === auth_id &&
                                res.friends[f].friend_status === 1) {
                                console.log(res.users[i].name, 'sender request')
                                status = 'sender request'
                                break
                                // return
                                // htmlView += `
                            //     <a href=`+url+` class = "profiles">
                            //         <p>`+res.users[i].name+`</p>
                            //     </a>
                            //     <a href="?id=` + res.users[i].id+`" class="customer-secondary-btn cancel-request-btn"
                            //     id = "cancelRequest">Cancel Request</a>
                            //     `
                            } else if (
                                res.users[i].id === res.friends[f].sender_id &&
                                res.friends[f].receiver_id === auth_id &&
                                res.friends[f].friend_status === 1
                            ) {
                                console.log(res.users[i].name, 'receiver request')
                                status = 'receiver request'
                                break
                                // return
                                // htmlView += `
                            //     <a href=`+url+` class = "profiles">
                            //         <p>`+res.users[i].name+`</p>
                            //     </a>
                            //     <a href="?id=` + res.users[i].id+`" class="customer-secondary-btn cancel-request-btn"
                            //     id = "cancelRequest">Response</a>
                            //     `
                            } else if (res.users[i].id === auth_id) {
                                console.log(res.users[i].name, 'profile')
                                status = "profile"
                                break
                                // return
                                // htmlView += `
                            //     <a href=`+url+` class = "profiles">
                            //         <p>`+res.users[i].name+`</p>
                            //     </a>
                            //     <a href=`+url+` class="customer-secondary-btn "
                            //     >View Profile</a>
                            //     `
                            } else if (res.users[i].id === res.friends[f].receiver_id &&
                                res.friends[f].sender_id === auth_id &&
                                res.friends[f].friend_status === 2
                            ) {
                                console.log(res.users[i].name, 'sender view profile')
                                status = "sender view profile"
                                break
                                // return
                                // htmlView += `
                            //     <a href= `+url+` class = "profiles">
                            //         <p>`+res.users[i].name+`</p>
                            //     </a>
                            //     <a href="?id=` + res.users[i].id+`" class="customer-secondary-btn add-friend-btn">Friend</a>
                            //     `
                            } else if (
                                res.users[i].id === res.friends[f].sender_id &&
                                res.friends[f].receiver_id === auth_id &&
                                res.friends[f].friend_status === 2
                            ) {
                                console.log(res.users[i].name, 'receiver view profile')
                                status = "receiver view profile"
                                break
                                // return
                                // htmlView += `
                            //     <a href= `+url+` class = "profiles">
                            //         <p>`+res.users[i].name+`</p>
                            //     </a>
                            //     <a href="?id=` + res.users[i].id+`" class="customer-secondary-btn add-friend-btn">Friend</a>
                            //   `
                            } else {
                                status = "add fri"
                                console.log(res.users[i].name, 'add fri')
                                //     htmlView += `
                            //         <a href=`+url+` class = "profiles">
                            //             <p>`+res.users[i].name+`</p>
                            //         </a>
                            //         <a href="?id=` + res.users[i].id+`" class="customer-secondary-btn add-friend-btn" id = "AddFriend">Add</a>
                            // `
                            }

                        }

                        if (status === 'sender request') {
                            htmlView += `
                                            <div class="social-media-left-searched-item">
                                            <a href=` + url + ` class = "profiles">
                                                <p>` + res.users[i].name + `</p>
                                            </a>
                                            <a href="?id=` + res.users[i].id + `" class="cancel-request-btn"
                                            id = "cancelRequest"><iconify-icon icon="material-symbols:cancel-schedule-send-outline" class="search-item-icon"></iconify-icon></a>
                                            </div>
                                            `
                        } else if (status === 'receiver request') {
                            htmlView += `
                                            <div class="social-media-left-searched-item">
                                            <a href=` + url + ` class = "profiles">
                                                <p>` + res.users[i].name + `</p>
                                            </a>
                                            <a href=` + url + `><iconify-icon icon="mdi:account-question-outline" class="search-item-icon"></iconify-icon></a>
                                            </div>
                                            `
                        } else if (status === "profile") {
                            htmlView += `
                                            <div class="social-media-left-searched-item">
                                            <a href=` + url + ` class = "profiles">
                                                <p>` + res.users[i].name + `</p>
                                            </a>
                                            </div>
                                            `
                        } else if (status === "sender view profile") {
                            htmlView += `
                                            <div class="social-media-left-searched-item">
                                            <a href= ` + url + ` class = "profiles">
                                                <p>` + res.users[i].name + `</p>
                                            </a>
                                            <a href=` + url + ` ><iconify-icon icon="ion:people-sharp" class="search-item-icon"></iconify-icon></a>
                                            </div>
                                          `
                        } else if (status === "receiver view profile") {
                            htmlView += `
                                            <div class="social-media-left-searched-item">
                                            <a href= ` + url + ` class = "profiles">
                                                <p>` + res.users[i].name + `</p>
                                            </a>
                                            <a href=` + url + `><iconify-icon icon="ion:people-sharp" class="search-item-icon"></iconify-icon></a>
                                            </div>
                                          `
                        } else {
                            htmlView += `
                                            <div class="social-media-left-searched-item">
                                            <a href=` + url + ` class = "profiles">
                                                <p>` + res.users[i].name + `</p>
                                            </a>
                                            <a href="?id=` + res.users[i].id + `"  id = "AddFriend"><iconify-icon icon="bi:person-add" class="search-item-icon"></iconify-icon></a>
                                            </div>
                                    `
                        }
                    }
                }
                $('.social-media-left-searched-items-container').html(htmlView);
            }



            $('.social-media-post-header-icon').click(function() {
                $(this).next().toggle()
            })

            $(".social-media-left-container-trigger").click(function() {
                $('.social-media-left-container').toggleClass("social-media-left-container-open")
                $('.social-media-overlay').toggle()
                $(".social-media-left-container-trigger .arrow-icon").toggleClass("rotate-arrow")
            })

            // $('#form').submit(function(e) {
            //     e.preventDefault();

            //     var url = "{{ route('post.store') }}";

            //             $.ajaxSetup({
            //                 headers: {
            //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //                 }
            //             });

            //             $.ajax({
            //                 type: 'POST',
            //                 url: "{{ route('post.store') }}",
            //                 processData: false,
            //                 cache: false,
            //                 contentType: false,
            //                 success: function(data) {
            //                 }
            //         })
            // })

            $('#form').submit(function(e) {

                e.preventDefault();
                var totalSize = 0;

                $("#addPostInput").each(function() {
                    for (var i = 0; i < this.files.length; i++) {
                    totalSize += this.files[i].size;
                    }
                });

                var valid = totalSize <= 157286400;

                console.log(valid)


                var caption = $('#addPostCaption').val();

                var url = "{{ route('post.store') }}";
                var $fileUpload = $('#addPostInput');

                if (!$('.addpost-caption-input').val() && parseInt($fileUpload.get(0).files.length) === 0) {
                    Swal.fire({
                            text: "Cannot Post",
                            timerProgressBar: true,
                            timer: 5000,
                            icon: 'warning',
                        });
                } else {
                    if (parseInt($fileUpload.get(0).files.length) > 5) {
                        Swal.fire({
                            text: "You can only upload a maximum of 5 files",
                            timerProgressBar: true,
                            timer: 5000,
                            icon: 'warning',
                        });
                    }else if(!valid){
                        Swal.fire({
                            text: "You cannot upload more than 150MBs",
                            timerProgressBar: true,
                            timer: 5000,
                            icon: 'warning',
                        });
                    }

                    else {
                        e.preventDefault();
                        $('#addPostModal').modal('hide');
                        $('.addpost-submit-btn').prop("disabled", true)
                        let formData = new FormData(form);

                        const totalImages = $("#addPostInput")[0].files.length;
                        let images = $("#addPostInput")[0];

                        for (let i = 0; i < totalImages; i++) {
                            formData.append('images' + i, images.files[i]);
                        }
                        formData.append('totalImages', totalImages);

                        var caption = $('#addPostCaption').val();

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('post.store') }}",
                            data: formData,
                            processData: false,
                            cache: false,
                            contentType: false,
                            success: function(data) {
                                if (data.message) {
                                    Swal.fire({
                                        text: data.message,
                                        timerProgressBar: true,
                                        timer: 5000,
                                        icon: 'success',
                                    }).then(() => {
                                        window.location.reload()
                                    })
                                } else {
                                    Swal.fire({
                                        text: data.ban,
                                        timerProgressBar: true,
                                        timer: 5000,
                                        icon: 'error',
                                    }).then(() => {
                                        window.location.reload()
                                    })
                                }

                            }
                        });

                    }
                }

            })



            $(document).on('click', '#edit_post', function(e) {
                sessionStorage.clear();
                e.preventDefault();
                $(".editpost-photo-video-imgpreview-container").empty();

                dtEdit.clearData()
                document.getElementById('editPostInput').files = dtEdit.files;
                var id = $(this).data('id');

                $('#editPostModal').modal('show');
                var add_url = "{{ route('post.edit', [':id']) }}";
                add_url = add_url.replace(':id', id);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "POST",
                    url: add_url,
                    datatype: "json",
                    success: function(data) {
                        if (data.status == 400) {
                            alert(data.message)
                        } else {
                            $('#editPostCaption').val(data.post.caption);
                            $('#edit_post_id').val(data.post.id);

                            var filesdb = data.post.media ? JSON.parse(data.post.media) : [];
                            console.log(data.post.media,'media');
                            console.log(data.imageData,'image data');
                            var imageDataDb = data.imageData
                            // var filesAmount=files.length;
                            var storedFilesdb = filesdb;
                            // console.log(storedFilesdb)


                            filesdb.forEach(function(f) {
                                fileExtension = f.replace(/^.*\./, '');
                                console.log(fileExtension);
                                if (fileExtension == 'mp4') {
                                    var html = "<div class='addpost-preview'>\
                                                    <iconify-icon icon='akar-icons:cross' data-file='" + f + "' class='delete-preview-db-icon'></iconify-icon>\
                                                    <video controls><source src='https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/" + f + "' data-file='" + f +
                                        "' class='selFile' title='Click to remove'>" +
                                        f + "<br clear=\"left\"/>\
                                                    <video>\
                                                </div>"
                                    $(".editpost-photo-video-imgpreview-container")
                                        .append(html);

                                } else {
                                    var html =
                                        "<div class='addpost-preview'><iconify-icon icon='akar-icons:cross' data-file='" +
                                        f + "' class='delete-preview-db-icon'></iconify-icon>\
                                                    <img src='https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/" + f + "' data-file='" + f +
                                        "' class='selFile' title='Click to remove'></div>";
                                    $(".editpost-photo-video-imgpreview-container")
                                        .append(html);
                                }

                            });

                            $("body").on("click", ".delete-preview-db-icon", removeFiledb);

                            function removeFiledb() {
                                var file = $(this).data('file')
                                storedFilesdb = storedFilesdb.filter((item) => {
                                    return file !== item
                                })
                                imageDataDb = imageDataDb.filter((item) => {
                                    return file !== item.name
                                })


                                $(this).parent().remove();
                            }

                            $(".addpost-photovideo-clear-btn").click(function() {
                                storedFilesdb = []
                            })

                                $('#edit_form').off('submit').on('submit', function (e) {
                                e.preventDefault();
                                $('#editPostModal').modal('hide');

                                var totalSize = 0;

                                $("#editPostInput").each(function() {
                                    for (var i = 0; i < this.files.length; i++) {
                                    totalSize += this.files[i].size;
                                    }
                                });

                                for(var j = 0;j < imageDataDb.length;j++){
                                    totalSize += imageDataDb[j].size
                                }

                                var valid = totalSize <= 157286400;
                                var fileUpload = $('#editPostInput');
                                console.log(storedFilesdb.length);
                                console.log(parseInt(fileUpload.get(0).files.length));
                                console.log(storedFilesdb);
                                console.log(fileUpload.get(0).files);

                                if (!$('#editPostCaption').val() && (parseInt(fileUpload
                                            .get(0).files.length) + storedFilesdb
                                        .length) === 0) {
                                    alert("Cannot post!!")
                                } else {
                                    if ((parseInt(fileUpload.get(0).files.length)) +
                                        storedFilesdb.length > 5) {
                                        Swal.fire({
                                            text: "You can only upload a maximum of 5 files",
                                            timer: 5000,
                                            icon: 'warning',
                                        });
                                    }else if(!valid){
                                        Swal.fire({
                                            text: "You cannot upload more than 150MBs",
                                            timerProgressBar: true,
                                            timer: 5000,
                                            icon: 'warning',
                                        });
                                    }

                                    else {
                                        e.preventDefault();

                                        var url = "{{ route('post.update') }}";
                                        let formData = new FormData(edit_form);
                                        var oldimg = storedFilesdb;
                                        var edit_post_id = $('#edit_post_id').val();
                                        var caption = $('#editPostCaption').val();

                                        const totalImages = $("#editPostInput")[0].files
                                            .length;
                                        let images = $("#editPostInput")[0];

                                        // for (let i = 0; i < totalImages; i++) {
                                        formData.append('images', images);
                                        // }
                                        formData.append('totalImages', totalImages);
                                        formData.append('caption', caption);
                                        formData.append('oldimg', storedFilesdb);
                                        formData.append('edit_post_id', edit_post_id);

                                        for (const value of formData.values()) {
                                            console.log(value);
                                        }

                                        $.ajaxSetup({
                                            headers: {
                                                'X-CSRF-TOKEN': $(
                                                    'meta[name="csrf-token"]'
                                                ).attr('content')
                                            }
                                        });

                                        $.ajax({
                                            type: 'POST',
                                            url: url,
                                            data: formData,
                                            processData: false,
                                            cache: false,
                                            contentType: false,
                                            success: function(data) {
                                                if (data.ban) {
                                                    Swal.fire({
                                                        text: data
                                                            .ban,
                                                        timer: 5000,
                                                        timerProgressBar: true,
                                                        icon: 'error',
                                                    })
                                                } else {
                                                    Swal.fire({
                                                        text: data
                                                            .success,
                                                        timer: 5000,
                                                        timerProgressBar: true,
                                                        icon: 'success',
                                                    }).then(() => {
                                                        window
                                                            .location
                                                            .reload()
                                                    })
                                                }
                                            }
                                        });
                                    }

                                }
                            })

                        }

                    }
                })

            })

            $("#addPostInput").on("change", handleFileSelect);

            $("#editPostInput").on("change", handleFileSelectEdit);

            selDiv = $(".addpost-photo-video-imgpreview-container");

            console.log(selDiv);

            $("body").on("click", ".delete-preview-icon", removeFile);
            $("body").on("click", ".delete-preview-edit-input-icon", removeFileFromEditInput);

            console.log($("#selectFilesM").length);
        });


        var selDiv = "";

        var storedFiles = [];
        var storedFilesEdit = [];
        const dt = new DataTransfer();
        const dtEdit = new DataTransfer();

        function handleFileSelect(e) {

            var files = e.target.files;
            console.log(files)

            var filesArr = Array.prototype.slice.call(files);

            var device = $(e.target).data("device");

            filesArr.forEach(function(f) {
                console.log(f);
                if (f.type.match("image.*")) {
                    storedFiles.push(f);

                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var html =
                            "<div class='addpost-preview'><iconify-icon icon='akar-icons:cross' data-file='" + f
                            .name + "' class='delete-preview-icon'></iconify-icon><img src=\"" + e.target
                            .result + "\" data-file='" + f.name +
                            "' class='selFile' title='Click to remove'></div>";

                        if (device == "mobile") {
                            $("#selectedFilesM").append(html);
                        } else {
                            $(".addpost-photo-video-imgpreview-container").append(html);
                        }
                    }
                    reader.readAsDataURL(f);
                    dt.items.add(f);
                } else if (f.type.match("video.*")) {
                    storedFiles.push(f);

                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var html =
                            "<div class='addpost-preview'><iconify-icon icon='akar-icons:cross' data-file='" + f
                            .name +
                            "' class='delete-preview-icon'></iconify-icon><video controls><source src=\"" + e
                            .target.result + "\" data-file='" + f.name +
                            "' class='selFile' title='Click to remove'>" + f.name +
                            "<br clear=\"left\"/><video></div>";

                        if (device == "mobile") {
                            $("#selectedFilesM").append(html);
                        } else {
                            $(".addpost-photo-video-imgpreview-container").append(html);
                        }
                    }
                    reader.readAsDataURL(f);
                    dt.items.add(f);
                }


            });

            document.getElementById('addPostInput').files = dt.files;
            console.log(document.getElementById('addPostInput').files + " Add Post Input")

        }

        function handleFileSelectEdit(e) {

            var files = e.target.files;
            console.log(files)

            var filesArr = Array.prototype.slice.call(files);

            var device = $(e.target).data("device");

            filesArr.forEach(function(f) {

                if (f.type.match("image.*")) {
                    storedFilesEdit.push(f);

                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var html =
                            "<div class='addpost-preview'><iconify-icon icon='akar-icons:cross' data-file='" + f
                            .name + "' class='delete-preview-edit-input-icon'></iconify-icon><img src=\"" + e
                            .target.result + "\" data-file='" + f.name +
                            "' class='selFile' title='Click to remove'></div>";

                        if (device == "mobile") {
                            $("#selectedFilesM").append(html);
                        } else {
                            $(".editpost-photo-video-imgpreview-container").append(html);
                        }
                    }
                    reader.readAsDataURL(f);
                    dtEdit.items.add(f);
                } else if (f.type.match("video.*")) {
                    storedFilesEdit.push(f);

                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var html =
                            "<div class='addpost-preview'><iconify-icon icon='akar-icons:cross' data-file='" + f
                            .name +
                            "' class='delete-preview-edit-input-icon'></iconify-icon><video controls><source src=\"" +
                            e.target.result + "\" data-file='" + f.name +
                            "' class='selFile' title='Click to remove'>" + f.name +
                            "<br clear=\"left\"/><video></div>";

                        if (device == "mobile") {
                            $("#selectedFilesM").append(html);
                        } else {
                            $(".editpost-photo-video-imgpreview-container").append(html);
                        }
                    }
                    reader.readAsDataURL(f);
                    dtEdit.items.add(f);
                }

            });

            document.getElementById('editPostInput').files = dtEdit.files;
            console.log(document.getElementById('editPostInput').files + " Edit Post Input")

        }

        function removeFile(e) {
            var file = $(this).data("file");
            var names = [];
            for (let i = 0; i < dt.items.length; i++) {
                if (file === dt.items[i].getAsFile().name) {
                    dt.items.remove(i);
                }
            }
            document.getElementById('addPostInput').files = dt.files;

            for (var i = 0; i < storedFiles.length; i++) {
                if (storedFiles[i].name === file) {
                    storedFiles.splice(i, 1);
                    break;
                }
            }
            $(this).parent().remove();
        }

        function removeFileFromEditInput(e) {
            var file = $(this).data("file");
            var names = [];
            for (let i = 0; i < dtEdit.items.length; i++) {
                if (file === dtEdit.items[i].getAsFile().name) {
                    dtEdit.items.remove(i);
                }
            }
            document.getElementById('editPostInput').files = dtEdit.files;

            for (var i = 0; i < storedFilesEdit.length; i++) {
                if (storedFilesEdit[i].name === file) {
                    storedFilesEdit.splice(i, 1);
                    break;
                }
            }
            $(this).parent().remove();
        }


        function clearAddPost() {
            storedFiles = []
            dt.clearData()
            document.getElementById('addPostInput').files = dt.files;
            $(".addpost-photo-video-imgpreview-container").empty();
        }

        function clearEditPost() {
            storedFilesEdit = []
            dtEdit.clearData()
            document.getElementById('editPostInput').files = dtEdit.files;
            $(".editpost-photo-video-imgpreview-container").empty();

        }
    </script>

    <script>
        $(document).ready(function() {

            $('.nav-icon').click(function() {
                $('.notis-box-container').toggle()
            })


            $(document).on('click', '.report_noti', function(e) {
                e.preventDefault();
                var url = new URL(this.href);
                var id = url.searchParams.get("id");
                // console.log(id, "noti_id");
                var sender_id = $(this).attr("id");
                var social_url = "{{ route('socialmedia.profile', [':id']) }}";
                social_url = social_url.replace(':id', sender_id);

                var url = "{{ route('noti.status') }}";
                $(".add-member-btn").attr('href', '');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: url,
                    datatype: "json",
                    data: {
                        id: sender_id,
                        noti_id: id
                    },
                    success: function(data) {
                        console.log(data)
                        // window.location.href = social_url
                    }
                })
            });

            $(document).on('click', '.accept', function(e) {
                e.preventDefault();
                // alert("okk")
                
                var url = new URL(this.href);
                var id = url.searchParams.get("id");
                //  alert(id);
                console.log(id, "noti_id");
                var sender_id = $(this).attr("id");
                console.log(sender_id, "rererer");
                var social_url = "{{ route('socialmedia.profile', [':id']) }}";
                social_url = social_url.replace(':id', sender_id);

                var url = "{{ route('social_media_profile') }}";
                $(".add-member-btn").attr('href', '');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: url,
                    datatype: "json",
                    data: {
                        id: sender_id,
                        noti_id: id
                    },
                    success: function(data) {
                        console.log(data)
                        window.location.href = social_url
                    }
                })
            });

            $(document).on('click', '.view_comment', function(e) {
                e.preventDefault();
                // alert("view_post")
                var url = new URL(this.href);
                var id = url.searchParams.get("id");
                //  console.log(id,"noti_id");
                var post_id = $(this).attr("id");
                
                var comment_url = "{{ route('post.comment', [':id']) }}";
                comment_url = comment_url.replace(':id', post_id);

                var url = "{{ route('comment_list', [':id']) }}";
                url = url.replace(':id', post_id);
                $(".add-member-btn").attr('href', '');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: url,
                    datatype: "json",
                    data: {
                        id: post_id,
                        noti_id: id
                    },
                    success: function(data) {
                            window.location.href = comment_url
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            text: "Post Deleted!",
                            confirmButtonColor: '#3CDD57',
                            timer: 3000,
                        })
                    }
            
                })
            });

            $(document).on('click', '.view_like', function(e) {
                e.preventDefault();
                // alert("view_post")
                var url = new URL(this.href);
                var id = url.searchParams.get("id");
                //  console.log(id,"noti_id");
                var post_id = $(this).attr("id");
               
                var like_url = "{{ route('social_media_likes', [':post_id']) }}";
                like_url = like_url.replace(':post_id', post_id);

                var url = "{{ route('social_media_likes', [':post_id']) }}";
                url = url.replace(':post_id', post_id);
                console.log(url);
                $(".add-member-btn").attr('href', '');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "GET",
                    url: url,
                    datatype: "json",
                    data: {
                        id: post_id,
                        noti_id: id
                    },
                    success: function(data) {
                        //console.log(data)
                        window.location.href = like_url
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            text: "Post Deleted!",
                            confirmButtonColor: '#3CDD57',
                            timer: 3000,
                        })
                    }
                })
            });

        })
    </script>

    {{-- video call script start --}}
    <script>
        $(".chat-backdrop").hide();

        let authuserId = "{{ auth()->user()->id }}"
        let authuser = "{{ auth()->user()->name }}"
        let incomingCall = false;
        let incomingAudioCall = false;
        let receiver_user_id = null;
        const agora_id = 'e8d6696cc7dc449dbd78ebbd1e15ee13'

        let video_container = document.getElementById('video-main-container')

        let callPlaced = false
        let localStream = null
        let incomingCaller = "";
        let agoraChannel = null

        let videoCallEvent = false;
        let audioCallEvent = false;
        let mutedVideo = false
        let mutedAudio = false

        let myArray = null;
        let authuser_name = null
        let receiveruser_name = null

        let friend_data= @json($left_friends);
        console.log('friends',friend_data);

        Echo.channel('agora-videocall')
            .listen(".MakeAgoraCall", ({
                data
            }) => {

                myArray = data.channelName.split("_");
                receiveruser_name = myArray[0];
                receiver_user_id = data.from
                if (parseInt(data.userToCall) === parseInt(authuserId)) {

                    incomingCall = true


                    if (incomingCall) {
                        $(".chat-backdrop").show();

                        incomingCallContainer.innerHTML = `<div class="row my-5" id="incoming_call">

                                <div class="card shadow p-4 col-12">
                                    <p>
                                        Calling from ${receiveruser_name}
                                    </p>
                                    <div class="d-flex justify-content-center gap-3">
                                        <button type="button" class="btn btn-sm btn-danger"  id="" onclick="declineCall()">
                                            Decline
                                        </button>
                                        <button type="button" class="btn btn-sm btn-success ml-5" onclick="acceptCall()">
                                            Accept
                                        </button>
                                    </div>
                                </div>
                            </div>`;


                    }
                    agoraChannel = data.channelName
                }
            }).listen(".MakeAgoraAudioCall", ({
                data
            }) => {

                myArray = data.channelName.split("_");
                receiveruser_name = myArray[0];
                receiver_user_id = data.from
                if (parseInt(data.userToCall) === parseInt(authuserId)) {



                    incomingCall = true
                    incomingAudioCall = true

                    if (incomingCall) {
                        $(".chat-backdrop").show();
                        if (incomingAudioCall) {
                            incomingCallContainer.innerHTML = `<div class="row my-5" id="incoming_call">

                                <div class="card shadow p-4 col-12">
                                    <p>
                                        Calling from ${receiveruser_name}
                                    </p>
                                    <div class="d-flex justify-content-center gap-3">
                                        <button type="button" class="btn btn-sm btn-danger"  id="" onclick="declineCall()">
                                            Decline
                                        </button>
                                        <button type="button" class="btn btn-sm btn-success ml-5" onclick="acceptCall()">
                                            Accept
                                        </button>
                                    </div>
                                </div>
                            </div>`;

                        }


                    }
                    agoraChannel = data.channelName
                }
            }).listen(".DeclineCallUser", ({
                data
            }) => {

                friend_data.forEach(friend => {
                    if(parseInt(data.userFromCall) == friend.id){
                        video_container.innerHTML = "";
                    $(".chat-backdrop").hide();
                    location.reload(true)
                    }
               });

            })



        async function placeCall(id, call_name) {

            try {
                const channelName = `${authuser}_${call_name}`;
                const tokenRes = await generateToken(channelName)

                console.log(tokenRes.data);
                console.log(tokenRes, "call Token")
                axios.post("/agora/call-user", {
                    user_to_call: id,
                    username: authuser,
                    channel_name: channelName,
                });
                initializeAgora()
                joinRoom(tokenRes.data, channelName)
                callPlaced = true

                videoCallEvent = true;


            } catch (error) {
                console.log("No internet connection");
            }
        }

        async function placeCallAudio(id, call_name) {
            try {
                const channelName = `${authuser}_${call_name}`;
                const tokenRes = await generateToken(channelName);

                console.log(tokenRes.data);

                axios.post("/agora/call-audio-user", {
                    user_to_call: id,
                    username: authuser,
                    channel_name: channelName,
                });
                initializeAgora()
                joinRoom(tokenRes.data, channelName)
                callPlaced = true;
                incomingAudioCall = true;

                audioCallEvent = true;

            } catch (error) {
                console.log(error);
            }
        }


        function generateToken(channelName) {
            return axios.post("/agora/token", {
                channelName,
            });
        }

        function initializeAgora() {
            client = AgoraRTC.createClient({
                mode: "rtc",
                codec: "h264"
            });
            client.init(
                agora_id,
                () => {
                    console.log("AgoraRTC client initialized");
                },
                (err) => {
                    console.log("AgoraRTC client init failed", err);
                }
            );
        }

        async function acceptCall() {
            console.log('call accept');
            initializeAgora();
            const tokenRes = await generateToken(agoraChannel);
            joinRoom(tokenRes.data, agoraChannel);
            incomingCall = false;
            callPlaced = true;
            videoCallEvent = true;
            incomingCallContainer.innerHTML = ""
            console.log(tokenRes, "accept")
        }

        function declineCall() {
            incomingCall = false;
            incomingCallContainer.innerHTML = "";
            $(".chat-backdrop").hide();
            // nc start
            axios.post("/agora/decline-call-user", {
                user_from_call: authuserId
            });

        }

        async function joinRoom(token, channel) {
            console.log(token, channel);
            client.join(
                token,
                channel,
                authuser,
                (uid) => {
                    console.log("User " + uid + " join channel successfully");
                    callPlaced = true

                    console.log("incoming audio call lay pr", incomingAudioCall);

                    if (callPlaced) {

                        $("#video-main-container").show()
                        $(".chat-backdrop").show();
                        if (incomingAudioCall) {
                            video_container.innerHTML += `
                                                    <div id="audio-container">
                                                       <div id="local-audio"></div>
                                                        <div id="remote-audio"></div>
                                                    <div class="text-center ">
                                                        <p class="text-black">Audio Call</p>
                                                    </div>
                                                    <div class="action-btns">
                                                        <button type="button" class="btn btn-info p-2 me-3" id="muteAudio" onclick="handleAudioToggle(this)">
                                                            <i class="fa-solid fa-microphone-slash" style="width:30px"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger p-2" onclick="endCall()">
                                                            <i class="fa-solid fa-phone-slash" style="width:30px"></i>
                                                        </button>
                                                    </div></div>
                                        `;

                            createAudioLocalStream();
                            initializedAgoraListeners();
                        } else {
                            video_container.innerHTML += `
                                                    <div id="video-container">
                                                        <div id="local-video"></div>
                                                    <div id="remote-video"></div>
                                                    <div class="action-btns">
                                                        <button type="button" class="btn btn-info p-2" id="muteAudio" onclick="handleAudioToggle(this)">
                                                            <i class="fa-solid fa-microphone-slash" style="width:30px"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-primary mx-4 p-2" id="muteVideo" onclick="handleVideoToggle(this)">
                                                            <i class="fa-solid fa-video-slash" style="width:30px"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger p-2" onclick="endCall()">
                                                            <i class="fa-solid fa-phone-slash" style="width:30px"></i>
                                                        </button>
                                                    </div></div>
                                        `;
                            createLocalStream();
                            initializedAgoraListeners();
                        }

                    }

                },
                (err) => {
                    console.log("Join channel failed", err);
                }
            );
        }

        function initializedAgoraListeners() {

            client.on("stream-published", function(evt) {
                console.log("Publish local stream successfully");
                console.log(evt);
            });

            client.on("stream-added", ({
                stream
            }) => {
                console.log("New stream added: " + stream.getId());
                client.subscribe(stream, function(err) {
                    console.log("Subscribe stream failed", err);
                });
            });
            client.on("stream-subscribed", (evt) => {

                if (videoCallEvent) {
                    evt.stream.play("remote-video");
                    client.publish(evt.stream);
                }

                if (audioCallEvent) {
                    evt.stream.play("remote-audio");
                    client.publish(evt.stream);
                }



            });
            client.on("stream-removed", ({
                stream
            }) => {
                console.log(String(stream.getId()));
                stream.close();
            });
            client.on("peer-online", (evt) => {
                console.log("peer-online", evt.uid);
            });
            client.on("peer-leave", (evt) => {
                var uid = evt.uid;
                var reason = evt.reason;
                console.log("remote user left ", uid, "reason: ", reason);
            });
            client.on("stream-unpublished", (evt) => {
                console.log(evt);
            });
        }


        function createLocalStream() {
            localStream = AgoraRTC.createStream({
                audio: true,
                video: true,
            });

            localStream.init(
                () => {

                    localStream.play("local-video");

                    client.publish(localStream, (data) => {
                        console.log("publish local stream", data);
                    });
                },
                (err) => {
                    console.log(err);
                }
            );
        }

        function createAudioLocalStream() {
            localStream = AgoraRTC.createStream({
                audio: true,
                video: false,
            });

            localStream.init(
                () => {

                    localStream.play("local-audio");

                    client.publish(localStream, (data) => {
                        console.log("publish local stream", data);
                    });
                },
                (err) => {
                    console.log(err);
                }
            );
        }

        function endCall() {
            localStream.close();
            client.leave(
                () => {
                    console.log("Leave channel successfully");
                    callPlaced = false;
                },
                (err) => {
                    console.log("Leave channel failed");
                }
            );

            axios.post("/agora/decline-call-user", {
                user_from_call: authuserId
            });
            video_container.innerHTML = "";
            $(".chat-backdrop").hide()
            location.reload(true)
        }

        function handleAudioToggle(e) {
            if (mutedAudio) {
                localStream.unmuteAudio();
                mutedAudio = false;
                e.innerHTML = `<i class="fa-solid fa-microphone-slash" style="width:30px"></i>`;
            } else {
                localStream.muteAudio();
                mutedAudio = true;
                e.innerHTML = `<i class="fa-solid fa-microphone" style="width:30px"></i>`;
            }
        }

        function handleVideoToggle(e) {
            if (mutedVideo) {
                localStream.unmuteVideo();
                mutedVideo = false;
                e.innerHTML = ` <i class="fa-solid fa-video-slash" style="width:30px"></i>`;
            } else {
                localStream.muteVideo();
                mutedVideo = true;
                e.innerHTML = `<i class="fa-solid fa-video" style="width:30px"></i>`;
            }
        }
    </script>
    <script>
        var url = "{{route('langChange')}}"
        $('.langChange').change(function(){
            window.location.href = url + "?lang="+$(this).val()
        })
    </script>

    @stack('scripts')

</body>

</html>
