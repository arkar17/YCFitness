@extends('layouts.app')
@section('content')
    <div class="col-md-8 mx-auto">
        <div class="card shadow p-4">
            <h3 class="text-center mb-2">Create Member Type</h3>

            <form action="{{ route('member.store') }}" method="POST" id="create_member_type">
                @csrf
                @method('POST')
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                <div class="mb-4">
                    <label for="member_type" class="form-label">Member Type</label>
                    <input type="text" class="form-control" id="member_type" name="member_type">

                </div>
                <div class="mt-4">
                    <label for="duration" class="form-label">Duration(Month)</label>
                    <input type="number" class="form-control" id="duration" name="duration">
                </div>
                <div class="mt-4">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price">
                </div>
                <div class="mt-4">
                    <label for="price" class="form-label">Pros</label>
                    <textarea  class="form-control" id="price" name="pros"></textarea>
                </div>
                <div class="mt-4">
                    <label for="price" class="form-label">Cons</label>
                    <textarea class="form-control" id="price" name="cons"></textarea>
                </div>
                <div class="mt-4">
                    <label for="price" class="form-label">Role</label>
                    <select class="form-control " name="role_id">
                        @foreach ($roles as $role)
                        <option value="{{$role->id}}">{{$role->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="float-end mt-4">
                    <a href="{{ route('member.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\CreateMemberRequest', '#create_member_type') !!}
@endpush
