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
@section('banwords-active', 'active')
@section('content')

<div class="col-md-11 mx-auto">
    <div class="d-flex justify-content-between mb-3">
        <h2 class="text-center mb-0">Ban Words</h2>
        <a href="{{ route('banwords.create') }}" class="btn btn-primary align-middle">
            <i class="fa-solid fa-circle-plus me-2 fa-lg align-middle"></i> <span class="align-middle">Add Ban Word</span></a>
    </div>

    <div class="col-12 card p-4 mb-5">
        <table class="table table-striped Datatable " style="width: 100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>English</th>
                    <th>Myanmar</th>
                    <th>Myanglish</th>
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
            var i = 1;
            var table = $('.Datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: 'admin/banword/datatable/ssd',
                columns: [
                    {data: 'DT_RowIndex',
                     name: 'DT_RowIndex',
                     orderable: false,
                     searchable: false
                    },
                    {
                        data: 'ban_word_english',
                        name: 'ban_word_english'
                    },
                    {
                        data: 'ban_word_myanmar',
                        name: 'ban_word_myanmar'
                    },
                    {
                        data: 'ban_word_myanglish',
                        name: 'ban_word_myanglish'
                    },
                    {
                        data: 'action',
                        name: 'action'
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

        });
</script>
@endpush
