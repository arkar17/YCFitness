@extends('customer.layouts.app_home')

@section('content')
@include('sweetalert::alert')

<!-- Report Modal -->
<div class="modal fade " id="reportmodal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="reportLabel">{{__('msg.report')}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div>
                <h6 class="text-bold">Please select a problem</h6>
                <form id="report_form" value="">
                    <label class="form-label text-secondary" style="font-size:0.75em">Your report is anonymous,except if you're reporting an intellectual property infringement.</label>
                    <input type="hidden" value="" id="post_id">

                    <label class="report-option">
                        <input type="radio" id="nudity" name="report_msg" value="nudity">
                        <label for="nudity">Nudity</label>
                    </label>
                    <label class="report-option">
                        <input type="radio" id="violence" name="report_msg" value="violence">
                        <label for="violence">Violence</label>
                    </label>

                    <label class="report-option">
                        <input type="radio" id="harassment" name="report_msg" value="harassment">
                        <label for="harassment">Harassment</label>
                    </label>

                    <label class="report-option">
                        <input type="radio" id="suicide" name="report_msg" value="suicide or self-injury">
                        <label for="suicide">Suicide or self-injury</label>
                    </label>

                    <label class="report-option">
                        <input type="radio" id="false" name="report_msg" value="false information">
                        <label for="false">false information</label>
                    </label>


                    <label class="report-option">
                        <input type="radio" id="spam" name="report_msg" value="spam">
                        <label for="spam">Spam</label>
                    </label>


                    <label class="report-option">
                        <input type="radio" id="Hate speech" name="report_msg" value="hate speech">
                        <label for="Hate speech">Hate speech</label>
                    </label>

                    <label class="report-option">
                        <input type="radio" id="terrorism" name="report_msg" value="terrorism">
                        <label for="terrorism">Terrorism</label>
                    </label>

                    <label class="report-option">
                        <input type="radio" id="other" name="report_msg" value="other">
                        <label for="other">Other</label>
                    </label>
                    <textarea id="other_msg" name="other_report_msg"></textarea>

                    <button type="submit" class="btn btn-primary disabled" id="report_submit">Submit</button>
                </form>
            </div>
        </div>
      </div>
    </div>
</div>

<div class="social-media-right-container">
    <div class="social-media-posts-parent-container">
        @foreach ($posts as $post)
        <div class="social-media-post-container">
            <div class="social-media-post-header">
                <div class="social-media-post-name-container">
                    <a href="{{route('socialmedia.profile',$post->user_id)}}" style="text-decoration:none">
                        <?php $profile=$post->user->profiles->first();
                            $profile_id=$post->user->profile_id;
                                $img=$post->user->profiles->where('id',$profile_id)->first();
                        ?>
                        @if ($img==null)
                            <img class="nav-profile-img" src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                        @else
                            <img class="nav-profile-img" src="{{asset('storage/post/'.$img->profile_image)}}"/>
                        @endif
                    </a>
                    <div class="social-media-post-name">
                        <a href="{{route('socialmedia.profile',$post->user_id)}}" style="text-decoration:none">
                            <p>{{$post->user->name}}</p>
                        </a>
                        <span>{{ \Carbon\Carbon::parse($post->created_at)->format('d M Y , g:i A')}}</span>
                    </div>
                </div>

                <iconify-icon icon="bi:three-dots-vertical" class="social-media-post-header-icon"></iconify-icon>

                <div class="post-actions-container">
                    <a href="#" style="text-decoration:none" class="post_save" id="{{$post->id}}">
                        <div class="post-action">
                            <iconify-icon icon="bi:save" class="post-action-icon"></iconify-icon>
                            @php
                                $already_save=auth()->user()->user_saved_posts->where('post_id',$post->id)->first();
                            @endphp

                            @if ($already_save)
                                <p class="save">Unsave</p>
                            @else
                                <p class="save">Save</p>
                                @endif
                        </div>
                    </a>
                    @if ($post->user->id == auth()->user()->id)

                        <a id="edit_post" data-id="{{$post->id}}" data-bs-toggle="modal" >
                            <div class="post-action">
                                <iconify-icon icon="material-symbols:edit" class="post-action-icon"></iconify-icon>
                                <p>Edit</p>
                            </div>
                        </a>
                        <a id="delete_post" data-id="{{$post->id}}">
                            <div class="post-action">
                            <iconify-icon icon="material-symbols:delete-forever-outline-rounded" class="post-action-icon"></iconify-icon>
                            <p>Delete</p>
                            </div>
                        </a>
                    @else
                    <div class="post-action" id="report" data-id="{{$post->id}}">
                        <iconify-icon icon="material-symbols:report-outline" class="post-action-icon"></iconify-icon>
                        <p>Report</p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="social-media-content-container" >
                @if ($post->media==null)
                <p>{{$post->caption}}</p>
                @else
                <p>{{$post->caption}}</p>
                <div class="social-media-media-container" data-id="{{$post->id}}">
                    <?php foreach (json_decode($post->media)as $m){?>
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

                            <?php foreach (json_decode($post->media)as $m){?>
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
                            <?php foreach (json_decode($post->media)as $m){?>
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
                        $total_likes=$post->user_reacted_posts->count();
                        $total_comments=$post->comments->count();
                        $user=auth()->user();
                        $already_liked=$user->user_reacted_posts->where('post_id',$post->id)->count();
                    @endphp

                    <a class="like" href="#" id="{{$post->id}}">

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
                        <a href="{{route('social_media_likes',$post->id)}}">
                            @if ($total_likes>1)
                            Likes
                            @else
                            Like
                            @endif
                        </a>
                    </p>
                </div>
                <div class="social-media-post-comment-container">
                    <a href = "{{route('post.comment',$post->id)}}">
                    <iconify-icon icon="bi:chat-right" class="comment-icon"></iconify-icon>
                    <p><span>{{$total_comments}}</span>
                        @if ($total_comments>1)
                        Comments
                        @else
                        Comment
                        @endif
                    </p>
                    </a>
                </div>
                @if($post->media!=null)
                    <div class="social-media-post-comment-container">
                        <iconify-icon icon="ic:outline-remove-red-eye" class="comment-icon"></iconify-icon>
                        <p><span>{{$post->viewers}}</span>
                            @if ($post->viewers>1)
                            Views
                            @else
                            View
                            @endif
                        </p>
                    </div>
                @else
                @endif

            </div>
        </div>
        @endforeach
    </div>

    </div>
</div>

@endsection
@push('scripts')
<script>

    $(document).ready(function() {
        $('#other_msg').hide();
        $('#report_submit').attr("class",'btn btn-primary disabled')
        console.log($("input[name='report_msg']:checked").val());

        $('input[name="report_msg"]').on('click', function() {
                if ($(this).val() == 'other') {
                    $('#other_msg').show();
                    $('#other_msg').keydown(function() {
                        if(!$('#other_msg').val()){
                            $('#report_submit').attr("class",'btn btn-primary disabled')
                        }else{
                            $('#report_submit').attr("class",'btn btn-primary')
                        }
                    })

                }else if ($(this).val() == 'nudity'){
                    $('#other_msg').hide();
                    $('#other_msg').val('');
                    $('#report_submit').attr("class",'btn btn-primary')

                }else if ($(this).val() == 'violence'){
                    $('#other_msg').hide();
                    $('#other_msg').val('')
                    $('#report_submit').attr("class",'btn btn-primary')
                }else if ($(this).val() == 'harassment'){
                    $('#other_msg').hide();
                    $('#other_msg').val('')
                    $('#report_submit').attr("class",'btn btn-primary')
                }else if ($(this).val() == 'suicide or self-injury'){
                    $('#other_msg').hide();
                    $('#other_msg').val('')
                    $('#report_submit').attr("class",'btn btn-primary')
                }else if ($(this).val() == 'false information'){
                    $('#other_msg').hide();
                    $('#other_msg').val('');
                    $('#report_submit').attr("class",'btn btn-primary')
                }else if ($(this).val() == 'spam'){
                    $('#other_msg').hide();
                    $('#other_msg').val('')
                    $('#report_submit').attr("class",'btn btn-primary')
                }else if ($(this).val() == 'hate speech'){
                    $('#other_msg').hide();
                    $('#other_msg').val('')
                    $('#report_submit').attr("class",'btn btn-primary')
                }else if ($(this).val() == 'terrorism'){
                    $('#other_msg').hide();
                    $('#other_msg').val('')
                    $('#report_submit').attr("class",'btn btn-primary')
                }
        });


        $('.like').click(function(e){
            e.preventDefault();
            var isLike=e.target.previousElementSibiling == null ? true : false;
            var post_id=$(this).attr('id');
            console.log(post_id)
            var add_url = "{{ route('user.react.post', [':post_id']) }}";
            add_url = add_url.replace(':post_id', post_id);
            var that = $(this)
            $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                    $.ajax({
                        method: "POST",
                        url: add_url,
                        data:{ isLike : isLike , post_id: post_id },
                        success:function(data){
                            that.siblings('p').children('.total_likes').html(data.total_likes)

                            if(that.children('.like-icon').hasClass("already-liked")){
                                that.children('.like-icon').attr('style','')
                                that.children('.like-icon').attr('class','like-icon')
                                that.children(".like-icon").attr('icon','mdi:cards-heart-outline')
                            }else{
                                that.children('.like-icon').attr('style','color : red')
                                that.children('.like-icon').attr('class','like-icon already-liked')
                                that.children(".like-icon").attr('icon','mdi:cards-heart')
                            }

                        }
                    })


        })

        $('.post_save').click(function(e){
            e.preventDefault();
            $('.post-actions-container').hide();
            var post_id=$(this).attr('id');
            var add_url = "{{ route('socialmedia.post.save', [':post_id']) }}";
            add_url = add_url.replace(':post_id', post_id);
                    $.ajax({
                        method: "GET",
                        url: add_url,
                        data:{
                                post_id : post_id
                            },
                            success: function(data) {
                                // window.location.reload();
                                if(data.save){
                                    Swal.fire({
                                        text: data.save,
                                        timerProgressBar: true,
                                        timer: 5000,
                                        icon: 'success',
                                    }).then((result) => {
                                        e.target.querySelector(".save").innerHTML = `Unsave`;
                                    })
                                }else{
                                    Swal.fire({
                                            text: data.unsave,
                                            timerProgressBar: true,
                                            timer: 5000,
                                            icon: 'success',
                                        }).then((result) => {
                                            e.target.querySelector(".save").innerHTML = `Save`;

                                        })
                                }

                            }
                    })


        })

        $(document).on('click', '#report', function(e){
            var post_id=$(this).data('id')
            $('#post_id').val(post_id)
            $('input[name="report_msg"]').prop('checked', false);
            $('#other_msg').hide();
            $('#other_msg').val('');
            $('#report_submit').attr("class",'btn btn-primary disabled')
            $('#reportmodal').modal('show');

        })

        $(document).on('submit','#report_form',function(e){
            e.preventDefault()
            $('#reportmodal').modal('hide');
            var report_msg
            var post_id=$('#post_id').val();
            $('#post_id').val('')
            var user_id={{auth()->user()->id}}

            if($('input[name="report_msg"]:checked').val()=='other'){
                report_msg=$("#other_msg").val();

            } else{
                 report_msg=$("input[name='report_msg']:checked").val();
            }

            var add_url = "{{ route('socialmedia.report')}}";
            $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                    $.ajax({
                        method: "POST",
                        url: add_url,
                        data:{ post_id : post_id , user_id:user_id ,report_msg:report_msg},
                        success:function(data){
                            if(data.success){
                                Swal.fire({
                                        text: data.success,
                                        timerProgressBar: true,
                                        timer: 3000,
                                        icon: 'success',
                                    }).then((result) => {
                                        $('input[name="report_msg"]').prop('checked', false);
                                        $('#reportmodal').modal('hide');
                                        $('#other_msg').hide();
                                        $('#other_msg').val('');
                                    })
                            }
                        }
                    })

        })
    });
</script>

@endpush
