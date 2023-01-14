@extends('layouts.app')
@section('bankinginfo-active','active')

@section('content')
    <div class="col-md-8 mx-auto">
        <a href="javascript:history.back()" class="btn btn-primary mb-2"><i class="fa-solid fa-arrow-left-long"></i> &nbsp; Back</a>
        <div class="card shadow p-4">
            <h3 class="text-center mb-2">Edit Bank Account</h3>

            <form action="{{ route('bankinginfo.update',$bankinginfo->id) }}" method="POST" id="create-trainer">
                @csrf
                @method('PUT')
                <div class="d-flex mb-3">
                    <label>Payment Type :</label>
                    <div class="form-check ms-3">
                        <label class="form-check-label" >
                          Bank Transfer
                          <input class="form-check-input" type="radio" name="paymentType" id="bank" value="bank transfer">
                        </label>
                    </div>
                     <div class="form-check ms-3">
                        <label class="form-check-label">
                          E-wallet
                          <input class="form-check-input" type="radio" name="paymentType" id="ewallet" value="ewallet">
                        </label>
                    </div>
                </div>

                <div id="bank-select" >
                    <label for="accname">Payment Name</label>
                    <select class="form-select mb-3" name="bankName" aria-label="Default select example">
                        <option selected>Select Bank Name</option>
                        <option value="KBZ Bank">KBZ Bank</option>
                        <option value="CB Bank">CB Bank</option>
                        <option value="AYA Bank">AYA Bank</option>
                        <option value="MAB Bank">MAB Bank</option>
                    </select>
                </div>

                <div id="wallet-select" style="display: none;">
                    <label for="accname">Payment Name</label>
                    <select class="form-select mb-3" name="ewalletName" aria-label="Default select example" >
                        <option selected>Select Wallet Name</option>
                        <option value="KBZ Pay">KBZ Pay</option>
                        <option value="CB Pay">CB Pay</option>
                        <option value="AYA Pay">AYA Pay</option>
                        <option value="Wave Pay">Wave Pay</option>
                    </select>
                </div>

                <div class="mb-3" id="bank-holder">
                    <label for="name">Bank Account Holder</label>
                    <input type="text" class="form-control" name="accountHolder" value="{{$bankinginfo->bank_account_holder}}">
                </div>
                <div class="mb-3" id="bank-number">
                    <label for="name">Bank Account No.</label>
                    <input type="text" class="form-control" name="accountNo" value="{{$bankinginfo->bank_account_number}}">
                </div>

                <div class="mb-3" id="accname">
                    <label for="accname">Account Name</label>
                    <input type="text" class="form-control" name="accountName" value="{{$bankinginfo->account_name}}">
                </div>
                <div class="mb-4" id="phone">
                    <label for="phone">Phone</label>
                    <input type="number" class="form-control" name="phone" value="{{$bankinginfo->phone}}">
                    @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <p class="text-danger">Phone number is required at least 11.</p>
                            @endforeach
                    @endif
                </div>

                <div class="float-end mt-4">
                    <a href="{{ route('bankinginfo.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>

            </form>
        </div>
    </div>
@endsection


@push('scripts')
<script>
    $(document).ready( function(){
        var paymentType = @json($bankinginfo);
    if (paymentType.payment_type == 'ewallet') {
        $("#ewallet").attr('checked', 'checked');
        $('#accname').show();
        $('#phone').show();
        $('#wallet-select').show();
        $('#bank-select').hide();
        $('#bank-number').hide();
        $('#bank-holder').hide();
    }else{
        $("#bank").attr('checked', 'checked');
        $('#bank-number').show();
        $('#bank-holder').show();
        $('#accname').hide();
        $('#phone').hide();
    }

    $("#ewallet").click(function() {
        $('#accname').show();
        $('#phone').show();
        $('#wallet-select').show();
        $('#bank-select').hide();
        $('#bank-number').hide();
        $('#bank-holder').hide();
    });
    $("#bank").click(function() {
        $('#bank-select').show();
        $('#bank-holder').show();
        $('#bank-number').show();
        $('#wallet-select').hide();
        $('#accname').hide();
        $('#phone').hide();
    });
    })

</script>

@endpush
