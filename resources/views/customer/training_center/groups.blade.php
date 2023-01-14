@extends('customer.training_center.layouts.app')

@section('content')
<a href="{{route('trainingcenter.member_plan')}}" class="customer-primary-btn margin-top customer-change-member-plan-link">Change Member Plan</a>
<div class="customer-training-center-header-container">
    {{-- <h1>{{ $group->group->group_name }}</h1> --}}
    {{-- <p>Thursday Sep 22, 2022</p> --}}
</div>

<div class="customer-training-center-plans-container">
    <a href="{{route('group')}}" class="customer-training-center-plan-container">
        <div class="customer-training-center-plan-name-container">
            {{-- <iconify-icon icon="fluent-emoji:man-lifting-weights" class="customer-training-center-plan-name-icon"></iconify-icon>
             --}}
             {{-- <img src="{{ asset('image/default.jpg') }}" class="img w-25" /> --}}
             <iconify-icon icon="fa:group" class="customer-training-center-plan-name-icon"></iconify-icon>
            <div class="customer-training-center-plan-name-text">
                <p>Groups</p>
                {{-- <span>{{ $group->group->group_name }}</span> --}}
            </div>
        </div>

        <iconify-icon icon="dashicons:arrow-right-alt2" class="customer-training-plan-icon"></iconify-icon>
    </a>

</div>
@endsection
