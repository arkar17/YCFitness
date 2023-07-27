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
@section('role-active', 'active')
@section('content')
    <div class="col-md-11 mx-auto">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-center mb-0">All Reports</h2>
        </div>

        <div class="col-12 card p-4 mb-5">
            <table class="table table-striped Datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Post ID</th>
                        <th>Comment ID</th>
                        <th>Description</th>
                        <th>Reported Date</th>
                        <th>Report Count</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

        </div>

        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-center mb-0">Action Reports</h2>
        </div>

        <div class="col-12 card p-4 mb-5">
            <table class="table table-striped action-report" style="width: 100%">
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Post ID</th>
                        <th>Comment ID</th>
                        <th>Action Message</th>
                        <th>Report Description</th>
                        <th>Reported Date</th>
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
            $('#accept').click(function(e){
                e.preventDefault()
                Swal.fire({
                            text: 'This post goes Against Our Community and Guidelines.',
                            timerProgressBar: true,
                            showCloseButton: true,
                            showCancelButton: true,
                            confirmButtonText:'Delete Post',
                            cancelButtonText:'No',
                            icon: 'warning',
                        }).then((result) => {
                            var report_id=$(this).data('id');
                            console.log(report_id);
                        if (result.isConfirmed) {
                        var add_url = "{{ route('admin.accept.report', [':report_id']) }}";
                            add_url = add_url.replace(':report_id', report_id);
                            $.ajax({
                                method: "GET",
                                url: add_url,
                                success:function(data){
                                    Swal.fire({
                                            text: data.success,
                                            icon: 'success',
                                        }).then((result) => {
                                            window.location.href = "{{ route('report.index') }}";
                                        })
                                }
                            })
                        }else{
                            console.log('not delete');
                        }
                        })
            })
        })

        $(function() {
           var table =  $('.Datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: 'admin/report/datatable/ssd',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'post_id',
                        name: 'post_id'
                    },
                    {
                        data: 'comment_id',
                        name: 'comment_id'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'rp_count',
                        name: 'rp_count'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });

            var table =  $('.action-report').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: 'admin/action-report/datatable/ssd',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'post_id',
                        name: 'post_id'
                    },
                    {
                        data: 'comment_id',
                        name: 'comment_id'
                    },
                    {
                        data: 'action_message',
                        name: 'action_message'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });

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

            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                swal({
                        text: "Are you sure you want to delete?",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                method: "DELETE",
                                url: `/admin/report/${id}`
                            }).done(function(res) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Deleted'
                                })
                                table.ajax.reload();
                                console.log("deleted");
                            })
                        } else {
                            swal("Your imaginary file is safe!");
                        }
                    });
            })
        });

       
    </script>
@endpush
