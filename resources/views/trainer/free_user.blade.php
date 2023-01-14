@extends('customer.layouts.app')

@section('content')
<h1>Welcome Free User -  {{Auth()->user()->name}}</h1>
@endsection
@push('scripts')
@endpush

