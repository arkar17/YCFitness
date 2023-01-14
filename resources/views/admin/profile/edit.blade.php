@extends('layouts.app')

@section('title', 'Edit Profile')

@section('css')
    <style>

    </style>
@endsection

@section('content')
    <div class="container">
        <div class="col-md-8 mx-auto">
            <div class="card shadow py-2 mb-2 position-relative">
                <div class="d-md-flex">
                    <div class="col-md-4 text-md-end text-center">
                        <img src="{{ asset('img/avatar.jpg') }}" width="200"
                            class="img-fluid p-1  border border-secondary rounded-circle preview_img" alt="">
                    </div>
                    <div class="col-md-7  py-4 px-5 text-md-start text-center">
                        <h2 class="mb-3">
                            {{ $user->name }}
                        </h2>
                        <p class="mb-1"><i class="fa-solid fa-user-shield me-2"></i>Admin</p>
                        <p class="text-info mb-1">
                            <i class="fa-solid fa-envelope text-dark me-2"></i>
                            {{ $user->email }}
                        </p>
                        <p class="mb-1"><i class="fa-solid fa-phone me-2"></i>{{ $user->phone ?? '----' }}</p>
                    </div>
                </div>

            </div>

            <div class="card shadow p-3 mb-2">
                <form action="{{ route('admin-update', auth()->user()->id) }}" method="POST" id="update-admin"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="d-md-flex flex-md-wrap justify-content-md-center gap-2">
                        <div class="col-md-5">
                            <div class="mb-2">
                                <label for="name">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" id="name" value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-2">
                                <label for="email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" id="email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-2">
                                <label for="phone">Phone</label>
                                <input type="phone" class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" id="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-2">
                                <label for="profile_img">Image</label>
                                <input type="file" class="form-control" name="image" id="profile_img">
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="mb-2">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-2">
                                <label for="password_confirmation">Confirmed Password</label>
                                <input type="password" class="form-control" name="password_confirmation"
                                    id="password_confirmation">
                            </div>
                        </div>
                    </div>

                    <div class="mt-2 text-center">
                        <a href="{{ route('admin-profile') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </div>
                </form>
            </div>



        </div>
    </div>
@endsection

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\UpdateAdminProfileRequest', '#update-admin') !!}
    <script>
        $(document).ready(function() {
            $('#profile_img').on('change', function() {
                let file_length = document.getElementById('profile_img').files.length;

                for (let i = 0; i < file_length; i++) {
                    $('.preview_img').html(
                        `<img src="${URL.createObjectURL(event.target.files[i])}" class="img-fluid me-2" width="100" />`
                    );
                    // $('.preview_img').html("");
                    // $('.preview_img').append(
                    //     `<img src="${URL.createObjectURL(event.target.files[i])}" class="img-fluid me-2" width="100" />`
                    // );
                }
            });
        });
    </script>
@endpush
