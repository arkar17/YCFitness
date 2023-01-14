@extends('layouts.app')
@section('bankinginfo-active','active')

@section('content')
    <div class="col-md-8 mx-auto">
        <a href="javascript:history.back()" class="btn btn-primary mb-2"><i class="fa-solid fa-arrow-left-long"></i> &nbsp; Back</a>

        <div class="card shadow p-4">
            <h3 class="text-center mb-2">Add New Bank Account</h3>

            <form action="{{ route('bankinginfo.store') }}" method="POST" id="create-bankinfo">
                @csrf
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

                <div id="bank-select" style="display: none;">
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

                <div class="mb-3" id="bank-number" style="display: none;">
                    <label for="name">Bank Account No.</label>
                    <input type="text" class="form-control" name="accountNo">
                </div>
                <div class="mb-3" id="bank-holder" style="display: none;">
                    <label for="name">Bank Account Holder</label>
                    <input type="text" class="form-control" name="accountHolder">
                </div>

                <div class="mb-3" id="accname" style="display: none;">
                    <label>Account Name </label>
                    <input type="text" class="form-control" name="accountName">
                </div>

                <div class="mb-4" id="phone" style="display: none;">
                    <label>Phone</label>
                    <input type="number" class="form-control" name="phone">
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
{!! JsValidator::formRequest('App\Http\Requests\BankingRequest', '#create-bankinfo') !!}
<script>
    $("#ewallet").click(function() {
        $('#accname').show();
        $('#phone').show();
        $('#wallet-select').show();
        $('#bank-number').hide();
        $('#bank-holder').hide();
        $('#bank-select').hide();
    });

    $("#bank").click(function() {
        $('#bank-select').show();
        $('#bank-number').show();
        $('#bank-holder').show();
        $('#accname').hide();
        $('#phone').hide();
        $('#wallet-select').hide();
    });

</script>

@endpush
