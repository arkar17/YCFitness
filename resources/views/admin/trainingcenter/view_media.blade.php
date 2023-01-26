@extends('layouts.app')
@section('training-center-active', 'active')
@section('content')
    <div class="customer-main-content-container">
        <div class="trainer-main-content-container">

            <div class="trainer-two-columns-container">
                <div class="trainer-group-chats-parent-container">
                    <p>Groups</p>
                    <div class="trainer-group-chats-container">
                        @forelse ($groups as $group)
                        <a href ="{{route('chat_message',$group['id'])}}" class="tainer-group-chat-name-container" id="group-chat" value="{{ $group->id }}"
                            data-id="{{ $group->id }}"
                            style=" background-color: transparent;background-repeat: no-repeat;border: none;cursor: pointer;overflow: hidden;outline: none; text-decoration: none;">
                            <img src="{{ asset('img/avatar.png') }}" />
                            <p>{{ $group->group_name }}</p>
                        </a>
                    @empty
                        <p class="text-secondary p-1">No Group</p>
                    @endforelse
                    </div>
                </div>

                <div class="group-chat-container">
                    <div class="group-chat-header">
                    <a href="{{route('chat_message',$group_data['id'])}}" id="group" class="group-chat-header-name-container" style="text-decoration: none">
                        <img src="{{ asset('img/avatar.png') }}" />
                        <div class="group-chat-header-name-text-container">
                            <p>{{$group_data->group_name}}</p>
                        </div>
                    </a>
                </div>
                    <div class="trainer-group-chat-media-header">
                        <a class="back-btn" href="javascript: history.back()">
                            <iconify-icon icon="bi:arrow-left" class="back-btn-icon"></iconify-icon>
                        </a>
                    </div>

                    <div class="trainer-group-chat-media-container">
                        @foreach ($medias as $media)
                            @if (pathinfo($media->media, PATHINFO_EXTENSION) == 'mp4')
                                <div class="modal fade" id="exampleModalToggle1{{ $media->id }}" aria-hidden="true"
                                    aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <video class="w-100" controls>
                                                    <source
                                                        src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/trainer_message_media/{{$media->media}}"
                                                        type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="trainer-group-chat-media">
                                    <a data-bs-toggle="modal" href="#exampleModalToggle1{{ $media->id }}" role="button">
                                        <video style="z-index: -1;">
                                            <source 
                                            src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/trainer_message_media/{{$media->media}}"
                                                type="video/mp4">
                                        </video>
                                    </a>
                                </div>
                            @else
                                <div class="modal fade" id="exampleModalToggle2{{ $media->id }}" aria-hidden="true"
                                    aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <img 
                                                src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/trainer_message_media/{{$media->media}}"
                                                    alt="test" class="w-100">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="trainer-group-chat-media">
                                    <a data-bs-toggle="modal" href="#exampleModalToggle2{{ $media->id }}" role="button">
                                        <img 
                                        src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/trainer_message_media/{{$media->media}}"
                                            alt="test">
                                    </a>
                                </div>
                            @endif
                        @endforeach

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
