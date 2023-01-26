
@extends('layouts.app')
@section('request-active', 'active')


@section('content')

<div class="container">
    <a href="javascript:history.back()" class="btn btn-primary mb-2"><i class="fa-solid fa-arrow-left-long"></i> &nbsp; Back</a>
    <div class="card mb-3 shadow" >
        <div class="card-header">
           <h3>Request Member Payment Detail</h3>
          </div>



          @if ($payment != null)


                <div class="row g-0">
                    <div class="col-md-4 shadow mt-3 ms-3" style="width:300px; height:600px">
                        <img 
                        src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/payments/{{ $payment->photo}}"
                    
                        class="img-fluit rounded" alt="..." style="width: 100%;
                        height:100%">
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                    <div class="card-body">
                        <div>
                        <label class="fs-5">Name :</label>
                        <label class="ms-2 text-capitalize">{{$payment->user->name ?? "User not have."}}</label>
                        </div>
                        <div>
                        <label class="fs-5">Payment Type :</label>
                        <label class="ms-2 text-capitalize">{{$payment->payment_type ?? "Type not have."}}</label>
                        </div>
                        <div id="payment-name">
                        <label class="fs-5">Payment name :</label>
                        <label class="ms-2 text-capitalize">{{$payment->payment_name ?? "Bank"}}</label>
                        </div>
                        @if ($payment->payment_type == "banking")
                            <div id="bank-number">
                                <label class="fs-5">Bank account number :</label>
                                <label class="ms-2 text-capitalize">{{$payment->bank_account_number ?? "E-wallet"}}</label>
                            </div>
                            <div id="bank-holder">
                            <label class="fs-5">Bank account holder :</label>
                            <label class="ms-2 text-capitalize">{{$payment->bank_account_holder ?? "E-wallet"}}</label>
                            </div>
                        @else
                            <div id="account-name">
                            <label class="fs-5">Account name :</label>
                            <label class="ms-2 text-capitalize">{{$payment->account_name ?? "Bank"}}</label>
                            </div>
                            <div id="phone">
                            <label class="fs-5">Phone No. :</label>
                            <label class="ms-2 text-capitalize">{{$payment->phone ?? "Bank"}}</label>
                            </div>
                        @endif



                    <div>
                        <label class="fs-5">Amount :</label>
                        <label class="ms-2 text-capitalize">{{$payment->amount ?? "Amount not have."}}</label>
                    </div>
                    </div>
                    </div>
                </div>


          @else
                <div class="row g-0">
                    Payment is empty!
                </div>
          @endif

      </div>
</div>
@endsection


{{-- @push('scripts')
<script>
    $(document).ready(function(){
        var paymentType = @json($payment);
    if (paymentType.payment_type == 'bank') {
        $('#account-name').css('display','none');
        $('#phone').css('display','none');
    }else if(paymentType.payment_type == 'ewallet'){
        $('#bank-number').css('display','none');
        $('#bank-holder').css('display','none');
    }
    })

</script>

@endpush --}}
