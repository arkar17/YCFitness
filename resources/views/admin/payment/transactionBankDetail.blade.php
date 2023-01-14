
@extends('layouts.app')
@section('transction-active', 'active')


@section('content')

<div class="container">
    <a href="javascript:history.back()" class="btn btn-primary mb-2"><i class="fa-solid fa-arrow-left-long"></i> &nbsp; Back</a>
    <div class="card mb-3 shadow" >
        <div class="card-header">
           <h3>Bank Transaction Detail</h3>
          </div>

        <div class="row g-0">
          <div class="col-md-4 shadow" style="width:300px; height:600px">
            <img src="{{asset('/storage/payments/'.$banktransctiondetail->photo)}}" class="img-fluit rounded" style="width: 100%;
            height:100%">
          </div>
          <div class="col-md-6 d-flex align-items-center">
            <div class="card-body">
              <div>
                <label class="fs-5">Name :</label>
                <label class="ms-2 text-capitalize">{{$banktransctiondetail->user->name}}</label>
              </div>
              <div>
                <label class="fs-5">Payment Type :</label>
                <label class="ms-2 text-capitalize">{{$banktransctiondetail->payment_type}}</label>
              </div>
              <div>
                <label class="fs-5">Bank Name :</label>
                <label class="ms-2 text-capitalize">{{$banktransctiondetail->payment_name}}</label>
              </div>
             <div>
                <label class="fs-5">Bank account number :</label>
                <label class="ms-2 text-capitalize">{{$banktransctiondetail->bank_account_number ?? "E-wallet"}}</label>
             </div>
              <div>
                <label class="fs-5">Bank account holder :</label>
                <label class="ms-2 text-capitalize">{{$banktransctiondetail->bank_account_holder ?? "E-wallet"}}</label>
              </div>
             <div>
                <label class="fs-5">Amount :</label>
                <label class="ms-2 text-capitalize">{{$banktransctiondetail->amount}}</label>
             </div>
            </div>
          </div>
        </div>
      </div>
</div>
@endsection
