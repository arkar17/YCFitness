@extends('customer.layouts.app_home')

@section('content')
@include('sweetalert::alert')
<br><br><br><br><br><br><br><br>
<form method="POST"  enctype= multipart/form-data id="form">
    {{-- @csrf --}}
{{-- <form class="modal-body" method="POST" action="{{route('post.store')}}" enctype= multipart/form-data>
    @csrf
    @method('POST') --}}
  <div class="addpost-caption">
    <p>Post Caption</p>
    <textarea placeholder="Caption goes here..." name="caption" id="addPostCaption" class="addpost-caption-input"></textarea>
    test:  <input type="text" class="cp" name="cp">
  </div>

  <div class="addpost-photovideo">

    <span class="selectImage">

        <div class="addpost-photovideo-btn">
            <iconify-icon icon="akar-icons:circle-plus" class="addpst-photovideo-btn-icon"></iconify-icon>
            <p>Photo/Video</p>
            <input type="file" id="addPostInput" name="addPostInput[]" multiple>
        </div>

    </span>

    <div class="addpost-photo-video-imgpreview-container">
    </div>


    </div>
    <button type="submit" class="customer-primary-btn addpost-submit-btn">Post</button>
    {{-- <button type="submit" class="customer-primary-btn addpost-submit-btn">Post</button> --}}
</form>


@endsection
@push('scripts')
<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    $("#form").submit(function(e){

        e.preventDefault();
        let formData = new FormData(form);

        const totalImages = $("#addPostInput")[0].files.length;
        let images = $("#addPostInput")[0];

        for (let i = 0; i < totalImages; i++) {
            formData.append('images' + i, images.files[i]);
        }
        formData.append('totalImages', totalImages);

        var cp = $("input[name=cp]").val();
        var caption=$('#addPostCaption').val();

        $.ajax({
           type:'POST',
           url:"{{route('testing.store')}}",
           data: formData,
            processData: false,
            cache: false,
            contentType: false,
           success:function(data){
              alert('Success');
           }
        });

    });


    });

</script>
@endpush
