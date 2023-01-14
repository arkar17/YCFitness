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
        tbody{
            text-transform: capitalize;
        }
        .exportBtn{
            border-radius: 100px;
        }
    </style>
@endsection

@section('transction-active', 'active')
@section('content')
<div class="col-md-12 mx-auto">
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
        <div class="col-md-2 ">
            <button id="filter" class="btn btn-outline-info btn-sm">Filter</button>
            <button id="refresh" class="btn btn-outline-warning btn-sm">Reset</button>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <h2 class="text-center mb-0">Bank Payment Transaction</h2>
            </div>

            <div class="col-12 card p-4 mb-5">
                <table class="table table-striped Datatable" id="bank" style="width: 100%">
                    <thead>
                        <tr class="align-middle">
                            <th>ID</th>
                            <th>Name</th>
                            <th>Payment Type</th>
                            <th>Bank Name</th>
                            <th>Bank Account</th>
                            <th>Account Holder</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>View Detail</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>

<div class="col-md-12 mx-auto">
    <div class="row input-daterange">
        <div class="col-md-5">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-info text-white" id="basic-addon1"><i
                            class="fas fa-calendar-alt"></i></span>
                </div>
                <input type="date" class="form-control" name="start_date" id="start_datewallet" placeholder="Start Date" >
            </div>
        </div>
        <div class="col-md-5">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-info text-white" id="basic-addon1"><i
                            class="fas fa-calendar-alt"></i></span>
                </div>
                <input type="date" class="form-control" name="end_date" id="end_datewallet" placeholder="End Date" >
            </div>
        </div>
        <div class="col-md-2">
            <button id="filterwallet" class="btn btn-outline-info btn-sm">Filter</button>
            <button id="refreshwallet" class="btn btn-outline-warning btn-sm">Reset</button>
        </div>
    </div>


    <div class="row">

        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <h2 class="text-center mb-0">E-wallet Payment Transaction</h2>
            </div>

            <div class="col-12 card p-4 mb-5">
                <table class="table table-striped Datatablewallet" id="wallet" style="width: 100%">
                    <thead>
                        <tr class="align-middle">
                            <th>ID</th>
                            <th>Name</th>
                            <th>Payment Type</th>
                            <th>Payment Name</th>
                            <th>Account Name</th>
                            <th>Phone No.</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>View Detail</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>


@endsection



@push('scripts')
    <script>

        $(document).ready(function() {
            // var $j = jQuery.noConflict();
            // $j("#start_date").datepicker({
            //     "dateFormat": "yy-mm-dd"
            // });
            // $j("#end_date").datepicker({
            //     "dateFormat": "yy-mm-dd"
            // });
            // $('#start_date').datepicker({
            //     todayBtn:'linked',
            //     format:'yyyy-mm-dd',
            //     autoclose:true
            // });
            load_data();

            function load_data(start_date = '', end_date = ''){

                    var i = 1;
                    var table = $('.Datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                                url:'{{ route("banktransaction") }}',
                                data:{start_date:start_date, end_date:end_date}
                            },
                    columns: [
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'user.name',
                            name: 'user.name'
                        },
                        {
                            data: 'payment_type',
                            name: 'payment_type'
                        },
                        {
                            data: 'payment_name',
                            name: 'payment_name'
                        },
                        {
                            data: 'bank_account_number',
                            name: 'bank_account_number'
                        },
                        {
                            data: 'bank_account_holder',
                            name: 'bank_account_holder'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        }
                    ],
                    dom:'lBfrtip',
                    buttons: [
                        { extend: 'pdfHtml5',text:'Export PDF', className:"bg-primary text-white rounded p-1 mt-1", title:'Bank payment transaction', exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }},
                        { extend: 'excelHtml5', text: ' Export Excel', className: "bg-primary text-white rounded p-1 mt-1", title:'Bank payment transaction', exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                    }},
                    ],


                });
            }

            load_data_wallet();
            function load_data_wallet(start_date = '', end_date = ''){
                var i = 1;
                var wallettable = $('.Datatablewallet').DataTable({
                    select: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,

                    ajax: {
                                url:'{{ route("wallettransaction") }}',
                                data:{start_date:start_date, end_date:end_date}
                            },
                    columns: [
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'user.name',
                            name: 'user.name'
                        },
                        {
                            data: 'payment_type',
                            name: 'payment_type'
                        },
                        {
                            data: 'payment_name',
                            name: 'payment_name'
                        },
                        {
                            data: 'account_name',
                            name: 'account_name'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        }
                    ],
                    dom:'lBfrtip',
                    buttons: [
                        { extend: 'pdfHtml5',text:'Export PDF', className:"bg-primary text-white rounded p-1 mt-1", title:'E-wallet payment transaction', exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]}},
                        { extend: 'excelHtml5', text: ' Export Excel', className: "bg-primary text-white rounded p-1 mt-1", title:'E-wallet payment transaction', exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6 ]}},
                    ],

                });
            }



            $('#filter').click(function(){
                var start_date = $('#start_date').val();
                console.log(start_date);
                var end_date = $('#end_date').val();
                console.log(end_date);

                if(start_date != '' &&  end_date != '')
                {
                $('.Datatable').DataTable().destroy();
                load_data(start_date, end_date);
                }
                else
                {
                alert('Both Date is required');
                }
            });

            $('#refresh').click(function(){
            $('#start_date').val('');
            $('#end_date').val('');
            $('.Datatable').DataTable().destroy();
            load_data();
            });


            //wallet
            $('#filterwallet').click(function(){
                var start_date = $('#start_datewallet').val();
                console.log(start_date);
                var end_date = $('#end_datewallet').val();
                console.log(end_date);

                if(start_date != '' &&  end_date != '')
                {
                $('.Datatablewallet').DataTable().destroy();
                load_data_wallet(start_date, end_date);
                }
                else
                {
                alert('Both Date is required');
                }
            });

            $('#refreshwallet').click(function(){
            $('#start_datewallet').val('');
            $('#end_datewallet').val('');
            $('.Datatablewallet').DataTable().destroy();
            load_data_wallet();
            });
        });

    </script>
@endpush
