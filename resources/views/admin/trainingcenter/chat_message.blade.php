@extends('layouts.app')
@section('training-center-active', 'active')
@section('content')
    <div class="customer-main-content-container">
        <div class="trainer-main-content-container">
            {{-- <button data-bs-toggle="modal" data-bs-target="#CreateGroupModal" class="trainer-create-gp-modal-btn customer-primary-btn">
                <iconify-icon icon="akar-icons:circle-plus" class="trainer-create-gp--modal-btn-icon"></iconify-icon>
                <p>Group</p>
            </button> --}}

            <div class="trainer-two-columns-container">
                <div class="trainer-group-chats-parent-container">
                    <p>Groups</p>
                    <div class="trainer-group-chats-container">
                        @forelse ($groups as $group)
                            <a href="{{ route('chat_message', $group['id']) }}" data-id="{{ $group['id'] }}" style="text-decoration: none">
                                <div class="tainer-group-chat-name-container">
                                    <img src="{{ asset('img/avatar.png') }}" />
                                    <p>{{ $group->group_name }}</p>
                                </div>
                            </a>
                        @empty
                            <p>Not Found </p>
                        @endforelse

                    </div>
                </div>
                @yield('chat_message_body')
                <div class="group-chat-container">
                    <div class="group-chat-header">
                        <a href="{{route('trainingcenter.view_member',$group_data['id'])}}" class="group-chat-header-name-container" style="text-decoration: none">
                            <img src="{{ asset('img/avatar.png') }}" />
                            <div class="group-chat-header-name-text-container">
                                <p>{{ $group_data->group_name }}</p>
                            </div>
                        </a>

                        <a href="{{route('trainingcenter.view_media',$group_data['id'])}}" class="group-chat-view-midea-link" style="text-decoration: none">
                            <p>View Media</p>
                            <iconify-icon icon="akar-icons:arrow-right" class="group-chat-view-midea-link-icon">
                            </iconify-icon>
                        </a>
                    </div>

                    <div class="group-chat-messages-container">
                        @forelse ($chat_messages as $chat_message)
                            @if ($chat_message->media == null)
                                <div class="group-chat-sender-container">
                                    <div class="group-chat-sender-text-container">
                                        <p>{{ $chat_message->text }}</p>
                                    </div>
                                    <img src="{{ asset('img/avatar.png') }}" />
                                </div>
                            @else
                                @if (pathinfo($chat_message->media, PATHINFO_EXTENSION) == 'mp4' ||
                                    pathinfo($chat_message->media, PATHINFO_EXTENSION) == 'mov' ||
                                    pathinfo($chat_message->media, PATHINFO_EXTENSION) == 'webm')
                                    <div class="group-chat-sender-container" id="trainer_message_el">
                                        <div class="group-chat-sender-text-container">
                                            <video width="100%" height="100%" controls>
                                                <source src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/trainer_message_media/{{ $chat_message->media}}">
                                            </video>
                                        </div>
                                        <img src="{{ asset('img/avatar.png') }}" />
                                    </div>
                                @elseif(pathinfo($chat_message->media, PATHINFO_EXTENSION) == 'png' ||
                                    pathinfo($chat_message->media, PATHINFO_EXTENSION) == 'jpg' ||
                                    pathinfo($chat_message->media, PATHINFO_EXTENSION) == 'jpeg')
                                    <div class="modal fade" id="exampleModalToggle" aria-hidden="true"
                                        aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/trainer_message_media/{{ $chat_message->media}}"
                                                        alt="test" class="w-100">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="group-chat-sender-container" id="trainer_message_el">
                                        <div class="group-chat-sender-text-container">
                                            <a data-bs-toggle="modal" href="#exampleModalToggle" role="button">
                                                <img
                                                src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/trainer_message_media/{{ $chat_message->media}}">
                                            </a>
                                        </div>
                                        <img src="{{ asset('img/avatar.png') }}" />
                                    </div>
                                @endif
                            @endif
                        @empty

                        @endforelse
                    </div>

                    <div class="trainer-group-chat-view-members-header">
                        <a class="back-btn">
                            <iconify-icon icon="bi:arrow-left" class="back-btn-icon"></iconify-icon>
                        </a>

                    </div>

                    <div class="trainer-group-chat-members-container">

                    </div>


                    <form class="group-chat-send-form-container" id="message_form" enctype="multipart/form-data">
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

                            <textarea id="message_input" class="group-chat-send-form-input" placeholder="Message..."></textarea>
                            <img class="group-chat-img-preview groupChatImg">
                            <div style="display: none;" class='video-prev'>
                                <video height="200" width="300" class="video-preview" controls="controls"></video>
                            </div>
                            <button type="reset" class="group-chat-img-cancel" onclick="clearGroupChatImg()">
                                <iconify-icon icon="charm:cross" class="group-chat-img-cancel-icon"></iconify-icon>
                            </button>
                        </div>


                        <button type="button" class="group-chat-send-form-submit-btn">
                            <iconify-icon icon="akar-icons:send" class="group-chat-send-form-submit-btn-icon">
                            </iconify-icon>
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var group = @json($group_data);
        var id = group.id;

        var fileName;
        console.log(id);
        var messageForm = document.getElementById('message_form');
        var messageInput = document.querySelector('#message_input');
        Pusher.logToConsole = true;
        var pusher = new Pusher('6606755f57556c2654c6', {
            cluster: 'eu',
            encrypted: true
        });
        var channel = pusher.subscribe('trainer-message.' + id);

        $(document).ready(function() {
                    $('.group-chat-messages-container').scrollTop($('.group-chat-messages-container')[0].scrollHeight);
        });


        $(document).ready(function() {

            var messageForm = document.getElementById('message_form');
            var messageInput = document.querySelector('#message_input');

            ///start


            var groupChatImgInput = document.querySelector('#groupChatImg');

            const groupChatImgPreview = document.querySelector('.groupChatImg');
            const cancelBtn = document.querySelector(".group-chat-img-cancel");
            const emojibutton = document.querySelector('.emoji-trigger');

            const picker = new EmojiButton();

            emojibutton.addEventListener('click', () => {
                picker.togglePicker(emojibutton);

            });

            picker.on('emoji', emoji => {
                messageInput.value += emoji;
            });


            if (groupChatImgPreview != null) {
                if (!groupChatImgPreview.hasAttribute("src")) {
                    groupChatImgPreview.remove()
                    //$('.video-prev').remove();
                    cancelBtn.remove()
                }
            }


            groupChatImgInput.addEventListener('change', (e) => {
                console.log('lahsdjk');
                fileName = groupChatImgInput.files[0];
                console.log(fileName);
                var fileExtension;

                fileExtension = e.target.value.replace(/^.*\./, '');
                console.log(fileExtension)
                if (fileExtension === "jpg" || fileExtension === "jpeg" || fileExtension ===
                    "png" || fileExtension ===
                    "gif") {
                    const reader = new FileReader();
                    reader.onloadend = e => groupChatImgPreview.setAttribute('src', e.target
                        .result);
                    reader.readAsDataURL(groupChatImgInput.files[0]);
                    groupChatImgInput.value = ""
                    $('.video-preview').removeAttr("src")
                    $('.video-prev').hide();
                    // if(groupChatImgPreview.hasAttribute("src")){
                    console.log(reader)
                    messageInput.remove()
                    document.querySelector(".group-chat-send-form-message-parent-container")
                        .append(groupChatImgPreview)
                    document.querySelector(".group-chat-send-form-message-parent-container")
                        .append(cancelBtn)
                    // }
                }

                if (fileExtension === "mp4") {
                    var fileUrl = window.URL.createObjectURL(groupChatImgInput.files[0]);
                    $(".video-preview").attr("src", fileUrl)
                    groupChatImgInput.value = ""
                    groupChatImgPreview.removeAttribute("src")
                    groupChatImgPreview.remove()
                    messageInput.remove()
                    document.querySelector(".group-chat-send-form-message-parent-container")
                        .append(cancelBtn)
                    // document.querySelector(".group-chat-send-form-message-parent-container").append($(".video-prev"))
                    $(".video-prev").show()
                }
            }); // //
        })
        var messageFormsend = document.querySelector('.group-chat-send-form-submit-btn');
        messageFormsend.addEventListener('click', function(e) {
            e.preventDefault();
            var message_input = document.querySelector('#message_input');
            var groupChatImg_input = document.querySelector('#groupChatImg');
            var text_container = document.querySelector('.group-chat-messages-container');
            //formdata
            var formdata = new FormData(messageForm);
            formdata.append('fileInput', fileName);

            if (message_input != null) {
                axios.post('/api/sendmessage/' + id, {
                    text: message_input.value,
                }).then();
                message_input.value = "";
            } else {
                axios.post('/api/sendmessage/' + id, formdata).then();
                clearGroupChatImg();
            }

            channel.bind('training_message_event', function(data) {
                console.log(data);
                if (data.message.media == null && data.message.text == null) {} else {
                    if (data.message.media != null) {
                        var Extension;
                        Extension = data.message.media.split('.').pop();
                        console.log('file extension is', Extension);

                        if (data.message.media.split('.').pop() === 'png' || data.message.media
                            .split('.').pop() ===
                            'jpg' || data.message.media.split('.').pop() === 'jpeg' || data
                            .message.media.split('.').pop() === 'gif') {
                            text_container.innerHTML += `
                                    <div class="modal fade" id="exampleModalToggle${data.message.id}" aria-hidden="true"
                                        aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/trainer_message_media/${data.message.media}"
                                                        alt="test" class="w-100">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="group-chat-sender-container" id="trainer_message_el">
                                        <div class="group-chat-sender-text-container">
                                            <a data-bs-toggle="modal" href="#exampleModalToggle${data.message.id}" role="button">
                                                <img  src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/trainer_message_media/${data.message.media}"
                                             }}">
                                            </a>
                                        </div>
                                        <img src="{{ asset('img/avatar.png') }}" />
                                    </div>`;
                        } else if (data.message.media.split('.').pop() === 'mp4' || data.message
                            .media.split('.')
                            .pop() ===
                            'mov' || data.message.media.split('.').pop() === 'webm') {
                            text_container.innerHTML += `
                                        <div class="group-chat-sender-container" id="trainer_message_el">
                                            <div class="group-chat-sender-text-container">
                                                <video width="100%" height="100%" controls>
                                                    <source 
                                                    src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/trainer_message_media/${data.message.media}"
                                                    type="video/mp4">
                                                </video>
                                            </div>
                                            <img src="{{ asset('img/avatar.png') }}" />
                                        </div>`;
                        }

                    } else {
                        text_container.innerHTML += `<div class="group-chat-sender-container"> <div class="group-chat-sender-text-container">
                                        <p>${data.message.text}</p>
                                    </div>
                                    <img src="{{ asset('img/avatar.png') }}" /> </div>`;
                    }
                }

            }, channel.unbind());
        })
        var messageInput = document.querySelector('#message_input');

        var groupChatImgInput = document.querySelector('#groupChatImg');

        const groupChatImgPreview = document.querySelector('.groupChatImg');
        const cancelBtn = document.querySelector(".group-chat-img-cancel");
        const emojibutton = document.querySelector('.emoji-trigger');


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
