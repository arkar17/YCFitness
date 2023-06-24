@extends('layouts.app')
@section('content')
<style>
    a{
        text-decoration: none !important;
    }
</style>
    <div class="col-md-11 mx-auto">
        <div class="d-flex justify-content-between mb-3">
            <h3 class="text-center mb-0">All Notification</h3>
        </div>

        <div class="col-12 card p-4 mb-5">
           <div class="notis-box-notis-container">
                            @foreach (auth()->user()->notifri->sortByDesc('created_at') as $noti)
                                @if ($noti->report_id != 0 and $noti->notification_status == 1)
                                    <a href="{{ route('admin.view.report', $noti->report_id) }}">
                                        <div class="notis-box-noti-row notis-box-unread-noti">
                                            <img src="{{ asset('img/customer/imgs/report.png') }}" />
                                            <div class="notis-box-noti-row-detail">
                                                <span>{{ $noti->created_at->diffForHumans() }}
                                                </span>
                                                <p>{{ $noti->description }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @elseif($noti->report_id != 0 and $noti->notification_status != 1)
                                    <a href="{{ route('admin.view.report', $noti->report_id) }}">
                                        <div class="notis-box-noti-row notis-box-read-noti">
                                            <img src="{{ asset('img/customer/imgs/report.png') }}" />
                                            <div class="notis-box-noti-row-detail">
                                                <span>{{ $noti->created_at->diffForHumans() }}
                                                </span>
                                                <p>{{ $noti->description }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
        </div>
    </div>
@endsection
