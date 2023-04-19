<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--iconify-->
    <script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!--global css-->
    {{-- <link href={{ asset('css/customer/css/globals.css')}} rel="stylesheet"/> --}}
    <link href={{ asset('css/globals.css')}} rel="stylesheet"/>
    <link href={{ asset('css/aos.css')}} rel="stylesheet"/>
    <link href={{ asset('css/home.css')}} rel="stylesheet"/>
     <!--customer registeration-->
    <link href={{ asset('css/customer/css/customerRegisteration.css')}} rel="stylesheet"/>

    <!--customer login-->
    <link href="{{ asset('css/customer/css/customerLogin.css')}}" rel="stylesheet"/>

    <link href="{{ asset('css/customer/css/transactionChoice.css')}}" rel="stylesheet"/>
     <!--social media -->
     <link href="{{ asset('css/shop.css')}}" rel="stylesheet"/>
    <!--social media -->
    <link href="{{ asset('css/socialMedia.css')}}" rel="stylesheet"/>

    <!--comment mention--->
    <link href="{{asset('css/customer/jquery.mentiony.css')}}" rel="stylesheet"/>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!--comment emoji-->
    <link href="{{asset('css/customer/emojis.css')}}" rel="stylesheet"/>

    @yield('styles')

    <title>YC-fitness</title>
  </head>
  <body class="customer-loggedin-bg">
    <!-- <div class="customer-registeration-bgimg"> -->
        <script>
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
        </script>

        @include('customer.training_center.layouts.header')
        <!--theme-->


        <script src="{{asset('js/theme.js')}}"></script>
        <script src="{{asset('js/aos.js')}}"></script>

        <div class="nav-overlay">
        </div>

    <script src="{{asset('js/AgoraRTCSDK.js')}}"></script>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

     <!-- Sweet Alert -->
     <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
    <script src={{ asset('js/customer/js/customerRegisteration.js')}}></script>

    <!--nav bar-->
    <script src={{asset('js/navBar.js')}}></script>
    <script src={{asset('js/notify.js')}}></script>


    {{-- axios && Echo --}}
    <script src={{asset('js/app.js')}}></script>
    {{-- pusher --}}
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    {{-- emoji --}}
    <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- comment emoji --}}
    <script src="https://twemoji.maxcdn.com/v/latest/twemoji.min.js"></script>
    <script src="{{asset('js/customer/DisMojiPicker.js')}}"></script>



    <script src="{{asset('js/customer/jquery.mentiony.js')}}"></script>

    <script>


        var user_id = {{auth()->user()->id}};
                console.log(user_id);
                var pusher = new Pusher('{{env("MIX_PUSHER_APP_KEY")}}', {
                cluster: '{{env("PUSHER_APP_CLUSTER")}}',
                encrypted: true
                });
                var channel = pusher.subscribe('friend_request.'+user_id);
                channel.bind('friendRequest', function(data) {
                console.log(data , "ted");
                 document.getElementById("testing").text = data
                $.notify(data, "success",{ position:"left" });
                });


                var channel = pusher.subscribe('chat_message.'+user_id);
                channel.bind('chat', function(data) {

                let htmlView = '';
                for (let i = 0; i < data.length; i++) {
                var id =  data[i].id;
                var url = "{{ route('message.chat', ':id') }}";
                url = url.replace(':id', id);
                var group_url = "{{ route('socialmedia.group', ':id') }}";
                group_url = group_url.replace(':id', id);

                if(data[i].is_group == 0){
                    if(data[i].profile_image!=null){
                        htmlView += `<a href=`+url+` class="social-media-left-messages-row">
                                            <img  class="nav-profile-img" src="{{asset('storage/post/`+data[i].profile_image+`')}}"/>
                                        <p>
                                            ` + data[i].name + `<br>
                                            <span>` + data[i].text + ` </span>
                                        </p>
                                    </a>
                            `
                    }else{
                        htmlView += `<a href=`+url+` class="social-media-left-messages-row">
                                            <img  class="nav-profile-img" src="{{asset('img/customer/imgs/user_default.jpg')}}" />
                                        <p>
                                            ` + data[i].name + `<br>
                                            <span>` + data[i].text + ` </span>
                                        </p>
                                    </a>
                            `
                    }

                }
                else{
                    htmlView += `
                                    <a href=`+group_url+` class="social-media-left-messages-row">
                                            <img  class="nav-profile-img" src="{{asset('img/customer/imgs/group_default.png')}}" />
                                        <p>
                                            ` + data[i].name + `<br>
                                            <span>` + data[i].text + ` </span>
                                        </p>
                                    </a>
                            `
                }
            }
            $('.social-media-left-messages-rows-container').html(htmlView);
                });

                    table()
            function table(){
                // alert("send");
                let htmlView = '';
                var latest_messages=@json($latest_messages);
                console.log(latest_messages,'latest msg')
                if (latest_messages.length <= 0) {
                    htmlView += `
                        No Messages.
                        `;
                }
                for (let i = 0; i < latest_messages.length; i++) {
                var id =  latest_messages[i].id;
                var url = "{{ route('message.chat', ':id') }}";
                url = url.replace(':id', id);

                var group_url = "{{ route('socialmedia.group', ':id') }}";
                group_url = group_url.replace(':id', id);
                if(latest_messages[i].is_group == 0){

                    if(latest_messages[i].profile_image===null){
                        htmlView += `
                                    <a href=`+url+` class="social-media-left-messages-row">
                                            <img  class="nav-profile-img" src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                                        <p>
                                            ` + latest_messages[i].name + `<br>
                                            <span>` + latest_messages[i].text + ` </span>
                                        </p>
                                    </a>
                            `
                    }else{
                        htmlView += `
                                    <a href=`+url+` class="social-media-left-messages-row">
                                            <img  class="nav-profile-img" src="{{asset('storage/post/`+latest_messages[i].profile_image+`')}}"/>
                                        <p>
                                            ` + latest_messages[i].name + `<br>
                                            <span>` + latest_messages[i].text + ` </span>
                                        </p>
                                    </a>
                            `
                    }

                }
                else{
                    htmlView += `
                                    <a href=`+group_url+` class="social-media-left-messages-row">
                                            <img  class="nav-profile-img" src="{{asset('img/customer/imgs/group_default.png')}}"/>
                                        <p>
                                            ` + latest_messages[i].name + `<br>
                                            <span>` + latest_messages[i].text + ` </span>
                                        </p>
                                    </a>
                            `
                }

            }
            $('.social-media-left-messages-rows-container').html(htmlView);
        }
        // group_messages()
        // function group_messages(){
        //         // alert("send");
        //         let htmlView = '';
        //         var latest_messages=@json($chat_group);
        //         console.log(latest_messages)
        //         if (latest_messages.length <= 0) {
        //             htmlView += `
        //                 No Messages.
        //                 `;
        //         }
        //         for (let i = 0; i < latest_messages.length; i++) {
        //         var id =  latest_messages[i].id;

        //         htmlView += `
        //                             <a href=`+url+` class="social-media-left-gpmessages-row">
        //                                     <img src="{{asset('img/customer/imgs/group_default.png')}}" class="w-25">
        //                                     <p>
        //                                         ` + latest_messages[i].group_name + `<br>
        //                                         <span>` + latest_messages[i].text + `</span>
        //                                     </p>
        //                                 </a>
        //                     `

        //     }
        //     $('.social-media-left-gpmessages-rows-container').html(htmlView);
        // }


    $(document).ready(function() {

        //image slider start
        console.log($(".image-slider"))

        $.each($(".ul-image-slider"),function(){
            console.log($(this).children('li').length)

            $(this).children('li:first').addClass("active-img")
        })

        $.each($(".img-slider-thumbnails ul"),function(){
            console.log($(this).children('li').length)

            $(this).children('li:first').addClass("active")
        })

        $(function(){

            $('.img-slider-thumbnails li').click(function(){
            var thisIndex = $(this).index()
            // console.log(thisIndex,$(this).siblings("li.active").index())
            if($(this).siblings(".active").index() === -1){
                return
            }


            if(thisIndex < $(this).siblings(".active").index()){
                prevImage(thisIndex, $(this).parents(".img-slider-thumbnails").prev("#image-slider"));
            }else if(thisIndex > $(this).siblings(".active").index()){
                nextImage(thisIndex, $(this).parents(".img-slider-thumbnails").prev("#image-slider"));
            }


            $(this).siblings('.active').removeClass('active');
            $(this).addClass('active');

            });

        });

        var width = $('#image-slider').width();
        console.log(width)

        function nextImage(newIndex, parent){
            parent.find('li').eq(newIndex).addClass('next-img').css('left', width).animate({left: 0},600);
            parent.find('li.active-img').removeClass('active-img').css('left', '0').animate({left: '-100%'},600);
            parent.find('li.next-img').attr('class', 'active-img');
        }
        function prevImage(newIndex, parent){
            parent.find('li').eq(newIndex).addClass('next-img').css('left', -width).animate({left: 0},600);
            parent.find('li.active-img').removeClass('active-img').css('left', '0').animate({left: '100%'},600);
            parent.find('li.next-img').attr('class', 'active-img');
        }

        /* Thumbails */
        // var ThumbailsWidth = ($('#image-slider').width() - 18.5)/7;
        // $('#thumbnail li').find('img').css('width', ThumbailsWidth);

        $('.social-media-media-slider').hide()

        $(".social-media-media-container").click(function(){

            $(this).siblings(".social-media-media-slider").show()
            $(this).hide()
        })

        $(".slider-close-icon").click(function(){
            $(this).closest('.social-media-media-slider').hide()
            $(this).closest('.social-media-media-slider').siblings('.social-media-media-container').show()
        })
        //image slider end




        $(".cancel").hide();
        $( ".social-media-left-search-container input" ).focus(function() {
            // alert( "Handler for .focus() called." );
            $( ".social-media-left-infos-container" ).hide()
            $(".social-media-left-searched-items-container").show()
            $(".cancel").show();
        });

        $(document).on('click', '.cancel', function(e) {
            // alert( "Handler for .focus() called." );
            $( ".social-media-left-infos-container" ).show()
            $(".social-media-left-searched-items-container").hide()
            $(".cancel").hide()
            $('.social-media-left-search-container input').val('')
        });

        $(document).on('click', '#delete_post', function(e){
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
                    }else{

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
                $(".add-member-btn").attr('href','');
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
                                    $(".cancel-request-btn").attr('href','');
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


                    $('.social-media-left-search-container input').on('keyup', function(){
                            search();
                    });

                        function search(){

                            var keyword = $('#search').val();
                            //console.log(keyword);
                            var search_url = "{{ route('search_users') }}";
                            $.post(search_url,
                            {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                keyword:keyword
                            },
                            function(data){
                                table_post_row(data);
                                console.log(data);
                            });
                        }
                        // table row with ajax
                        function table_post_row(res){
                        var auth_id = {{auth()->user()->id}}
                        let htmlView = '';
                            if(res.users.length <= 0){
                                htmlView+= `
                                No data found.
                                `;
                            }
                            else if(res.friends.length <= 0 && res.users.length != 0){
                                console.log("myself");
                                for(let i = 0; i < res.users.length; i++){
                                id = res.users[i].id;
                                var url = "{{ route('socialmedia.profile', [':id']) }}";
                                url = url.replace(':id',id);
                                if(res.users[i].id === auth_id){
                                    htmlView += `
                                            <div class="social-media-left-searched-item">
                                            <a href=`+url+` class = "profiles">
                                                <p>`+res.users[i].name+`</p>
                                            </a>
                                            </div>
                                    `
                                }
                                else{
                                    console.log("no friends");
                                    htmlView += `
                                            <div class="social-media-left-searched-item">
                                            <a href=`+url+` class = "profiles">
                                                <p>`+res.users[i].name+`</p>
                                            </a>
                                            <a href="?id=` + res.users[i].id+`"  id = "AddFriend"><iconify-icon icon="bi:person-add" class="search-item-icon"></iconify-icon></a>
                                            </div>
                                    `
                                }
                                }
                            }
                            else{
                                for(let i = 0; i < res.users.length; i++){
                                    var status = ''
                                for(let f = 0; f < res.friends.length; f++){
                                    id = res.users[i].id;
                                    var url = "{{ route('socialmedia.profile', [':id']) }}";
                                    url = url.replace(':id',id);
                                    console.log(auth_id)

                                    if(res.users[i].id === res.friends[f].receiver_id &&
                                    res.friends[f].sender_id === auth_id &&
                                    res.friends[f].friend_status === 1 ){
                                        console.log(res.users[i].name,'sender request')
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
                                    }
                                    else if(
                                    res.users[i].id === res.friends[f].sender_id &&
                                    res.friends[f].receiver_id === auth_id &&
                                    res.friends[f].friend_status === 1
                                    ){
                                        console.log(res.users[i].name,'receiver request')
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
                                    }
                                    else if (res.users[i].id === auth_id){
                                        console.log(res.users[i].name,'profile')
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
                                    }
                                    else if (res.users[i].id === res.friends[f].receiver_id &&
                                    res.friends[f].sender_id === auth_id &&
                                    res.friends[f].friend_status === 2
                                    ){
                                        console.log(res.users[i].name,'sender view profile')
                                        status = "sender view profile"
                                        break
                                        // return
                                        // htmlView += `
                                        //     <a href= `+url+` class = "profiles">
                                        //         <p>`+res.users[i].name+`</p>
                                        //     </a>
                                        //     <a href="?id=` + res.users[i].id+`" class="customer-secondary-btn add-friend-btn">Friend</a>
                                        //     `
                                    }
                                    else if (
                                    res.users[i].id === res.friends[f].sender_id &&
                                    res.friends[f].receiver_id === auth_id &&
                                    res.friends[f].friend_status === 2
                                    ){
                                        console.log(res.users[i].name,'receiver view profile')
                                        status = "receiver view profile"
                                        break
                                        // return
                                        // htmlView += `
                                        //     <a href= `+url+` class = "profiles">
                                        //         <p>`+res.users[i].name+`</p>
                                        //     </a>
                                        //     <a href="?id=` + res.users[i].id+`" class="customer-secondary-btn add-friend-btn">Friend</a>
                                        //   `
                                    }
                                    else{
                                        status="add fri"
                                        console.log(res.users[i].name,'add fri')
                                    //     htmlView += `
                                    //         <a href=`+url+` class = "profiles">
                                    //             <p>`+res.users[i].name+`</p>
                                    //         </a>
                                    //         <a href="?id=` + res.users[i].id+`" class="customer-secondary-btn add-friend-btn" id = "AddFriend">Add</a>
                                    // `
                                    }

                            }

                            if(status === 'sender request'){
                                htmlView += `
                                            <div class="social-media-left-searched-item">
                                            <a href=`+url+` class = "profiles">
                                                <p>`+res.users[i].name+`</p>
                                            </a>
                                            <a href="?id=` + res.users[i].id+`" class="cancel-request-btn"
                                            id = "cancelRequest"><iconify-icon icon="material-symbols:cancel-schedule-send-outline" class="search-item-icon"></iconify-icon></a>
                                            </div>
                                            `
                            }

                            else if(status === 'receiver request'){
                               htmlView += `
                                            <div class="social-media-left-searched-item">
                                            <a href=`+url+` class = "profiles">
                                                <p>`+res.users[i].name+`</p>
                                            </a>
                                            <a href=`+url+`><iconify-icon icon="mdi:account-question-outline" class="search-item-icon"></iconify-icon></a>
                                            </div>
                                            `
                            }

                            else if(status === "profile"){
                                 htmlView += `
                                            <div class="social-media-left-searched-item">
                                            <a href=`+url+` class = "profiles">
                                                <p>`+res.users[i].name+`</p>
                                            </a>
                                            </div>
                                            `
                            }

                            else if(status === "sender view profile"){
                                htmlView += `
                                            <div class="social-media-left-searched-item">
                                            <a href= `+url+` class = "profiles">
                                                <p>`+res.users[i].name+`</p>
                                            </a>
                                            <a href=`+url+` ><iconify-icon icon="ion:people-sharp" class="search-item-icon"></iconify-icon></a>
                                            </div>
                                          `
                            }
                            else if(status === "receiver view profile"){
                                htmlView += `
                                            <div class="social-media-left-searched-item">
                                            <a href= `+url+` class = "profiles">
                                                <p>`+res.users[i].name+`</p>
                                            </a>
                                            <a href=`+url+`><iconify-icon icon="ion:people-sharp" class="search-item-icon"></iconify-icon></a>
                                            </div>
                                          `
                            }
                            else{
                                    htmlView += `
                                            <div class="social-media-left-searched-item">
                                            <a href=`+url+` class = "profiles">
                                                <p>`+res.users[i].name+`</p>
                                            </a>
                                            <a href="?id=` + res.users[i].id+`"  id = "AddFriend"><iconify-icon icon="bi:person-add" class="search-item-icon"></iconify-icon></a>
                                            </div>
                                    `
                            }
                            }
                            }
                            $('.social-media-left-searched-items-container').html(htmlView);
                        }



        $('.social-media-post-header-icon').click(function(){
            $(this).next().toggle()
        })

        $(".social-media-left-container-trigger").click(function(){
            $('.social-media-left-container').toggleClass("social-media-left-container-open")
            $('.social-media-overlay').toggle()
            $(".social-media-left-container-trigger .arrow-icon").toggleClass("rotate-arrow")
        })


        $('#form').submit(function(e){
            e.preventDefault();
            $('#addPostModal'). modal('hide');
            var caption=$('#addPostCaption').val();

            var url="{{route('post.store')}}";
            var $fileUpload=$('#addPostInput');

            if(!$('.addpost-caption-input').val() && parseInt($fileUpload.get(0).files.length) === 0){
                alert("Cannot post!!")
            }
            else{
                if (parseInt($fileUpload.get(0).files.length)>5){
                    Swal.fire({
                        text: "You can only upload a maximum of 5 files",
                        timerProgressBar: true,
                        timer: 5000,
                        icon: 'warning',
                    });
                }
                else{
                    e.preventDefault();
                    let formData = new FormData(form);

                    const totalImages = $("#addPostInput")[0].files.length;
                    let images = $("#addPostInput")[0];

                    for (let i = 0; i < totalImages; i++) {
                        formData.append('images' + i, images.files[i]);
                    }
                    formData.append('totalImages', totalImages);

                    var caption=$('#addPostCaption').val();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                            type:'POST',
                            url:"{{route('post.store')}}",
                            data: formData,
                                processData: false,
                                cache: false,
                                contentType: false,
                            success:function(data){
                            if(data.message){
                                Swal.fire({
                                            text: data.message,
                                            timerProgressBar: true,
                                            timer: 5000,
                                            icon: 'success',
                                        }).then(() => {
                                            window.location.reload()
                                        })
                            }else{
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

        $(document).on('click','#edit_shoppost',function(e){

            e.preventDefault();
            $(".editpost-photo-video-imgpreview-container").empty();

            dtEdit.clearData()
            document.getElementById('editPostInput').files = dtEdit.files;
            var id = $(this).data('id');

            $('#editPostModal').modal('show');
            var add_url = "{{ route('shoppost.edit', [':id']) }}";
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
                        if(data.status==400){
                            alert(data.message)
                        }else{
                            console.log('fgghfghfghgh');
                            $('#editPostCaption').val(data.post.caption);
                            $('#edit_post_id').val(data.post.id);

                            var filesdb =data.post.media ? JSON.parse(data.post.media) : [];

                            console.log(data.imageData,'image data');
                            // var filesAmount=files.length;
                            var storedFilesdb = filesdb;
                            // console.log(storedFilesdb)


                            filesdb.forEach(function(f) {
                                fileExtension = f.replace(/^.*\./, '');
                                console.log(fileExtension);
                                if(fileExtension=='mp4') {
                                    var html="<div class='addpost-preview'>\
                                        <iconify-icon icon='akar-icons:cross' data-file='" + f + "' class='delete-preview-db-icon'></iconify-icon>\
                                        <video controls><source src='storage/post/" + f + "' data-file='" + f+ "' class='selFile' title='Click to remove'>" + f + "<br clear=\"left\"/>\
                                        <video>\
                                    </div>"
                                    $(".editpost-photo-video-imgpreview-container").append(html);

                                }else{
                                    var html = "<div class='addpost-preview'><iconify-icon icon='akar-icons:cross' data-file='" + f + "' class='delete-preview-db-icon'></iconify-icon><img src='storage/post/"+f+"' data-file='" + f + "' class='selFile' title='Click to remove'></div>";
                                    $(".editpost-photo-video-imgpreview-container").append(html);
                                }

                            });

                            $("body").on("click", ".delete-preview-db-icon", removeFiledb);

                            function removeFiledb(){
                                var file = $(this).data('file')
                                storedFilesdb = storedFilesdb.filter((item) => {
                                    return file !== item
                                })

                                $(this).parent().remove();
                            }

                            $(".addpost-photovideo-clear-btn").click(function(){
                                storedFilesdb = []
                            })

                            $('#edit_form').submit(function(e){
                                e.preventDefault();
                                $('#editPostModal'). modal('hide');

                            var fileUpload=$('#editPostInput');
                            console.log(storedFilesdb.length );
                            console.log(parseInt(fileUpload.get(0).files.length) );
                            console.log(storedFilesdb);
                            console.log(fileUpload.get(0).files);

                            if(!$('#editPostCaption').val() && (parseInt(fileUpload.get(0).files.length) + storedFilesdb.length) === 0){
                                alert("Cannot post!!")
                            }else{
                                if((parseInt(fileUpload.get(0).files.length))+storedFilesdb.length > 5){
                                    Swal.fire({
                                                text: "You can only upload a maximum of 5 files",
                                                timer: 5000,
                                                icon: 'warning',
                                            });
                                }else{
                                    e.preventDefault();

                                    var url="{{route('post.update')}}";
                                    let formData = new FormData(edit_form);
                                    var oldimg=storedFilesdb;
                                    var edit_post_id=$('#edit_post_id').val();
                                    var caption=$('#editPostCaption').val();

                                    const totalImages = $("#editPostInput")[0].files.length;
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
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                }
                                            });

                                    $.ajax({
                                            type:'POST',
                                            url:url,
                                            data: formData,
                                            processData: false,
                                            cache: false,
                                            contentType: false,
                                            success:function(data){
                                                if(data.ban){
                                                    Swal.fire({
                                                        text: data.ban,
                                                        timer: 5000,
                                                        timerProgressBar: true,
                                                        icon: 'error',
                                                    })
                                                }else{
                                                    Swal.fire({
                                                        text: data.success,
                                                        timer: 5000,
                                                        timerProgressBar: true,
                                                        icon: 'success',
                                                    }).then(() => {
                                                        window.location.reload()
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
                var html = "<div class='addpost-preview'><iconify-icon icon='akar-icons:cross' data-file='" + f.name + "' class='delete-preview-icon'></iconify-icon><img src=\"" + e.target.result + "\" data-file='" + f.name + "' class='selFile' title='Click to remove'></div>";

                if (device == "mobile") {
                    $("#selectedFilesM").append(html);
                } else {
                    $(".addpost-photo-video-imgpreview-container").append(html);
                }
                }
                reader.readAsDataURL(f);
                dt.items.add(f);
            }else if(f.type.match("video.*")){
                storedFiles.push(f);

                var reader = new FileReader();
                reader.onload = function(e) {
                var html = "<div class='addpost-preview'><iconify-icon icon='akar-icons:cross' data-file='" + f.name + "' class='delete-preview-icon'></iconify-icon><video controls><source src=\"" + e.target.result + "\" data-file='" + f.name + "' class='selFile' title='Click to remove'>" + f.name + "<br clear=\"left\"/><video></div>";

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
        console.log(document.getElementById('addPostInput').files+" Add Post Input")

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
                var html = "<div class='addpost-preview'><iconify-icon icon='akar-icons:cross' data-file='" + f.name + "' class='delete-preview-edit-input-icon'></iconify-icon><img src=\"" + e.target.result + "\" data-file='" + f.name + "' class='selFile' title='Click to remove'></div>";

                if (device == "mobile") {
                    $("#selectedFilesM").append(html);
                } else {
                    $(".editpost-photo-video-imgpreview-container").append(html);
                }
                }
                reader.readAsDataURL(f);
                dtEdit.items.add(f);
            }else if(f.type.match("video.*")){
                storedFilesEdit.push(f);

                var reader = new FileReader();
                reader.onload = function(e) {
                var html = "<div class='addpost-preview'><iconify-icon icon='akar-icons:cross' data-file='" + f.name + "' class='delete-preview-edit-input-icon'></iconify-icon><video controls><source src=\"" + e.target.result + "\" data-file='" + f.name + "' class='selFile' title='Click to remove'>" + f.name + "<br clear=\"left\"/><video></div>";

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
        console.log(document.getElementById('editPostInput').files+" Edit Post Input")

    }

    function removeFile(e) {
        var file = $(this).data("file");
        var names = [];
        for(let i = 0; i < dt.items.length; i++){
            if(file === dt.items[i].getAsFile().name){
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
        for(let i = 0; i < dtEdit.items.length; i++){
            if(file === dtEdit.items[i].getAsFile().name){
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


    function clearAddPost(){
        storedFiles = []
        dt.clearData()
        document.getElementById('addPostInput').files = dt.files;
        $(".addpost-photo-video-imgpreview-container").empty();
    }

    function clearEditPost(){
        storedFilesEdit = []
        dtEdit.clearData()
        document.getElementById('editPostInput').files = dtEdit.files;
        $(".editpost-photo-video-imgpreview-container").empty();

    }

</script>

<script>
    $(document).ready(function(){

        $('.nav-icon').click(function(){
                $('.notis-box-container').toggle()
        })

        $(document).on('click', '.accept', function(e) {
                e.preventDefault();
                alert("okk")
                var url = new URL(this.href);
                var id = url.searchParams.get("id");
                 console.log(id,"noti_id");
                var sender_id = $(this).attr("id");
                console.log(sender_id , "rererer");
                var social_url = "{{ route('socialmedia.profile', [':id']) }}";
                social_url = social_url.replace(':id', sender_id);

                var url = "{{ route('social_media_profile') }}";
                $(".add-member-btn").attr('href','');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                    $.ajax({
                        type: "POST",
                        url: url,
                        datatype: "json",
                        data : {
                            id : sender_id,
                            noti_id : id
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
            console.log(post_id , "rererer");
            var comment_url = "{{ route('post.comment', [':id']) }}";
            comment_url = comment_url.replace(':id', post_id);

            var url = "{{ route('comment_list',[':id']) }}";
            url = url.replace(':id', post_id);
            $(".add-member-btn").attr('href','');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
                $.ajax({
                    type: "POST",
                    url: url,
                    datatype: "json",
                    data : {
                        id : post_id,
                        noti_id : id
                },
                    success: function(data) {
                        if(data.comment == null){
                            Swal.fire({
                            text: "Post Deleted!",
                            confirmButtonColor: '#3CDD57',
                            timer: 3000
                      });
                        }
                        else{
                            window.location.href = comment_url
                        }
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
            console.log(post_id , "rererer");
            var like_url = "{{ route('social_media_likes',[':post_id']) }}";
            like_url = like_url.replace(':post_id', post_id);

            var url = "{{ route('social_media_likes',[':post_id']) }}";
            url = url.replace(':post_id', post_id);
            $(".add-member-btn").attr('href','');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
                $.ajax({
                    type: "GET",
                    url: url,
                    datatype: "json",
                    data : {
                        id : post_id,
                        noti_id : id
                },
                    success: function(data) {
                        console.log(data)
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


@stack('scripts')

  </body>
</html>
