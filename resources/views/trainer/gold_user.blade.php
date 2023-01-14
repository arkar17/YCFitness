@extends('customer.layouts.app')

@section('content')
@include('sweetalert::alert')
<h1>Welcome Gold User -  {{Auth()->user()->name}}</h1>
@endsection
@push('scripts')
@endpush

