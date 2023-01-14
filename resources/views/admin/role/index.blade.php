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
            <h2 class="text-center mb-0">All Roles</h2>
            <a href="{{ route('role.create') }}" class="btn btn-primary align-middle"><i
                    class="fa-solid fa-circle-plus me-2 fa-lg align-middle"></i> <span class="align-middle">Create Role</span> </a>
        </div>

        <div class="col-12 card p-4 mb-5">
            <table class="table table-striped Datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Permissions</th>
                        <th>Created At</th>
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
        $(function() {
           var table =  $('.Datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: 'admin/role/datatable/ssd',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'permissions',
                        name: 'permissions'
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
                                url: `admin/role/${id}`
                            }).done(function(res) {
                                console.log("deleted");
                                table.ajax.reload();
                            })
                        } else {
                            swal("Your imaginary file is safe!");
                        }
                    });
            })
            // $(document).on('click', '.create_trainer', function(e) {
            //     e.preventDefault();
            //     var id = $(this).data('id');
            //     Swal.fire({
            //         title: 'Create Trainer',
            //         html: `
        //                <div class="text-start">
        //                 <form action="" method="POST">
        //                     <div class="mb-3">
        //                         <label class="form-label">Name</label>
        //                         <input class="form-control @error('name') is-invalid @enderror" name="name">
        //                     </div>
        //                     <div class="mb-3">
        //                         <label class="form-label">Phone</label>
        //                         <input class="form-control" name="phone">
        //                     </div>
        //                     <div class="mb-3">
        //                         <label class="form-label">Address</label>
        //                         <textarea class="form-control" col="10" row="5">
        //                         </textarea>
        //                     </div>
        //                     <div class="mb-3">
        //                         <label class="form-label">Training Type</label>
        //                         <select class="select2">
        //                             <option selected>Open this select menu</option>
        //                             <option value="1">One</option>
        //                             <option value="2">Two</option>
        //                             <option value="3">Three</option>
        //                         </select>
        //                     </div>
        //                     <select class="select2">
        //                             <option selected>Open this select menu</option>
        //                             <option value="1">One</option>
        //                             <option value="2">Two</option>
        //                             <option value="3">Three</option>
        //                         </select>
        //                 </form>
        //                 </div>
        //         `
            //     });
            // });


        });
    </script>
@endpush
