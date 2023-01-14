@extends('layouts.app')
@section('content')
    <div class="col-md-8 mx-auto">
        <div class="card shadow p-4">
            <h3 class="text-center mb-2">Edit Permission</h3>
            <form action="{{ route('permission.update', $permission->id) }}" method="POST" id="update-permission">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $permission->name) }}">
                </div>

                <div class="float-end mt-4">
                    <a href="{{ route('permission.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\UpdatePermissionRequest', '#update-permission') !!}
@endpush
