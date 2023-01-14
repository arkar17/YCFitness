@extends('customer.layouts.app_home')
@section('content')
    <div class="social-media-right-container">
        <div class="group-chat-messages-container">
            <a href="{{ route('socialmedia') }}" class="text-decoration-none">
                <button class="social-media-allchats-header-add-btn customer-primary-btn group-chat-add-btn">
                    <iconify-icon icon="bi:arrow-left" class="social-media-allchats-header-plus-icon"></iconify-icon>
                    <p>New Feed</p>
                </button>
            </a>
            <div class="d-flex justify-content-center">
                <p>Your group is deleted by admin</p>
            </div>

        </div>
    </div>
@endsection
