@extends('layouts.app')

@section('styles')
    <style>
        .swal2-popup {
            display: none;
            position: relative;
            box-sizing: border-box;
            grid-template-columns: minmax(0, 100%);
            width: 40em !important;
            max-width: 100%;
            padding: 0 0 1.25em;
            border: none;
            border-radius: 5px;
            background: #fff;
            color: #545454;
            font-family: inherit;
            font-size: 1rem;
        }

        .form-label {
            font-size: 14px;
        }
    </style>
@endsection
@section('member-active', 'active')
@section('content')
    {{-- @if (Session::has('success'))
        <script>
    Toast.fire({
        icon: 'success',
        title: '{{ Session::get('success') }}'
    })
        </script>
    @endif --}}
        <div class="col-md-11 mx-auto">
            <div class="d-flex justify-content-between mb-3">
                <h2 class="text-center mb-0">All Members</h2>
            </div>
            <div class="row input-daterange">
                <div class="col-md-5">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-info text-white" id="basic-addon1"><i
                                    class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="date" class="form-control" name="start_date" id="start_date" placeholder="Start Date" >
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-info text-white" id="basic-addon1"><i
                                    class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="date" class="form-control" name="end_date" id="end_date" placeholder="End Date" >
                    </div>
                </div>
                <div class="col-md-2 d-flex justify-content-center">
                    <div>
                        <button id="filter" class="btn btn-outline-info btn-sm">Filter</button>
                        <button id="refresh" class="btn btn-outline-warning btn-sm">Reset</button>
                    </div>
                </div>
            </div>


            <div class="col-12 card p-4 mb-5">

                <table class="table table-striped datatable" style="width: 100%">
                    <thead>
                        <tr class="align-middle">
                        <th>No</th>
                        <th>Name</th>
                        <th>Phone </th>
                        <th>Member type</th>
                        <th>Level</th>
                        <th>Started Date</th>
                        <th>Expired Date</th>
                        <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>

        <div class="col-md-11 mx-auto">
            <div class="d-flex justify-content-between mb-3">
                <h2 class="text-center mb-0">Declined Members</h2>
            </div>
            <div class="row input-daterange">
                <div class="col-md-5">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-info text-white" id="basic-addon1"><i
                                    class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="date" class="form-control" name="start_date_declined" id="start_date_declined" placeholder="Start Date" >
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-info text-white" id="basic-addon1"><i
                                    class="fas fa-calendar-alt"></i></span>
                        </div>
                        <input type="date" class="form-control" name="end_date_declined" id="end_date_declined" placeholder="End Date" >
                    </div>
                </div>
                <div class="col-md-2 d-flex justify-content-center">
                    <div>
                        <button id="filterwallet" class="btn btn-outline-info btn-sm">Filter</button>
                        <button id="refreshwallet" class="btn btn-outline-warning btn-sm">Reset</button>
                    </div>
                </div>
            </div>

            <div class="col-12 card p-4 mb-5">
                <table class="table table-striped datatabledecline"  style="width: 100%">
                    <thead>
                        <tr class="align-middle">
                            <th>No</th>
                            <th>Name</th>
                            <th>Phone </th>
                            <th>Member type</th>
                            <th>Level</th>
                            <th>Started Date</th>
                            <th>Expired Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            load_member_data();
            function load_member_data(start_date = '', end_date = ''){
              var table = $('.datatable').DataTable({
                // dom: 'Bfrtip',
                "oSearch": {"bSmart": false},
                processing: true,
                serverSide: true,
                // ajax: 'admin/user_member/datatable/ssd',
                ajax: {
                        url:'{{ route("admin/user_member/datatable/ssd") }}',
                        data:{start_date:start_date, end_date:end_date}
                    },
                columns: [
                    {data: 'DT_RowIndex',
                     name: 'DT_RowIndex',
                     orderable: false,
                     searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'member_type',
                        name: 'member_type'
                    },
                    {
                        data: 'membertype_level',
                        name: 'membertype_level'
                    },
                    {
                        data: 'from_date',
                        name: 'from_date'
                    },
                    {
                        data: 'to_date',
                        name: 'to_date'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });
        }

        load_declined_member_data();
        function load_declined_member_data(start_date_declined = '', end_date_declined = ''){
            var declined = $('.datatabledecline').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                        url:'admin/user_member/datatable_decline/ssd',
                        data:{start_date_declined:start_date_declined, end_date_declined:end_date_declined}
                    },
               
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'member_type',
                        name: 'member_type'
                    },
                    {
                        data: 'membertype_level',
                        name: 'membertype_level'
                    },
                    {
                        data: 'from_date',
                        name: 'from_date'
                    },
                    {
                        data: 'to_date',
                        name: 'to_date'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                // dom: 'Bfrtip',
                // buttons: [
                //     'excel', 'pdf'
                // ]
            });
        }

            const Toast = Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            @if (Session::has('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ Session::get('success') }}'
                })
            @endif

            $('#filter').click(function(){
                var start_date = $('#start_date').val();
                console.log(start_date);
                var end_date = $('#end_date').val();
                console.log(end_date);

                if(start_date != '' &&  end_date != '')
                {
                $('.datatable').DataTable().destroy();
                load_member_data(start_date, end_date);
                }
                else
                {
                alert('Both Date is required');
                }
            });

            $('#refresh').click(function(){
            $('#start_date').val('');
            $('#end_date').val('');
            $('.datatable').DataTable().destroy();
            load_member_data();
            });


            //wallet
            $('#filterwallet').click(function(){
                var start_date_declined = $('#start_date_declined').val();
                console.log(start_date_declined);
                var end_date_declined = $('#end_date_declined').val();
                console.log(end_date_declined);

                if(start_date_declined != '' &&  end_date_declined != '')
                {
                $('.datatabledecline').DataTable().destroy();
                load_declined_member_data(start_date_declined, end_date_declined);
                }
                else
                {
                alert('Both Date is required');
                }
            });

            $('#refreshwallet').click(function(){
            $('#start_date_declined').val('');
            $('#end_date_declined').val('');
            $('.datatabledecline').DataTable().destroy();
            load_declined_member_data();
            });

        })
    </script>
@endpush
