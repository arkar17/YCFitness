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

        <input  type="phone" value="{{ old('phone') }}" id="phone" class="customer-registeration-input @error('phone') is-invalid @enderror" placeholder="Phone" name="phone">
        @error('phone')
        <div class="text-danger">{{ $message }}</div>
        @enderror

        <input  type="email" id = "email" value="{{ old('email') }}" class="customer-registeration-input @error('email') is-invalid @enderror" placeholder="Email" name="email">
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
        <div class="password-reset-otp" >
          <div >
              <button class="customer-primary-btn"  id="checkPhone" type="button">
                  {{ __('Get OTP') }}
              </button>
          </div>
          <div >

              <input class="customer-registeration-input otp-input" placeholder="Enter OTP code"  type="number"  id = "checkOTP" required >

          </div>
          <div >

               <i class="fa-solid fa-circle-check" id="check_icon"></i>

          </div>
      </div>
       

        <div class="customer-form-btn-container">
          <button class="customer-registeration-next-btn customer-primary-btn" type="submit">
            <p>{{__('msg.sign up')}}</p>
          </button>
        </div>

    </div>

</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
  $(document).ready(function(){
          var otpStatus
          var phoneNumber
          $("#checkPhone").click(function(){
            var phone = $("#phone").val();
            var email = $("#email").val();
            var url = "{{ route('getOPT') }}";
            $.ajax({
                  url : url,
                  method: 'get',
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  data:  {"phone":phone,"email":email},
                  success   : function(data) {
                    otpStatus = data
                    if(data.status == 100){
                        Swal.fire({
                        text: data.message,
                        confirmButtonColor: '#3CDD57',
                        timer: 3000
                      });
                    }
                    else if (data.status == 101){
                        Swal.fire({
                        text: data.message,
                        confirmButtonColor: '#3CDD57',
                        timer: 3000
                      });
                    }
                    else if (data.status == 102){
                        Swal.fire({
                        text: data.message,
                        confirmButtonColor: '#3CDD57',
                        timer: 3000
                      });
                    }
                    else if (data.status == 300){
                        Swal.fire({
                        text: data.message,
                        confirmButtonColor: '#3CDD57',
                        timer: 3000
                      });
                    }
                    else if(data.status == 200){
                        phoneNumber = data.message
                        var ClickedDate = Date($.now());
                        localStorage.setItem('DateClicked', ClickedDate);
                        localStorage.setItem('Phone', phoneNumber);
                        Swal.fire({
                                        text: "Check your phone message inbox.",
                                        timerProgressBar: true,
                                        timer: 3000,
                                        icon: 'success',
                                })
                    }
                  },
          });
      });
  
      $("#checkOTP").keyup(function(){
              if(otpStatus.status === 200){
                  var code = $('#checkOTP').val();
                                  fetch(`https://verify.smspoh.com/api/v1/verify?access-token=vJMxoWJOITaHCjm-bMoUe8PNZcFh79Z1-R4VpzRPjOnMB6mTd06FE6U497SldLe-&request_id=${otpStatus.message}&code=${code}`)
                                  .then(function(response) {
                                      // handle the response
                                      console.log(response.status)
                                      if(response.status === 200){
                                          $("#check_icon").css("color", "green");
                                          $("#checkOTP").prop('disabled', true);
                                          $("#check_icon").css("color", "green");
                                          $("#otp").prop('disabled', false);
                                      }
                                      else{
                                          $("#check_icon").css("color", "red");
                                      }
                                  })
                                  .catch(function() {
                                      // handle the error
                                      alert('Something went wrong!')
                                  });
              }
          });
  
  })
  
  </script>
@endsection
@push('scripts')
{{-- {!! JsValidator::formRequest('App\Http\Requests\CustomerRequest', '#registerform') !!} --}}

@endpush

