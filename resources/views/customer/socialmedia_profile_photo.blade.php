@extends('customer.layouts.app_home')

@section('content')
@include('sweetalert::alert')

<!-- The Image Modal -->
<div id="modal01" class="modal-image" onclick="this.style.display='none'">
    <div class="view-media-modal-btns">
        <span class="close-image">&times;</span>
    </div>
    <div class="modal-content-image">
      <img id="img01" style="max-width:100%">
    </div>
  </div>
<!-- End Image Modal -->

<div class="social-media-right-container">
    <p style="text-align:center" class="social-media-user-photos">{{$user->name}}'s Photos</p>
    <div class="social-media-photos-tabs-container">
        <p class="social-media-photos-tab social-media-profiles-tab">Profile Photos</p>
        <p class="social-media-photos-tab social-media-covers-tab">Cover Photos</p>
    </div>

    <div class="social-media-photos-container social-media-profiles-container">
        @forelse ($user_profile_image as $profile)
            @if ($profile->cover_photo)

            @else
                <div class="social-media-photo">
                    <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$profile->profile_image }}" style="max-width:100%;cursor:pointer"
                    onclick="onClick(this)" class="modal-hover-opacity">
                </div>
            @endif
        @empty
        <p>No Profile Photo</p>
        @endforelse
    </div>

    <div class="social-media-photos-container social-media-covers-container">

        @forelse ($user_profile_cover as $cover)
        @if ($cover->profile_image)

        @else
            <div class="social-media-photo">
                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$cover->cover_photo}}" style="max-width:100%;cursor:pointer"
                onclick="onClick(this)" class="modal-hover-opacity">
            </div>
        @endif
        @empty
        <p>No Cover Photo</p>
        @endforelse

    </div>
</div>
@endsection
@push('scripts')

<script>
    $(document).ready(function() {
        $('.social-media-post-header-icon').click(function(){
            $(this).next().toggle()
        })

        $(".social-media-profiles-tab").addClass("social-media-photos-tab-active")

        $(".social-media-covers-container").hide()

        $(".social-media-profiles-tab").click(function(){
            console.log("sdfsadfsdf")
            $(".social-media-covers-container").hide()
            $(".social-media-profiles-container").show()

            $(".social-media-profiles-tab").addClass("social-media-photos-tab-active")
            $(".social-media-covers-tab").removeClass("social-media-photos-tab-active")

        })

        $(".social-media-covers-tab").click(function(){
            $(".social-media-covers-container").show()
            $(".social-media-profiles-container").hide()

            $(".social-media-profiles-tab").removeClass("social-media-photos-tab-active")
            $(".social-media-covers-tab").addClass("social-media-photos-tab-active")

        })
    })

    function onClick(element) {
        document.getElementById("img01").src = element.src;
        document.getElementById("modal01").style.display = "block";
    }
</script>

@endpush
