@extends('layouts.app')
@section('content')
    <div class="col-md-8 mx-auto">
        <div class="card shadow p-4">
            <h3 class="text-center mb-2">Add Ban Word</h3>

            <form action="{{ route('banwords.store') }}" method="POST" id="create_banword">
                @csrf
                @method('POST')
                <div class="mb-4">
                    <label for="ban_word_english" class="form-label">Ban Word English</label>
                    <input type="text" class="form-control" id="ban_word_english" name="ban_word_english">

                </div>
                <div class="mt-4">
                    <label for="ban_word_myanmar" class="form-label">Ban Word Myanmar</label>
                    <input type="text" class="form-control" id="ban_word_myanmar" name="ban_word_myanmar">
                </div>
                <div class="mt-4">
                    <label for="ban_word_myanglish" class="form-label">Ban Word Myanglish</label>
                    <input type="text" class="form-control" id="ban_word_myanglish" name="ban_word_myanglish">
                </div>
                <div class="float-end mt-4">
                    <a href="{{ route('banwords.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\BanWordsRequest', '#create_banword') !!}
@endpush
