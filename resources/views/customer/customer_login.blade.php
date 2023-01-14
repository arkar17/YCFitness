@extends('customer.layouts.app')

@section('content')
    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf
        <div class="cutomer-registeration-form">
            <p class="customer-registeration-form-header">
                {{__('msg.log in')}}
            </p>
            <div class="customer-login-phone-container">
                <iconify-icon icon="akar-icons:phone" class="customer-login-phone-icon"></iconify-icon>
                <input  type="number" required class="customer-registeration-input" placeholder="Phone" name="phone">
            </div>
            <div class="customer-login-pw-container">
                <iconify-icon icon="akar-icons:lock-on" class="customer-login-pw-icon"></iconify-icon>
                <input  type="password" required class="customer-registeration-input" placeholder="Password" name="password">
            </div>


            <a href="#" class="forgot-password-link">{{__('msg.forgot password?')}}</a>

            <div class="customer-form-btn-container">
                <button class="customer-primary-btn customer-login-submit-form-btn" type="submit" >
               {{__('msg.log in')}}
                </button>
                <button class="customer-secondary-btn customer-login-cancel-form-btn" type="reset">
                {{__('msg.cancel')}}
                </button>
            </div>

        </div>
    </form>
@endsection
