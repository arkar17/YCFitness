@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 50px;">
    <h3 class="text-center">Weight Loss Plan</h3>
    <p class="text-center">Monday OCT 3,2022</p>
    <div class="card mb-3 mx-auto" style="max-width: 540px;">
      <div class="row g-0">
        <div class="col-md-4">
          <img src="gym1.jpg" class="float-start" alt="" style="width: 100px; height: 100px; border-radius: 50%; margin: 10px;">
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title">Work out</h5>
            <p class="text-muted">Core + Arms $ Legs work out. <a href=""><i class="fa-solid fa-arrow-right float-end text-dark"></i></a></p>
            <p class="card-text"><small>35 mins</small></p>
          </div>
        </div>
      </div>
    </div>
    <div class="card mb-3 mx-auto" style="max-width: 540px;">
      <div class="row g-0">
        <div class="col-md-4">
          <img src="gym1.jpg" class="float-start" alt="" style="width: 100px; height: 100px; border-radius: 50%; margin: 10px;">
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title">Add Food</h5>
            <p class="text-muted">0 of 2014 kcal <a href=""><i class="fa-solid fa-arrow-right float-end text-dark"></i></a></p>
            <p class="card-text"><small>35 mins</small></p>
          </div>
        </div>
      </div>
    </div>
    <div class="card mx-auto" style="max-width: 540px;">
      <div class="row g-0">
        <div class="col-md-4">
          <img src="gym1.jpg" class="float-start" alt="" style="width: 100px; height: 100px; border-radius: 50%; margin: 10px;">
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title">Water Tracker</h5>
            <p class="text-muted">0 of 92 oz. <a href=""><i class="fa-solid fa-arrow-right float-end text-dark"></i></a></p>
            <p class="card-text"><small>35 mins</small></p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
