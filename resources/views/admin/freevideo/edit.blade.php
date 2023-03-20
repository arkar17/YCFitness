@extends('layouts.app')
@section('content')
    <div class="col-md-8 mx-auto">
        <div class="card shadow p-4">
            <h3 class="text-center mb-2">Edit Free Videos</h3>
            <form action="{{ route('free_video.update',$data->id) }}" method="POST" id="free-video" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mt-4">
                    <label for="name">Video Name</label>
                    <input type="text" class="form-control" name="name" value="{{$data->name}}">
                </div>

                {{-- <div class="input-group mb-3">
                    <label class="input-group-text"> Upload video</label>
                    <input type="file" class="form-control" name="video" id="videoUpload">
                    <input type="input-group-text" value="{{ $data->video }}" disabled>
                    <input type="hidden" name="videoTime" value="" class="video-duration">
                </div> --}}

                <label for="video" class="control-label mb-1" >Current Video</label>  
                  
                  <video class= "w-25" style="height:25%;">
                    <source src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/free_video/{{$data->video}}" type="video/mp4">
                  </video>
                      <input type="file" name="video" class="form-control form-control-file @error('video') is-invalid @enderror" id="video45">
                      <input type="hidden" name="video" value="{{ old('video') ?? $data->video}}">
                      {{-- <span class="text-danger help-inline">@error('video'){{$message}}@enderror</span> --}}

                {{-- <div class="mt-4">
                    <label for="file">Video</label>
                    <input type="file" class="form-control" name="video" id="videoUpload" value="{{ $data->video }}">
                    <input type="input-group-text" value="{{ $data->video }}" hidden>
                </div> --}}


                {{-- <div class="mb-3">
                    <label class="input-group-text"> Upload video</label>
                    <input type="file" class="form-control" name="video" id="videoUpload">
                    <input type="input-group-text" value="{{ $data->video }}" disabled>
                </div> --}}

                {{-- <div class="input-group mb-3">
                    <label class="input-group-text"> Upload video</label>
                    <input type="file" class="form-control" name="video" id="videoUpload">
                    <input type="input-group-text" value="{{ $data->video }}" disabled>
                </div> --}}

                <div class="float-end mt-4">
                    <button type="submit" class="btn btn-primary" onclick="submitForm(this);">Confirm</button>
                    <a href="{{ route('free_video.index') }}" class="btn btn-secondary">Cancel</a>

                </div>
            </form>
        </div>
    </div>

@endsection


@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\FreeVideoRequest', '#free-video') !!}
@endpush
