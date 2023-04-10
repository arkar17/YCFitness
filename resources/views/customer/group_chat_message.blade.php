@extends('customer.layouts.app_home')
@section('styles')
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
            z-index: 21;
        }

        #incoming_call {
            position: relative;
            z-index: 99;
        }
    </style>
@endsection
@section('content')
    <div class="chat-backdrop"></div>
    <div class="social-media-right-container">


        <input type="text" value="{{ $group->id }}" id="groupId" hidden>
        <input type="text" value="{{ $group->group_name }}" id="groupName" hidden>
        <div class="group-chat-header">
            <a href="{{ route('socialmedia.group.detail', $group->id) }}" class="group-chat-header-name-container">
                <img src="{{ asset('img/customer/imgs/group_default.png') }}" />
                <div class="group-chat-header-name-text-container">
                    <p>{{ $group->group_name }}</p>
                </div>
            </a>
            <div class="chat-header-call-icons-container">
                {{-- Here --}}
                <a onclick="placeCallAudio('{{ $group->id }}')">
                    <iconify-icon icon="ant-design:phone-outlined" class="chat-header-phone-icon"></iconify-icon>
                </a>
                <a onclick="placeCall('{{ $group->id }}')">
                    <iconify-icon icon="eva:video-outline" class="chat-header-video-icon"></iconify-icon>
                </a>

                <a href="{{ route('socialmedia.group.viewmedia', $group->id) }}" class="group-chat-view-midea-link">
                    <p>{{__('msg.view media')}}</p>
                    <iconify-icon icon="akar-icons:arrow-right" class="group-chat-view-midea-link-icon"></iconify-icon>
                </a>
            </div>
        </div>

        <div class="group-chat-messages-container">
            @foreach ($gp_messages as $gp_message)
                @if (auth()->user()->id != $gp_message->sender_id)
                    @if ($gp_message->text != null)
                        <div class="group-chat-receiver-container">
                            @if ($gp_message->user == null || $gp_message->user->user_profile == null)
                                <img class="nav-profile-img" src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                            @else
                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$gp_message->user->user_profile->profile_image}}" />
                            @endif
                            <div class="group-chat-receiver-text-container">
                                <span>{{ $gp_message->user->name }}</span>
                                <p>{{ $gp_message->text }}</p>
                            </div>
                        </div>
                    @else
                        <div class="group-chat-receiver-container">
                            @if ($gp_message->user->user_profile == null)
                                <img class="nav-profile-img" src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                            @else
                                <img
                                    src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$gp_message->user->user_profile->profile_image}}" />
                            @endif
                            <div class="group-chat-receiver-text-container">
                                <span>{{ $gp_message->user->name }}</span>
                                <div class=" group-chat-imgs-vids-container">
                                    @foreach (json_decode($gp_message->media) as $key => $media)
                                        @if (pathinfo($media, PATHINFO_EXTENSION) == 'png' ||
                                            pathinfo($media, PATHINFO_EXTENSION) == 'jpg' ||
                                            pathinfo($media, PATHINFO_EXTENSION) == 'jpeg')
                                            <div class="modal fade"
                                                id="exampleModalToggle{{ $gp_message->id }}{{ $key }}"
                                                aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/{{ $media }}"
                                                                class="w-100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- <div class="group-chat-receiver-text-container"> --}}
                                            <a data-bs-toggle="modal"
                                                href="#exampleModalToggle{{ $gp_message->id }}{{ $key }}"
                                                role="button">
                                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/{{ $media }}">
                                            </a>
                                            {{-- </div> --}}
                                        @elseif(pathinfo($media, PATHINFO_EXTENSION) == 'mp4' ||
                                            pathinfo($media, PATHINFO_EXTENSION) == 'mov' ||
                                            pathinfo($media, PATHINFO_EXTENSION) == 'webm')
                                            {{-- <div class="group-chat-receiver-text-container"> --}}

                                            <video width="100%" height="100%" controls>
                                                <source src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/{{$media}}"
                                                    type="video/mp4">
                                            </video>
                                            {{-- </div> --}}
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    @endif
                @elseif(auth()->user()->id == $gp_message->sender_id)
                    <div class="group-chat-sender-container">
                        <div class="group-chat-sender-text-container">
                            <span>{{ $gp_message->user->name }}</span>
                            @if ($gp_message->text != null)
                                <p>{{ $gp_message->text }}</p>
                            @else
                                <div class="group-chat-imgs-vids-container">
                                    @foreach (json_decode($gp_message->media) as $key => $media)
                                        @if (pathinfo($media, PATHINFO_EXTENSION) == 'png' ||
                                            pathinfo($media, PATHINFO_EXTENSION) == 'jpg' ||
                                            pathinfo($media, PATHINFO_EXTENSION) == 'jpeg')
                                            <div class="modal fade"
                                                id="exampleModalToggle{{ $gp_message->id }}{{ $key }}"
                                                aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/{{$media}}"
                                                                class="w-100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <a data-bs-toggle="modal"
                                                href="#exampleModalToggle{{ $gp_message->id }}{{ $key }}"
                                                role="button">
                                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/{{$media}}">
                                            </a>
                                        @elseif(pathinfo($media, PATHINFO_EXTENSION) == 'mp4' ||
                                            pathinfo($media, PATHINFO_EXTENSION) == 'mov' ||
                                            pathinfo($media, PATHINFO_EXTENSION) == 'webm')
                                            <video width="100%" height="100%" controls>
                                                <source src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/{{$media}}"
                                                    type="video/mp4">
                                            </video>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @if ($gp_message->user->user_profile == null)
                            <img class="nav-profile-img" src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$gp_message->user->user_profile->profile_image}}" />
                        @endif
                    </div>
                @else
                @endif
            @endforeach

        </div>

        <!-- Incoming Call  -->


        <!-- End of Incoming Call  -->

        <div id="incomingCallContainer">

        </div>

        <div id="video-main-container">

        </div>

        <form class="group-chat-send-form-container" id="message_form" enctype="multipart/form-data">
            <div class="group-chat-send-form-message-parent-container">
                <div class="group-chat-send-form-img-emoji-container">
                    <label class="group-chat-send-form-img-contaier">
                        <iconify-icon icon="bi:images" class="group-chat-send-form-img-icon">

                        </iconify-icon>
                        <input type="file" name="fileSend[]" id="groupChatImg_message" multiple="multiple">
                    </label>
                    <button type="button" id="emoji-button" class="emoji-trigger">
                        <iconify-icon icon="bi:emoji-smile" class="group-chat-send-form-emoji-icon"></iconify-icon>
                    </button>

                </div>

                <textarea id="mytextarea" class="group-chat-send-form-input message_input" placeholder="Message..." required></textarea>
                <div class="group-chat-img-preview-container-wrapper">
                    <div class="group-chat-img-preview-container"></div>
                </div>
            </div>

            <button type="submit" class="group-chat-send-form-submit-btn">
                <iconify-icon icon="akar-icons:send" class="group-chat-send-form-submit-btn-icon"></iconify-icon>
            </button>
        </form>

        <!-- </div> -->
    </div>
@endsection
@push('scripts')
    <script>
        var messageForm = document.getElementById('message_form');
        var sendMessage = document.querySelector('.group-chat-send-form-submit-btn');
        const emojibutton = document.querySelector('.emoji-trigger');
        var groupId = document.getElementById('groupId').value;
        var auth_user_id = {{ auth()->user()->id }};
        var messageContainer = document.querySelector('.group-chat-messages-container');
        var auth_user_data;
        var auth_user_img;
        var messageInput_message = document.querySelector('.message_input');



        $(document).ready(function() {
            $('.group-chat-messages-container').scrollTop($('.group-chat-messages-container')[0].scrollHeight);
            //image and video select start
            $("#groupChatImg_message").on("change", handleFileSelect_message);

            // $("#editPostInput").on("change", handleFileSelectEdit);

            selDiv = $(".group-chat-img-preview-container");

            console.log(selDiv);

            $("body").on("click", ".delete-preview-icon", removeFile_message);
            // $("body").on("click", ".delete-preview-edit-input-icon", removeFileFromEditInput);

            console.log($("#selectFilesM").length);
            //image and video select end


            auth_user_data = @json($auth_user_data);
            auth_user_img = auth_user_data.user_profile;

            var messageInput = document.querySelector('.message_input');

            const emojibutton = document.querySelector('.emoji-trigger');

            const picker = new EmojiButton();

            emojibutton.addEventListener('click', () => {
                picker.togglePicker(emojibutton);
            });

            picker.on('emoji', emoji => {
                messageInput.value += emoji;
            });

        })


        //image and video select start
        var selDiv = "";

        var storedFiles_message = [];
        // var storedFiles_messageEdit = [];
        const dt_message = new DataTransfer();
        // const dtEdit = new DataTransfer();

        function handleFileSelect_message(e) {

            var files = e.target.files;
            console.log(files)

            var filesArr = Array.prototype.slice.call(files);

            var device = $(e.target).data("device");

            filesArr.forEach(function(f) {
                // console.log(f);
                if (f.type.match("image.*")) {
                    storedFiles_message.push(f);

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
                            $(".group-chat-img-preview-container").append(html);
                        }
                    }
                    reader.readAsDataURL(f);
                    dt_message.items.add(f);
                } else if (f.type.match("video.*")) {
                    storedFiles_message.push(f);

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
                            $(".group-chat-img-preview-container").append(html);
                        }
                    }
                    reader.readAsDataURL(f);
                    dt_message.items.add(f);
                }


            });

            document.getElementById('groupChatImg_message').files = dt_message.files;
            console.log(document.getElementById('groupChatImg_message').files + " Add Post Input")
            console.log(storedFiles_message.length, "stored files")

            if (storedFiles_message.length === 0) {
                $('.group-chat-send-form-message-parent-container').append(messageInput_message)
                $(".group-chat-img-preview-container-wrapper").hide()

            } else {
                messageInput_message.remove()
                $(".group-chat-img-preview-container-wrapper").show()
            }

        }


        function removeFile_message(e) {
            var file = $(this).data("file");
            var names = [];
            for (let i = 0; i < dt_message.items.length; i++) {
                if (file === dt_message.items[i].getAsFile().name) {
                    dt_message.items.remove(i);
                }
            }
            document.getElementById('groupChatImg_message').files = dt_message.files;

            for (var i = 0; i < storedFiles_message.length; i++) {
                if (storedFiles_message[i].name === file) {
                    storedFiles_message.splice(i, 1);
                    break;
                }
            }
            $(this).parent().remove();

            console.log(storedFiles_message.length)

            if (storedFiles_message.length === 0) {
                console.log($('.group-chat-send-form-message-parent-container'))
                $('.group-chat-send-form-message-parent-container').append(messageInput_message)
                $(".group-chat-img-preview-container-wrapper").hide()

            } else {
                messageInput_message.remove()
                $(".group-chat-img-preview-container-wrapper").show()
            }
        }

        function clearAddPost() {
            storedFiles_message = []
            dt_message.clearData()
            document.getElementById('groupChatImg_message').files = dt_message.files;
            $(".group-chat-img-preview-container").empty();

            if (storedFiles_message.length === 0) {
                console.log($('.group-chat-send-form-message-parent-container'))
                $('.group-chat-send-form-message-parent-container').append(messageInput_message)
                $(".group-chat-img-preview-container-wrapper").hide()

            } else {
                messageInput_message.remove()
                $(".group-chat-img-preview-container-wrapper").show()
            }
        }



        //image and video select end
        sendMessage.addEventListener('click', function(e) {
            e.preventDefault();

            var messageInput = document.querySelector('.message_input');
            var fileMessage = document.getElementById('groupChatImg_message')

            var formData;
            var fileLength = fileMessage.files.length
            console.log(fileLength);

            if (fileLength > 5) {
                console.log('can not send');
            } else {
                formData = new FormData(messageForm);
                let images = $("#groupChatImg_message")[0];

                for (let i = 0; i < fileLength; i++) {
                    formData.append('images' + i, images.files[i]);
                }

                var default_user = 'user_default';
                formData.append('totalFiles', fileLength);
                formData.append('senderId', auth_user_id);
                formData.append('senderName', auth_user_data.name);
                if (auth_user_img == null) {
                    formData.append('senderImg', default_user)
                } else {
                    formData.append('senderImg', auth_user_img.profile_image)
                }
            }

            if (messageInput != null) {
                axios.post('/api/group/message/chat/' + groupId, {
                    text: messageInput.value,
                    senderId: auth_user_id,
                    senderImg: auth_user_img ? auth_user_img.profile_image : 'user_default',
                    senderName: auth_user_data.name
                }).then();
                messageInput.value = "";
            } else {
                axios.post('/api/group/message/chat/' + groupId, formData).then();
                clearAddPost()
            }

        })

        // Here
        Echo.channel('groupChatting.' + auth_user_id)
            .listen('GroupVideoCall', (data) => {
                console.log("Listening data Grop Call .............", data.data.channelName);
                incomingCall = true;
                agoraChannel = data.data.channelName;
                console.log("Listeninggagdg ", agoraChannel);
                if (incomingCall) {
                    $(".chat-backdrop").show();
                    document.getElementById('incomingCallContainer').innerHTML += `<div class="row my-5">
                                        <div class="card shadow p-4 col-12">
                                            <p>
                                                Incoming Call From <strong>One for All</strong>
                                            </p>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="declineGroupCall()">
                                                    Decline
                                                </button>
                                                <button type="button" class="btn btn-success ml-5" onclick="acceptGroupCall()">
                                                    Accept
                                                </button>
                                            </div>
                                        </div>
                                    </div>`;
                }
            })
            .listen('GroupAudioCall', (data) => {
                console.log("Group audio start.....", data);
                incomingCall = true;
                agoraChannel = data.data.channelName;
                incomingAudioCall = true;
                console.log("Listeninggagdg ", agoraChannel);
                if (incomingCall) {
                    $(".chat-backdrop").show();
                    if (incomingAudioCall) {
                        incomingCallContainer.innerHTML += `<div class="row my-5" id="incoming_call">
                                <div class="card shadow p-4 col-12">
                                    <p>
                                        Audio Call From <span>${groupId}</span>
                                    </p>
                                    <div class="d-flex justify-content-center gap-3">
                                        <button type="button" class="btn btn-sm btn-danger"  id="" onclick="declineGroupCall()">
                                            Decline
                                        </button>
                                        <button type="button" class="btn btn-sm btn-success ml-5" onclick="acceptGroupCall()">
                                            Accept
                                        </button>
                                    </div>
                                </div>
                           </div>`;
                    }
                }
            })

            .listen('.group-chatting-event', (data) => {
                    console.log(data);
                    console.log(auth_user_img);

                    if (data.message.sender_id == auth_user_id) {
                        if (data.message.text != null) {
                            if (auth_user_img == null) {
                                messageContainer.innerHTML += `<div class="group-chat-sender-container">
                                        <div class="group-chat-sender-text-container">
                                            <span>${data.senderName}</span>
                                            <p>${data.message.text}</p>
                                        </div>
                                        <img class="nav-profile-img" src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                                    </div>`;
                            } else {
                                messageContainer.innerHTML += `<div class="group-chat-sender-container">
                                                <div class="group-chat-sender-text-container">
                                                    <span>${data.senderName}</span>
                                                    <p>${data.message.text}</p>
                                                </div>
                                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${auth_user_img.profile_image}" />
                                            </div>`;
                            }
                        } else {

                            var imageFile = data.message.media
                            var imageArr = JSON.parse(imageFile)
                            var messageMediaContainer

                            var messageMediaContainer = `<div class="group-chat-imgs-vids-container">
                        ${
                            Object.keys(imageArr).map(key => {

                            if (imageArr[key].split('.').pop() === 'png' || imageArr[key]
                                .split('.').pop() ===
                                'jpg' || imageArr[key].split('.').pop() === 'jpeg' || imageArr[key].split('.')
                                .pop() === 'gif') {
                                    return `<div class="modal fade" id="exampleModalToggle${data.message.id}${key}" aria-hidden="true"
                                                                        aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                                                        <div class="modal-dialog modal-dialog-centered">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                                        aria-label="Close"></button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/${imageArr[key]}"
                                                                                        alt="test" class="w-100">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                </div>

                                                            <a data-bs-toggle="modal" href="#exampleModalToggle${data.message.id}${key}" role="button">
                                                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/${imageArr[key]}">
                                                            </a>`
                            } else if (imageArr[key].split('.').pop() === 'mp4' || imageArr[key].split('.')
                                .pop() ===
                                'mov' || imageArr[key].split('.').pop() === 'webm') {

                                return ` <video width = "100%" height = "100%" controls>
                                <source src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/${imageArr[key]}" type = "video/mp4">
                                </video>`

                    }
                }).join('')
        } </div>`;
        if (auth_user_img == null) {
            messageContainer.innerHTML += `
                                    <div class="group-chat-sender-container">
                                        <div class="group-chat-sender-text-container">
                                            <span>${data.senderName}</span>
                                            ${messageMediaContainer}
                                            </div>
                                        <img class="nav-profile-img" src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                                    </div>`;
        } else {
            messageContainer.innerHTML += `
                                    <div class="group-chat-sender-container">
                                        <div class="group-chat-sender-text-container">
                                            <span>${data.senderName}</span>
                                            ${messageMediaContainer}
                                            </div>
                                        <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${auth_user_img.profile_image}" />
                                    </div>`;
        }

        }

        } else {
            var receiverMessageMedia = ``
            if (data.senderImg == 'user_default') {
                if (data.message.text != null) {
                    messageContainer.innerHTML += `<div class="group-chat-receiver-container">
                                    <img class="nav-profile-img" src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                                <div class="group-chat-receiver-text-container">
                                    <span>${data.senderName}</span>
                                    <p>${data.message.text}</p>
                                </div>
                            </div>`;
                } else {
                    var imageFile = data.message.media
                    var imageArr = JSON.parse(imageFile)

                    receiverMessageMedia = `
                            <div class="group-chat-imgs-vids-container">
                            ${Object.keys(imageArr).map(key => {

                                if (imageArr[key].split('.').pop() === 'png' || imageArr[key]
                                    .split('.').pop() ===
                                    'jpg' || imageArr[key].split('.').pop() === 'jpeg' || imageArr[key].split(
                                        '.')
                                    .pop() === 'gif') {
                                        return `
                                                            <div class="modal fade" id="exampleModalToggle${data.message.id}${key}" aria-hidden="true"
                                                                aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/${imageArr[key]}"
                                                                                alt="test" class="w-100">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <a data-bs-toggle="modal" href="#exampleModalToggle${data.message.id}${key}" role="button">
                                                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/${imageArr[key]}">
                                                            </a>
                                                            `

                                } else if (imageArr[key].split('.').pop() === 'mp4' || imageArr[key].split('.')
                                    .pop() ===
                                    'mov' || imageArr[key].split('.').pop() === 'webm') {
                                        return ` <video width = "100%" height = "100%" controls>
                        <source src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/${imageArr[key]}" type ="video/mp4" >
                        </video>`

            }
        }).join('')
    } </div>`
        messageContainer.innerHTML += `<div class = "group-chat-receiver-container">
        <img class = "nav-profile-img" src = "{{ asset('img/customer/imgs/user_default.jpg') }}" / >
        <div class = "group-chat-receiver-text-container" >
        <span>${data.senderName}</span>
    ${receiverMessageMedia} </div> </div>`;

        }

        }
        else {
            if (data.message.text != null) {
                messageContainer.innerHTML += `<div class="group-chat-receiver-container">
                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${data.senderImg}" />
                                <div class="group-chat-receiver-text-container">
                                    <span>${data.senderName}</span>
                                    <p>${data.message.text}</p>
                                </div>
                            </div>`;
            } else {
                var imageFile = data.message.media
                var imageArr = JSON.parse(imageFile)
                receiverMessageMedia = `
                            <div class = "group-chat-imgs-vids-container">
                            ${Object.keys(imageArr).map(key => {
                                if (imageArr[key].split('.').pop() === 'png' || imageArr[key]
                                    .split('.').pop() ===
                                    'jpg' || imageArr[key].split('.').pop() === 'jpeg' || imageArr[key].split(
                                        '.')
                                    .pop() === 'gif') {
                                        return `
                                                <div class="modal fade" id="exampleModalToggle${data.message.id}${key}" aria-hidden="true"
                                                    aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/${imageArr[key]}"
                                                                    alt="test" class="w-100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <a data-bs-toggle="modal" href="#exampleModalToggle${data.message.id}${key}" role="button">
                                                    <img src="{{ asset('storage/customer_message_media/${imageArr[key]}') }}">
                                                </a>`


                                } else if (imageArr[key].split('.').pop() === 'mp4' || imageArr[key].split('.')
                                    .pop() ===
                                    'mov' || imageArr[key].split('.').pop() === 'webm') {
                                        return `<video width = "100%" height = "100%" controls>
                    <source src = "{{ asset('storage/customer_message_media/${imageArr[key]}') }}" type = "video/mp4" >
                    </video>`

        }
    }).join('')
    } </div>`
        messageContainer.innerHTML += `<div class ="group-chat-receiver-container">
        <img src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${data.senderImg}" / >
        <div class = "group-chat-receiver-text-container" >
        <span>${data.senderName}</span>
    ${receiverMessageMedia}</div> </div>`;
        }



        }

        }
        })




        //////////////////////////////////////////////////////////////////////////////////////////
        var messageInput = document.querySelector('.message_input');

        var groupChatImgInput = document.querySelector('#groupChatImg');

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

    <script>
        $(".chat-backdrop").hide();
        let hero = "Super"
        let onlineUsers = [];
        let client = null;
        let callPlaced = false;
        let localStream = null;
        let incomingCaller = "";
        let agoraChannel = null;
        let incomingCall = false;
        let incomingAudioCall = false;
        let videoCallEvent = false;
        let audioCallEvent = false;
        let mutedVideo = false;
        let mutedAudio = false;
        const agora_id = 'e8d6696cc7dc449dbd78ebbd1e15ee13';
        var groupId = document.getElementById('groupId').value;
        var groupName = document.getElementById('groupName').value;

        let authuser = "{{ auth()->user()->name }}"
        let authuserId = "{{ auth()->id() }}"

        let incoming_call = document.getElementById('incoming_call')
        let video_container = document.getElementById('video-main-container')
        let incomingCallContainer = document.querySelector('#incomingCallContainer')

        // Here
        Echo.channel('groupCalling.' + authuserId)
            .listen('.GroupVideoCall', (data) => {
                console.log("Listening data Grop Call .............", data.data.channelName);
                incomingCall = true;
                agoraChannel = data.data.channelName;
                console.log("Listeninggagdg ", agoraChannel);
                if (incomingCall) {
                    $(".chat-backdrop").show();
                    document.getElementById('incomingCallContainer').innerHTML += `<div class="row my-5">
                                        <div class="card shadow p-4 col-12">
                                            <p>
                                                Incoming Call From <strong>${groupName}</strong>
                                            </p>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="declineGroupCall()">
                                                    Decline
                                                </button>
                                                <button type="button" class="btn btn-success ml-5" onclick="acceptGroupCall()">
                                                    Accept
                                                </button>
                                            </div>
                                        </div>
                                    </div>`;
                }
            })
            .listen('.GroupAudioCall', (data) => {
                console.log("Group audio start.....", data);
                incomingCall = true;
                agoraChannel = data.data.channelName;
                incomingAudioCall = true;
                console.log("Listeninggagdg ", agoraChannel);
                if (incomingCall) {
                    $(".chat-backdrop").show();
                    if (incomingAudioCall) {
                        incomingCallContainer.innerHTML += `<div class="row my-5" id="incoming_call">
                                <div class="card shadow p-4 col-12">
                                    <p>
                                        Audio Call From <span>${groupName}</span>
                                    </p>
                                    <div class="d-flex justify-content-center gap-3">
                                        <button type="button" class="btn btn-sm btn-danger"  id="" onclick="declineGroupCall()">
                                            Decline
                                        </button>
                                        <button type="button" class="btn btn-sm btn-success ml-5" onclick="acceptGroupCall()">
                                            Accept
                                        </button>
                                    </div>
                                </div>
                           </div>`;
                    }
                }
            });


        // vcall functions start
        async function placeCall(groupId) {
            try {
                const channelName = groupId.toString();
                const tokenRes = await generateToken(channelName)

                axios.post("/agora/call-gp-user", {
                    group_id: groupId,
                    channel_name: channelName
                });
                initializeAgora();
                joinRoom(tokenRes.data, channelName);
                callPlaced = true;

                videoCallEvent = true;

            } catch (error) {
                console.log(error);
            }
        }

        async function placeCallAudio(groupId) {
            try {
                const channelName = groupId.toString();
                const tokenRes = await generateToken(channelName);

                console.log(tokenRes.data);

                axios.post("/agora/call-gpaudio-user", {
                    group_id: groupId,
                    channel_name: channelName
                });
                initializeAgora();
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

        async function acceptGroupCall() {
            console.log('call accept lite p', agoraChannel);
            initializeAgora();
            const tokenRes = await generateToken(agoraChannel);
            console.log("group accept", tokenRes.data);
            joinRoom(tokenRes.data, agoraChannel);
            incomingCall = false;
            callPlaced = true;
            videoCallEvent = true;
            incomingCallContainer.innerHTML = "";
        }

        function declineGroupCall() {
            incomingCall = false;
            incomingCallContainer.innerHTML = "";
            $(".chat-backdrop").hide()
        }

        function initializedAgoraListeners() {
            //   Register event listeners
            client.on("stream-published", function(evt) {
                console.log("Publish local stream successfully");
                console.log(evt);
            });
            //subscribe remote stream
            client.on("stream-added", ({
                stream
            }) => {
                console.log("New stream added: " + stream.getId());
                client.subscribe(stream, function(err) {
                    console.log("Subscribe stream failed", err);
                });
            });
            client.on("stream-subscribed", (evt) => {
                // Attach remote stream to the remote-video div
                // evt.stream.play("remote-video");
                //     client.publish(evt.stream);
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

        async function joinRoom(token, channel) {
            client.join(
                token,
                channel,
                authuser,
                (uid) => {
                    console.log("User " + uid + " join channel successfully");
                    callPlaced = true;
                    console.log("incoming audio call lay pr", incomingAudioCall);
                    if (callPlaced) {
                        // parent.document.body.classList.add('backdrop')
                        $("#video-main-container").show()
                        $(".chat-backdrop").show();
                        if (incomingAudioCall) {
                            video_container.innerHTML += `
                                                    <div id="audio-container">
                                                       <div id="local-audio"></div>
                                                        <div id="remote-audio"></div>
                                                    <div class="text-center ">
                                                        <img src="" class="rounded-circle img-thumbnail img-fluid shadow" width="200" height="200" />
                                                        <p class="mb-0 mt-3" style="color:#3CDD57;">${groupName}</p>
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

        function createLocalStream() {
            localStream = AgoraRTC.createStream({
                audio: true,
                video: true,
            });
            // Initialize the local stream
            localStream.init(
                () => {
                    // Play the local stream
                    localStream.play("local-video");
                    // Publish the local stream
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
            // Initialize the local stream
            localStream.init(
                () => {
                    // Play the local stream
                    localStream.play("local-audio");
                    // Publish the local stream
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

        // vcall functions end
</script>
@endpush
