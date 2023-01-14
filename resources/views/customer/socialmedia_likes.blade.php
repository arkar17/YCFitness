@extends('customer.layouts.app_home')

@section('content')
@include('sweetalert::alert')

<div class="social-media-right-container">
    <div class="social-media-all-likes-parent-container">
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
                    <div class="post-action">
                        <iconify-icon icon="material-symbols:report-outline" class="post-action-icon"></iconify-icon>
                        <p>Report</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="social-media-content-container">
                @if ($post->media==null)
                <p>{{$post->caption}}</p>
                @else
                <p>{{$post->caption}}</p>
                <div class="social-media-media-container">
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
                            {{-- <li class="active"><img src="https://40.media.tumblr.com/tumblr_m92vwz7XLZ1qf4jqio1_540.jpg" alt="" /></li> --}}
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
                    <p><span class="total_likes">{{$post_likes->count()}}</span>
                        @if ($post_likes->count()>1)
                        Likes
                        @else
                        Like
                        @endif
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

        <div class="social-media-all-likes-container">
                @forelse ($post_likes as $user_like_post)
                <div class="social-media-all-likes-row">
                    <div class="social-media-all-likes-row-img">
                        <?php $profile=$user_like_post->user->profiles->first();
                        $profile_id=$user_like_post->user->profile_id;
                         $img=$user_like_post->user->profiles->where('id',$profile_id)->first();
                        ?>

                        @if($img==null)
                        <a href="{{route('socialmedia.profile',$user_like_post->user_id)}}" style="text-decoration:none">
                        <img src="{{asset('img/customer/imgs/user_default.jpg')}}">
                        </a>
                        <a href="{{route('socialmedia.profile',$user_like_post->user_id)}}" style="text-decoration:none">
                        <p>{{$user_like_post->user->name}}</p>
                        </a>
                        @else
                        <a href="{{route('socialmedia.profile',$user_like_post->user_id)}}" style="text-decoration:none">
                        <img src="{{asset('storage/post/'.$img->profile_image)}}">
                        </a>
                        <a href="{{route('socialmedia.profile',$user_like_post->user_id)}}" style="text-decoration:none">
                        <p>{{$user_like_post->user->name}}</p>
                        </a>
                        @endif

                    </div>
                    <div class="social-media-all-likes-row-btns">
                        @if($user_like_post->friend_status=='myself')

                        @elseif($user_like_post->friend_status=='friend')
                        {{-- <a class="customer-primary-btn" href="{{route('socialmedia.profile',$user_like_post->user_id)}}" >Friend</a> --}}
                        @elseif($user_like_post->friend_status=='response')
                        <a class="customer-primary-btn" href="{{route('socialmedia.profile',$user_like_post->user_id)}}" >Response</a>
                        @elseif($user_like_post->friend_status=='cancel request')
                        <a class="customer-primary-btn" id="cancelrequest" href="?id={{$user_like_post->user_id}}" >Cancel</a>
                        @else
                        <a class="customer-primary-btn add-friend" id="addfriend" href="?id={{$user_like_post->user_id}}" >Add</a>
                        @endif
                    </div>
                </div>
                @empty
                    <p class="text-secondary p-1">No Like</p>
                @endforelse
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
   $(document).ready(function() {
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
                                        e.target.innerHTML = "Unsave";
                                    })
                                }else{
                                    Swal.fire({
                                            text: data.unsave,
                                            timerProgressBar: true,
                                            timer: 5000,
                                            icon: 'success',
                                        }).then((result) => {
                                            e.target.innerHTML="Save";

                                    })
                                }

                            }
                    })


        })

        $('#addfriend').click(function(e) {
                e.preventDefault();

                var url = new URL(this.href)
                var id = url.searchParams.get("id")

                var add_url = "{{ route('addUser', [':id']) }}";
                add_url = add_url.replace(':id', id);

                $(".add-friend").attr('href','');

                $.ajax({
                    type: "GET",
                    url: add_url,
                    datatype: "json",
                    success: function(data) {
                        window.location.reload();
                    }
                })
        });

        $('#cancelrequest').click(function(e) {
                        e.preventDefault();
                        Swal.fire({
                                text: "Are you sure?",
                                showClass: {
                                        popup: 'animate__animated animate__fadeInDown'
                                    },
                                    hideClass: {
                                        popup: 'animate__animated animate__fadeOutUp'
                                    },
                                showCancelButton: true,
                                timerProgressBar: true,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'No',

                                }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                    var url = new URL(this.href);
                                    var id = url.searchParams.get("id");
                                    var url = "{{ route('cancelRequest', [':id']) }}";
                                    url = url.replace(':id', id);
                                    $(".cancel-request-btn").attr('href','');
                                        $.ajax({
                                            type: "GET",
                                            url: url,
                                            datatype: "json",
                                            success: function(data) {
                                                window.location.reload();
                                            }
                                        })

                                }
                })
        })
   })
</script>
@endpush
