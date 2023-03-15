@extends('customer.layouts.app_home')

@section('content')
@include('sweetalert::alert')

    {{-- <div class="social-media-header-btns-container margin-top">
        <a class="back-btn">
            <iconify-icon icon="bi:arrow-left" class="back-btn-icon"></iconify-icon>
        </a>
    </div> --}}

        <!-- Report Modal -->
        <div class="modal fade " id="reportmodal">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="reportLabel">Report</h5>
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
        <!-- End Report Modal -->

        <div class="social-media-right-container social-media-right-container-nopadding">
            <div class="social-media-profile-parent-container">
                <div class="social-media-profile-bgimg-container">
                    <?php $profile=$user->profiles->first();
                    $cover_id=$user->cover_id;
                    $cover_img=$user->profiles->where('id',$cover_id)->first();
                    ?>

                    @if ($cover_img==null)
                        <img src="{{asset('image/cover.jpg')}}">
                    @else
                        <img class="" src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{$cover_img->cover_photo}}"/>
                    @endif

                    <div class="social-media-profile-profileimg-container">

                        <?php $profile=$user->profiles->first();
                        $profile_id=$user->profile_id;
                         $img=$user->profiles->where('id',$profile_id)->first();
                        ?>

                        @if ($img==null)
                            <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                        @else
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $img->profile_image }}"/>
                        @endif

                    </div>
                </div>

                <div class="social-media-profile-content-container">

                    <div id = "addFriclass" class="social-media-profile-btns-container">
                       
                        @if (count($friend) < 1)
                        <a href ="?id={{$user->id}}" class="customer-primary-btn add-friend-btn" id = "Add">
                            <iconify-icon icon="akar-icons:circle-plus" class="add-friend-icon"></iconify-icon>
                            <p>{{__('msg.add friend')}}</p>
                        </a>
                        @elseif($user->id == auth()->user()->id)
                            <button class="customer-primary-btn add-friend-btn">
                                <iconify-icon icon="material-symbols:person-outline" class="add-friend-icon"></iconify-icon>
                                <p>My self </p>

                            </button>
                        @else
                    @foreach ($friend as $friend_status)
                    @if($friend_status->friend_status == 2  )
                        <a href="{{route('message.chat',$user->id)}}" class="customer-primary-btn add-friend-btn">
                            <iconify-icon icon="mdi:message-reply-outline" class="add-friend-icon"></iconify-icon>
                            <p>{{__('msg.message')}}</p>
                        </a>
                        <a href ="?id={{$user->id}}" class="customer-red-btn add-friend-btn unfriend "  data-id = {{$user->id}}>
                            <iconify-icon icon="mdi:account-minus-outline" class="add-friend-icon"></iconify-icon>
                            <p>{{__('msg.unfriend')}}</p>
                        </a>
                        @elseif ($friend_status->friend_status == 1 AND $friend_status->sender_id  === auth()->user()->id )
                        <button class="customer-primary-btn add-friend-btn">
                            <iconify-icon icon="material-symbols:cancel-schedule-send-outline-rounded" class="add-friend-icon"></iconify-icon>
                            <p>{{__('msg.cancel request')}}</p>
                        </button>
                        @elseif ($friend_status->friend_status == 1 AND $friend_status->receiver_id  === auth()->user()->id)
                        <div class="" style = "margin-top:10px; display:flex; justify-content:right">
                        <div class="social-media-btns-container">
                            <a href = {{route('confirmRequest',$user->id)}} class="customer-primary-btn">
                                Accept
                            </a>
                            {{-- <button class="customer-red-btn">Decline</button> --}}
                            <a href = {{route('declineRequest',$user->id)}} class="customer-red-btn">
                                Decline
                            </a>
                        </div>
                        </div>
                    @endif
                    @endforeach
                    @endif
                    <a href="{{route('block',$user->id)}}" class="customer-red-btn add-friend-btn">
                        <iconify-icon icon="mdi:block-helper"  class=""></iconify-icon>
                        <p>Block</p>
                    </a>
                    </div>
                    <div class="social-media-profile-username-container">
                        <span class="social-media-profile-username">{{$user->name}}</span><br>
                        {{-- <span class="social-media-profile-userID">(User ID: 1234567890)</span><br> --}}
                        <span class="social-media-profile-description">
                            {{$user->bio}}
                        </span>
                    </div>

                    <div class="social-media-profile-friends-parent-container">
                        <div class="social-media-profile-friends-header">
                            @if (count($friends)>1)
                            <p>{{count($friends)}} {{__('msg.friends')}}</p>
                            @else
                            <p>{{count($friends)}} {{__('msg.friend')}}</p>
                            @endif
                            <a href="{{route('friendsList',$user->id)}}">
                               {{__('msg.see all')}}
                                <iconify-icon icon="bi:arrow-right" class="arrow-icon"></iconify-icon>
                            </a>
                        </div>

                        <div class="social-media-profile-friends-container">
                            @forelse ($friends as $friend)
                            <div class="social-media-profile-friend">
                                <?php $profile=$friend->profiles->first();
                                    $profile_id=$friend->profile_id;
                                    $img=$friend->profiles->where('id',$profile_id)->first();
                                ?>
                                 <a href="{{route('socialmedia.profile',$friend->id)}}" style="text-decoration:none">
                                @if ($img==null)
                                    <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                                @else
                                    <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $img->profile_image }}"/>
                                @endif


                                <p>{{$friend->name}}</p>
                                 </a>
                            </a>
                            </div>
                            @empty
                            <p class="text-secondary p-1">No Friend</p>
                            @endforelse

                        </div>
                    </div>

                        <a href="{{route('socialmedia_profile_photos',$user->id)}}" class="social-media-profile-photos-link">{{__('msg.photos')}}</a>
                        {{-- <a href="{{route('socialmedia_profile_photos')}}" class="social-media-profile-photos-link">Photos</a> --}}

                    <div class="social-media-profile-posts-parent-container">
                        <p>{{__('msg.post & activities')}}</p>

                        @forelse ($posts as $post)
                            <div class="social-media-post-container">
                                <div class="social-media-post-header">
                                    <div class="social-media-post-name-container">
                                        <a href="{{route('socialmedia.profile',$post->user_id)}}" style="text-decoration:none">

                                            <?php $profile=$post->user->profiles->first();
                                            $profile_id=$post->user->profile_id;
                                             $img=$post->user->profiles->where('id',$profile_id)->first();
                                            ?>
                                            @if ($img==null)
                                                <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                                            @else
                                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $img->profile_image }}"/>
                                            @endif
                                        </a>
                                        <div class="social-media-post-name">
                                            <a href="{{route('socialmedia.profile',$post->user_id)}}" style="text-decoration:none">
                                                <p>{{$post->user->name}}
                                                    @if($post->roles == 'Gold')
                                                        <span style = "color:#D1B000;font-weight:bold"> [G]</span>
                                                    @elseif ($post->roles == 'Platinum')
                                                        <span style = "color:#A9A9A9;font-weight:bold"> [D] </span>
                                                    @elseif ($post->roles == 'Diamond')
                                                    <span style = "color:#afeeee;font-weight:bold"> [P]</span>
                                                    @elseif ($post->roles == 'Ruby')
                                                        <span style = "color:#B22222;font-weight:bold"> [R] </span>
                                                    @elseif ($post->roles == 'Ruby Premium')
                                                        <span style = "color:#B22222;font-weight:bold"> [R <sup>+</sup>]</span>
                                                    @elseif ($post->roles == 'Trainer')
                                                        <span style = "color:#4444FF;font-weight:bold"> [T]</span>
                                                    @elseif ($post->roles == 'Gym Member')
                                                        <span style = "color:#A9A9A9;font-weight:bold"> [GM]</span>
                                                    @endif
                                                </p>
                                            </a>
                                            <span>{{\Carbon\Carbon::parse($post->created_at)->format('d M Y , g:i A')}}</span>
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
                                                    <p class="save">{{__('msg.unsave')}}</p>
                                                @else
                                                    <p class="save">{{__('msg.save')}}</p>
                                                    @endif
                                            </div>
                                        </a>
                                        <div class="post-action" id="report" data-id="{{$post->id}}">
                                            <iconify-icon icon="material-symbols:report-outline" class="post-action-icon"></iconify-icon>
                                            <p>{{__('msg.report')}}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="social-media-content-container">
                                    @if ($post->media==null)
                                    <p>{{$post->caption}}</p>
                                    @else
                                    <p>{{$post->caption}}</p>
                                    <div class="social-media-media-container" id = "photo_view_count" data-id="{{$post->id}}">
                                        <?php foreach (json_decode($post->media)as $m){?>
                                        <div class="social-media-media">
                                            @if (pathinfo($m, PATHINFO_EXTENSION) == 'mp4')
                                                <video controls>
                                                    <source src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $m}}">
                                                </video>
                                            @else
                                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $m}}">
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
                                                            <source src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $m}}">
                                                        </video>
                                                    </li>
                                                    @else
                                                        <li>
                                                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $m}}" alt="" />
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
                                                            <source src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $m}}">
                                                        </video>
                                                    </li>
                                                    @else
                                                        <li>
                                                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $m}}" alt="" />
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
                                        <p><span class="total_likes">{{$total_likes}}</span>
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
                                        <p><span id="viewers{{$post->id}}">{{$post->viewers}}</span>
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
                            @empty
                            <p class="text-secondary p-1">No Post And Activity</p>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>

@endsection
@push('scripts')
<script>
    $(document).ready(function() {

        // $('#other_msg').hide();
        // $('#report_submit').attr("class",'btn btn-primary disabled')
        // console.log($("input[name='report_msg']:checked").val());

        // $('input[name="report_msg"]').on('click', function() {
        //         if ($(this).val() == 'other') {
        //             $('#other_msg').show();
        //             $('#other_msg').keydown(function() {
        //                 if(!$('#other_msg').val()){
        //                     $('#report_submit').attr("class",'btn btn-primary disabled')
        //                 }else{
        //                     $('#report_submit').attr("class",'btn btn-primary')
        //                 }
        //             })

        //         }else if ($(this).val() == 'nudity'){
        //             $('#other_msg').hide();
        //             $('#other_msg').val('');
        //             $('#report_submit').attr("class",'btn btn-primary')

        //         }else if ($(this).val() == 'violence'){
        //             $('#other_msg').hide();
        //             $('#other_msg').val('')
        //             $('#report_submit').attr("class",'btn btn-primary')
        //         }else if ($(this).val() == 'harassment'){
        //             $('#other_msg').hide();
        //             $('#other_msg').val('')
        //             $('#report_submit').attr("class",'btn btn-primary')
        //         }else if ($(this).val() == 'suicide or self-injury'){
        //             $('#other_msg').hide();
        //             $('#other_msg').val('')
        //             $('#report_submit').attr("class",'btn btn-primary')
        //         }else if ($(this).val() == 'false information'){
        //             $('#other_msg').hide();
        //             $('#other_msg').val('');
        //             $('#report_submit').attr("class",'btn btn-primary')
        //         }else if ($(this).val() == 'spam'){
        //             $('#other_msg').hide();
        //             $('#other_msg').val('')
        //             $('#report_submit').attr("class",'btn btn-primary')
        //         }else if ($(this).val() == 'hate speech'){
        //             $('#other_msg').hide();
        //             $('#other_msg').val('')
        //             $('#report_submit').attr("class",'btn btn-primary')
        //         }else if ($(this).val() == 'terrorism'){
        //             $('#other_msg').hide();
        //             $('#other_msg').val('')
        //             $('#report_submit').attr("class",'btn btn-primary')
        //         }
        // });

        // $('.post_save').click(function(e){
        //     $('.social-media-post-header-icon').next().toggle()
        //     e.preventDefault();
        //     var post_id=$(this).attr('id');
        //     var add_url = "{{ route('socialmedia.post.save', [':post_id']) }}";
        //     add_url = add_url.replace(':post_id', post_id);
        //             $.ajax({
        //                 method: "GET",
        //                 url: add_url,
        //                 data:{
        //                         post_id : post_id
        //                     },
        //                     success: function(data) {
        //                         // window.location.reload();
        //                         if(data.save){
        //                             Swal.fire({
        //                                 text: data.save,
        //                                 timerProgressBar: true,
        //                                 timer: 5000,
        //                                 icon: 'success',
        //                             }).then((result) => {
        //                                 e.target.querySelector(".save").innerHTML = `Unsave`;
        //                             })
        //                         }else{
        //                             Swal.fire({
        //                                     text: data.unsave,
        //                                     timerProgressBar: true,
        //                                     timer: 5000,
        //                                     icon: 'success',
        //                                 }).then((result) => {
        //                                     e.target.querySelector(".save").innerHTML = `Save`;

        //                             })
        //                         }

        //                     }
        //             })


        // })

        // $(document).on('click', '#report', function(e){
        //     var post_id=$(this).data('id')
        //     $('#post_id').val(post_id)
        //     $('input[name="report_msg"]').prop('checked', false);
        //     $('#other_msg').hide();
        //     $('#other_msg').val('');
        //     $('#report_submit').attr("class",'btn btn-primary disabled')
        //     $('#reportmodal').modal('show');

        // })

        // $(document).on('submit','#report_form',function(e){
        //     e.preventDefault()
        //     $('#reportmodal').modal('hide');
        //     var report_msg
        //     var post_id=$('#post_id').val();
        //     $('#post_id').val('')
        //     var user_id={{auth()->user()->id}}

        //     if($('input[name="report_msg"]:checked').val()=='other'){
        //         report_msg=$("#other_msg").val();

        //     } else{
        //          report_msg=$("input[name='report_msg']:checked").val();
        //     }

        //     var add_url = "{{ route('socialmedia.report')}}";
        //     $.ajaxSetup({
        //                     headers: {
        //                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                     }
        //                 });
        //             $.ajax({
        //                 method: "POST",
        //                 url: add_url,
        //                 data:{ post_id : post_id , user_id:user_id ,report_msg:report_msg},
        //                 success:function(data){
        //                     if(data.success){
        //                         Swal.fire({
        //                                 text: data.success,
        //                                 timerProgressBar: true,
        //                                 timer: 3000,
        //                                 icon: 'success',
        //                             }).then((result) => {
        //                                 $('input[name="report_msg"]').prop('checked', false);
        //                                 $('#reportmodal').modal('hide');
        //                                 $('#other_msg').hide();
        //                                 $('#other_msg').val('');
        //                             })
        //                     }
        //                 }
        //             })

        // })

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

        $(document).on('click', '#Add', function(e) {
                e.preventDefault();
                $('.social-media-left-searched-items-container').empty();
                var url = new URL(this.href);

                var id = url.searchParams.get("id");

                var add_url = "{{ route('addUser', [':id']) }}";
                add_url = add_url.replace(':id', id);
                $(".add-member-btn").attr('href','');
                $.ajax({
                    type: "GET",
                    url: add_url,
                    datatype: "json",
                    success: function(data) {
                        console.log(data)
                        window.location.reload();
                    }
                })
        });

        $(document).on('click', '.unfriend', function(e) {
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
                        var url = "{{ route('unfriend', [':id']) }}";
                        url = url.replace(':id', id);
                        $(".cancel-request-btn").attr('href','');
                        $.ajax({
                            type: "GET",
                            url: url,
                            datatype: "json",
                            success: function(data) {
                                console.log(data)
                                window.location.reload();
                            }
                        })
                }
                })
        });
    });

</script>

@endpush
