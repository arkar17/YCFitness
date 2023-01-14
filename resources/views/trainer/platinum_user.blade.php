.
@extends('customer.layouts.app')

@section('content')
<h1>Welcome Platinum User -  {{Auth()->user()->name}}</h1>
@endsection
@push('scripts')
@endpush

