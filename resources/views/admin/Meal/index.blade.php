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

@section('meal-active', 'active')
@section('content')
    <div class="col-md-11 mx-auto">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-center mb-0">All Meals</h2>
            <a href="{{ route('meal.create') }}" class="btn btn-primary align-middle"><i
                    class="fa-solid fa-circle-plus me-2 fa-lg align-middle"></i> <span class="align-middle">Create Meal</span> </a>
        </div>

        <div class="col-12 card p-4 mb-5">
            <table class="table table-striped Datatable " style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Calories</th>
                        <th>Fat</th>
                        <th>Carbohydrates</th>
                        <th>Protein</th>
                        {{-- <th>Meal Plan Type</th> --}}
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
       $(document).ready(function () {
            var i = 1;
            var table = $('.Datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: 'admin/getmeal',
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
                        data: 'calories',
                        name: 'calories'
                    },
                    {
                        data: 'fat',
                        name: 'fat'
                    },
                    {
                        data: 'carbohydrates',
                        name: 'carbohydrates'
                    },
                    {
                        data: 'protein',
                        name: 'protein'
                    },
                    // {
                    //     data: 'meal_plan_type',
                    //     name: 'meal_plan_type'
                    // },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });


            $(document).on('click','.delete', function (e) {
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
                                method: "GET",
                                url: `admin/meal/${id}/delete`
                            }).done(function(res) {
                                table.ajax.reload(null, false);
                            })
                        } else {
                            swal("Your imaginary file is safe!");
                        }
                    });

                })

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
                            var add_url = "{{ route('meal.delete', [':id']) }}";
                            add_url = add_url.replace(':id', id);
                            $.ajax({
                                method: "GET",
                                url: add_url
                            }).done(function(res) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Deleted!'
                                })
                                // console.log("deleted");
                                table.ajax.reload();
                            })
                        } else {
                            swal("Your imaginary file is safe!");
                        }
                    });
            })
        });
    </script>
@endpush
