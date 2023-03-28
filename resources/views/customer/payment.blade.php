@extends('customer.layouts.app_home1')

@section('content')
@include('sweetalert::alert')


@if(Session::has('error'))
    <script>
        swal({
            title: "Validation Error",
            text: "{{ Session::get('error') }}",
            icon: "error",
        });
    </script>
@endif
<style>
    .member-duration{
        margin-right: 100px;
        font-weight: bold;
    }

    .member-cost{
        margin-right: 120px;
        font-weight: bold;
    }
</style>
<!--kpay modal-->
<div class="modal fade" id="kpayModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header  customer-transaction-modal-header">
          <h5 class="modal-title text-center" id="exampleModalLabel">Send Payment Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearTransactionInputs()"></button>
        </div>
        <div class="modal-body">

        <form action="{{ route('ewallet_store') }}" method="POST" enctype="multipart/form-data" class="customer-transaction-form">
            @csrf
            <input type="hidden" name="member_type" value={{$member->member_type}}>
            <div class="customer-transaction-form-img">
                <img src="{{asset('image/kpay.png')}}"/>
            </div>
            @if(auth()->user()->shopmember_type_id==0 || auth()->user()->shopmember_type_id==null)
            <input type = "hidden" name = "cost"  value = {{$member->price}}>
            <div class="customer-transaction-input-container">
                <p>Choosen Plan:</p>
                <p class="member-duration">{{$member->member_type}}( {{$member->duration}} month)</p>
            </div>
            <div class="customer-transaction-input-container">
                <p>Cost:</p>
                <p class="member-cost">{{$member->price}}MMK</p>
            </div>
            @else
            <input type = "hidden" name = "cost" value = {{$member->price}}>
            <div class="customer-transaction-input-container">
                <p>Choosen Level:</p>
                <p class="member-duration">{{$member->member_type}}( {{$member->duration}} month)</p>
            </div>
            <div class="customer-transaction-input-container">
                <p>Cost:</p>
                <p class="member-cost">{{$member->price}}MMK</p>
            </div>
            @endif
            <div class="customer-transaction-input-container">
                <p>KBZ pay Phone Number:</p>
                <input type="text" name = "payment_name" hidden value="KBZ Pay">
                <input type="number" name="phone" required>
            </div>
            <div class="customer-transaction-input-container">
                <p>Kpay pay Name:</p>
                <input type="text" name="account_name" required>
            </div>
            <div class="customer-transaction-input-container">
                <p>Amount:
                </p>
                <input type="text" required name = "amount">
            </div>

            <div class="customer-transaction-receipt-img">

                <span class="selectImage">
                Transaction screen shot
                <div class="customer-screenshot-upload-btn">
                    <iconify-icon icon="akar-icons:circle-plus" class="customer-screenshot-upload-btn-icon"></iconify-icon>
                    <p>Photo</p>
                    <input type="file" id="kpayImg" name="image" required>
                </div>

                <button class="customer-transaction-clear-btn" type="button" onclick="clearTransactionImg()">Clear</button>

                </span>
                <img class="preview kpayImg">
            </div>

            <div class="customer-transaction-admin-details">
                @foreach($banking_info as $wave)
                @if($wave->payment_name	== "Wave Pay")
                <div class="customer-transaction-admin-phone">
                    <p>Wave pay Phone Number:</p>
                    <p>{{$wave->phone}}</p>
                </div>
                <div class="customer-transaction-admin-phone">
                    <p>Account Name:</p>
                    <p>{{$wave->account_name}}</p>
                </div>
                <hr>
                @endif
                @endforeach
            </div>
            <div class="customer-transaction-form-btn-container">
                <button type="submit" class="customer-transaction-form-submit">Confirm</button>
                <button type="reset"  class="customer-transaction-form-cencel" onclick="clearTransactionImg()">Reset</button>
            </div>
        </form>
        </div>

      </div>
    </div>
</div>

<!--cbpay modal-->
<div class="modal fade" id="cbpayModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header customer-transaction-modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Send Payment Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearTransactionInputs()"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('ewallet_store') }}" method="POST" enctype="multipart/form-data" class="customer-transaction-form">
                @csrf
                <input type="hidden" name="member_type" value={{$member->member_type}}>
                <div class="customer-transaction-form-img">
                    <img src="{{asset('image/cbpay.jfif')}}"/>
                </div>
                @if(auth()->user()->shopmember_type_id==0 || auth()->user()->shopmember_type_id==null)
                <input type = "hidden" name = "cost" value = {{$member->price}}>
                <div class="customer-transaction-input-container">
                    <p>Choosen Plan:</p>
                    <p class="member-duration">{{$member->member_type}}( {{$member->duration}} month)</p>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Cost:</p>
                    <p class="member-cost">{{$member->price}}MMK</p>
                </div>
                @else
                <input type = "hidden" name = "cost" value = {{$member->price}}>
                <div class="customer-transaction-input-container">
                    <p>Choosen Level:</p>
                    <p class="member-duration">{{$member->member_type}}( {{$member->duration}} month)</p>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Cost:</p>
                    <p class="member-cost">{{$member->price}}MMK</p>
                </div>
                @endif
                <div class="customer-transaction-input-container">
                    <p>CB pay Phone Number:</p>
                    <input type="text" name = "payment_name" hidden value="CB Pay">
                    <input type="number" name="phone" required>
                </div>
                <div class="customer-transaction-input-container">
                    <p>CB pay Name:</p>
                    <input type="text" name="account_name" required>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Amount:
                    </p>
                    <input type="text" required name = "amount">
                </div>

                <div class="customer-transaction-receipt-img">

                    <span class="selectImage">
                    Transaction screen shot
                    <div class="customer-screenshot-upload-btn">
                        <iconify-icon icon="akar-icons:circle-plus" class="customer-screenshot-upload-btn-icon"></iconify-icon>
                        <p>Photo</p>
                        <input type="file" id="cbpayImg" name="image" required>
                    </div>

                    <button class="customer-transaction-clear-btn" type="button" onclick="clearTransactionImg()">Clear</button>

                    </span>
                    <img class="preview cbpayImg">
                </div>

                <div class="customer-transaction-admin-details">
                    @foreach($banking_info as $wave)
                    @if($wave->payment_name	== "Wave Pay")
                    <div class="customer-transaction-admin-phone">
                        <p>Wave pay Phone Number:</p>
                        <p>{{$wave->phone}}</p>
                    </div>
                    <div class="customer-transaction-admin-phone">
                        <p>Account Name:</p>
                        <p>{{$wave->account_name}}</p>
                    </div>
                    <hr>
                    @endif
                    @endforeach
                </div>


                <div class="customer-transaction-form-btn-container">
                    <button type="submit" class="customer-transaction-form-submit">Confirm</button>
                    <button type="reset"  class="customer-transaction-form-cencel" onclick="clearTransactionImg()">Reset</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>

<!--wavepay modal-->
<div class="modal fade" id="wavepayModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header customer-transaction-modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Send Payment Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearTransactionInputs()"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('ewallet_store') }}" method="POST" enctype="multipart/form-data" class="customer-transaction-form">
                @csrf
                <input type="hidden" name="member_type" value={{$member->member_type}}>
                <div class="customer-transaction-form-img">
                    <img src="{{asset('image/wavepay.jfif')}}"/>
                </div>
                @if(auth()->user()->shopmember_type_id==0 || auth()->user()->shopmember_type_id==null)
                <div class="customer-transaction-input-container">
                    <p>Choosen Plan:</p>
                    <p class="member-duration">{{$member->member_type}}( {{$member->duration}} month)</p>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Cost:</p>
                    <p class="member-cost">{{$member->price}}MMK</p>
                </div>
                @else
                <div class="customer-transaction-input-container">
                    <p>Choosen Level:</p>
                    <p class="member-duration">{{$member->member_type}}( {{$member->duration}} month)</p>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Cost:</p>
                    <p class="member-cost">{{$member->price}}MMK</p>
                </div>
                @endif
                <div class="customer-transaction-input-container">
                    <p>Wave pay Phone Number:</p>
                    <input type="text" name = "payment_name" hidden value="Wave Pay">
                    <input type="number" name="phone" required>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Wave pay Name:</p>
                    <input type="text" name="account_name" required>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Amount:
                    </p>
                    <input type="text" required name = "amount">
                </div>

                <div class="customer-transaction-receipt-img">

                    <span class="selectImage">
                    Transaction screen shot
                    <div class="customer-screenshot-upload-btn">
                        <iconify-icon icon="akar-icons:circle-plus" class="customer-screenshot-upload-btn-icon"></iconify-icon>
                        <p>Photo</p>
                        <input type="file" id="wavepayImg" name="image" required>
                    </div>

                    <button class="customer-transaction-clear-btn" type="button" onclick="clearTransactionImg()">Clear</button>

                    </span>
                    <img class="preview wavepayImg">
                </div>

                <div class="customer-transaction-admin-details">
                    @foreach($banking_info as $wave)
                    @if($wave->payment_name	== "Wave Pay")
                    <div class="customer-transaction-admin-phone">
                        <p>Wave pay Phone Number:</p>
                        <p>{{$wave->phone}}</p>
                    </div>
                    <div class="customer-transaction-admin-phone">
                        <p>Account Name:</p>
                        <p>{{$wave->account_name}}</p>
                    </div>
                    <hr>
                    @endif
                    @endforeach
                </div>


                <div class="customer-transaction-form-btn-container">
                    <button type="submit" class="customer-transaction-form-submit">Confirm</button>
                    <button type="reset"  class="customer-transaction-form-cencel" onclick="clearTransactionImg()">Reset</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>

<!--ayapay modal-->
<div class="modal fade" id="ayapayModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header customer-transaction-modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Send Payment Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearTransactionInputs()"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('ewallet_store') }}" method="POST" enctype="multipart/form-data" class="customer-transaction-form">
                @csrf
                <input type="hidden" name="member_type" value={{$member->member_type}}>
                <div class="customer-transaction-form-img">
                    <img src="{{asset('image/ayapay.jfif')}}"/>
                </div>
                @if(auth()->user()->shopmember_type_id==0 || auth()->user()->shopmember_type_id==null)
                <div class="customer-transaction-input-container">
                    <p>Choosen Plan:</p>
                    <p class="member-duration">{{$member->member_type}}( {{$member->duration}} month)</p>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Cost:</p>
                    <p class="member-cost">{{$member->price}}MMK</p>
                </div>
                @else
                <div class="customer-transaction-input-container">
                    <p>Choosen Level:</p>
                    <p class="member-duration">{{$member->member_type}}( {{$member->duration}} month)</p>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Cost:</p>
                    <p class="member-cost">{{$member->price}}MMK</p>
                </div>
                @endif
                <div class="customer-transaction-input-container">
                    <p>AYA pay Phone Number:</p>
                    <input type="text" name = "payment_name" hidden value="AYA Pay">
                    <input type="number" name="phone" required>
                </div>
                <div class="customer-transaction-input-container">
                    <p>AYA pay Name:</p>
                    <input type="text" name="account_name" required>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Amount:
                    </p>
                    <input type="text" required name = "amount">
                </div>

                <div class="customer-transaction-receipt-img">

                    <span class="selectImage">
                    Transaction screen shot
                    <div class="customer-screenshot-upload-btn">
                        <iconify-icon icon="akar-icons:circle-plus" class="customer-screenshot-upload-btn-icon"></iconify-icon>
                        <p>Photo</p>
                        <input type="file" id="ayapayImg" name="image" required>
                    </div>

                    <button class="customer-transaction-clear-btn" type="button" onclick="clearTransactionImg()">Clear</button>

                    </span>
                    <img class="preview ayapayImg">
                </div>

                <div class="customer-transaction-admin-details">
                    @foreach($banking_info as $wave)
                    @if($wave->payment_name	== "Wave Pay")
                    <div class="customer-transaction-admin-phone">
                        <p>Wave pay Phone Number:</p>
                        <p>{{$wave->phone}}</p>
                    </div>
                    <div class="customer-transaction-admin-phone">
                        <p>Account Name:</p>
                        <p>{{$wave->account_name}}</p>
                    </div>
                    <hr>
                    @endif
                    @endforeach
                </div>


                <div class="customer-transaction-form-btn-container">
                    <button type="submit" class="customer-transaction-form-submit">Confirm</button>
                    <button type="reset"  class="customer-transaction-form-cencel" onclick="clearTransactionImg()">Reset</button>
                </div>
            </form>
        </div>

      </div>
    </div>
</div>

<!--kbz bank modal-->
<div class="modal fade" id="kbzbankModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header customer-transaction-modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Send Payment Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearTransactionInputs()"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('bank_payment_store') }}" method="POST" enctype="multipart/form-data" class="customer-transaction-form">
                @csrf
                <input type="hidden" name="member_type" value={{$member->member_type}}>
                <div class="customer-transaction-form-img">
                    <img src="{{asset('image/kbzbank-removebg-preview.png')}}"/>
                </div>
                @if(auth()->user()->shopmember_type_id==0 || auth()->user()->shopmember_type_id==null)
                <div class="customer-transaction-input-container">
                    <p>Choosen Plan:</p>
                    <p class="member-duration">{{$member->member_type}}( {{$member->duration}} month)</p>
                </div>
                @else
                <div class="customer-transaction-input-container">
                    <p>Choosen Level:</p>
                    <p class="member-duration">{{$member->member_type}}( {{$member->duration}} month)</p>
                </div>
                @endif
                <div class="customer-transaction-input-container">
                    <p>Cost:</p>
                    <p class="member-cost">{{$member->price}}MMK</p>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Bank Account Number:</p>
                    <input type="text" name = "payment_name" hidden value="KBZ Bank">
                    <input type="number" name = "bank_account_number" required>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Bank Account Holder Name:</p>
                    <input type="text" name ="bank_account_holder" required>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Amount:
                    </p>
                    <input type="text" required name = "amount">
                </div>

                <div class="customer-transaction-receipt-img">

                    <span class="selectImage">
                    Transaction screen shot
                    <div class="customer-screenshot-upload-btn">
                        <iconify-icon icon="akar-icons:circle-plus" class="customer-screenshot-upload-btn-icon"></iconify-icon>
                        <p>Photo</p>
                        <input type="file" id="kbzbankimg" name="image" required>
                    </div>

                    <button class="customer-transaction-clear-btn" type="button" onclick="clearTransactionImg()">Clear</button>

                    </span>
                    <img class="preview kbzbankimg">
                </div>

                {{-- <div class="customer-transaction-admin-details">

                    <div class="customer-transaction-admin-phone">
                        @foreach($banking_info as $kbzbank)
                        @if($kbzbank->payment_name	== "KBZ Bank")
                        <p>Bank Account Number:</p>
                        <p>{{$kbzbank->bank_account_number}}</p>
                    </div>
                    <div class="customer-transaction-admin-phone">
                        <p>Bank Account Name:</p>
                        <p>{{$kbzbank->bank_account_holder}}</p>
                    </div>
                     @endif
                     @endforeach
                </div> --}}

                <div class="customer-transaction-admin-details">
                    @foreach($banking_info as $kbzB)
                    @if($kbzB->payment_name	== "KBZ Bank")
                    <div class="customer-transaction-admin-phone">
                        <p>Bank Account Number:</p>
                        <p>{{$kbzB->bank_account_number}}</p>
                    </div>
                    <div class="customer-transaction-admin-phone">
                        <p>Bank Account Holder Name:</p>
                        <p>{{$kbzB->bank_account_holder}}</p>
                    </div>
                    @endif
                    @endforeach
                </div>

                <div class="customer-transaction-form-btn-container">
                    <button type="submit" class="customer-transaction-form-submit">Confirm</button>
                    <button type="reset"  class="customer-transaction-form-cencel" onclick="clearTransactionImg()">Reset</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>

<!--cb bank modal-->
<div class="modal fade" id="cbbankModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header customer-transaction-modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Send Payment Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearTransactionInputs()"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('bank_payment_store') }}" method="POST" enctype="multipart/form-data" class="customer-transaction-form">
                @csrf
                <input type="hidden" name="member_type" value={{$member->member_type}}>
                <div class="customer-transaction-form-img">
                    <img src="{{asset('image/cbbank-removebg-preview.png')}}"/>
                </div>
                @if(auth()->user()->shopmember_type_id==0 || auth()->user()->shopmember_type_id==null)
                <div class="customer-transaction-input-container">
                    <p>Choosen Plan:</p>
                    <p class="member-duration">{{$member->member_type}}( {{$member->duration}} month)</p>
                </div>
                @else
                <div class="customer-transaction-input-container">
                    <p>Choosen Level:</p>
                    <p class="member-duration">{{$member->member_type}}( {{$member->duration}} month)</p>
                </div>
                @endif
                <div class="customer-transaction-input-container">
                    <p>Cost:</p>
                    <p class="member-cost">{{$member->price}}MMK</p>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Bank Account Number:</p>
                    <input type="text" name = "payment_name" hidden value="CB Bank">
                    <input type="number" required name ="bank_account_number">
                </div>
                <div class="customer-transaction-input-container">
                    <p>Bank Account Holder Name:</p>
                    <input type="text" name ="bank_account_holder" required>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Amount:
                    </p>
                    <input type="number" required name = "amount">
                </div>

                <div class="customer-transaction-receipt-img">

                    <span class="selectImage">
                    Transaction screen shot
                    <div class="customer-screenshot-upload-btn">
                        <iconify-icon icon="akar-icons:circle-plus" class="customer-screenshot-upload-btn-icon"></iconify-icon>
                        <p>Photo</p>
                        <input type="file" id="cbbankimg" name="image" required>
                    </div>

                    <button class="customer-transaction-clear-btn" type="button" onclick="clearTransactionImg()">Clear</button>

                    </span>
                    <img class="preview cbbankimg">
                </div>

                <div class="customer-transaction-admin-details">
                    @foreach($banking_info as $cb)
                    @if($cb->payment_name	== "CB Bank")
                    <div class="customer-transaction-admin-phone">
                        <p>Bank Account Number:</p>
                        <p>{{$cb->bank_account_number}}</p>
                    </div>
                    <div class="customer-transaction-admin-phone">
                        <p>Bank Account Holder Name:</p>
                        <p>{{$cb->bank_account_holder}}</p>
                    </div>
                    @endif
                    @endforeach
                </div>

                <div class="customer-transaction-form-btn-container">
                    <button type="submit" class="customer-transaction-form-submit">Confirm</button>
                    <button type="reset"  class="customer-transaction-form-cencel" onclick="clearTransactionImg()">Reset</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>

<!--aya bank modal-->
<div class="modal fade" id="ayabankModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header customer-transaction-modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Send Payment Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearTransactionInputs()"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('bank_payment_store') }}" method="POST" enctype="multipart/form-data" class="customer-transaction-form">
                @csrf
                <input type="hidden" name="member_type" value={{$member->member_type}}>
                <div class="customer-transaction-form-img">
                    <img src="{{asset('image/ayabank-removebg-preview.png')}}"/>
                </div>
                @if(auth()->user()->shopmember_type_id==0 || auth()->user()->shopmember_type_id==null)
                <div class="customer-transaction-input-container">
                    <p>Choosen Plan:</p>
                    <p class="member-duration">{{$member->member_type}}( {{$member->duration}} month)</p>
                </div>
                @else
                <div class="customer-transaction-input-container">
                    <p>Choosen Level:</p>
                    <p class="member-duration">{{$member->member_type}}( {{$member->duration}} month)</p>
                </div>
                @endif
                <div class="customer-transaction-input-container">
                    <p>Cost:</p>
                    <p class="member-cost">{{$member->price}}MMK</p>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Bank Account Number:</p>
                    <input type="text" name = "payment_name" hidden value="AYA Bank">
                    <input type="number" name ="bank_account_number" required>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Bank Account Holder Name:</p>
                    <input type="text" name = "bank_account_holder" required>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Amount:
                    </p>
                    <input type="number" name ="amount" required>
                </div>

                <div class="customer-transaction-receipt-img">

                    <span class="selectImage">
                    Transaction screen shot
                    <div class="customer-screenshot-upload-btn">
                        <iconify-icon icon="akar-icons:circle-plus" class="customer-screenshot-upload-btn-icon"></iconify-icon>
                        <p>Photo</p>
                        <input type="file" id="ayabankimg" name="image" required>
                    </div>

                    <button class="customer-transaction-clear-btn" type="button" onclick="clearTransactionImg()">Clear</button>

                    </span>
                    <img class="preview ayabankimg">
                </div>

                <div class="customer-transaction-admin-details">
                    @foreach($banking_info as $aya)
                    @if($aya->payment_name	== "AYA Bank")
                    <div class="customer-transaction-admin-phone">
                        <p>Bank Account Number:</p>
                        <p>{{$aya->bank_account_number}}</p>
                    </div>
                    <div class="customer-transaction-admin-phone">
                        <p>Bank Account Holder Name:</p>
                        <p>{{$aya->bank_account_holder}}</p>
                    </div>
                    @endif
                    @endforeach
                </div>

                <div class="customer-transaction-form-btn-container">
                    <button type="submit" class="customer-transaction-form-submit">Confirm</button>
                    <button type="reset"  class="customer-transaction-form-cencel" onclick="clearTransactionImg()">Reset</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>

<!--mab bank modal-->
<div class="modal fade" id="mabbankModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header customer-transaction-modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Send Payment Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearTransactionInputs()"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('bank_payment_store') }}" method="POST" enctype="multipart/form-data" class="customer-transaction-form">
                @csrf
                <input type="hidden" name="member_type" value={{$member->member_type}}>
                <div class="customer-transaction-form-img">
                    <img src="{{asset('image/mabbank-removebg-preview.png')}}"/>
                </div>
                @if(auth()->user()->shopmember_type_id==0 || auth()->user()->shopmember_type_id==null)
                <div class="customer-transaction-input-container">
                    <p>Choosen Plan:</p>
                    <p class="member-duration">{{$member->member_type}}( {{$member->duration}} month)</p>
                </div>
                @else
                <div class="customer-transaction-input-container">
                    <p>Choosen Level:</p>
                    <p class="member-duration">{{$member->member_type}}( {{$member->duration}} month)</p>
                </div>
                @endif
                <div class="customer-transaction-input-container">
                    <p>Cost:</p>
                    <p class="member-cost">{{$member->price}}MMK</p>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Bank Account Number:</p>
                    <input type="text" name = "payment_name" hidden value="MAB Bank">
                    <input type="number" name = "bank_account_number" required>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Bank Account Name:</p>
                    <input type="text" name = "bank_account_holder" required>
                </div>
                <div class="customer-transaction-input-container">
                    <p>Amount:
                    </p>
                    <input type="number" name = "amount" required>
                </div>

                <div class="customer-transaction-receipt-img">

                    <span class="selectImage">
                    Transaction screen shot
                    <div class="customer-screenshot-upload-btn">
                        <iconify-icon icon="akar-icons:circle-plus" class="customer-screenshot-upload-btn-icon"></iconify-icon>
                        <p>Photo</p>
                        <input type="file" id="mabbankImg" name="image" required>
                    </div>

                    <button class="customer-transaction-clear-btn" type="button" onclick="clearTransactionImg()">Clear</button>

                    </span>
                    <img class="preview mabbankImg">
                </div>

                <div class="customer-transaction-admin-details">
                    @foreach($banking_info as $mab)
                    @if($mab->payment_name	== "MAB Bank")
                    <div class="customer-transaction-admin-phone">
                        <p>Bank Account Number:</p>
                        <p>{{$mab->bank_account_number}}</p>
                    </div>
                    <div class="customer-transaction-admin-phone">
                        <p>Bank Account Holder Name:</p>
                        <p>{{$mab->bank_account_holder}}</p>
                    </div>
                    @endif
                    @endforeach
                </div>

                <div class="customer-transaction-form-btn-container">
                    <button type="submit" class="customer-transaction-form-submit">Confirm</button>
                    <button type="reset"  class="customer-transaction-form-cencel" onclick="clearTransactionImg()">Reset</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>



<div class="customer-main-content-container margin-top">
    <form id="customer-transaction-choice-form" class="customer-payment-parent-container">
        <p class="customer-transaction-form-header">
            Confirm Transaction Via
        </p>

        

        <div class="customer-transaction-choices-parent-container">
            <div class="customer-transaction-choices-ewallet-parent-container">
                    @if (count($errors) > 0)
                        <div class="alert alert-warning alert-dismissible fade show">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                        </div>
                    @endif
                <p class="customer-transaction-choices-ewallet-header">
                    <iconify-icon icon="akar-icons:wallet" class="customer-transaction-choices-icon"></iconify-icon>
                    Ewallet
                    <span class="customer-transaction-choices-recommended">(Recommended)</span>
                </p>

                <div class="customer-transaction-choices-ewallet-checkboxes-container checkbox-flex-container">


                        <div class="checkbox checkbox-vertical customer-transaction-choice">
                          <label class="checkbox-wrapper">
                            <input type="checkbox" name = "transactionChoice" class="checkbox-input" data-bs-toggle="modal" data-bs-target="#kpayModal" onclick="checkedOnTransactionChoiceClick(this,'transactionChoice')"/>
                            <span class="checkbox-tile">
                              <span class="checkbox-icon">
                                <div class="transaction-choice-img">
                                    <img src="{{asset('image/kpay.png')}}"/>
                                </div>
                              </span>
                              <span class="checkbox-label">KBZ Pay<br>

                              </span>

                            </span>
                          </label>
                        </div>
                        <div class="checkbox checkbox-vertical customer-transaction-choice">
                          <label class="checkbox-wrapper">
                            <input type="checkbox" name = "transactionChoice" class="checkbox-input" data-bs-toggle="modal" data-bs-target="#cbpayModal" onclick="checkedOnTransactionChoiceClick(this,'transactionChoice')"/>
                            <span class="checkbox-tile">
                              <span class="checkbox-icon">
                                <div class="transaction-choice-img"><img src="{{asset('image/cbpay.jfif')}}"/></div>
                              </span>
                              <span class="checkbox-label">CB Pay<br>

                              </span>
                            </span>
                          </label>
                        </div>
                        <div class="checkbox checkbox-vertical customer-transaction-choice">
                          <label class="checkbox-wrapper">
                            <input type="checkbox" name = "transactionChoice" class="checkbox-input" data-bs-toggle="modal" data-bs-target="#wavepayModal" onclick="checkedOnTransactionChoiceClick(this,'transactionChoice')"/>
                            <span class="checkbox-tile">
                              <span class="checkbox-icon">
                                <div class="transaction-choice-img"><img src="{{asset('image/wavepay.jfif')}}"/></div>
                              </span>
                              <span class="checkbox-label">Wave Pay<br>

                              </span>
                            </span>
                          </label>
                        </div>
                        <div class="checkbox checkbox-vertical customer-transaction-choice">
                          <label class="checkbox-wrapper">
                            <input type="checkbox" name = "transactionChoice" class="checkbox-input" data-bs-toggle="modal" data-bs-target="#ayapayModal" onclick="checkedOnTransactionChoiceClick(this,'transactionChoice')"/>
                            <span class="checkbox-tile">
                              <span class="checkbox-icon">
                                <div class="transaction-choice-img"><img src="{{asset('image/ayapay.jfif')}}"/></div>
                              </span>
                              <span class="checkbox-label">AYA Pay<br>

                              </span>
                            </span>
                          </label>
                        </div>

                </div>
            </div>
            <div class="customer-transaction-line"></div>
            <div class="customer-transaction-choices-ewallet-parent-container">
                <p class="customer-transaction-choices-ewallet-header">
                    <!-- <iconify-icon icon="akar-icons:wallet" class="customer-transaction-choices-icon"></iconify-icon> -->
                    <iconify-icon icon="fluent:building-bank-16-filled" class="customer-transaction-choices-icon"></iconify-icon>
                    Bank Account

                </p>

                <div class="customer-transaction-choices-ewallet-checkboxes-container checkbox-flex-container">


                        <div class="checkbox checkbox-vertical customer-transaction-choice">
                          <label class="checkbox-wrapper">
                            <input type="checkbox" name = "transactionChoice" class="checkbox-input" data-bs-toggle="modal" data-bs-target="#kbzbankModal" onclick="checkedOnTransactionChoiceClick(this,'transactionChoice')"/>
                            <span class="checkbox-tile">
                              <span class="checkbox-icon">
                                <div class="transaction-choice-img">
                                    <img src="{{asset('image/kbzbank-removebg-preview.png')}}"/>
                                </div>
                              </span>
                              <span class="checkbox-label">KBZ Bank<br>

                              </span>

                            </span>
                          </label>
                        </div>
                        <div class="checkbox checkbox-vertical customer-transaction-choice">
                          <label class="checkbox-wrapper">
                            <input type="checkbox" name = "transactionChoice" class="checkbox-input" data-bs-toggle="modal" data-bs-target="#cbbankModal" onclick="checkedOnTransactionChoiceClick(this,'transactionChoice')"/>
                            <span class="checkbox-tile">
                              <span class="checkbox-icon">
                                <div class="transaction-choice-img"><img src="{{asset('image/cbbank-removebg-preview.png')}}"/></div>
                              </span>
                              <span class="checkbox-label">CB Bank<br>

                              </span>
                            </span>
                          </label>
                        </div>
                        <div class="checkbox checkbox-vertical customer-transaction-choice">
                          <label class="checkbox-wrapper">
                            <input type="checkbox" name = "transactionChoice" class="checkbox-input" data-bs-toggle="modal" data-bs-target="#ayabankModal" onclick="checkedOnTransactionChoiceClick(this,'transactionChoice')"/>
                            <span class="checkbox-tile">
                              <span class="checkbox-icon">
                                <div class="transaction-choice-img"><img src="{{asset('image/ayabank-removebg-preview.png')}}"/></div>
                              </span>
                              <span class="checkbox-label">AYA Bank<br>

                              </span>
                            </span>
                          </label>
                        </div>
                        <div class="checkbox checkbox-vertical customer-transaction-choice">
                          <label class="checkbox-wrapper">
                            <input type="checkbox" name = "transactionChoice" class="checkbox-input" data-bs-toggle="modal" data-bs-target="#mabbankModal" onclick="checkedOnTransactionChoiceClick(this,'transactionChoice')"/>
                            <span class="checkbox-tile">
                              <span class="checkbox-icon">
                                <div class="transaction-choice-img"><img src="{{asset('image/mabbank-removebg-preview.png')}}"/></div>
                              </span>
                              <span class="checkbox-label">MAB Bank<br>

                              </span>
                            </span>
                          </label>
                        </div>

                </div>
            </div>
        </div>

    </form>
</div>

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>

        const kpayInput = document.querySelector('#kpayImg');
        const kpayPreview = document.querySelector('.kpayImg');

        const cbpayInput = document.querySelector('#cbpayImg');
        const cbpayPreview = document.querySelector('.cbpayImg');

        const wavepayInput = document.querySelector('#wavepayImg');
        const wavepayPreview = document.querySelector('.wavepayImg');

        const ayapayInput = document.querySelector('#ayapayImg');
        const ayapayPreview = document.querySelector('.ayapayImg');


        const kbzbankInput = document.querySelector('#kbzbankimg');
        const kbzbankPreview = document.querySelector('.kbzbankimg');

        const cbbankInput = document.querySelector('#cbbankimg');
        const cbbankPreview = document.querySelector('.cbbankimg');

        const ayabankInput = document.querySelector('#ayabankimg');
        const ayabankPreview = document.querySelector('.ayabankimg');

        const mabbankInput = document.querySelector('#mabbankImg');
        const mabbankPreview = document.querySelector('.mabbankImg');

        kpayInput.addEventListener('change', (e) =>{
            // console.log(kpayPreview)
        const reader = new FileReader();
        reader.onload = e => kpayPreview.setAttribute('src', e.target.result);
        reader.readAsDataURL(kpayInput.files[0]);
        });//

        cbpayInput.addEventListener('change', (e) =>{
            // console.log(kpayPreview)
        const reader = new FileReader();
        reader.onload = e => cbpayPreview.setAttribute('src', e.target.result);
        reader.readAsDataURL(cbpayInput.files[0]);
        });//


        wavepayInput.addEventListener('change', (e) =>{
            // console.log(kpayPreview)
        const reader = new FileReader();
        reader.onload = e => wavepayPreview.setAttribute('src', e.target.result);
        reader.readAsDataURL(wavepayInput.files[0]);
        });//

        ayapayInput.addEventListener('change', (e) =>{
            // console.log(kpayPreview)
        const reader = new FileReader();
        reader.onload = e => ayapayPreview.setAttribute('src', e.target.result);
        reader.readAsDataURL(ayapayInput.files[0]);
        });//

        kbzbankInput.addEventListener('change', (e) =>{
            // console.log(kpayPreview)
        const reader = new FileReader();
        reader.onload = e => kbzbankPreview.setAttribute('src', e.target.result);
        reader.readAsDataURL(kbzbankInput.files[0]);
        });//

        cbbankInput.addEventListener('change', (e) =>{
            // console.log(kpayPreview)
        const reader = new FileReader();
        reader.onload = e => cbbankPreview.setAttribute('src', e.target.result);
        reader.readAsDataURL(cbbankInput.files[0]);
        });//

        ayabankInput.addEventListener('change', (e) =>{
            // console.log(kpayPreview)
        const reader = new FileReader();
        reader.onload = e => ayabankPreview.setAttribute('src', e.target.result);
        reader.readAsDataURL(ayabankInput.files[0]);
        });//

        mabbankInput.addEventListener('change', (e) =>{
            // console.log(kpayPreview)
        const reader = new FileReader();
        reader.onload = e => mabbankPreview.setAttribute('src', e.target.result);
        reader.readAsDataURL(mabbankInput.files[0]);
        });//


        function clearTransactionImg(){
            kpayInput.value = ""
            kpayPreview.removeAttribute("src")

            cbpayInput.value = ""
            cbpayPreview.removeAttribute("src")

            wavepayInput.value = ""
            wavepayPreview.removeAttribute("src")

            ayapayInput.value = ""
            ayapayPreview.removeAttribute("src")

            kbzbankInput.value = ""
            kbzbankPreview.removeAttribute("src")

            cbbankInput.value = ""
            cbbankPreview.removeAttribute("src")

            ayabankInput.value = ""
            ayabankPreview.removeAttribute("src")

            mabbankInput.value = ""
            mabbankPreview.removeAttribute("src")
        }

        function clearTransactionInputs(){
            const inputs = document.querySelectorAll("input")
            for(let i = 0; i < inputs.length;i++){
                inputs[i].value = ''
            }
            kpayPreview.removeAttribute("src")
            cbpayPreview.removeAttribute("src")
            wavepayPreview.removeAttribute("src")
            ayapayPreview.removeAttribute("src")

            kbzbankPreview.removeAttribute("src")
            cbbankPreview.removeAttribute("src")
            ayabankPreview.removeAttribute("src")
            mabbankPreview.removeAttribute("src")
        }
        function checkedOnTransactionChoiceClick(el, category){

            if(category === "transactionChoice"){
                var transactionChoicecheckboxesList = document.getElementsByName("transactionChoice");
                for (var i = 0; i < transactionChoicecheckboxesList.length; i++) {
                    if(transactionChoicecheckboxesList.item(i).checked){
                        transactionChoicecheckboxesList.item(i).checked = false; // Uncheck all checkboxes
                    }
                }
            }
            el.checked = true
        }

        $(document).ready(function(){
            $('.alert').alert()
        })

    </script>

@endpush