@extends('layouts.app')
@section('content')
    <div class="col-md-8 mx-auto">
        <div class="card shadow p-4">
            <h3 class="text-center mb-2">Edit User</h3>
            <form action="{{ route('user.update', $user->id) }}" method="POST" id="edit-user">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}">
                </div>
                <div class="mt-4">
                    <label for="phone">Phone</label>
                    <input type="number" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}">
                </div>
                <div class="mt-4">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}">
                </div>

                <div class="mt-4">
                    <label for="address">Address</label>
                    <textarea name="address" id="" cols="30" rows="5" class="form-control">{{old('address', $user->address)}}</textarea>
                </div>

                <div class="mt-4">
                    <label for="member">Member</label>
                    <select class="form-select" id="member" name="member_id">
                        @foreach ($members as $member)
                            <option value="{{ $member->id }}"  @if (in_array($member->id, $old_members))
                                selected
                            @endif> {{ $member->member_type }} </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <label for="role">Role</label>
                    <select class="form-select" id="role" name="roles">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @if (in_array($role->id, $old_roles))
                                selected
                            @endif> {{ $role->name }} </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
                <div class="mt-4">
                    <label for="password_confirmation">Confirmed Password</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                </div>

                <div class="float-end mt-4">
                    <a href="{{ route('user.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\UpdateUserRequest', '#edit-user') !!}
@endpush
