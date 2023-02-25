@extends('layouts.app')
@include('sweetalert::alert')
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

        td,
        th {
            text-align: center;
            border-bottom: 1px solid #ddd;
            padding: 10px;
        }

        table {
            width: 100%;
            height: 20px;
            overflow-y: auto;
        }





    </style>
@endsection
@section('training-center-active', 'active')

@section('content')
    <div class="col-md-11 mx-auto">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-center mb-0">All Training Group</h2>
            <a href="{{ route('traininggroup.create') }}" class="btn btn-primary align-middle"><i class="fa-solid fa-people-group"></i> <span class="align-middle">Training Group</span> </a>
        </div>


        @for ($i = 0; $i <= $trainingGroup->count()-1; $i++)

            <div class="col-12 card mb-3">
                <h5 class="mt-1 ms-1">Trainer Name - <span class="text-capitalize">{{ $trainingGroup[$i][0]['user']['name'] ?? 'Not Found'}}</span></h5>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Group Name</th>
                            <th>Trainer Name</th>
                            <th>Group Type</th>
                            <th>Member Level</th>
                            <th>Member Type</th>
                            <th>Gender</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 1; ?>

                            @foreach ($trainingGroup[$i] as $group[$i] )

                            <tr>
                                <td>{{ $j++ }}</td>
                                <td>{{ $group[$i]['group_name']}}</td>
                                <td>{{ $group[$i]['user']['name'] ?? "Not Found Data"}}</td>
                                <td>{{ $group[$i]['group_type'] }}</td>
                                <td>{{ $group[$i]['member_type_level'] }}</td>
                                <td>{{ $group[$i]['member_type'] }}</td>
                                <td>{{ $group[$i]['gender'] }}</td>
                                <td>{{ $group[$i]['created_at'] }}</td>
                                <td>
                                    <a href="{{route('traininggroup.show',$group[$i]['id'])}}" class="text-warning mx-1" title="View detail">
                                        <i class="fa-solid fa-circle-info fa-xxl"></i>
                                    </a>
                                    <a href="" class="text-danger mx-1 delete-btn" title="Delete" data-id="{{$group[$i]['id']}}">
                                        <i class="fa-solid fa-trash fa-xxl"></i>
                                    </a>
                                </td>
                            </tr>

                        @endforeach




                    </tbody>
                </table>

            </div>

        @endfor

    </div>
@endsection



@push('scripts')
    <script>
        $(document).ready(function() {


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
                                url: `/admin/traininggroup/${id}`
                            }).done(function(res) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Deleted'
                                })
                                location.reload();
                                console.log("deleted");
                            })
                        }
                    });
            })
        });
    </script>
@endpush
