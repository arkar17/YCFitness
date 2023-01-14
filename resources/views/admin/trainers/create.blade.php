@extends('layouts.app')
@section('content')
    <div class="col-md-8 mx-auto">
        <div class="card shadow p-4">
            <h3 class="text-center mb-2">Create Trainer</h3>

            <form action="{{ route('trainer.store') }}" method="POST" id="create-trainer">
                @csrf
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name">
                </div>
                <div class="mt-4">
                    <label for="phone">Phone</label>
                    <input type="number" class="form-control" name="phone">
                </div>
                <div class="mt-4">
                    <label class="" for="training_type">Training Type</label> <br>
                    <select class="form-control " name="training_type" id="training_type">
                        <option value="weight_gain">Weight Gain</option>
                        <option value="weight_loss">Weight Loss</option>
                        <option value="body_beauty">Body Beauty</option>
                    </select>
                </div>
                <div class="mt-4">
                    <label for="address">Address</label>
                    <textarea name="address" id="" cols="30" rows="5" class="form-control"></textarea>
                </div>
                <div class="mt-4">
                    <label for="role">Roles</label>
                    <select class="form-select" name="role" id="role">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}"> {{ $role->name }} </option>
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
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\CreateTrainerRequest', '#create-trainer') !!}
@endpush
