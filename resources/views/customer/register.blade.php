@extends('customer.layouts.app')

@section('content')
@include('sweetalert::alert')

<form method="POST" action={{ route('customer_register')}} id="registerform">
    @csrf
  @method('POST')
    <!--personal infos-->
    <div class="cutomer-registeration-form tab">
        <p class="customer-registeration-form-header">
            {{__('msg.personal informations')}}
        </p>
        <input  type="text" value="{{ old('name') }}" class="customer-registeration-input @error('name') is-invalid @enderror" placeholder="Name" name="name">
        @error('name')
        <div class="text-danger">{{ $message }}</div>
        @enderror

        <input  type="phone" value="{{ old('phone') }}" class="customer-registeration-input @error('phone') is-invalid @enderror" placeholder="Phone" name="phone">
        @error('phone')
        <div class="text-danger">{{ $message }}</div>
        @enderror

        <input  type="email" value="{{ old('email') }}" class="customer-registeration-input @error('email') is-invalid @enderror" placeholder="Email" name="email">
        @error('email')
        <div class="text-danger">{{ $message }}</div>
        @enderror

        <input  type="text" value="{{ old('address') }}" class="customer-registeration-input @error('address') is-invalid @enderror" placeholder="Address" name="address">
        @error('address')
        <div class="text-danger">{{ $message }}</div>
        @enderror

        <input  type="password"  class="customer-registeration-input @error('password') is-invalid @enderror" placeholder="Password" name="password">
        @error('password')
        <div class="text-danger">{{ $message }}</div>
        @enderror

        <input  type="password" class="customer-registeration-input @error('confirmPassword') is-invalid @enderror" placeholder="Confirm Password" name="confirmPassword">
        @error('confirmPassword')
        <div class="text-danger">{{ $message }}</div>
        @enderror

        <div class="customer-form-btn-container">
          <button class="customer-registeration-next-btn customer-primary-btn" type="submit">
            <p>{{__('msg.sign up')}}</p>
          </button>
        </div>

    </div>

</form>

@endsection
@push('scripts')
{{-- {!! JsValidator::formRequest('App\Http\Requests\CustomerRequest', '#registerform') !!} --}}
<script>
$(document).ready(function(){


})

</script>
@endpush

