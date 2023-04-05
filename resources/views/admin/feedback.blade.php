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

@section('feedback-active', 'active')
@section('content')
    <div class="col-md-11 mx-auto">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-center mb-0">All Feedback</h2>
            {{-- <a href="{{ route('feedback.create') }}" class="btn btn-primary align-middle"><i
                    class="fa-solid fa-circle-plus me-2 fa-lg align-middle"></i> <span class="align-middle">Create Meal</span> </a> --}}
        </div>

        <div class="col-12 card p-4 mb-5">
            <table class="table table-striped Datatable " style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>User Name</th>
                        <th>Feedback</th>
                        <th>Roles</th>
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
                ajax: 'getfeedbcak',
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
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'roles',
                        name: 'roles'
                    }
                ]
            });



     

           
        });
    </script>
@endpush
