@extends('layouts.app')
@section('social-report-active', 'active')

@section('content')
<a href="{{route('report.index')}}" class="btn btn-sm btn-primary"><i class="fa-solid fa-arrow-left-long"></i>&nbsp; Back</a>
<div class="container d-flex justify-content-center">
    <div class="social-media-right-container">
        <div class="social-media-posts-parent-container">
            <div class="social-media-post-container">
                <div class="social-media-post-header">
                    <div class="social-media-post-name-container">
                        <a href="{{route('socialmedia.profile',$report->post->user_id)}}" style="text-decoration:none">
                            <?php $profile=$report->post->user->profiles->first();
                                $profile_id=$report->post->user->profile_id;
                                 $img=$report->post->user->profiles->where('id',$profile_id)->first();
                            ?>
                            @if ($img==null)
                                <img class="nav-profile-img" src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                            @else
                                <img class="nav-profile-img" src="{{asset('storage/post/'.$img->profile_image)}}"/>
                            @endif
                        </a>
                        <div class="social-media-post-name">
                            <a href="{{route('socialmedia.profile',$report->post->user_id)}}" style="text-decoration:none">
                                <p>{{$report->post->user->name}}</p>
                            </a>
                            <span>{{ \Carbon\Carbon::parse($report->post->created_at)->format('d M Y , g:i A')}}</span>

                            <div style="margin-left: 300px">
                                <button class="btn btn-primary" id="accept" data-id="{{$report->id}}"><i class="fa fa-check" ></i>&nbsp;&nbsp;Accept</button>
                                <button class="btn btn-secondary" id="decline" data-id="{{$report->id}}"><i class="fa fa-times"></i>&nbsp;&nbsp;Decline</button>
                            </div>
                        </div>
                    </div>

                    {{-- <iconify-icon icon="bi:three-dots-vertical" class="social-media-post-header-icon"></iconify-icon> --}}

                    <div class="post-actions-container">
                        <a href="#" style="text-decoration:none" class="post_save" id="{{$report->post->id}}">
                            <div class="post-action">
                                <iconify-icon icon="bi:save" class="post-action-icon"></iconify-icon>
                                @php
                                    $already_save=auth()->user()->user_saved_posts->where('post_id',$report->post->id)->first();
                                @endphp

                                @if ($already_save)
                                    <p class="save">Unsave</p>
                                @else
                                    <p class="save">Save</p>
                                    @endif
                            </div>
                        </a>
                        @if ($report->post->user->id == auth()->user()->id)

                            <a id="edit_post" data-id="{{$report->post->id}}" data-bs-toggle="modal" >
                                <div class="post-action">
                                    <iconify-icon icon="material-symbols:edit" class="post-action-icon"></iconify-icon>
                                    <p>Edit</p>
                                </div>
                            </a>
                            <a id="delete_post" data-id="{{$report->post->id}}">
                                <div class="post-action">
                                <iconify-icon icon="material-symbols:delete-forever-outline-rounded" class="post-action-icon"></iconify-icon>
                                <p>Delete</p>
                                </div>
                            </a>
                        @else
                        <div class="post-action" id="report" data-id="{{$report->post->id}}">
                            <iconify-icon icon="material-symbols:report-outline" class="post-action-icon"></iconify-icon>
                            <p>Report</p>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="social-media-content-container">
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                        <div>
                            &nbsp;&nbsp;{{$report->description}}
                        </div>
                      </div>
                    @if ($report->post->media==null)
                    <p>{{$report->post->caption}}</p>
                    @else
                    <p>{{$report->post->caption}}</p>
                    <div class="social-media-media-container">
                        <?php foreach (json_decode($report->post->media)as $m){?>
                        <div class="social-media-media">
                            @if (pathinfo($m, PATHINFO_EXTENSION) == 'mp4')
                                <video controls>
                                    <source src="{{asset('storage/post/'.$m) }}">
                                </video>
                            @else
                                <img src="{{asset('storage/post/'.$m) }}">
                            @endif
                        </div>
                        <?php }?>
                    </div>

                    <div id="slider-wrapper" class="social-media-media-slider">
                        <iconify-icon icon="akar-icons:cross" class="slider-close-icon"></iconify-icon>

                        <div id="image-slider" class="image-slider">
                            <ul class="ul-image-slider">

                                <?php foreach (json_decode($report->post->media)as $m){?>
                                    @if (pathinfo($m, PATHINFO_EXTENSION) == 'mp4')
                                    <li>
                                        <video controls>
                                            <source src="{{asset('storage/post/'.$m) }}">
                                        </video>
                                    </li>
                                    @else
                                        <li>
                                            <img src="{{asset('storage/post/'.$m) }}" alt="" />
                                        </li>
                                    @endif

                                <?php }?>
                            </ul>

                        </div>

                        <div id="thumbnail" class="img-slider-thumbnails">
                            <ul>
                                {{-- <li class="active"><img src="https://40.media.tumblr.com/tumblr_m92vwz7XLZ1qf4jqio1_540.jpg" alt="" /></li> --}}
                                <?php foreach (json_decode($report->post->media)as $m){?>
                                    @if (pathinfo($m, PATHINFO_EXTENSION) == 'mp4')
                                    <li>
                                        <video>
                                            <source src="{{asset('storage/post/'.$m) }}">
                                        </video>
                                    </li>
                                    @else
                                        <li>
                                            <img src="{{asset('storage/post/'.$m) }}" alt="" />
                                        </li>
                                    @endif

                                <?php }?>

                            </ul>
                        </div>

                    </div>

                    @endif
                </div>

                <div class="social-media-post-footer-container">
                    <div class="social-media-post-like-container">
                        @php
                            $total_likes=$report->post->user_reacted_posts->count();
                            $total_comments=$report->post->comments->count();
                            $user=auth()->user();
                            $already_liked=$user->user_reacted_posts->where('post_id',$report->post->id)->count();
                        @endphp

                        <a class="like" href="#" id="{{$report->post->id}}">

                        @if($already_liked==0)
                        <iconify-icon icon="mdi:cards-heart-outline" class="like-icon">
                        </iconify-icon>
                        @else
                        <iconify-icon icon="mdi:cards-heart" style="color: red;" class="like-icon already-liked">
                        </iconify-icon>
                        @endif

                        </a>
                        <p>
                            <span class="total_likes">
                            {{$total_likes}}
                            </span>
                            <a href="{{route('social_media_likes',$report->post->id)}}">Likes</a>
                        </p>
                    </div>
                    <div class="social-media-post-comment-container">
                        <a href = "{{route('post.comment',$report->post->id)}}">
                        <iconify-icon icon="bi:chat-right" class="comment-icon"></iconify-icon>
                        <p><span>{{$total_comments}}</span> Comments</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#accept').click(function(e){
        e.preventDefault()
        Swal.fire({
                        text: 'This post goes Against Our Community and Guidelines.',
                        timerProgressBar: true,
                        showCloseButton: true,
                        showCancelButton: true,
                        confirmButtonText:'Delete Post',
                        cancelButtonText:'No',
                        icon: 'warning',
                    }).then((result) => {
                        var report_id=$(this).data('id');
                        console.log(report_id);
                    if (result.isConfirmed) {
                       var add_url = "{{ route('admin.accept.report', [':report_id']) }}";
                        add_url = add_url.replace(':report_id', report_id);
                        $.ajax({
                            method: "GET",
                            url: add_url,
                            success:function(data){
                                Swal.fire({
                                        text: data.success,
                                        icon: 'success',
                                    }).then((result) => {
                                        window.location.href = "{{ route('report.index') }}";
                                    })
                            }
                        })
                    }else{
                        console.log('not delete');
                    }
                    })
    })

    $('#decline').click(function(e){
        e.preventDefault()
        Swal.fire({
                        text: 'This post does not go Against Our Community and Guidelines.',
                        timerProgressBar: true,
                        showCloseButton: true,
                        showCancelButton: true,
                        confirmButtonText:'Yes',
                        cancelButtonText:'No',
                        icon: 'warning',
                    }).then((result) => {
                        var report_id=$(this).data('id');
                        console.log(report_id);
                        if (result.isConfirmed) {
                        var add_url = "{{ route('admin.decline.report', [':report_id']) }}";
                            add_url = add_url.replace(':report_id', report_id);
                            $.ajax({
                                method: "GET",
                                url: add_url,
                                success:function(data){
                                    Swal.fire({
                                            text: data.success,
                                            icon: 'success',
                                        }).then((result) => {
                                            window.location.href = "{{ route('report.index') }}";
                                        })
                                }
                            })
                        }else{
                            console.log('not delete');
                        }
                    })
    })
})
     //image slider start

        $.each($(".ul-image-slider"),function(){
            console.log($(this).children('li').length)

            $(this).children('li:first').addClass("active-img")
        })

        $.each($(".img-slider-thumbnails ul"),function(){
            console.log($(this).children('li').length)

            $(this).children('li:first').addClass("active")
        })

        $(function(){

            $('.img-slider-thumbnails li').click(function(){
            var thisIndex = $(this).index()
            // console.log(thisIndex,$(this).siblings("li.active").index())
            if($(this).siblings(".active").index() === -1){
                return
            }


            if(thisIndex < $(this).siblings(".active").index()){
                prevImage(thisIndex, $(this).parents(".img-slider-thumbnails").prev("#image-slider"));
            }else if(thisIndex > $(this).siblings(".active").index()){
                nextImage(thisIndex, $(this).parents(".img-slider-thumbnails").prev("#image-slider"));
            }


            $(this).siblings('.active').removeClass('active');
            $(this).addClass('active');

            });

        });

        var width = $('#image-slider').width();
        console.log(width)

        function nextImage(newIndex, parent){
            parent.find('li').eq(newIndex).addClass('next-img').css('left', width).animate({left: 0},600);
            parent.find('li.active-img').removeClass('active-img').css('left', '0').animate({left: '-100%'},600);
            parent.find('li.next-img').attr('class', 'active-img');
        }
        function prevImage(newIndex, parent){
            parent.find('li').eq(newIndex).addClass('next-img').css('left', -width).animate({left: 0},600);
            parent.find('li.active-img').removeClass('active-img').css('left', '0').animate({left: '100%'},600);
            parent.find('li.next-img').attr('class', 'active-img');
        }

        /* Thumbails */
        // var ThumbailsWidth = ($('#image-slider').width() - 18.5)/7;
        // $('#thumbnail li').find('img').css('width', ThumbailsWidth);

        $('.social-media-media-slider').hide()

        $(".social-media-media-container").click(function(){

            $(this).siblings(".social-media-media-slider").show()
            $(this).hide()
        })

        $(".slider-close-icon").click(function(){
            $(this).closest('.social-media-media-slider').hide()
            $(this).closest('.social-media-media-slider').siblings('.social-media-media-container').show()
        })
        //image slider end
     $('.social-media-post-header-icon').click(function(){
            $(this).next().toggle()
        })
</script>
@endpush
