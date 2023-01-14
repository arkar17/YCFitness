@extends('layouts.app')

@section('title', 'Profile')

@section('styles')
    <style>
        .profile-edit-icn {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="col-md-8 mx-auto">

            <div class="card shadow py-2 mb-2 position-relative">
                <div class="d-md-flex">
                    <div class="col-md-4 text-md-end text-center">
                        <img src="{{ asset('img/avatar.jpg') }}" width="200"
                            class="img-fluid p-1  border border-secondary rounded-circle" alt="">
                    </div>
                    <div class="col-md-7  py-4 px-5 text-md-start text-center">
                        <h2 class="mb-3">
                            {{ auth()->user()->name }}
                        </h2>
                        <p class="mb-1"><i class="fa-solid fa-user-shield me-2"></i>Admin</p>
                        <p class="text-info mb-1">
                            <i class="fa-solid fa-envelope text-dark me-2"></i>
                            {{ auth()->user()->email }}
                        </p>
                        <p class="mb-1"><i class="fa-solid fa-phone me-2"></i>{{ auth()->user()->phone }}</p>
                    </div>

                    <div class="col-md-1 d-md-block d-none">
                        <a href="{{ route('admin-edit') }}" class="text-decoration-none text-dark" title="Edit Profile"><i
                                class="fa-solid fa-pencil fa-shake  me-2"></i></a>
                    </div>
                </div>


                {{-- will appear when it becomes small screen  --}}
                <div class="profile-edit-icn d-md-none">
                    <a href="{{ route('admin-edit', auth()->user()->id) }}" class="text-decoration-none text-dark"
                        title="Edit Profile"><i class="fa-solid fa-pencil fa-shake  me-2"></i></a>
                </div>

            </div>


            <!-- Logout Button -->
            {{-- <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-block btn-primary">Logout</button>
            </form> --}}

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })


            @if (Session::has('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ Session::get('success') }}"
                })
            @endif
        });
    </script>
@endpush
