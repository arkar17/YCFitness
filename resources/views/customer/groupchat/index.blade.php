@extends('customer.training_center.layouts.app')

@section('content')
    <div class="customer-training-center-header-container">
        {{-- <h1>{{ $group->group->group_name }}</h1> --}}
        <p> Training Group </p>
    </div>

    <div class="group-chat-container customer-trainingcenter-group-chat-container">
        <div class="group-chat-header">
            <a href="" class="group-chat-header-name-container" id="view_group_member">
                <img src="{{ asset('img/customer/imgs/group_default.png') }}" />
                <div class="group-chat-header-name-text-container">
                    <p>{{ $group->group->group_name }}</p>
                    {{-- <span>group member, group member,group member,group member,group member,</span> --}}
                </div>
            </a>

            <a href="{{route('view_media')}}" class="group-chat-view-midea-link" id="view_media">
                <p>View Media</p>
                <iconify-icon icon="akar-icons:arrow-right" class="group-chat-view-midea-link-icon"></iconify-icon>
            </a>
        </div>

        <div class="group-chat-messages-container" id="chat">
            @foreach ($chats as $chat)
                {{-- model box for chat photo view --}}
                <div class="modal fade" id="exampleModalToggle{{ $chat->id }}" aria-hidden="true"
                    aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <img src="{{ asset('storage/trainer_message_media/' . $chat->media) }}" alt="test"
                                    class="w-100">
                            </div>
                        </div>
                    </div>
                </div>
                {{-- end model box for chat photo view --}}

                @if ($chat->media == null)
                    <div class="group-chat-sender-container">
                        <div class="group-chat-sender-text-container">
                            <span>Group Member</span>
                            <p>{{ $chat->text }}</p>
                        </div>
                        <img src="{{ asset('img/customer/imgs/group_default.png') }}" />
                    </div>
                @elseif (pathinfo($chat->media, PATHINFO_EXTENSION) == 'mp4' ||
                    pathinfo($chat->media, PATHINFO_EXTENSION) == 'mov' ||
                    pathinfo($chat->media, PATHINFO_EXTENSION) == 'webm')
                    <div class="group-chat-sender-container">
                        <div class="group-chat-sender-text-container">
                            <span>Group Member</span>
                            <video width="100%" height="100%" controls>
                                <source src="{{ asset('storage/trainer_message_media/' . $chat->media) }}" type="video/mp4">
                            </video>
                        </div>
                        <img src="{{ asset('img/customer/imgs/group_default.png') }}" />
                    </div>
                @elseif (pathinfo($chat->media, PATHINFO_EXTENSION) == 'png' ||
                    pathinfo($chat->media, PATHINFO_EXTENSION) == 'jpg' ||
                    pathinfo($chat->media, PATHINFO_EXTENSION) == 'jpeg')
                    <div class="group-chat-sender-container">
                        <div class="group-chat-sender-text-container">
                            <span>Group Member</span>
                            <a data-bs-toggle="modal" href="#exampleModalToggle{{ $chat->id }}" role="button">
                                <img src="{{ asset('/storage/trainer_message_media/' . $chat->media) }}" alt="">
                            </a>
                        </div>
                        <img src="{{ asset('img/customer/imgs/group_default.png') }}" />
                    </div>
                @endif
            @endforeach
        </div>

        {{-- member container --}}
        <div id="members">
            <div class="customer-group-chat-view-members-header">
                <a class="back-btn">
                    <iconify-icon icon="bi:arrow-left" class="back-btn-icon"></iconify-icon>
                </a>
            </div>
            <div class="customer-group-chat-members-container">
                @forelse ($group_members as $member)
                    <div class="customer-group-chat-member-row">
                        <div class="customer-group-chat-member-name">
                            @if ($member->user->profile == null)
                                <img src="{{asset('img/customer/imgs/user_default.jpg')}}" alt="">
                            @else
                                <img src="{{asset('storage/post/'.$member->user->profile->profile_image)}}">
                            @endif
                            <p>{{ $member->user->name }}</p>
                        </div>
                        <div class="customer-group-chat-member-btns-container">
                            <a href="{{route('socialmedia.profile',$member->user->id)}}" class="customer-secondary-btn">View Profile</a>
                        </div>
                    </div>
                @empty
                    <p class="text-secondary p-1">No Group Member</p>
                @endforelse
            </div>
        </div>
        {{-- member container end  --}}
    </div>
@endsection
@push('scripts')
    <script>

        $(document).ready(function() {
            $('.group-chat-messages-container').scrollTop($('.group-chat-messages-container')[0].scrollHeight);
            $('#members').hide();

            $(document).on('click', '#view_group_member', function(e) {
                e.preventDefault();
                $('#members').show();
                $('#chat').hide();

            });

            $(document).on('click', '.back-btn', function(e) {
                e.preventDefault();
                $('#members').hide();

                $('#chat').show();
            });
        });
        var id = @json($group->training_group_id);
        console.log(id);
        var groupchatcontainer = document.querySelector('.group-chat-messages-container');
        var pusher = new Pusher('576dc7f4f561e15a42ef', {
            cluster: 'eu'
        });
        var channel = pusher.subscribe('trainer-message.'+id);
        channel.bind('training_message_event', (data) => {
            console.log(data.message.text);
            if(data.message.media == null && data.message.text == null){

            }else{
                if (data.message.media == null || data.media == null) {
                    groupchatcontainer.innerHTML += `<div class="group-chat-sender-container">

                            <div class="group-chat-sender-text-container">
                                <span>Group Member</span>
                                <p>${data.message.text}</p>
                            </div>
                            <img src="{{ asset('img/customer/imgs/group_default.png') }}" />
                        </div>`;
                } else {
                    if (data.message.media.split('.').pop() === 'png' || data.message.media.split('.').pop() ===
                        'jpg' || data.message.media.split('.').pop() === 'jpeg') {
                        groupchatcontainer.innerHTML += `<div class="modal fade" id="exampleModalToggle3${data.message.id}" aria-hidden="true"
                        aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                    <img src="{{ asset('/storage/trainer_message_media/${data.message.media}') }}" alt="test" class="w-100">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="group-chat-sender-container">
                            <div class="group-chat-sender-text-container">
                                <span>Group Member</span>
                                <a data-bs-toggle="modal" href="#exampleModalToggle3${data.message.id}" role="button">
                                    <img src="{{ asset('/storage/trainer_message_media/${data.message.media}') }}">
                                </a>
                            </div>
                            <img src="{{ asset('img/customer/imgs/group_default.png') }}" />
                        </div>`;
                    } else if (data.message.media.split('.').pop() === 'mp4' || data.message.media.split('.').pop() ===
                        'mov' || data.message.media.split('.').pop() === 'webm') {
                        groupchatcontainer.innerHTML += `<div class="group-chat-sender-container">
                            <div class="group-chat-sender-text-container">
                                <span>Group Member</span>
                                <video width="100%" height="100%" controls>
                            <source src="{{ asset('/storage/trainer_message_media/${data.message.media}') }}" type="video/mp4">
                                </video>
                            </div>
                            <img src="{{ asset('img/customer/imgs/group_default.png') }}" />
                        </div>`;
                    }
                }
            }
        });
    </script>
@endpush
