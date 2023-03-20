@extends('layouts.app')
@section('content')
    <div class="col-md-8 mx-auto">
        <div class="card shadow p-4">
            <h3 class="text-center mb-2">Create Free Videos</h3>
            <form action="{{ route('free_video.store') }}" method="POST" id="video" enctype="multipart/form-data">
                @csrf
                <div class="mt-4">
                    <label for="name">Video Name</label>
                    <input type="text" class="form-control" name="name">
                </div>

                <div class="mt-4">
                    <label for="file">Video</label>
                    <input type="file" class="form-control" name="video">
                </div>

              

                <div class="float-end mt-4">
                    <button type="submit" class="btn btn-primary" onclick="submitForm(this);">Confirm</button>
                    <a href="{{ route('free_video.index') }}" class="btn btn-secondary">Cancel</a>

                </div>
            </form>
        </div>
    </div>

@endsection


@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\FreeVideoRequest', '#video') !!}
@endpush
