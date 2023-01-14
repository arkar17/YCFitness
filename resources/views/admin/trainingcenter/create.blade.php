@extends('layouts.app')
@section('training-center-active', 'active')

@section('content')

    <!--create gp modal-->
    <button data-bs-toggle="modal" data-bs-target="#CreateGroupModal" class="trainer-create-gp-modal-btn customer-primary-btn" style="background-color: #3CDD57; color: white;">
        <iconify-icon icon="akar-icons:circle-plus" class="trainer-create-gp--modal-btn-icon"></iconify-icon>
        <p>Group</p>
    </button>

    
    <div class="modal fade" id="CreateGroupModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header  customer-transaction-modal-header">

                    <h5 class="modal-title text-center" id="exampleModalLabel">Create Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="clearCreateGroupInputs()"></button>
                </div>
                <div class="modal-body">
                    <form class="create-group-form" action="{{ route('traininggroup.store') }}" method="POST">
                        @method('POST')
                        @csrf
                        {{-- <input type="hidden" name="trainer_id" value="{{auth()->user()->id}}"> --}}
                        <div class="mb-2">
                            <label>Group Name</label>
                            <input class="form-control" type="text" name="group_name" required>
                        </div>
                        <div class="mb-2">
                            <label>Member Type</label>
                            <select class="form-control" name="member_type"
                                class="@error('member_type') is-invalid @enderror" required>
                                <option value="">Choose Member Type</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->member_type }}">{{ $member->member_type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label>Level</label>
                            <select class="form-control" name="member_type_level"
                                class="@error('member_type_level') is-invalid @enderror" required>
                                <option value="">Choose Level</option>
                                <option value="beginner">Beginner</option>
                                <option value="advanced">Advanced</option>
                                <option value="professional">Professional</option>
                            </select>
                            @error('member_type_level')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label>Gender</label>
                            <select class="form-control" name="gender" class="@error('gender') is-invalid @enderror"
                                required>
                                <option value="">Choose Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Group Type</label>
                            <select class="form-control" name="group_type" class="@error('gender') is-invalid @enderror"
                                id="group_type" required>
                                <option value="">Choose Group Type</option>
                                <option value="weight gain">Weight Gain</option>
                                <option value="body beauty">Body Beauty</option>
                                <option value="weight loss">Weight Loss</option>
                            </select>
                        </div>

                        <div class="">
                            <button type="submit" class="btn me-2" style="background-color: #3CDD57; color: white;">Confirm</button>
                            <button type="reset" class="btn customer-secondary-btn" data-bs-dismiss="modal" aria-label="Close"
                                onclick="clearCreateGroupInputs()">Cancel</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
    {{-- end modal box --}}

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
                            <a href="{{route('chat_message',$group['id'])}}" style="text-decoration: none;">
                                {{-- data-id="{{ $group['id'] }}" class="enterGroup" --}}
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

                <div class="group-chat-container">
                </div>
            </div>
        </div>
    </div>

@endsection
