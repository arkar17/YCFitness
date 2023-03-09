@extends('trainer.layouts.app')

@section('content')
    @include('sweetalert::alert')
    <div class="groups-toggle-overlay"></div>
    <p class="groups-toggle">
        Groups
        <iconify-icon icon="akar-icons:arrow-right" class="groups-toggle-icon"></iconify-icon>
    </p>
    <div class="trainer-two-columns-container">
        <div class="trainer-group-chats-parent-container">
            <p>Groups</p>
            <div class="trainer-group-chats-container">
                @forelse ($groups as $group)
                    <button class="tainer-group-chat-name-container" id="group-chat" value="{{ $group->id }}"
                        data-id="{{ $group->id }}"
                        style=" background-color: transparent;background-repeat: no-repeat;border: none;cursor: pointer;overflow: hidden;outline: none;">
                        <img src="{{ asset('image/default.jpg') }}" />
                        <p>{{ $group->group_name }}</p>
                    </button>
                @empty
                    <p class="text-secondary p-1">No Group</p>
                @endforelse
            </div>
        </div>
        <div id="content-container">

            <div class="group-chat-container">
                <div class="group-chat-header">

                </div>
                <div class="trainer-group-chat-view-members-header">

                </div>
                <div class="trainer-group-chat-members-container">

                </div>
                <div class="trainer-group-chat-media-container">

                </div>
                <div class="group-chat-messages-container " id="send_message">
                    <p id="p" class="text-secondary" style=" text-align: center;">
                        Choose group and start chatting
                    </p>
                    <div class="group-chat-sender-container" id="trainer_message_el">

                    </div>
                </div>
                <div class="chat-message-form">
                    <form class="group-chat-send-form-container" id="trainer_message_form" enctype="multipart/form-data">
                        @csrf
                        <div class="group-chat-send-form-message-parent-container">
                            <div class="group-chat-send-form-img-emoji-container">
                                <label class="group-chat-send-form-img-contaier">
                                    <iconify-icon icon="bi:images" class="group-chat-send-form-img-icon">

                                    </iconify-icon>
                                    <input type="file" id="groupChatImg" name="fileInput">
                                </label>
                                <button type="button" id="emoji-button" class="emoji-trigger">
                                    <iconify-icon icon="bi:emoji-smile" class="group-chat-send-form-emoji-icon">
                                    </iconify-icon>
                                </button>

                            </div>

                            <textarea id="mytextarea" name="text" class="group-chat-send-form-input trainer_message_input"
                                placeholder="Message..."></textarea>
                            <img class="group-chat-img-preview groupChatImg">
                            <div style="display: none;" class='video-prev'>
                                <video height="200" width="300" class="video-preview" controls="controls"></video>
                            </div>
                            <button type="reset" class="group-chat-img-cancel" onclick="clearGroupChatImg()">
                                <iconify-icon icon="charm:cross" class="group-chat-img-cancel-icon"></iconify-icon>
                            </button>
                        </div>

                        <button type="submit" class="group-chat-send-form-submit-btn">
                            <iconify-icon icon="akar-icons:send" class="group-chat-send-form-submit-btn-icon">
                            </iconify-icon>
                        </button>
                    </form>
                </div>

            </div>
        </div>

        {{-- pop up for video and image  --}}
        {{-- @foreach ($messages as $sms)
            <div class="modal fade" id="exampleModalToggle1{{ $sms->id }}" aria-hidden="true"
                aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            @if (pathinfo($sms->media, PATHINFO_EXTENSION) == 'mp4')
                                <video class="w-100" controls>
                                    <source src="{{ asset('/storage/trainer_message_media/' . $sms->media) }}"
                                        type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @else
                                <img src="{{ asset('/storage/trainer_message_media/' . $sms->media) }}" alt="test"
                                    class="w-100">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach --}}
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            $( window ).resize(function() {
                // console.log($(this).width())
                if($(this).width() > 1000){
                    $('.groups-toggle-overlay').hide()
                }else if($(this).width() <= 1000){
                    if( $(".trainer-group-chats-parent-container").hasClass("toggle-groups") ){
                        $('.groups-toggle-overlay').show()
                    }

                }
            });

            $(".groups-toggle").click(function(){
               $(".trainer-group-chats-parent-container").toggleClass("toggle-groups")
               $(".groups-toggle-icon").toggleClass("toggle-arrow")
                // $(".trainer-group-chats-parent-container").animate({"left":"0px"}, "slow");
                $('.groups-toggle-overlay').toggle()
            })

            // $(".tainer-group-chat-name-container").click(function(){
            //     $(".trainer-group-chats-parent-container").hide()
            //    $(".groups-toggle-icon").toggleClass("toggle-arrow")
            //     // $(".trainer-group-chats-parent-container").animate({"left":"0px"}, "slow");
            //     $('.groups-toggle-overlay').hide()
            // })



            $('#search_bar').hide();
            $('.trainer-group-chat-view-members-header').hide();
            $('.trainer-group-chat-members-container').hide();
            $('.trainer-group-chat-media-container').hide();
            $('.chat-message-form').hide();
            // $(".group-chat-messages-container").attr('class','');
            $(window).on("load", function() {
                $('.group-chat-messages-container').scrollTop($('.group-chat-messages-container')[0]
                    .scrollHeight);
            });
            $(document).on('click', '#group-chat', function(e) {
                e.preventDefault();
                $("#send_message").empty();
                $('#trainer_message_form').show();
                $(".trainer-group-chat-media-container").empty();
                $('#p').hide();

                $('.trainer-group-chat-members-container').hide();
                $('.trainer-group-chat-media-container').hide();
                $('.trainer-group-chat-view-members-header').hide();
                $('.group-chat-messages-container').show();
                $('#send_form').show();
                $('.chat-message-form').show();
                // var id=$(this).val();
                var id = $(this).data('id');
                localStorage.setItem('group_id', id);
                console.log(id)
                $.ajax({
                    type: "GET",
                    url: "trainer/group/show/" + id,
                    datatype: "json",
                    success: function(data) {
                        var view_member_url = '{{ route('trainer/view_member', ':id') }}';
                        view_member_url = view_member_url.replace(':id', data.group_chat.id);
                        var htmlView =
                            `<a href="JavaScript:Void(0);" class="group-chat-header-name-container view_member" id="` +
                            data.group_chat.id +
                            `">
                            <img src="{{ asset('image/default.jpg') }}" /><div class="group-chat-header-name-text-container">` +
                            data
                            .group_chat.group_name + `<p id="group_name">
                                        </p>
                                        </div></a>
                                        <a href="" class="group-chat-view-midea-link" id="view_media">
                                        <p>View Media</p>
                                        <iconify-icon icon="akar-icons:arrow-right" class="group-chat-view-midea-link-icon"></iconify-icon>
                                        </a>`;

                        $('.group-chat-header').html(htmlView);
                        //$('#send_form').append(sender);
                        $.each(data.chat_messages, function(key, value) {
                            console.log(value.media);

                            if(value.media == null && value.text ==null){
                                $('#send_message').append();
                            }else{
                                if (value.media == null) {
                                        $('#send_message').append(`
                                    <div class="group-chat-sender-container">
                                        <div class="group-chat-sender-text-container">
                                            <p>${value.text}</p>
                                        </div>
                                        <img src="{{ asset('image/default.jpg') }}" >
                                    </div>`);
                                } else if (value.media.split('.').pop() === 'png' || value
                                    .media.split('.').pop() === 'jpg' || value.media.split(
                                        '.').pop() === 'jpeg') {

                                    $('#send_message').append(`
                                    <div class="modal fade" id="exampleModalToggle${value.id}" aria-hidden="true"
                                        aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="{{ asset('/storage/trainer_message_media/${value.media}') }}" alt="test"
                                                        class="w-100">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="group-chat-sender-container" id="trainer_message_el">
                                            <div class="group-chat-sender-text-container">
                                                <a data-bs-toggle="modal" href="#exampleModalToggle${value.id}" role="button">
                                                <img src="{{ asset('storage/trainer_message_media/${value.media}') }}">
                                                </a>
                                            </div>
                                            <img src="{{ asset('image/default.jpg') }}" />
                                        </div>`);
                                } else {
                                    $('#send_message').append(
                                        `<div class="group-chat-sender-container" id="trainer_message_el">
                                            <div class="group-chat-sender-text-container">
                                                <video width="100%" height="100%" controls>
                                                    <source src="{{ asset('storage/trainer_message_media/${value.media}') }}" type="video/mp4">
                                                </video>
                                            </div>
                                            <img src="{{ asset('image/default.jpg') }}" />
                                        </div>`);
                                }
                            }

                        });

                        //render image preview function
                        function clearCreateGroupInputs() {
                            const inputs = document.querySelectorAll(
                                ".create-group-form input" + ",.create-group-form select")
                            // console.log(inputs)
                            for (var i = 0; i < inputs.length; i++) {
                                // console.log("hi")
                                // console.log(inputs[i])
                                inputs[i].value = ""
                            }
                        }
                    }
                })

            });

            $(document).on('click', '.kick_member', function(e) {
                e.preventDefault();
                var url = new URL(this.href);

                var id = url.searchParams.get("id");
                var group_id = $(this).attr("id");

                $.ajax({
                    type: "GET",
                    url: "trainer/group/member/kick/" + group_id,
                    datatype: "json",
                    success: function(data) {
                        Swal.fire({
                                    icon: 'danger',
                                    text:'Kicked Member',
                                    timer: 1000,

                                    })

                        view_member();
                    }
                });
            })
            $(document).on('click', '.view_member', function(e) {

                e.preventDefault();
                $('#trainer_message_form').hide();
                view_member();

            })

            $(document).on('click', '#view_media', function(e) {
                e.preventDefault();
                $(".trainer-group-chat-media-container").empty();
                $('.trainer-group-chat-media-container').show();
                $('#trainer_message_form').hide();

                group_id = localStorage.getItem('group_id');
                console.log("view_media", group_id);
                $.ajax({
                    type: "GET",
                    url: "/trainer/view_media/" + group_id,
                    datatype: "json",
                    success: function(data) {

                        $.each(data.messages, function(key, value) {
                            fileExtension = value.media.split('.').pop();
                            console.log("Type: " + fileExtension);
                            if (fileExtension === "mp4") {
                                $('.trainer-group-chat-media-container').append(`
                                <div class="modal fade" id="exampleModalToggle1` + value.id + `" aria-hidden="true"
                aria-labelledby="exampleModalToggleLabel" tabindex="-1">\
                <div class="modal-dialog modal-dialog-centered">\
                    <div class="modal-content">\
                        <div class="modal-header">\
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">\</button>\
                        </div>\

                        <div class="modal-body">\

                            <video class="w-100" controls>\
                                        <source src="{{ asset('storage/trainer_message_media/`+value.media+`') }}" type="video/mp4">\
                                     </video>\

                        </div>\
                    </div>\
                </div>\
            </div>\
                                <div class="trainer-group-chat-media" title="video">\
                                <a  data-bs-toggle="modal" href="#exampleModalToggle1` + value.id + `" role="button">\
                                     <video class="w-100">\
                                        <source src="{{ asset('storage/trainer_message_media/`+value.media+`') }}" type="video/mp4">\
                                     </video>\
                                </a>\
                                </div>`);
                            } else {
                                $('.trainer-group-chat-media-container').append(`
                                <div class="modal fade" id="exampleModalToggle2` + value.id + `" aria-hidden="true"
                aria-labelledby="exampleModalToggleLabel" tabindex="-1">\
                <div class="modal-dialog modal-dialog-centered">\
                    <div class="modal-content">\
                        <div class="modal-header">\
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">\</button>\
                        </div>\

                        <div class="modal-body">\
                            <img src="{{ asset('storage/trainer_message_media/`+value.media+`') }}" alt="test" class = "w-100">\
                        </div>\
                    </div>\
                </div>\
            </div>\
                                <div class="trainer-group-chat-media" title="Photo">\
                                <a  data-bs-toggle="modal" href="#exampleModalToggle2` + value.id + `" role="button">\
                                    <img src="{{ asset('storage/trainer_message_media/`+value.media+`') }}" alt="test">\
                                </a>\
                                </div>`);
                            }

                        });
                    }
                });
                $('.trainer-group-chat-view-members-header').hide();
                $('.trainer-group-chat-members-container').hide();
                $('.group-chat-messages-container').hide();
                $('#send_form').hide();
            })

            $(document).on('click', '#addMember', function(e) {

                $('#addMember').hide();
                $('#search_bar').append(`<form class="add-member-form" action="" >\
                    <input type="text" class = "form-control"  placeholder="Search member" id="search">\
                </form>\
                `);
                $('.add-member-form').show();
                add_member();
            });
            // Add member start
            function view_member() {
                $(".trainer-group-chat-view-members-header").empty();
                $(".trainer-group-chat-members-container").empty();

                $('.trainer-group-chat-view-members-header').show();
                $('.trainer-group-chat-members-container').show();
                $('.group-chat-messages-container').hide();
                $('.trainer-group-chat-media-container').hide();

                $('#send_form').hide();

                // var url = new URL(this.href);
                // var id = url.searchParams.get("id"); //get-id
                var id = $('.view_member').attr('id')
                console.log("group_idjhhhjh", id);
                $.ajax({
                    type: "GET",
                    url: "/trainer/view_member/" + id,
                    datatype: "json",
                    success: function(data) {

                        $.each(data.group_members, function(key, value) {
                            var kick_url = "{{ route('member.kick', ':id') }}";
                            kick_url = kick_url.replace(':id', value.id);

                            $('.trainer-group-chat-members-container').append(`<div class="trainer-group-chat-member-row">\
                                                <div class="trainer-group-chat-member-name">\
                                                    <img src="{{ asset('image/default.jpg') }}" />\
                                                    <p>` + value.name + `</p>\
                                                </div>\
                                                <div class="trainer-group-chat-member-btns-container">\
                                                    <a href="#" class="customer-secondary-btn">View Profile</a>\
                                                    <a href="?id=` + value.id +
                                `"class="trainer-group-chat-member-kick-btn customer-red-btn kick_member" id="` +
                                value.id + `">Kick Member</a>\
                                                </div>\
                                            </div>`);
                        });
                    }
                });
                group_id = localStorage.getItem('group_id');
                $('.trainer-group-chat-view-members-header').append(
                    '<div class="trainer-view-members-add-delete-btn-contaier">\
                                        <button id="addMember"  class="trainer-view-members-add-btn" value=' + group_id + '>\
                                            <iconify-icon icon="akar-icons:circle-plus" class="trainer-view-members-add-icon"></iconify-icon>\
                                            <p>Add Member</p>\
                                        </button>\
                                        <div id="search_bar">\
                                        </div>\
                                        <form action="{{ route('group.delete') }}">\
                                            <input type ="text" name = "group_id" value=' + group_id + ' hidden>\
                                            <button  class="trainer-view-members-delete-btn customer-red-btn">\
                                                Delete Group\
                                            </button>\
                                        </form>\
                                    </div>');
            }

            function add_member() {

                // e.preventDefault();
                $('.view_member').show();
                var id = $('#addMember').val();
                $('#search').on('keyup', function() {
                    search();
                });
                search();

                function search() {
                    var keyword = $('#search').val();
                    group_id = localStorage.getItem('group_id');

                    var search_url = "{{ route('trainer/member/search', ':id') }}";
                    search_url = search_url.replace(':id', group_id);
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
                    let htmlView = '';
                    if (res.members.length <= 0) {
                        htmlView += `
                    No data found.
                    `;
                    }
                    for (let i = 0; i < res.members.length; i++) {
                        id = res.members[i].id;
                        group_id = localStorage.getItem('group_id');
                        console.log("select", group_id);
                        var url = "{{ route('addMember', [':id', ':group_id']) }}";
                        url = url.replace(':id', id);
                        url = url.replace(':group_id', group_id);
                        // console.log(url);
                        htmlView += `
                        <div class="add-member-row">
                            <div class="add-member-name-container">
                                <img src="{{ asset('image/default.jpg') }}" />
                                <p>` + res.members[i].name + `</p>
                            </div>
                            <div class="add-member-row-btns-container">
                                <a href="?id=` + res.members[i].id +
                            `" class="customer-secondary-btn add-member-btn" id="` + group_id + `">Add</a>
                                <a class="customer-secondary-btn add-member-view-profile-btn" id="` + res.members[i]
                            .id + `">View Profile</a>

                            </div>
                        </div>`
                    }
                    $('.trainer-group-chat-members-container').html(htmlView);
                }


            };

            $(document).on('click', '.add-member-btn', function(e) {
                e.preventDefault();

                var url = new URL(this.href);

                var id = url.searchParams.get("id");
                var group_id = $(this).attr("id");

                var add_url = "{{ route('addMember', [':id', ':group_id']) }}";
                add_url = add_url.replace(':id', id);
                add_url = add_url.replace(':group_id', group_id);
                $(".add-member-btn").attr('href','');
                $.ajax({
                    type: "GET",
                    url: add_url,
                    datatype: "json",
                    success: function(data) {

                        // if (data.status == 200) {
                        //     Swal.fire({
                        //             title:'Success',
                        //             text:'Add Member Successfully',
                        //             timer: 500,

                        //             })
                        //     // $('.add-member-row').load(location.href);
                        // } else {
                        //     Swal.fire({
                        //             text:'Cannot Add Member',
                        //             timer: 500,

                        //             })
                        // }
                        add_member();

                    }
                })

            });
            //Add member end
        });

        ///start
        var groupChatImgInput = document.querySelector('#groupChatImg');
        var fileName
        const groupChatImgPreview = document.querySelector('.groupChatImg');
        const cancelBtn = document.querySelector(".group-chat-img-cancel");
        const messageInput = document.querySelector(".group-chat-send-form-input");
        const emojibutton = document.querySelector('.emoji-trigger');

        const picker = new EmojiButton();

        emojibutton.addEventListener('click', () => {
            picker.togglePicker(emojibutton);

        });

        picker.on('emoji', emoji => {
            messageInput.value += emoji;
        });


        if (!groupChatImgPreview.hasAttribute("src")) {
            groupChatImgPreview.remove()
            //$('.video-prev').remove();
            cancelBtn.remove()
        }


        groupChatImgInput.addEventListener('change', (e) => {
            console.log('lahsdjk');
            fileName = groupChatImgInput.files[0];
            var fileExtension;

            fileExtension = e.target.value.replace(/^.*\./, '');
            console.log(fileExtension)
            if (fileExtension === "jpg" || fileExtension === "jpeg" || fileExtension === "png" || fileExtension ===
                "gif") {
                const reader = new FileReader();
                reader.onloadend = e => groupChatImgPreview.setAttribute('src', e.target.result);
                reader.readAsDataURL(groupChatImgInput.files[0]);
                groupChatImgInput.value = ""
                $('.video-preview').removeAttr("src")
                $('.video-prev').hide();
                // if(groupChatImgPreview.hasAttribute("src")){
                console.log(reader)
                messageInput.remove()
                document.querySelector(".group-chat-send-form-message-parent-container").append(groupChatImgPreview)
                document.querySelector(".group-chat-send-form-message-parent-container").append(cancelBtn)
                // }
            }

            if (fileExtension === "mp4") {
                var fileUrl = window.URL.createObjectURL(groupChatImgInput.files[0]);
                $(".video-preview").attr("src", fileUrl)
                groupChatImgInput.value = ""
                groupChatImgPreview.removeAttribute("src")
                groupChatImgPreview.remove()
                messageInput.remove()
                document.querySelector(".group-chat-send-form-message-parent-container").append(cancelBtn)
                // document.querySelector(".group-chat-send-form-message-parent-container").append($(".video-prev"))
                $(".video-prev").show()
            }
        }); // //


        // $('#group-chat').on('click', function(){

        //     var id = localStorage.getItem('group_id');
        //     Pusher.logToConsole = true;
        // var pusher = new Pusher('6606755f57556c2654c6', {
        //     cluster: 'eu',
        //     encrypted: true
        // });
        //  pusher.subscribe('trainer-message.'+id);
        // })

        const messageform = document.getElementById("trainer_message_form");
        var group_chat_messages_container = document.querySelector(
            ".group-chat-messages-container");
        messageform.addEventListener('submit', function(e) {
            e.preventDefault();
            let groupChatImgInputimg = document.querySelector(
                '#groupChatImg');

            var trainer_message_input = document.querySelector(
                ".trainer_message_input");
            let trainer_file_message_input = document.querySelector(
                "#groupChatImg");

            var id = localStorage.getItem('group_id');
            console.log('group id', id);

            //formdata
            let formData = new FormData(messageform);
            formData.append('fileInput', fileName);
            console.log('hahhahaha', id);

            if (trainer_message_input == null) {
                axios.post('/api/sendmessage/' + id , formData).then();
            } else {
                axios.post('/api/sendmessage/' + id, {
                    text: trainer_message_input.value,
                    formData
                }).then();
                trainer_message_input.value = "";
            }
            clearGroupChatImg();

        });
        var id = localStorage.getItem('group_id');
            console.log('group id pusher', id);
        Pusher.logToConsole = true;
        var pusher = new Pusher('6606755f57556c2654c6', {
            cluster: 'eu',
            encrypted: true
        });

        var channel = pusher.subscribe('trainer-message.'+id);
        channel.bind('training_message_event', function(data) {
            console.log(data);
            if(data.message.media == null && data.message.text ==null){

            }else{
                if (data.message.media != null) {
                    var Extension;
                    Extension = data.message.media.split('.').pop();
                    console.log('file extension is', Extension);

                    if (data.message.media.split('.').pop() === 'png' || data.message.media.split('.').pop() ===
                        'jpg' || data.message.media.split('.').pop() === 'jpeg' || data.message.media.split('.').pop() === 'gif') {
                        group_chat_messages_container.innerHTML += `<div class="modal fade" id="exampleModalToggle${data.message.id}" aria-hidden="true"
                        aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                        <img src="{{ asset('/storage/trainer_message_media/${data.message.media}') }}" alt="test"
                                            class="w-100">
                                </div>
                            </div>
                        </div>
                    </div>

                                        <div class="group-chat-sender-container" id="trainer_message_el">
                                            <div class="group-chat-sender-text-container">
                                                <a data-bs-toggle="modal" href="#exampleModalToggle${data.message.id}" role="button">
                                    <img src="{{ asset('storage/trainer_message_media/${data.message.media}') }}">
                                </a>
                                            </div>
                                            <img src="{{ asset('image/default.jpg') }}" />
                                        </div>`;
                    } else if (data.message.media.split('.').pop() === 'mp4' || data.message.media.split('.')
                        .pop() ===
                        'mov' || data.message.media.split('.').pop() === 'webm') {
                        group_chat_messages_container.innerHTML += `
                                        <div class="group-chat-sender-container" id="trainer_message_el">
                                            <div class="group-chat-sender-text-container">
                                                <video width="100%" height="100%" controls>
                                                    <source src="{{ asset('storage/trainer_message_media/${data.message.media}') }}" type="video/mp4">
                                                </video>
                                            </div>
                                            <img src="{{ asset('image/default.jpg') }}" />
                                        </div>`;
                    }

                } else {
                    group_chat_messages_container.innerHTML += `
                                        <div class="group-chat-sender-container" id="trainer_message_el">
                                            <div class="group-chat-sender-text-container">
                                                <p>${data.message.text}</p>
                                            </div>
                                            <img src="{{ asset('image/default.jpg') }}" />
                                        </div>`;
                }
            }
            // pusher.unsubscribe('trainer-message.'+id);

        });

        //end


        function clearGroupChatImg() {
            console.log("clear img preview")
            groupChatImgPreview.removeAttribute("src")
            groupChatImgPreview.remove()
            cancelBtn.remove()
            $('.video-preview').removeAttr("src")
            $('.video-prev').hide();
            document.querySelector(".group-chat-send-form-message-parent-container").append(messageInput)
            groupChatImgInput.value = ""

        }
    </script>
@endpush
