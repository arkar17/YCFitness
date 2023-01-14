@extends('layouts.app')

@section('dashboard-active', 'active')
@section('content')
    <p class="fs-6 fw-bold">Number of members</p>
    <div class="d-flex justify-content-around align-items-center flex-wrap">
        <div style="width:150px;height:150px;" class="bd-white shadow rounded-circle">
            <img src="{{ asset('image/free.png') }}" style="width: 50px;height:50px;margin-top:16px;" class="d-block mx-auto">
            <p class="fw-bold text-center mb-0">Free</p>
            <p class="fw-bold text-center fs-3 text-dark">{{$free_user}}</p>
        </div>
        <div style="width:150px;height:150px;" class="bd-white shadow rounded-circle">
            <img src="{{ asset('image/platinum.png') }}" style="width: 50px;height:50px;margin-top:16px;"
                class="d-block mx-auto">
            <p class="fw-bold text-center mb-0">Platinum</p>
            <p class="fw-bold text-center fs-3 text-dark">{{$platinum_user}}</p>
        </div>
        <div style="width:150px;height:150px;" class="bd-white shadow rounded-circle">
            <img src="{{ asset('image/gold.png') }}" style="width: 50px;height:50px;margin-top:16px;"
                class="d-block mx-auto">
            <p class="fw-bold text-center mb-0">Gold</p>
            <p class="fw-bold text-center fs-3 text-dark">{{$gold_user}}</p>
        </div>
        <div style="width:150px;height:150px;" class="bd-white shadow rounded-circle">
            <img src="{{ asset('image/diamond.png') }}" style="width: 50px;height:50px;margin-top:16px;"
                class="d-block mx-auto">
            <p class="fw-bold text-center mb-0">Diamond</p>
            <p class="fw-bold text-center fs-3 text-dark">{{$diamond_user}}</p>
        </div>
        <div style="width:150px;height:150px;" class="bd-white shadow rounded-circle">
            <img src="{{ asset('image/ruby.png') }}" style="width: 50px;height:50px;margin-top:16px;"
                class="d-block mx-auto">
            <p class="fw-bold text-center mb-0">Ruby</p>
            <p class="fw-bold text-center fs-3 text-dark">{{$ruby_user}}</p>
        </div>
        <div style="width:150px;height:150px;" class="bd-white shadow rounded-circle">
            <img src="{{ asset('image/rubyPremium.png') }}" style="width: 50px;height:50px;margin-top:16px;"
                class="d-block mx-auto">
            <p class="fw-bold text-center mb-0">Ruby Premium</p>
            <p class="fw-bold text-center fs-3 text-dark">{{$rubyp_user}}</p>
        </div>
    </div>
    <div class="my-5">
        <form action="{{ route('member-upgraded-history') }}" class="d-flex justify-content-center gap-5" method="POST">
            @csrf
            <div>
                <p class="fs-6 fw-bold">Members who upgraded from</p>
                <select style="width: 170px;height:40px" class="ms-4 ps-1 rounded from_member" name="from_member">
                    @foreach ($member_plans as $member_plan)
                        <option value="{{ $member_plan->id }}">{{ $member_plan->member_type }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <p class="fs-6 fw-bold">Members who upgraded to</p>
                <select style="width: 170px;height:40px" class="ms-4 ps-1 rounded to_member" name="to_member">
                    @foreach ($member_plans as $member_plan)
                        <option value="{{ $member_plan->id }}">{{ $member_plan->member_type }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <p class="mb-3"></p>
                <button type="submit" class="btn btn-primary mt-4">Search</button>
            </div>
        </form>
    </div>

    <div style="max-width: 700px;max-height:400px;" class="mx-auto">

        <canvas id="chart1"></canvas>

    </div>

    <form action="{{route('member-upgraded-history')}}" method="POST">
        @csrf
        <div class="d-flex mt-5 justify-content-center gap-5">
            <p class="fs-6 fw-bold">Number of members in</p>
            <select style="width: 170px;height:40px" class="ms-4 ps-1 rounded" name="member_type">
                @foreach ($member_plans as $member_plan)
                            <option value="{{ $member_plan->id }}">{{ $member_plan->member_type }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <div style="max-width: 700px;max-height:400px;" class="mx-auto">
        <canvas id="chart2"></canvas>
    </div>
@endsection

@push('scripts')
    <script>
        let members = @json($members);
        let memberArr = Object.values(members)

        var months = JSON.parse('{!! json_encode($months) !!}');
        var monthCount = JSON.parse('{!! json_encode($monthCount) !!}');

        var month_filter = JSON.parse('{!! json_encode($months_filter) !!}');
        var monthCount_filter = JSON.parse('{!! json_encode($monthCount_filter) !!}');
        console.log(months);

        // $(document).ready(function() {
        //     let from_member = $('.from_member').val();
        //     $('.from_member').on('change', function() {
        //         from_member =  $('.from_member').val();
        //     });
        //     console.log('Outer',from_member);

        // });


        const labels1 = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        const data1 = {
            labels: months,
            datasets: [{
                label: 'Member upgraded history',
                backgroundColor: '#222E3C',
                borderColor: '#222E3C',
                data: monthCount,
            }]
        };

        const config1 = {
            type: 'bar',
            data: data1,
            options: {}
        };

        const myChart1 = new Chart(
            document.getElementById('chart1'),
            config1
        );

        const labels2 = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
        ];


        const data2 = {
            labels: month_filter,
            datasets: [{
                label: 'Number of Member',
                backgroundColor: '#222E3C',
                borderColor: '#222E3C',
                data: monthCount_filter
            }]
        };


        const config2 = {
            type: 'bar',
            data: data2,
            options: {}
        };

        const myChart2 = new Chart(
            document.getElementById('chart2'),
            config2
        );
    </script>
@endpush
