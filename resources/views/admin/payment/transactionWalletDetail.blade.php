
@extends('layouts.app')
@section('transction-active', 'active')


@section('content')

<div class="container">
    <a href="javascript:history.back()" class="btn btn-primary mb-2"><i class="fa-solid fa-arrow-left-long"></i> &nbsp; Back</a>
    <div class="card mb-3 shadow" >
        <div class="card-header">
           <h3>E-wallet Transaction Detail</h3>
          </div>

        <div class="row g-0">
          <div class="col-md-4 shadow ms-3 my-3" style="width:300px; height:600px">
            <img 
            src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/payments/{{ $wallettransctiondetail->photo}}"
            class="img-fluid rounded" alt="..." style="width: 100%;
            height:100%">
          </div>
          <div class="col-md-6 d-flex align-items-center">
            <div class="card-body">
              <div>
                <label class="fs-5">Name :</label>
                <label class="ms-2 text-capitalize">{{$wallettransctiondetail->user->name}}</label>
              </div>
              <div>
                <label class="fs-5">Payment Type :</label>
                <label class="ms-2 text-capitalize">{{$wallettransctiondetail->payment_type}}</label>
              </div>
              <div>
                <label class="fs-5">Payment name :</label>
                <label class="ms-2 text-capitalize">{{$wallettransctiondetail->payment_name ?? "E-wallet"}}</label>
             </div>
             <div>
                <label class="fs-5">Account name :</label>
                <label class="ms-2 text-capitalize">{{$wallettransctiondetail->account_name ?? "E-wallet"}}</label>
             </div>
              <div>
                <label class="fs-5">Phone :</label>
                <label class="ms-2 text-capitalize">{{$wallettransctiondetail->phone ?? "E-wallet"}}</label>
              </div>
             <div>
                <label class="fs-5">Amount :</label>
                <label class="ms-2 text-capitalize">{{$wallettransctiondetail->amount}}</label>
             </div>
            </div>
          </div>
        </div>
      </div>
</div>
@endsection
