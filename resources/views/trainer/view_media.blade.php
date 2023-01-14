@extends('trainer.layouts.app')

@section('content')

<div class="customer-main-content-container">
    <div class="trainer-main-content-container">

        <div class="trainer-two-columns-container">
            <div class="trainer-group-chats-parent-container">
                <p>Groups</p>
                <div class="trainer-group-chats-container">
                    @forelse ($groups as $group)
                        <a href ="{{route('trainer')}}" class="tainer-group-chat-name-container" id="group-chat" value="{{ $group->id }}"
                            data-id="{{ $group->id }}"
                            style=" background-color: transparent;background-repeat: no-repeat;border: none;cursor: pointer;overflow: hidden;outline: none;">
                            <img src="{{ asset('image/default.jpg') }}" />
                            <p>{{ $group->group_name }}</p>
                        </a>
                    @empty
                        <p class="text-secondary p-1">No Group</p>
                    @endforelse
                </div>
            </div>

            <div class="group-chat-container">
                <div class="group-chat-header">
                    <a href="#" id="group" class="group-chat-header-name-container">
                        <img src="{{ asset('image/default.jpg') }}" />
                        <div class="group-chat-header-name-text-container">
                            <p>{{$selected_group->group_name}}</p>
                        </div>
                    </a>

                    {{-- <a href="../htmls/trainerTrainingCenterViewMedia.html" class="group-chat-view-midea-link">
                        <p>View Media</p>
                        <iconify-icon icon="akar-icons:arrow-right" class="group-chat-view-midea-link-icon"></iconify-icon>
                    </a> --}}
                </div>
                <div class="trainer-group-chat-media-header">
                    <a class="back-btn">
                        <iconify-icon icon="bi:arrow-left" class="back-btn-icon"></iconify-icon>
                    </a>
                </div>

                <div class="trainer-group-chat-media-container">
                     @foreach($message as $sms)
                     <div class="modal fade" id="exampleModalToggle{{$sms->id}}" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                @if (pathinfo($sms->media, PATHINFO_EXTENSION) == 'mp4')
                                <video class="w-100" controls>
                                    <source src="{{asset('/storage/trainer_message_media/'.$sms->media)}}" type="video/mp4">
                                  Your browser does not support the video tag.
                              </video>
                                @else
                                 <img src="{{asset('/storage/trainer_message_media/'.$sms->media)}}" alt="test" class="w-100">
                                @endif
                            </div>
                          </div>
                        </div>
                      </div>

                     @if (pathinfo($sms->media, PATHINFO_EXTENSION) == 'mp4')
                        <div  class="trainer-group-chat-media">
                            <a  data-bs-toggle="modal" href="#exampleModalToggle{{$sms->id}}" role="button">
                                <video style="z-index: -1;">
                                    <source src="{{asset('/storage/trainer_message_media/'.$sms->media)}}" type="video/mp4">
                                </video>
                            </a>
                        </div>
                     @else
                        <div class="trainer-group-chat-media">
                                <a  data-bs-toggle="modal" href="#exampleModalToggle{{$sms->id}}" role="button">
                                    <img src="{{asset('/storage/trainer_message_media/'.$sms->media)}}" alt="test">
                                </a>
                        </div>
                     @endif
                    {{-- <div class="trainer-group-chat-media">
                         <button data-bs-toggle="modal" data-bs-target="#bd-example-modal-lg">
                            <img src="{{asset('/storage/trainer_message_media/'.$sms->media)}}" alt="test">

                         </button>
                    </div>
                    <div  class="trainer-group-chat-media">
                        <button data-bs-toggle="modal" data-bs-target="#bd-example-modal-lg">
                            <video style="z-index: -1;">
                                <source src="{{asset('/storage/trainer_message_media/'.$sms->media)}}" type="video/mp4">
                            </video>

                        </button>
                        </div> --}}
                    @endforeach

                </div>
                {{-- <form class="group-chat-send-form-container">
                    <div class="group-chat-send-form-message-parent-container">
                        <div class="group-chat-send-form-img-emoji-container">
                            <label class="group-chat-send-form-img-contaier">
                                <iconify-icon icon="bi:images" class="group-chat-send-form-img-icon">

                                </iconify-icon>
                                <input type="file" id="groupChatImg" name="groupChatImg">
                            </label>
                            <button type="button" id="emoji-button" class="emoji-trigger">
                                <iconify-icon icon="bi:emoji-smile" class="group-chat-send-form-emoji-icon"></iconify-icon>
                            </button>

                        </div>

                        <textarea id="mytextarea"  class="group-chat-send-form-input" placeholder="Message..." required ></textarea>
                        <img class="group-chat-img-preview groupChatImg">
                        <button type="reset"  class="group-chat-img-cancel" onclick="clearGroupChatImg()">
                            <iconify-icon icon="charm:cross" class="group-chat-img-cancel-icon"></iconify-icon>
                        </button>
                    </div>

                    <button type="submit" class="group-chat-send-form-submit-btn">
                        <iconify-icon icon="akar-icons:send" class="group-chat-send-form-submit-btn-icon"></iconify-icon>
                    </button>
                </form> --}}

            </div>
        </div>
    </div>
</div>

<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/emoji-picker/1.1.5/js/emoji-picker.min.js" integrity="sha512-EDnYyP0SRH/j5K7bYQlIQCwjm8dQtwtsE+Xt0Oyo9g2qEPDlwE+1fbvKqXuCoMfRR/9zsjSBOFDO6Urjefo28w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
<!-- <script src="../js/emoji.js"></script> -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
        $(document).on('click', '#group-chat', function() {
                    console.log("aa");
                    localStorage.setItem('selectGroup',$(this).data('id'));
        });
    function clearCreateGroupInputs(){
        const inputs = document.querySelectorAll(".create-group-form input"+",.create-group-form select")
        // console.log(inputs)
        for(var i = 0;i < inputs.length;i++){
            // console.log("hi")
            // console.log(inputs[i])
            inputs[i].value = ""
        }
    }

    const groupChatImgInput = document.querySelector('#groupChatImg');
    const groupChatImgPreview = document.querySelector('.groupChatImg');
    const cancelBtn = document.querySelector(".group-chat-img-cancel")
    const messageInput = document.querySelector(".group-chat-send-form-input")

    const button = document.querySelector('#emoji-button');

    const picker = new EmojiButton();

    button.addEventListener('click', () => {
    picker.togglePicker(button);

    });

    picker.on('emoji', emoji => {
        messageInput.value += emoji;
    });


    if(!groupChatImgPreview.hasAttribute("src")){
        groupChatImgPreview.remove()
        cancelBtn.remove()
    }


    groupChatImgInput.addEventListener('change', (e) =>{
        // console.log(kpayPreview)
    const reader = new FileReader();
    reader.onloadend = e => groupChatImgPreview.setAttribute('src', e.target.result);
    reader.readAsDataURL(groupChatImgInput.files[0]);
    // if(groupChatImgPreview.hasAttribute("src")){
        console.log(reader)
        messageInput.remove()
        document.querySelector(".group-chat-send-form-message-parent-container").append(groupChatImgPreview)
        document.querySelector(".group-chat-send-form-message-parent-container").append(cancelBtn)
    // }
    });//

    function clearGroupChatImg(){
        groupChatImgPreview.removeAttribute("src")
        groupChatImgPreview.remove()
        cancelBtn.remove()
        document.querySelector(".group-chat-send-form-message-parent-container").append(messageInput)
        groupChatImgInput.value = ""
    }



</script>

@endsection

