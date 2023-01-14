@extends('layouts.app')
@section('content')
    <div class="col-md-8 mx-auto">
        <div class="card shadow p-4">
            <h3 class="text-center mb-2">Edit Shop Member</h3>
            <form action="{{ route('shop-member.update', $shop_member->id) }}" method="POST" id="edit-shop-member">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="member_type">Member Type</label>
                    <select class="form-select" name="member_type">
                        <option value="level1" @if ($shop_member->member_type == 'level1') selected @endif>Level 1</option>
                        <option value="level2" @if ($shop_member->member_type == 'level2') selected @endif>Level 2</option>
                        <option value="level3" @if ($shop_member->member_type == 'level3') selected @endif>Level 3</option>
                    </select>
                </div>
                <div class="mt-4">
                    <label for="phone">Duration</label>
                    <input type="number" class="form-control" name="duration" value="{{ $shop_member->duration }}">
                </div>
                <div class="mt-4">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" name="price" value="{{ $shop_member->price }}">
                </div>
                <div class="mt-4">
                    <label for="count">Count</label>
                    <input type="number" class="form-control" name="count" value="{{ $shop_member->post_count }}">
                </div>
                <div class="mt-4">
                    <label for="pros">Pros</label>
                    <textarea class="form-control" name="pros" id="" cols="30" rows="5">{{ $shop_member->pros }}</textarea>
                </div>
                <div class="mt-4">
                    <label for="cons">Cons</label>
                    <textarea class="form-control" name="cons" id="" cols="30" rows="5">{{ $shop_member->cons }}</textarea>
                </div>

                <div class="float-end mt-4">
                    <a href="{{ route('shop-member.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\UpdateShopMemberRequest', '#edit-shop-member') !!}
@endpush
