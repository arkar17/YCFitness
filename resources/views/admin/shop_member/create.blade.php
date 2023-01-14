@extends('layouts.app')
@section('content')
    <div class="col-md-8 mx-auto">
        <div class="card shadow p-4">
            <h3 class="text-center mb-2">Create Shop Member</h3>

            <form action="{{ route('shop-member.store') }}" method="POST" id="create-shop-member">
                @csrf
                <div class="mb-3">
                    <label for="member_type">Member Type</label>
                    <select class="form-select" name="member_type">
                        <option value="level1">Level 1</option>
                        <option value="level2">Level 2</option>
                        <option value="level3">Level 3</option>
                    </select>
                </div>
                <div class="mt-4">
                    <label for="phone">Duration</label>
                    <input type="number" class="form-control" name="duration">
                </div>
                <div class="mt-4">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" name="price">
                </div>
                <div class="mt-4">
                    <label for="count">Count</label>
                    <input type="number" class="form-control" name="count">
                </div>

                <div class="mt-4">
                    <label for="pros">Pros</label>
                    <textarea class="form-control" name="pros" id="" cols="30" rows="5"></textarea>
                </div>
                <div class="mt-4">
                    <label for="cons">Cons</label>
                    <textarea class="form-control" name="cons" id="" cols="30" rows="5"></textarea>
                </div>

                <div class="float-end mt-4">
                    <a href="{{ route('shop-member.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\CreateShopMemberRequest', '#create-shop-member') !!}
@endpush
