@extends('customer.layouts.app_home')

@section('content')
@include('sweetalert::alert')

<div class="modal fade" id ="editModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Comment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form class="social-media-all-comments-input-edit" id="editComment">
                <textarea placeholder="Write a comment" id="editCommentTextArea"></textarea>
                <div id="menu" class="menu" role="listbox"></div>
                <button type="button" id="emoji-button" class="emoji-trigger">
                    <iconify-icon icon="bi:emoji-smile" class="group-chat-send-form-emoji-icon"></iconify-icon>
                </button>
                <div id="edit-emojis">
                </div>

                <button class="social-media-all-comments-send-btn">
                    <iconify-icon icon="akar-icons:send" class="social-media-all-comments-send-icon"></iconify-icon>
                </button>

            </form>
        </div>

      </div>
    </div>
</div>

<!-- Like Modal -->
<div class="modal fade " id="staticBackdrop">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Likes</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="social-media-all-likes-container">
                <div class="social-media-all-likes-row">
                    <div class="social-media-all-likes-row-img">

                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>

<div class="social-media-right-container">
    <div class="social-media-all-likes-parent-container">
        <div class="social-media-post-container">
            <div class="social-media-post-header">
                <div class="social-media-post-name-container">
                    <?php $profile=$post->user->profiles->first();
                        $profile_id=$post->user->profile_id;
                         $img=$post->user->profiles->where('id',$profile_id)->first();
                        ?>
                    <a href="{{route('socialmedia.profile',$post->user_id)}}" style="text-decoration:none">
                    @if ($img==null)
                        <img src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                    @else
                        <img 
                        src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $img->profile_image}}">
                    @endif
                    </a>
                    <div class="social-media-post-name">
                        <a href="{{route('socialmedia.profile',$post->user_id)}}" style="text-decoration:none">
                        <p>{{$post->name}} 
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
                                <source 
                                src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $m}}">
                            </video>
                        @else
                            <img  src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $m}}">
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
                                        <source  src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $m}}">
                                    </video>
                                </li>
                                @else
                                    <li>
                                        <img  src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $m}}" alt="" />
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
                                        <source  src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $m}}">
                                    </video>
                                </li>
                                @else
                                    <li>
                                        <img  src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $m}}" alt="" />
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
                        // $total_comments=$post->comments->where('report_status',0)->count();
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
                    <iconify-icon icon="bi:chat-right" class="comment-icon"></iconify-icon>
                    <p><span>{{$post->comment_count}}</span>
                        @if ($post->comment_count>1)
                        Comments
                        @else
                        Comment
                        @endif
                    </p>
                </div>
                @if($post->media!=null)
                    <div class="social-media-post-comment-container">
                        <iconify-icon icon="ic:outline-remove-red-eye" class="comment-icon"></iconify-icon>
                        <p><span id="viewers{{$post->id}}" >{{$post->viewers}}</span>
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

        <div class="social-media-all-comments-container">
            <form class="social-media-all-comments-input comment-main-input">
                <textarea placeholder="Write a comment" id="textarea"></textarea>
                <div id="menu" class="menu" role="listbox"></div>
                <button type="button" id="emoji-button" class="emoji-trigger">
                    <iconify-icon icon="bi:emoji-smile" class="group-chat-send-form-emoji-icon"></iconify-icon>
                </button>
                <div id="emojis">
                </div>
                <button class="social-media-all-comments-send-btn">
                    <iconify-icon icon="akar-icons:send" class="social-media-all-comments-send-icon"></iconify-icon>
                </button>

            </form>



            <div class="social-media-all-comments">

                {{-- @forelse ($comments as $comment)
                <div class="social-media-comment-container">
                    <img src="../imgs/trainer2.jpg">
                    <div class="social-media-comment-box">
                        <div class="social-media-comment-box-header">
                            <div class="social-media-comment-box-name">
                                <p>User Name</p>
                                <span>19 Sep 2022, 11:02 AM</span>
                            </div>

                            <iconify-icon icon="bi:three-dots-vertical" class="social-media-post-header-icon"></iconify-icon>

                                    <div class="post-actions-container">

                                    @if ($comment->user->id == auth()->user()->id)

                                        <a id="edit_post" data-id="{{$comment->id}}" data-bs-toggle="modal" >
                                            <div class="post-action">
                                                <iconify-icon icon="material-symbols:edit" class="post-action-icon"></iconify-icon>
                                                <p>Edit</p>
                                            </div>
                                        </a>
                                        <a id="delete_comment" data-id="{{$comment->id}}">
                                            <div class="post-action">
                                            <iconify-icon icon="material-symbols:delete-forever-outline-rounded" class="post-action-icon"></iconify-icon>
                                            <p>Delete</p>
                                            </div>
                                        </a>
                                    @else
                                    <a id="delete_comment" data-id="{{$comment->id}}">
                                        <div class="post-action">
                                        <iconify-icon icon="material-symbols:delete-forever-outline-rounded" class="post-action-icon"></iconify-icon>
                                        <p>Delete</p>
                                        </div>
                                    </a>
                                    @endif

                            </div>
                        </div>

                        <p>{{$comment->comment}}</p>
                        
                    </div>
                </div>
                @empty
                    <p class="text-secondary p-1">No comment</p>
                @endforelse --}}
            </div>
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
            $('.post-actions-container').hide();
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

            $(document).on('click', '.social-media-comment-icon', function(e) {
                $(this).next().toggle()
            })

            fetch_comment();
                $('#textarea').mentiony({
                    onDataRequest: function (mode, keyword, onDataRequestCompleteCallback) {
                        var search_url = "{{ route('users.mention') }}";
                        $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                        $.ajax({
                            method: "POST",
                            url:search_url,
                            data : keyword,
                            dataType: "json",
                            success: function (response) {
                                var data = response.data;


                                // NOTE: Assuming this filter process was done on server-side
                                data = jQuery.grep(data, function( item ) {
                                    return item.name.toLowerCase().indexOf(keyword.toLowerCase()) > -1;
                                });
                                // End server-side

                                // Call this to populate mention.
                                onDataRequestCompleteCallback.call(this, data);
                            }



                        });
                        console.log($("#editComment .mentiony-content") , "not edit")
                    },
                });
                $('#editCommentTextArea').mentiony({
                    onDataRequest: function (mode, keyword, onDataRequestCompleteCallback) {
                        var search_url = "{{ route('users.mention') }}";
                        $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                        $.ajax({
                            method: "POST",
                            url:search_url,
                            data : keyword,
                            dataType: "json",
                            success: function (response) {
                                var data = response.data;


                                // NOTE: Assuming this filter process was done on server-side
                                data = jQuery.grep(data, function( item ) {
                                    return item.name.toLowerCase().indexOf(keyword.toLowerCase()) > -1;
                                });
                                // End server-side

                                // Call this to populate mention.
                                onDataRequestCompleteCallback.call(this, data);
                            }
                        });


                    },

                });

                // //emoji start
                $("#emojis").disMojiPicker()
                $("#emojis").picker(emoji => {
                    // console.log($(".social-media-all-comments-input .mentiony-content"))
                    $(".social-media-all-comments-input .mentiony-content").append(emoji)
                });

                $("#edit-emojis").disMojiPicker()
                $("#edit-emojis").picker(emoji => {
                    // console.log($(".social-media-all-comments-input .mentiony-content"))
                    $(".social-media-all-comments-input-edit .mentiony-content").append(emoji)
                });
                // console.log(document.body)
                twemoji.parse(document.querySelector("#emojis"));
                twemoji.parse(document.querySelector("#edit-emojis"));

                $.each($(".emoji-trigger"), function(index,value){
                    // console.log($(this))
                    $(this).click(function(){
                        // console.log($(this).siblings("#emojis"))
                        $(this).siblings("#emojis").toggle()
                        $(this).siblings("#edit-emojis").toggle()
                    })
                })

                //emoji end

                //edit comment start
                $(document).on('click', '#editCommentModal', function(e) {
                        $('#editModal').modal('show');
                        var id = $(this).data('id');
                        $(".social-media-all-comments-input-edit").data('id',id)
                        console.log($(".social-media-all-comments-input-edit").data('id',id),'opop');
                        var edit_url = "{{ route('post.comment.edit',[':id']) }}";
                        edit_url = edit_url.replace(':id', id);
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                    $.ajax({
                        method: "GET",
                        url: edit_url,
                        dataType: "json",
                        success: function (response) {
                            console.log(response.data);
                            var replace = response.data.Replace
                            $("#editComment .mentiony-content").html(replace)
                        }
                    });
                })
                //edit comment end

            $(".mentiony-container").attr('style','')
            $(".mentiony-content").attr('style','')


            $(".social-media-all-comments-input").on('submit',function(e){
                e.preventDefault()

                var arr = []
                $.each($('.social-media-all-comments-input .mentiony-link'),function(){
                    let name = $(this).text()
                    if($(this).text()[0] === '@'){
                        name = $(this).text().slice(1)
                    }
                    arr.push({'id' : $(this).data('item-id'),'name' : name})
                    $(this).text(`@${$(this).data('item-id')+" "+" "}`)

                })

                var comment = $('.social-media-all-comments-input .mentiony-content').text()
                console.log(arr)
                console.log(comment)




                // <a href = "" >Trainer</a>
                var search_url = "{{ route('post.comment.store') }}";
                var post_id = "{{$post->id}}"
                // console.log(post_id)
                    $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                    $.ajax({
                        method: "POST",
                        url:search_url,
                        data : {'post_id':post_id,'mention' : arr , 'comment' : comment},
                        dataType: "json",
                        success: function (response) {
                            if(response.ban){
                                Swal.fire({
                                            text: response.ban,
                                            timerProgressBar: true,
                                            timer: 5000,
                                            icon: 'error',
                                        }).then(() => {
                                            $('.mentiony-content').empty()
                                        })

                            }else{
                                fetch_comment();
                                $('.mentiony-content').empty()
                            }
                        }

                    });

            })

            $(".social-media-all-comments-input-edit").on('submit',function(e){
                e.preventDefault()

                var arr = []
                $.each($('.social-media-all-comments-input-edit .mentiony-link'),function(){
                    let name = $(this).text()
                    if($(this).text()[0] === '@'){
                        name = $(this).text().slice(1)
                    }
                    arr.push({'id' : $(this).data('item-id'),'name' : name})
                    $(this).text(`@${$(this).data('item-id')}`)

                })
                var post_id = $(".social-media-all-comments-input-edit").data('id');

                var comment = $('.social-media-all-comments-input-edit .mentiony-content').text()
                console.log(arr)
                console.log(comment)
                // <a href = "" >Trainer</a>
                var search_url = "{{ route('post.comment.update') }}";
                //var post_id = "{{$post->id}}"
                // console.log(post_id)
                    $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                    $.ajax({
                        method: "POST",
                        url:search_url,
                        data : {'post_id':post_id,'mention' : arr , 'comment' : comment},
                        dataType: "json",
                        success: function (response) {
                            if(response.ban){
                                Swal.fire({
                                            text: response.ban,
                                            timerProgressBar: true,
                                            timer: 5000,
                                            icon: 'error',
                                        }).then(() => {
                                            $('#editModal').modal('hide');
                                            fetch_comment();
                                        })

                            }else{
                                $('#editModal').modal('hide');
                                Swal.fire({
                                            text: "edited",
                                            timerProgressBar: true,
                                            timer: 5000,
                                            icon: 'success',
                                        }).then(() => {
                                           
                                            fetch_comment();
                                        })
                                fetch_comment();
                                $('.mentiony-content').empty()
                            }
                        }

                    });

            })
            
        
            function fetch_comment(){
               
                        var postid = "{{$post->id}}"
                            var comment_url = "{{ route('comment_list',':id') }}";
                            comment_url = comment_url.replace(':id', postid);
                            $.post(comment_url,
                            {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            function(data){
                                table_post_row(data);
                            });
                        }
                        // table row with ajax
                        function table_post_row(res){
                            
                        let htmlView = '';
                            if(res.comment.length <= 0){
                                console.log("no data");
                                htmlView+= `
                                No Comment.
                                `;
                            }

                            var auth_id={{auth()->user()->id}};

                            for(let i = 0; i < res.comment.length; i++){
                            
                               
                                var comment_user=res.comment[i].user_id;
                                var id=res.comment[i].id;
                                var social_media = "{{ route('socialmedia.profile', [':id']) }}";
                                    social_media = social_media.replace(':id',comment_user);

                                    var post_owner=res.comment[i].post_owner;
                                   
                                        htmlView += `<div class="social-media-comment-container">`
                                        
                                        if(res.comment[i].profile_image===null){
                                            htmlView+= `
                                            <img src="{{ asset('/img/customer/imgs/user_default.jpg') }}"> `
                                        }else{
                                            htmlView+= `<img 
                                            src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${res.comment[i].profile_image}" >`
                                        } 
                                        
                                        htmlView += `<div class="social-media-comment-box">
                                            <div class="social-media-comment-box-header">
                                                <div class="social-media-comment-box-name">
                                                    <a href=` + social_media + ` style="text-decoration:none;" >
                                                    <p>`+res.comment[i].name+` 
                                                    </a> 
                                                        `
                                                        
                                        if(res.comment[i].roles =='Gold'){
                                        htmlView += `
                                                      <span style = "color:#D1B000;font-weight:bold"> [G]</span>
                                                `
                                        }
                                        if(res.comment[i].roles==='Platinum'){
                                        htmlView += `
                                                        <span style = "color:#A9A9A9;font-weight:bold"> [D] </span>
                                                `
                                        }
                                        if(res.comment[i].roles ==='Diamond'){
                                        htmlView += `
                                                        <span style = "color:#afeeee;font-weight:bold"> [P]</span>
                                                `
                                        }
                                        if(res.comment[i].roles ==='Ruby'){
                                        htmlView += `
                                                        <span style = "color:#B22222;font-weight:bold"> [R] </span>
                                                `
                                        }
                                        if(res.comment[i].roles == 'Ruby Premium'){
                                        htmlView += `
                                                        <span style = "color:#B22222;font-weight:bold"> [R <sup>+</sup>]</span>
                                                `
                                        }
                                        if(res.comment[i].roles==='Trainer'){
                                        htmlView += `
                                                        <span style = "color:#4444FF;font-weight:bold"> [T]</span>
                                                `
                                        }
                                        if(res.comment[i].roles==='Gym Member'){
                                        htmlView += `
                                                        <span style = "color:#A9A9A9;font-weight:bold"> [GM]</span>
                                                `
                                             
                                        }
                                        htmlView += `    
                                                </p>
                                                    <span>`+res.comment[i].date+`</span>
                                                </div>
                                            `
                                        
                                            if(auth_id==post_owner && auth_id==comment_user){
                                                htmlView+=`
                                                <iconify-icon icon="bx:dots-vertical-rounded" class="social-media-comment-icon"></iconify-icon>
                                                        <div class="comment-actions-container" >
                                                        <div class="comment-action" id="editCommentModal" data-id=`+res.comment[i].id+`>
                                                            <iconify-icon icon="akar-icons:edit" class="comment-action-icon"></iconify-icon>
                                                            <p>Edit</p>
                                                        </div>
                                                        <a id="delete_comment" data-id=`+res.comment[i].id+`>
                                                        <div class="comment-action">
                                                            <iconify-icon icon="fluent:delete-12-regular" class="comment-action-icon"></iconify-icon>
                                                            <p>Delete</p>
                                                        </div>
                                                        </a>
                                                    </div>`
                                            }else if(auth_id==post_owner && auth_id!=comment_user){
                                                htmlView+=`
                                                <iconify-icon icon="bx:dots-vertical-rounded" class="social-media-comment-icon"></iconify-icon>
                                                        <div class="comment-actions-container" >
                                                        <a id="delete_comment" data-id=`+res.comment[i].id+`>
                                                        <div class="comment-action">
                                                            <iconify-icon icon="fluent:delete-12-regular" class="comment-action-icon"></iconify-icon>
                                                            <p>Delete</p>
                                                        </div>
                                                        </a>

                                                        <div class="comment-action" id="comment_report" data-id=`+res.comment[i].id+`>
                                                        <iconify-icon icon="material-symbols:report-outline" class="post-action-icon"></iconify-icon>
                                                        <p>Report</p>
                                                        </div>
                                                    </div>`
                                            }else if(auth_id==comment_user){
                                                htmlView+=`
                                                <iconify-icon icon="bx:dots-vertical-rounded" class="social-media-comment-icon"></iconify-icon>
                                                        <div class="comment-actions-container" >
                                                        <div class="comment-action" id="editCommentModal" data-id=`+res.comment[i].id+`>
                                                            <iconify-icon icon="akar-icons:edit" class="comment-action-icon"></iconify-icon>
                                                            <p>Edit</p>
                                                        </div>
                                                        <a id="delete_comment" data-id=`+res.comment[i].id+`>
                                                        <div class="comment-action">
                                                            <iconify-icon icon="fluent:delete-12-regular" class="comment-action-icon"></iconify-icon>
                                                            <p>Delete</p>
                                                        </div>
                                                        </a>
                                                    </div>`
                                            }else{
                                                htmlView+=`
                                                <iconify-icon icon="bx:dots-vertical-rounded" class="social-media-comment-icon"></iconify-icon>
                                                        <div class="comment-actions-container" >
                                                        <div class="comment-action" id="comment_report" data-id=`+res.comment[i].id+`>
                                                        <iconify-icon icon="material-symbols:report-outline" class="post-action-icon"></iconify-icon>
                                                        <p>Report</p>
                                                        </div>
                                                        </div>`
                                            }
                                        htmlView+=`
                                                </div>
                                                    <div>`+res.comment[i].Replace+`</div>
                                                    <div class="comment-like-reply-container">
                                                        <div class="comment-like-container">
                                                            <a class="like_comment" style = "font-size:12px; text-decoration:none" herf = "#" id=`+res.comment[i].id+`>
                                                    `
                                                 if(res.comment[i].already_liked==0 ){
                                        htmlView += `
                                        <div class="social-media-post-comment-container">
                                                        <iconify-icon icon="mdi:cards-heart-outline" class="like-icon-comment">
                                                        </iconify-icon>`
                                                    }

                                                    else{
                                        htmlView += `
                                        <div class="social-media-post-comment-container">
                                                        <iconify-icon icon="mdi:cards-heart" style="color: red;" class="like-icon-comment already-liked-comment">
                                                        </iconify-icon>`
                                                    }

                                        htmlView +=`</a>
                                                                    <p>
                                                                        <span class="total_likes">
                                                                            `+res.comment[i].like_count+`
                                                                        </span>
                                                                        <a class="viewlikes" id="`+res.comment[i].id+`" style="display:inline;text-decoration:none;cursor: pointer, color: var(--customer-text-color);">`
                                                                    if(res.comment[i].like_count >1 ){
                                                                        htmlView+=`Likes`
                                                                    }else{
                                                                        htmlView+=`Like`
                                                                    }
                                                                    console.log(res.comment[i].like_count,'first cmt');
                                                            htmlView +=`</a>
                                                                    </p>
                                                </div>`
                                                                

                                        htmlView += `                                                             
                                                  </div>
                                                       
                                                       <p class="comment-reply" data-username=${res.comment[i].name} data-userid=${res.comment[i].user_id}>reply</p>
                                                    </div>
                                                </div>
                                            </div>
                                            `
                }

                            $('.social-media-all-comments').html(htmlView);
                             //comment reply start
                            $(".comment-reply").click(function(){
                                // console.log(this.getAttribute("data-userid"))
                                $(".mentiony-content").append(
                                    `
                                    <span class="mention-area">
                                        <span class="highlight">
                                            <a data-item-id=${this.getAttribute("data-userid")} class="mentiony-link">
                                                @${this.getAttribute("data-username")}
                                            </a>
                                        </span>
                                    </span>
                                    <span class="normal-text">&nbsp;</span>
                                    `
                                    
                                    )
                            })
                            
                            //comment reply end
            }


            $(document).on('click', '.viewlikes', function(e) {
            viewlikes(e);
            })

 


         function viewlikes(e){
            e.preventDefault();
            $(".social-media-all-likes-container").empty();
            $('#staticBackdrop').modal('show');
            // var post_id = $(this).attr('id');
            // var post_id= 7 ;
             var post_id=e.target.id;
            // alert(post_id);
            console.log(post_id,'post id');
            var add_url = "{{ route('likes.comment', [':id']) }}";
            add_url = add_url.replace(':id', post_id);

                    $.ajax({
                        method: "GET",
                        url: add_url,
                            success: function(data) {
                                let htmlView = '';
                                var finalHtmlView = ''
                                var post_likes=data.post_likes
                                console.log(post_likes);

                                for(let i = 0; i < post_likes.length; i++){
                                    htmlView = ''
                                    user_id = post_likes[i].user_id;

                                    var url = "{{ route('socialmedia.profile',[':id']) }}";
                                    url = url.replace(':id', user_id);

                                    if(post_likes[i].profile_image==null){
                                        console.log(post_likes[i].name +"has no profile")
                                        htmlView += `<a class="social-media-all-likes-row-img" href="`+url+`" style="text-decoration:none">
                                                    <img src="{{asset('img/customer/imgs/user_default.jpg')}}"  alt="" style="width:30px;height:30px"/>
                                                    <p>`+post_likes[i].name+`</p>
                                                </a>`
                                    }else{
                                        console.log(post_likes[i].name +"has profile")
                                        htmlView += `<a class="social-media-all-likes-row-img" href="`+url+`" style="text-decoration:none">
                                                    <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+post_likes[i].profile_image+`" alt="" style="width:30px;height:30px"/>
                                                    <p>`+post_likes[i].name+`</p>
                                                </a>`
                                    }

                                    if(post_likes[i].friend_status=='myself'){
                                        htmlView += ``
                                    }else if(post_likes[i].friend_status=='friend'){
                                        htmlView += ``
                                    }else if(post_likes[i].friend_status=='response'){
                                        var add_url = "{{ route('socialmedia.profile', [':user_id']) }}";
                                        var user_id=post_likes[i].user_id;
                                        add_url = add_url.replace(':user_id', user_id);
                                        htmlView += `<a class="customer-primary-btn" href="`+add_url+`" >Response</a>`
                                    }else if(post_likes[i].friend_status=='cancel request'){
                                        htmlView += `<a class="customer-primary-btn profile_cancelrequest" id="`+user_id+`">Cancel</a>`
                                    }else{
                                        htmlView += `<a class="customer-primary-btn profile_addfriend" id="`+user_id+`">Add</a>`
                                    }

                                    finalHtmlView = `<div class="social-media-all-likes-row">
                                        ${htmlView}
                                    </div>`

                                    $('.social-media-all-likes-container').append(finalHtmlView);

                                }

                            }
                    })

        }


            // $('.like_comment').click(function(e){
                $(document).on('click', '.like_comment', function(e) {
                e.preventDefault();
                var isLike=e.target.previousElementSibiling == null ? true : false;
                var comment_id=$(this).attr('id');
                console.log(comment_id)
                var add_url = "{{ route('user.react.comment', [':comment_id']) }}";
                add_url = add_url.replace(':comment_id', comment_id);
                var that = $(this)
                $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                        $.ajax({
                            method: "POST",
                            url: add_url,
                            data:{ isLike : isLike , comment_id: comment_id },
                            success:function(data){
                                that.siblings('p').children('.total_likes_comment').html(data.total_likes)
                                if(that.children('.like-icon-comment').hasClass("already-liked-comment")){
                                    that.children('.like-icon-comment').attr('style','')
                                    that.children('.like-icon-comment').attr('class','like-icon-comment')
                                    that.children(".like-icon-comment").attr('icon','mdi:cards-heart-outline')
                                }else{
                                    that.children('.like-icon-comment').attr('style','color : red')
                                    that.children('.like-icon-comment').attr('class','like-icon-comment already-liked-comment')
                                    that.children(".like-icon-comment").attr('icon','mdi:cards-heart')
                                }

                            }
                        })

        })


            $('.mentiony-content').on('keydown', function(event) {
                console.log(event.which)
                if (event.which == 8 || event.which == 46) {
                    s = window.getSelection();
                    r = s.getRangeAt(0)
                    el = r.startContainer.parentElement

                    console.log(el.classList.contains('mentiony-link') || el.classList.contains('mention-area') || el.classList.contains('highlight'))
                    console.log(el)
                    if (el.classList.contains('mentiony-link') || el.classList.contains('mention-area') || el.classList.contains('highlight')) {
                        console.log('delete mention')
                                el.remove();
                            return;

                    }
                }
                event.target.querySelectorAll('delete-highlight').forEach(function(el) { el.classList.remove('delete-highlight');})
            });


            $(document).on('click', '#delete_comment', function(e) {
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
                            var id = $(this).data('id');
                             var url = "{{ route('post.comment.delete', [':id']) }}";
                             url = url.replace(':id', id);
                             $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                              });
                                $.ajax({
                                    type: "post",
                                    url: url,
                                    datatype: "json",
                                    success: function(data) {
                        ;
                                        fetch_comment();
                                    }
                                })

                        }
                        })
                $('.social-media-left-searched-items-container').empty();
                });

       


    })
</script>


@endpush
