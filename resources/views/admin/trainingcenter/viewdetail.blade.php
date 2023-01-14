@extends('layouts.app')
@section('training-center-active', 'active')

@section('content')
<div class="col-md-11 mx-auto">
    <a href="{{route('traininggroup.index')}}" class="btn btn-sm btn-primary"><i class="fa-solid fa-arrow-left-long"></i>&nbsp; Back</a>
    <div class="d-flex justify-content-between mb-3">
        <h2 class="text-center mb-0">Training Group - Detail</h2>

    </div>

    <div class="col-12 card p-4 mb-5">
       {{--<p>{{$training_group->id}}</p> --}}
        <table class="table table-striped Datatable " style="width: 100%">
            <thead>
                <tr class="align-middle">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Member Type</th>
                    <th>Member Level</th>
                    <th>Gender</th>
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
            var i = 1;
            var id = "{{$training_group->id}}";
            console.log(id);
            var table = $('.Datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: `${id}/ssd`,
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'member_name',
                        name: 'member_name'
                    },
                    {
                        data: 'member_phone',
                        name: 'member_phone'
                    },
                    {
                        data: 'member_address',
                        name: 'member_address'
                    },
                    {
                        data: 'membertype',
                        name: 'membertype'
                    },
                    {
                        data: 'member_level',
                        name: 'member_level'
                    },
                    {
                        data: 'member_gender',
                        name: 'member_gender'
                    },

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
                                url: `/admin/trainer/${id}`
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
