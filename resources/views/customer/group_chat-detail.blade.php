@extends('customer.layouts.app_home')
@section('content')

    <div class="social-media-right-container">

        <div class="modal fade" id="createGroupModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{__('msg.add members')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('socialmedia.group.addmember', $id) }}" class="create-group-form"
                            method="POST">
                            @csrf
                            <div class="create-group-addfris">
                                <p>{{__('msg.add your friends')}}</p>
                                <select class="js-example-basic-multiple" name="members[]" multiple="multiple">

                                    {{-- @if ($members == null || count($members) == 0)
                                        @foreach ($friends as $friend)
                                            <option value="{{ $friend->id }}">{{ $friend->name }}</option>
                                        @endforeach
                                    @else
                                        @foreach ($friends as $friend)
                                            @foreach ($members as $item)
                                                @if ($item->member_id == $friend->id)
                                                @elseif(count($members) == count($friends))
                                                @else
                                                    <option value="{{ $friend->id }}">{{ $friend->name }}</option>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif --}}

                                    @foreach($friend as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                                    @endforeach

                                </select>
                            </div>

                            <button type="submit" class="customer-primary-btn create-group-submit-btn">{{__('msg.create')}}</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>


        <div class="group-chat-header">
            <div class="group-chat-header-name-container">
                <a href="{{ route('socialmedia.group', $group->id) }}" class="group-chat-header-name-container">
                    <img src="{{ asset('img/customer/imgs/group_default.png') }}" />
                    <div class="group-chat-header-name-text-container">
                        <p>{{ $group->group_name }}</p>
                    </div>
                </a>
            </div>
            <div class="chat-header-call-icons-container">
                <iconify-icon icon="ant-design:phone-outlined" class="chat-header-phone-icon"></iconify-icon>
                <iconify-icon icon="eva:video-outline" class="chat-header-video-icon"></iconify-icon>

                <a href="{{ route('socialmedia.group.viewmedia', $group->id) }}" class="group-chat-view-midea-link">
                    <p>{{__('msg.view media')}}</p>
                    <iconify-icon icon="akar-icons:arrow-right" class="group-chat-view-midea-link-icon"></iconify-icon>
                </a>
            </div>
        </div>


        @if (auth()->user()->id == $gp_admin->group_owner_id)
        <div class="social-media-view-members-header-btns-container">
            <button type="button" class="social-media-allchats-header-add-btn customer-primary-btn group-chat-add-btn"
                data-bs-toggle="modal" data-bs-target="#createGroupModal">
                <iconify-icon icon="akar-icons:circle-plus" class="social-media-allchats-header-plus-icon"></iconify-icon>
                <p>{{__('msg.add members')}}</p>
            </button>

            <a href="{{ route('socialmedia.group.delete', $group->id) }}" class="text-decoration-none">
                <button type="button" class="social-media-allchats-header-add-btn customer-primary-btn group-chat-add-btn">
                    <p>{{__('msg.delete group')}}</p>
                </button>
            </a>
        </div>
        @else
            <a href="{{ route('socialmedia.group.leave', [$group->id, auth()->user()->id]) }}"
                class="text-decoration-none">
                <button class="social-media-allchats-header-add-btn customer-primary-btn group-chat-add-btn">
                    <p>{{__('msg.leave group')}}</p>
                </button>
            </a>
        @endif


        <div class="social-media-view-members-container">
            <div class="social-media-view-memers-row">
                <div class="social-media-view-memers-row-name">

                    @if ($gp_admin->user->user_profile != null && $gp_admin->user->user_profile->profile_image != null)
                        {{-- <img src="{{ asset('storage/post/' . $gp_admin->user->user_profile->profile_image) }}"> --}}
                        <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $gp_admin->user->user_profile->profile_image }}">
                    @else
                        <img class="nav-profile-img" src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                    @endif
                    <p>{{ $gp_admin->user->name }}</p>

                </div>
                <div class="social-media-view-members-row-btns">
                    <p style="color: #3CDD57;">Admin</p>
                </div>
            </div>

            @forelse ($members as $member)
                <form action="{{ route('socialmedia.group.memberkick') }}" method="post">
                    @csrf
                    <input type="text" value="{{ $member->group_id }}" name="groupId" hidden>
                    <input type="text" value="{{ $member->member_id }}" name="memberId" hidden>
                    <div class="social-media-view-memers-row">
                        <div class="social-media-view-memers-row-name">
                            @if ($member->user->user_profile != null && $member->user->user_profile->profile_image != null)
                        <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $member->user->user_profile->profile_image }}">
                            @else
                                <img class="nav-profile-img" src="{{ asset('img/customer/imgs/user_default.jpg') }}" />
                            @endif
                            <a href="{{ route('socialmedia.profile', $member->member_id) }}">
                                <p>{{ $member->user->name }}</p>
                            </a>
                        </div>
                        <div class="social-media-view-members-row-btns">
                            @if (auth()->user()->id == $gp_admin->group_owner_id)
                                <button type="submit" class="customer-red-btn">{{__('msg.kick')}}</button>
                            @endif
                        </div>
                    </div>
                </form>
            @empty
                <div class="social-media-view-memers-row">
                    <p>Empty group member. Please add member.</p>
                </div>
            @endforelse


        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                dropdownParent: "#createGroupModal"
            });

            $('.select2-container').attr('style', '');
        })
    </script>
@endpush
