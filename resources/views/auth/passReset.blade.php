@extends('customer.layouts.app')

@section('content')
{{-- <div class=""> --}}
    {{-- <div class="row justify-content-center"> --}}

        {{-- <div class="col-md-8"> --}}
            {{-- <div class="card"> --}}
                {{-- <div class="card-header">{{ __('Password Reset') }}</div> --}}

                <div class="card-body" id = "can_reset_password">

                    <form class="cutomer-registeration-form " method="POST" action = "{{route('password_reset')}}" id="loginForm">
                        @csrf
                        <p class="customer-registeration-form-header">
                            {{ __('Password Reset') }}
                        </p>
                        <div >

                            {{-- <label for="phone" >{{ __('Phone') }}</label> --}}
                            <div>
                                <input id="[jpme]" type="phone" class="customer-registeration-input phone" placeholder="Phone" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>
                            </div>
                        </div>
                        <div >
                            @if (Session::has('phone'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong style = "color:red;">{{ Session::get('phone') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style = "color:red;"></button>
                              </div>

                            @endif
                        </div>

                        {{-- <div class="row mb-3 "> --}}

                            <div class="password-reset-otp" >
                                <div >
                                    <button class="customer-secondary-btn"  id="checkPhone" type="button">
                                        {{ __('Get OTP') }}
                                    </button>
                                </div>
                                <div >

                                    <input class="customer-registeration-input otp-input" placeholder="OTP code"  type="number"  id = "checkOTP" required >

                                </div>
                                <div >

                                     <i class="fa-solid fa-circle-check" id="check_icon"></i>

                                </div>
                            </div>

                            <div>
                                <div >
                                    {{-- <label for="password" >{{ __('Enter Password') }}</label> --}}
                                    <input class="customer-registeration-input" placeholder="New Password"  type="password" name="password"  id="password" required >
                                </div>
                                <div >
                                    {{-- <label for="confirmPassword" class="col-md-6 col-form-label text-md-end">{{ __('Enter Confirm Password') }}</label> --}}
                                    <input type="password" class="customer-registeration-input" placeholder="Confirm New Password" name="cpassword"  id="cpassword" required >
                                </div>
                            </div>

                        <div>
                            <div >
                                <button class="customer-primary-btn customer-pw-reset-btn" type="submit"  id="otp">
                                    {{ __('Reset') }}
                                </button>
                            </div>
                        </div>

                        {{-- <button class="btn btn-warning " id="checkDisable" onclick='dispAlert()'>
                            {{ __('Disabled Enabled') }}
                        </button> --}}


                    </form>
                </div>
                {{-- <div class="card-body d-flex justify-content-center" > --}}
                    {{-- <div class="col-md-5" id = "cannot_reset_password">
                        <h6 class="text-danger">You cannot reset your password now! <br><br>Please Try again later!</h6>
                    </div> --}}
                    <div class="cannot-reset-password-container" id = "cannot_reset_password">
                        <p>You cannot reset your password now!</p>
                        <span>Please try again later</span><br>
                        <a href="#" class="customer-primary-btn">Go Back</a>
                    </div>
                {{-- </div> --}}

            {{-- </div> --}}
        {{-- </div> --}}
    {{-- </div> --}}
{{-- </div> --}}
@endsection

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
     $(document).ready(function() {
        $("#cannot_reset_password").css("display", "none");
        $("#password").prop('disabled', true);
        $("#cpassword").prop('disabled', true);
        var otpStatus
        var phoneNumber
       $("#checkPhone").click(function(){
        // ("#checkPhone").prop('disabled', true);
          var phone = $(".phone").val();
          $.ajax({
                url : 'checkPhoneGetOTP',
                method: 'get',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:  {"phone":phone},
                success   : function(data) {
                    otpStatus = data
                    if(data.status == 300){
                        // alert(data.message);
                        Swal.fire({
                        text: data.message,
                        confirmButtonColor: '#3CDD57',
                        timer: 3000
                      });
                    }
                    if(data.status == 200){
                        phoneNumber = data.message
                        var ClickedDate = Date($.now());
                        localStorage.setItem('DateClicked', ClickedDate);
                        localStorage.setItem('Phone', phoneNumber);
                        $(this).prop('disabled', true);
                    }
                },
        });
    });

    reset_date = new Date(localStorage.getItem('DateClicked'));
    LocalStoragephone = new Date(localStorage.getItem('Phone'));
    dateNow = new Date(Date());
    dateDifferent = dateNow.getTime() - reset_date.getTime();
    enable_reset = dateDifferent / (1000 * 3600 *24);
    console.log(localStorage.getItem('DateClicked'));
    console.log(enable_reset);

     if(enable_reset >= 1 && LocalStoragephone == phoneNumber ){
        localStorage.removeItem('ClickedDate');
        localStorage.removeItem('Phone');
        $("#checkPhone").prop('disabled', false);
        $("#cannot_reset_password").css("display", "block");
        $("#can_reset_password").css("display", "none");
    }
    else{
        $("#can_reset_password").css("display", "block");
        $("#cannot_reset_password").css("display", "none");
    }

        $("#checkOTP").keyup(function(){
            if(otpStatus.status === 200){
                var code = $('#checkOTP').val();
                                fetch(`https://verify.smspoh.com/api/v1/verify?access-token=gND4P7JNgbd5supML5ZT5ayuRmG0gS1cr6C09apXkkpc5m2QzBEOH3Euc5jX27t0&request_id=${otpStatus.message}&code=${code}`)
                                .then(function(response) {
                                    // handle the response
                                    console.log(response.status)
                                    if(response.status === 200){
                                        $("#check_icon").css("color", "green");
                                        $("#password").prop('disabled', false);
                                        $("#cpassword").prop('disabled', false);
                                        $("#checkOTP").prop('disabled', true);
                                        $("#check_icon").css("color", "green");
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





