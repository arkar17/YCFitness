@extends('customer.layouts.app')

@section('content')
<h1>Welcome Gym Member User -  {{Auth()->user()->name}}</h1>
@endsection
@push('scripts')
@endpush

