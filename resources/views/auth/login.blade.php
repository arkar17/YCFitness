@extends('customer.layouts.app')

@section('content')
    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf
        <div class="cutomer-registeration-form">
            <p class="customer-registeration-form-header">
                Log In
            </p>
            <div class="customer-login-phone-container">
                <iconify-icon icon="akar-icons:phone" class="customer-login-phone-icon"></iconify-icon>
                <input  type="number" required class="customer-registeration-input @error('phone') is-invalid @enderror" placeholder="Phone" name="phone">
                @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="customer-login-pw-container">
                <iconify-icon icon="akar-icons:lock-on" class="customer-login-pw-icon"></iconify-icon>
                <input  type="password" required class="customer-registeration-input @error('password') is-invalid @enderror" placeholder="Password" name="password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>


            <a href="{{route('password_reset_view')}}" class="forgot-password-link">Forgot Password?</a>

            <div class="customer-form-btn-container">
                <button class="customer-primary-btn customer-login-submit-form-btn" type="submit" >
                Log In
                </button>
                <button class="customer-secondary-btn customer-login-cancel-form-btn" type="reset">
                Cancel
                </button>
            </div>

        </div>
    </form>
@endsection
