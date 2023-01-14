@extends('layouts.app')
@section('content')
    <div class="col-md-8 mx-auto">
        <div class="card shadow p-4">
            <h3 class="text-center mb-2">Edit Trainer</h3>
            <form action="{{ route('trainer.update', $trainer->id) }}" method="POST" id="edit-trainer">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $trainer->name) }}">
                </div>
                <div class="mt-4">
                    <label for="phone">Phone</label>
                    <input type="number" class="form-control" name="phone" value="{{ old('phone', $trainer->phone) }}">
                </div>
                <div class="mt-4">
                    <label class="" for="training_type">Training Type</label> <br>

                    <select class="form-control " name="training_type" id="training_type">
                        <option value="weight_gain" @if ($trainer->training_type == 'weight_gain') selected @endif>
                            Weight Gain
                        </option>
                        <option value="weight_loss" @if ($trainer->training_type == 'weight_loss') selected @endif>
                            Weight Loss
                        </option>
                        <option value="body_beauty" @if ($trainer->training_type == 'body_beauty') selected @endif>
                            Body Beauty
                        </option>
                    </select>


                </div>
                <div class="mt-4">
                    <label for="address">Address</label>
                    <textarea name="address" id="" cols="30" rows="5" class="form-control">{{ $trainer->address }}</textarea>
                </div>

                <div class="mt-4">
                    <label for="role">Role</label>
                    <select class="form-select" id="role" name="role">
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
                    <a href="{{ route('trainer.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\UpdateTrainerRequest', '#edit-trainer') !!}
@endpush
