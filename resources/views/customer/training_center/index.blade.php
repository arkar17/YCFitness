@extends('customer.training_center.layouts.app')

@section('content')

    
        <a href="{{route('training_center.member_plan')}}" class="customer-primary-btn margin-top customer-change-member-plan-link">{{__('msg.change member plan')}}</a>
    
      


<div class="customer-training-center-header-container">
    <h1>{{Str::ucfirst($workout_plan)}}</h1>
    {{-- <p>Thursday Sep 22, 2022</p> --}}
</div>

<div class="customer-training-center-plans-container">
    <a href="{{route('training_center.workout_plan')}}" class="customer-training-center-plan-container">
        <div class="customer-training-center-plan-name-container">
            <iconify-icon icon="fluent-emoji:man-lifting-weights" class="customer-training-center-plan-name-icon"></iconify-icon>
            <div class="customer-training-center-plan-name-text">
                <p>{{__('msg.work out')}}</p>
                {{-- <span>Core + Arms Workout</span> --}}
            </div>
        </div>

        <iconify-icon icon="dashicons:arrow-right-alt2" class="customer-training-plan-icon"></iconify-icon>
    </a>
    <a href="{{route('training_center.meal')}}" class="customer-training-center-plan-container">
        <div class="customer-training-center-plan-name-container">
            <iconify-icon icon="emojione:pot-of-food" class="customer-training-center-plan-name-icon"></iconify-icon>
            <div class="customer-training-center-plan-name-text">
                <p>{{__('msg.add food')}}</p>
                {{-- <span>0 of 2,104cal</span> --}}
            </div>
        </div>

        <iconify-icon icon="dashicons:arrow-right-alt2" class="customer-training-plan-icon"></iconify-icon>
    </a>
    <a href="{{route('training_center.water')}}" class="customer-training-center-plan-container">
        <div class="customer-training-center-plan-name-container">
            <iconify-icon icon="fluent-emoji-flat:glass-of-milk" class="customer-training-center-plan-name-icon"></iconify-icon>
            <div class="customer-training-center-plan-name-text">
                <p>{{__('msg.water tracker')}}</p>
                {{-- <span>0 of 92oz</span> --}}
            </div>
        </div>

        <iconify-icon icon="dashicons:arrow-right-alt2" class="customer-training-plan-icon"></iconify-icon>
    </a>
</div>
<div class="" style="width:10%;float:right;">
    <a href="{{route('message.chat.admin')}}" class="customer-primary-btn add-friend-btn">
        <iconify-icon icon="mdi:message-reply-outline" class="add-friend-icon" style="display: inline-block"></iconify-icon>
        <p>{{__('msg.chatwithadmin')}}</p>
    </a>
</div>

@endsection
