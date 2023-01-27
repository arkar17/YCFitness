@extends('customer.layouts.app_home1')

@section('content')

                <div class="cutomer-registeration-form tab customer-registeration-card" id = "can_reset_password">

                    <form class="" method="POST" action = "{{route('acc_del')}}" id="loginForm">
                        @csrf
                        <p class="customer-registeration-form-header">
                            {{ __('Delete Account') }}
                        </p>
                        <div>
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

                                    <input class="customer-registeration-input otp-input" placeholder="Enter OTP code"  type="number"  id = "checkOTP" required >

                                </div>
                                <div >

                                     <i class="fa-solid fa-circle-check" id="check_icon"></i>

                                </div>
                            </div>
                        <div>
                            <div >
                                <button class="customer-primary-btn customer-pw-reset-btn" type="submit"  id="otp">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
@endsection

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
     $(document).ready(function() {
        $("#cannot_reset_password").css("display", "none");
        // $("#otp").prop('disabled', true);
        // $("#otp").attr("disabled","disabled").css("cursor", "default").fadeTo(500,0.3);
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
                                fetch(`https://verify.smspoh.com/api/v1/verify?access-token=vJMxoWJOITaHCjm-bMoUe8PNZcFh79Z1-R4VpzRPjOnMB6mTd06FE6U497SldLe-&request_id=${otpStatus.message}&code=${code}`)
                                .then(function(response) {
                                    // handle the response
                                    console.log(response.status)
                                    if(response.status === 200){
                                        $("#check_icon").css("color", "green");
                                        $("#password").prop('disabled', false);
                                        $("#cpassword").prop('disabled', false);
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





