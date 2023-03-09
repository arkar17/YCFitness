@extends('layouts.admin_app')

@section('messages-active', 'active')

@section('content')
    <div class="chat-backdrop"></div>

    <div class="social-media-right-container">


        <div class="group-chat-header">
            <div class="group-chat-header-name-container">
                @if ($receiver_user->user_profile == null)
                    <img class="nav-profile-img" src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                @else
                    <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$receiver_user->user_profile->profile_image}}" />
                @endif

                <div class="group-chat-header-name-text-container">
                    <p>{{ $receiver_user->name }}</p>
                    <small class="active-now" style="color:#3CDD57;"></small>
                </div>
            </div>

            <div class="chat-header-call-icons-container">
                <a href="{{ route('admin.chat.viewmedia', $receiver_user->id) }}" class="group-chat-view-midea-link">
                    <p>{{__('msg.view media')}}</p>
                    <iconify-icon icon="akar-icons:arrow-right" class="group-chat-view-midea-link-icon"></iconify-icon>
                </a>
            </div>


        </div>
        <input type="hidden" value="{{ $id }}" id="recieveUser">
        <input type="hidden" value="{{ $receiver_user->name }}" id="receiverUserName">

        <div class="group-chat-messages-container">

            @forelse ($messages as $send_message)
                @if (auth()->user()->id == $send_message->from_user_id)
                    <div class="group-chat-sender-container" data-messageId="{{ $send_message->id }}">
                        <div class="message-actions-parent-container">
                            <iconify-icon icon="mdi:dots-vertical" class="message-icon" onclick="toggleActionBox(event)">

                            </iconify-icon>
                            <div class="message-actions-box">
                                <p onclick="message_hide(event,{{ $send_message->id }})">
                                    <iconify-icon icon="mdi:hide" class="message-action-icon"></iconify-icon>
                                    {{__('msg.delete')}}
                                </p>
                                <p onclick="message_delete(event,{{ $send_message->id }})">
                                    <iconify-icon icon="material-symbols:cancel-schedule-send-rounded"
                                        class="message-action-icon"></iconify-icon>
                                    {{__('msg.unsend')}}
                                </p>
                            </div>

                        </div>


                        <div class="group-chat-sender-text-container" style = "margin-top:20px;">
                            @if ($send_message->media == null)
                                <p>{{ $send_message->text }}</p>
                            @else
                                <div class="group-chat-imgs-vids-container">
                                    @foreach (json_decode($send_message->media) as $key => $media)
                                        @if (pathinfo($media, PATHINFO_EXTENSION) == 'png' ||
                                            pathinfo($media, PATHINFO_EXTENSION) == 'jpg' ||
                                            pathinfo($media, PATHINFO_EXTENSION) == 'jpeg')
                                            {{-- modal --}}
                                            <div class="modal fade"
                                                id="exampleModalToggle{{ $send_message->id }}{{ $key }}"
                                                aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/{{$media}}"
                                                                alt="test" class="w-100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- end modal --}}


                                            <a data-bs-toggle="modal"
                                                href="#exampleModalToggle{{ $send_message->id }}{{ $key }}"
                                                role="button">
                                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/{{ $media }}">
                                            </a>
                                        @elseif(pathinfo($media, PATHINFO_EXTENSION) == 'mp4' ||
                                            pathinfo($media, PATHINFO_EXTENSION) == 'mov' ||
                                            pathinfo($media, PATHINFO_EXTENSION) == 'webm')
                                            <video width="100%" height="100%" controls>
                                                <source src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/{{ $media}}"
                                                    type="video/mp4">
                                            </video>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @if ($sender_user->user_profile == null)
                            <img class="nav-profile-img" src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $sender_user->user_profile->profile_image }} " />
                        @endif
                    </div>
                @elseif(auth()->user()->id != $send_message->from_user_id)
                    <div class="group-chat-receiver-container" data-messageId="{{ $send_message->id }}" style="margin-top:20px;">
                        @if ($receiver_user->user_profile == null)
                            <img class="nav-profile-img" src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $receiver_user->user_profile->profile_image }}" />
                        @endif
                        <div class="group-chat-receiver-text-container">
                            {{-- <span>{{ $send_message->name }}</span> --}}

                            @if ($send_message->media == null)
                                <p>{{ $send_message->text }}</p>
                            @else
                                <div class="group-chat-imgs-vids-container">
                                    @foreach (json_decode($send_message->media) as $key => $media)
                                        @if (pathinfo($media, PATHINFO_EXTENSION) == 'png' ||
                                            pathinfo($media, PATHINFO_EXTENSION) == 'jpg' ||
                                            pathinfo($media, PATHINFO_EXTENSION) == 'jpeg')
                                            {{-- modal --}}
                                            <div class="modal fade"
                                                id="exampleModalToggle{{ $send_message->id }}{{ $key }}"
                                                aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/{{ $media }}"
                                                                alt="test" class="w-100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- end modal --}}


                                            <a data-bs-toggle="modal"
                                                href="#exampleModalToggle{{ $send_message->id }}{{ $key }}"
                                                role="button">
                                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/{{ $media }}">
                                            </a>
                                        @elseif(pathinfo($media, PATHINFO_EXTENSION) == 'mp4' ||
                                            pathinfo($media, PATHINFO_EXTENSION) == 'mov' ||
                                            pathinfo($media, PATHINFO_EXTENSION) == 'webm')
                                            <video width="100%" height="100%" controls>
                                                <source src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/{{ $media }}"
                                                    type="video/mp4">
                                            </video>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>

                    </div>
                @endif
            @empty
            @endforelse

        </div>


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
                        <input type="file" id="groupChatImg_message" name="fileSend[]" multiple="multiple">
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

            <button type="button" class="group-chat-send-form-submit-btn">
                <iconify-icon icon="akar-icons:send" class="group-chat-send-form-submit-btn-icon"></iconify-icon>
            </button>
        </form>

    </div>



@endsection
@push('scripts')
<script>
    var messageForm = document.getElementById('message_form');

    var sendMessage = document.querySelector('.group-chat-send-form-submit-btn')

    const emojibutton = document.querySelector('.emoji-trigger');
    var recieveUser = document.getElementById('recieveUser');

    var messageContainer = document.querySelector('.group-chat-messages-container');


    var auth_user_id = {{ auth()->user()->id }};
    var auth_user_name = "{{ auth()->user()->name }}";
    var recieveUserId = recieveUser.value;
    var fileName;
    var receive_user_img;
    var sender_user_img;

    var messageInput_message = document.querySelector('.message_input');


    $(document).ready(function() {
        $('.group-chat-messages-container').scrollTop($('.group-chat-messages-container')[0].scrollHeight);
    });

    $(document).ready(function() {

        //image and video select start
        $("#groupChatImg_message").on("change", handleFileSelect_message);
        $(".group-chat-img-preview-container-wrapper").hide()

        // $("#editPostInput").on("change", handleFileSelectEdit);

        selDiv = $(".group-chat-img-preview-container");

        console.log(selDiv);

        $("body").on("click", ".delete-preview-icon", removeFile_message);
        // $("body").on("click", ".delete-preview-edit-input-icon", removeFileFromEditInput);

        console.log($("#selectFilesM").length);
        //image and video select end



        ///start
        var check_receiver_img = @json($receiver_user->user_profile) == null;
        var check_sender_img = @json($sender_user->user_profile) == null;

        // console.log(check_sender_img == null)

        if (check_receiver_img) {

            receive_user_img = @json($receiver_user->user_profile);
        } else {
            // console.log("receiver img not null")
            receive_user_img = @json($receiver_user->user_profile?->profile_image);
        }
        if (check_sender_img) {
            // console.log("sender img  null")
            sender_user_img = @json($sender_user->user_profile);

        } else {
            // console.log("sender img not null")
            sender_user_img = @json($sender_user->user_profile?->profile_image);
        }


        console.log('sender profile', check_receiver_img);
        console.log('receiver profile', check_sender_img);

        const emojibutton = document.querySelector('.emoji-trigger');

        const picker = new EmojiButton();

        emojibutton.addEventListener('click', () => {
            picker.togglePicker(emojibutton);

        });

        picker.on('emoji', emoji => {
            messageInput_message.value += emoji;
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





    //image and video select end
    to_user_id = {{ $receiver_user->id }}
    // console.log(to_user_id , "touserid");


    sendMessage.addEventListener('click', function(e) {
        e.preventDefault();

        var messageInput = document.querySelector('.message_input');
        var fileMessage = document.getElementById('groupChatImg_message');
        console.log('reciever ', recieveUserId);
        console.log('sender auth user ', auth_user_id);

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
            formData.append('fileInput', fileName);
            formData.append('totalFiles', fileLength);
            formData.append('sender', auth_user_name);
            formData.append('to_user_id', to_user_id);
        }
        
        if (messageInput != null) {
            axios.post('/message/chat_admin/admin_side', {
                text: messageInput.value,
                sender: auth_user_name,
                to_user_id : to_user_id
            }).then();
            messageInput.value = "";
        } else {
            axios.post('/message/chat_admin/admin_side', formData).then();
            clearAddPost()
        }

    })

    function clearAddPost() {
        console.log('clear imgs')
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

    function message_hide(e, id) {
        axios.post('/socialmedia/message/hide', {
            id: id,
            delete_user: auth_user_id
        }).then(
            $(e.target).closest(".group-chat-sender-container").remove()
        )
    }

    function message_delete(e, id) {
        var delete_messageId = $(e.target).closest(".group-chat-sender-container").data("messageid");
        axios.post('/socialmedia/message/delete', {
            id: id,
            delete_user: auth_user_id,
            messageId: delete_messageId
        }).then(
            $(e.target).closest(".group-chat-sender-container").remove()
        )


    }



    Echo.channel('chatting.' + auth_user_id + '.' + recieveUserId)
        .listen('.chatting-event', (data) => {
                console.log('asdufsasusiui', data);
                if (data.message.from_user_id == recieveUserId) {
                    if (data.message.media == null && data.message.text == null) {} else {
                        if (data.message.media != null) {

                            var imageFile = data.message.media
                            var imageArr = JSON.parse(imageFile)
                            var receiverMessageMedia

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
                                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/${imageArr[key]}">
                                            </a>`


                                    } else if (imageArr[key].split('.').pop() === 'mp4' || imageArr[key].split('.')
                                        .pop() ===
                                        'mov' || imageArr[key].split('.').pop() === 'webm') {
                                            return `<video width = "100%" height = "100%" controls>
                                <source src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/${imageArr[key]}" type = "video/mp4">
                                </video>`
                    }
                }).join('')
        } </div>`;

            if (receive_user_img != null) {
                messageContainer.innerHTML += `<div class="group-chat-receiver-container" data-messageId="${data.message.id}">
                                                        <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${receive_user_img}" />
                                                        <div class="group-chat-receiver-text-container">

                                                            ${receiverMessageMedia}
                                                        </div>
                                                    </div>`;
            } else {
                messageContainer.innerHTML += `<div class="group-chat-receiver-container" data-messageId="${data.message.id}">
                                                        <img src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                                                        <div class="group-chat-receiver-text-container">

                                                            ${receiverMessageMedia}
                                                        </div>
                                                    </div>`;
            }


        }
    }
    }
    else {
        if (data.message.media == null && data.message.text == null) {} else {
            if (data.message.media != null) {

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

                            return `<video width = "100%" height = "100%" controls>
                    <source src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/${imageArr[key]}" type = "video/mp4">
                    </video>`

        }
    }).join('')
} </div>`

    if (sender_user_img != null) {
        messageContainer.innerHTML += `
                                <div class="group-chat-sender-container">
                                    <div class="message-actions-parent-container">
                                        <iconify-icon icon="mdi:dots-vertical" class="message-icon" onclick="toggleActionBox(event)">

                                        </iconify-icon>
                                        <div class="message-actions-box">
                                            <p onclick="message_hide(event,${data.message.id})">
                                                <iconify-icon icon="mdi:hide" class="message-action-icon"></iconify-icon>
                                                Delete
                                            </p>
                                            <p onclick="message_delete(event,${data.message.id})">
                                                <iconify-icon icon="material-symbols:cancel-schedule-send-rounded" class="message-action-icon"></iconify-icon>
                                                Unsend
                                            </p>
                                        </div>

                                    </div>
                                    <div class="group-chat-sender-text-container">

                                        ${messageMediaContainer}
                                        </div>
                                    <img class="nav-profile-img" src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${sender_user_img}" />
                                </div>`;
    } else {
        messageContainer.innerHTML += `
                                <div class="group-chat-sender-container">
                                    <div class="message-actions-parent-container">
                                        <iconify-icon icon="mdi:dots-vertical" class="message-icon" onclick="toggleActionBox(event)">

                                        </iconify-icon>
                                        <div class="message-actions-box">
                                            <p onclick="message_hide(event,${data.message.id})">
                                                <iconify-icon icon="mdi:hide" class="message-action-icon"></iconify-icon>
                                                Delete
                                            </p>
                                            <p onclick="message_delete(event,${data.message.id})">
                                                <iconify-icon icon="material-symbols:cancel-schedule-send-rounded" class="message-action-icon"></iconify-icon>
                                                Unsend
                                            </p>
                                        </div>

                                    </div>
                                    <div class="group-chat-sender-text-container">

                                        ${messageMediaContainer}
                                        </div>
                                    <img class="nav-profile-img" src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                                </div>`;
    }

    } else {
        if (sender_user_img != null) {
            messageContainer.innerHTML += `<div class="group-chat-sender-container">
                <div class="message-actions-parent-container">
                            <iconify-icon icon="mdi:dots-vertical" class="message-icon" onclick="toggleActionBox(event)">

                            </iconify-icon>
                            <div class="message-actions-box">
                                <p onclick="message_hide(event,${data.message.id})">
                                    <iconify-icon icon="mdi:hide" class="message-action-icon"></iconify-icon>
                                    Delete
                                </p>
                                <p onclick="message_delete(event,${data.message.id})">
                                    <iconify-icon icon="material-symbols:cancel-schedule-send-rounded" class="message-action-icon"></iconify-icon>
                                    Unsend
                                </p>
                            </div>

                        </div>
                                    <div class="group-chat-sender-text-container">

                                        <p>${data.message.text}</p>
                                    </div>
                                    <img class="nav-profile-img" src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${sender_user_img}" />
                                </div>`;
        } else {
            messageContainer.innerHTML += `<div class="group-chat-sender-container">
                <div class="message-actions-parent-container">
                            <iconify-icon icon="mdi:dots-vertical" class="message-icon" onclick="toggleActionBox(event)">

                            </iconify-icon>
                            <div class="message-actions-box">
                                <p onclick="message_hide(event,${data.message.id})">
                                    <iconify-icon icon="mdi:hide" class="message-action-icon"></iconify-icon>
                                    Delete
                                </p>
                                <p onclick="message_delete(event,${data.message.id})">
                                    <iconify-icon icon="material-symbols:cancel-schedule-send-rounded" class="message-action-icon"></iconify-icon>
                                    Unsend
                                </p>
                            </div>

                </div>
                                    <div class="group-chat-sender-text-container">

                                        <p>${data.message.text}</p>
                                    </div>
                                    <img class="nav-profile-img" src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                                </div>`;
        }

    }
    }
    }
    })

    Echo.channel('chatting.' + recieveUserId + '.' + auth_user_id)
        .listen('.chatting-event', (data) => {
                console.log(data);
                if (data.message.from_user_id == recieveUserId) {
                    if (data.message.media == null && data.message.text == null) {} else {
                        if (data.message.media != null) {

                            var imageFile = data.message.media
                            var imageArr = JSON.parse(imageFile)
                            var receiverMessageMedia

                            receiverMessageMedia = `
                            <div class = "group-chat-imgs-vids-container">
                                ${Object.keys(imageArr).map(key => {
                                    if (imageArr[key].split('.').pop() === 'png' || imageArr[key]
                                        .split('.').pop() ===
                                        'jpg' || imageArr[key].split('.').pop() === 'jpeg' || imageArr[key].split(
                                            '.')
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
                                                                    </a>`;


                                    } else if (imageArr[key].split('.').pop() === 'mp4' || imageArr[key].split('.')
                                        .pop() ===
                                        'mov' || imageArr[key].split('.').pop() === 'webm') {
                                                return `<video width = "100%" height = "100%" controls>
                                <source src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/${imageArr[key]}" type = "video/mp4">
                                </video>`

                    }
                }).join('')
        } </div>`;

            if (receive_user_img != null) {
                messageContainer.innerHTML += `<div class="group-chat-receiver-container" data-messageId="${data.message.id}">
                                                                        <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${receive_user_img}" />
                                                                        <div class="group-chat-receiver-text-container">

                                                                            ${receiverMessageMedia}
                                                                        </div>
                                                                    </div>`;
            } else {
                messageContainer.innerHTML += `<div class="group-chat-receiver-container" data-messageId="${data.message.id}">
                                                                        <img src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                                                                        <div class="group-chat-receiver-text-container">

                                                                            ${receiverMessageMedia}
                                                                        </div>
                                                                    </div>`;
            }

        }
    else {

        if (receive_user_img != null) {
            messageContainer.innerHTML += `<div class="group-chat-receiver-container" data-messageId="${data.message.id}">
                                                    <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${receive_user_img}" />
                                                    <div class="group-chat-receiver-text-container">

                                                        <p>${data.message.text}</p>
                                                    </div>
                                                </div>`;
        } else {
            messageContainer.innerHTML += `<div class="group-chat-receiver-container" data-messageId="${data.message.id}">
                                                        <img src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                                                    <div class="group-chat-receiver-text-container">

                                                        <p>${data.message.text}</p>
                                                    </div>
                                                </div>`;
        }

    }
    }
    } else {
        if (data.message.media == null && data.message.text == null) {} else {
            if (data.message.media != null) {

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
                                                return `<video width = "100%" height = "100%" controls>
                    <source src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/customer_message_media/${imageArr[key]}" type = "video/mp4">
                    </video>`

        }
    }).join('')
} </div>`;

    if (receive_user_img != null) {
        messageContainer.innerHTML += `<div class="group-chat-receiver-container" data-messageId="${data.message.id}">
                                                                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${receive_user_img}" />
                                                                                <div class="group-chat-receiver-text-container">

                                                                                    ${receiverMessageMedia}
                                                                                </div>
                                                                            </div>`;
    } else {
        messageContainer.innerHTML += `<div class="group-chat-receiver-container" data-messageId="${data.message.id}">
                                                                                <img src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                                                                                <div class="group-chat-receiver-text-container">

                                                                                    ${receiverMessageMedia}
                                                                                </div>
                                                                            </div>`;
    }
    } else {
        if (receive_user_img != null) {
            messageContainer.innerHTML += `<div class="group-chat-receiver-container" data-messageId="${data.message.id}">
                                                        <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${receive_user_img}" />
                                                        <div class="group-chat-receiver-text-container">

                                                            <p>${data.message.text}</p>
                                                        </div>
                                                    </div>`;
        } else {
            messageContainer.innerHTML += `<div class="group-chat-receiver-container" data-messageId="${data.message.id}">
                                                            <img src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                                                        <div class="group-chat-receiver-text-container">

                                                            <p>${data.message.text}</p>
                                                        </div>
                                                    </div>`;
        }

    }

    }
    }

    })


    //message delete and hide start

    function toggleActionBox(event) {
        $('.message-actions-box').not($(event.target).next('.message-actions-box')).hide()
        $(event.target).next('.message-actions-box').toggle()
    }

    Echo.channel('message-delete.' + recieveUserId + '.' + auth_user_id)
        .listen('.message-delete-event', (data) => {
            console.log(data);
            $.each($(".group-chat-receiver-container"), function() {
                if ($(this).data('messageid') === data.id) {
                    $(this).remove()
                }
            })
        })

    // message delete and hide end
</script>
@endpush