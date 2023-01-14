@extends('customer.training_center.layouts.app')

@section('content')
    <div class="customer-training-center-header-container">
        <h1>{{ $group->group->group_name }}</h1>
        <p> Training Group </p>
    </div>

    <div class="group-chat-container customer-trainingcenter-group-chat-container">
        <div class="group-chat-header">
            <a href="" class="group-chat-header-name-container" id="view_group_member">
                <img src="{{ asset('image/default.jpg') }}" />
                <div class="group-chat-header-name-text-container">
                    <p>{{ $group->group->group_name }}</p>
                    {{-- <span>group member, group member,group member,group member,group member,</span> --}}
                </div>
            </a>

            {{-- <a href="" class="group-chat-view-midea-link" id="view_media">
                <p>View Media</p>
                <iconify-icon icon="akar-icons:arrow-right" class="group-chat-view-midea-link-icon"></iconify-icon>
            </a> --}}
        </div>
        {{-- member container end  --}}
        {{-- media container --}}
        <div id="media">
            <div class="group-chat-media-header">
                <a class="back-btn" href={{route('group')}}>
                    <iconify-icon icon="bi:arrow-left" class="back-btn-icon"></iconify-icon>
                </a>
            </div>

            <div class="group-chat-media-container customer-trainingcenter-media-container">
                @foreach ($photo_video as $me)
                    <div class="modal fade" id="exampleModalToggle1{{ $me->id }}" aria-hidden="true"
                        aria-labelledby="exampleModalToggleLabel1" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    @if (pathinfo($me->media, PATHINFO_EXTENSION) == 'mp4' ||
                                    pathinfo($me->media, PATHINFO_EXTENSION) == 'mov' ||
                                    pathinfo($me->media, PATHINFO_EXTENSION) == 'webm')
                                        <video class="w-100" controls>
            <source src="{{ asset('/storage/trainer_message_media/' . $me->media) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @else
                                <img src="{{ asset('/storage/trainer_message_media/' . $me->media) }}" alt="test" class="w-100">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (pathinfo($me->media, PATHINFO_EXTENSION) == 'mp4' ||
                            pathinfo($me->media, PATHINFO_EXTENSION) == 'mov' ||
                            pathinfo($me->media, PATHINFO_EXTENSION) == 'webm')
                        <div class="group-chat-media">
                            <a data-bs-toggle="modal" href="#exampleModalToggle1{{ $me->id }}" role="button">
                                <video style="z-index: -1;">
                <source src="{{ asset('storage/trainer_message_media/'.$me->media) }}" type="video/mp4">
                                </video>
                            </a>
                        </div>

                    @else
                        <div class="group-chat-media">
                            <a data-bs-toggle="modal" href="#exampleModalToggle1{{ $me->id }}" role="button">
                                <img src="{{ asset('/storage/trainer_message_media/' . $me->media) }}" alt="test">
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- media container end --}}
    </div>
@endsection
@push('scripts')

@endpush
