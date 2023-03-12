@extends('customer.training_center.layouts.app')

@section('content')
@include('sweetalert::alert')

<!-- preloader start -->
<div class="preloader js-preloader">
    <div></div>
</div>

<div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">{{__('msg.edit post')}}</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form class="modal-body" id="edit_form" enctype= multipart/form-data>


        {{-- <form class="modal-body" method="POST" action="{{route('post.store')}}" enctype= multipart/form-data>
            @csrf
            @method('POST') --}}
            <input type="hidden" id="edit_post_id">

          <div class="addpost-caption">
            <p>{{__('msg.post caption')}}</p>
            <textarea placeholder="Caption goes here..." name="caption" id="editPostCaption" class="addpost-caption-input"></textarea>
          </div>

          <div class="addpost-photovideo">

            <span class="selectImage">

                <div class="addpost-photovideo-btn">
                    <iconify-icon icon="akar-icons:circle-plus" class="addpst-photovideo-btn-icon"></iconify-icon>
                    <p>{{__('msg.photo/video')}}</p>
                    <input type="file" id="editPostInput" name="editPostInput[]" multiple enctype="multipart/form-data">
                </div>

                <button class="addpost-photovideo-clear-btn" type="button" onclick="clearEditPost()">{{__('msg.clear')}}</button>

            </span>

            <div class="editpost-photo-video-imgpreview-container">
            </div>


            </div>
            {{-- <input type="submit" class="customer-primary-btn addpost-submit-btn" value="Update"> --}}
            {{-- <button type="button" class="customer-primary-btn addpost-submit-btn "  id="editpost-submit-btn">Update</button> --}}
            <button type="submit" class="customer-primary-btn addpost-submit-btn">{{__('msg.post')}}</button>
        </form>

      </div>
    </div>
</div>
    {{-- acc delete modal --}}
<div class="modal fade" id="accDeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Account</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form class="modal-body" id="edit_form" enctype= multipart/form-data>
          <div class="addpost-caption">
            <p>Account Delete</p>
            <textarea placeholder="Caption goes here..." name="caption" id="editPostCaption" class="addpost-caption-input"></textarea>
          </div>
            <button type="submit" class="customer-primary-btn addpost-submit-btn">{{__('msg.delete')}}</button>
        </form>

      </div>
    </div>
</div>
    {{-- end acc delete modal --}}
<!-- The Image Modal -->
<div id="modal01" class="modal-image" onclick="this.style.display='none'">
    <div class="view-media-modal-btns">
        <span class="close-image">&times;</span>
        <a href="#" class="delete-image" id="delete-image" onclick=deleteImage(this)>
            <i class="fa-solid fa-trash fa-xs"></i>
        </a>
    </div>
    <div class="modal-content-image">
      <img id="img01" style="max-width:100%">
    </div>
</div>
<!-- End Image Modal -->

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

<!-- View Comment Modal -->
<div class="modal fade " id="view_comments_modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Comments</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="social-media-all-comments-container">
                <form class="social-media-all-comments-input">
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
                </div>
            </div>
        </div>
      </div>
    </div>
</div>

<!-- Edit Comment Modal -->
<div class="modal fade" id ="edit_comments_modal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

<!-- View Comment Modal End -->

    <a class="back-btn" href="{{route("socialmedia")}}">
        <iconify-icon icon="bi:arrow-left" class="back-btn-icon"></iconify-icon>
    </a>

<div class="customer-profile-parent-container">
    <div class="customer-cover-photo-container">

        @if($user_profile_cover==null || auth()->user()->cover_id==null)
        <img class="customer-cover-photo" src="{{asset('image/cover.jpg')}}">
        @else

        <img class="customer-cover-photo" 
        src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $user_profile_cover->cover_photo}}">
        @endif
        {{-- <h1>{{auth()->user()->profiles->id}}</h1> --}}
        {{-- src="{{asset('storage/post/',auth()->user()->profiles->profile_image)}}" --}}
        <div class="customer-cover-change-btns-container">
            <form method="POST" action="{{route('customer-profile-cover.update')}}" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <button type="submit" class="customer-primary-btn">{{__('msg.confirm')}}</button>

            <button type="button" class="customer-secondary-btn customer-cover-change-cancel-btn">{{__('msg.cancel')}}</button>
        </div>
            <label class="customer-cover-img-change-btn">
                <input type="file" class="customer-cover-img-change-input" name="cover">
                <iconify-icon icon="cil:pen" class="customer-cover-img-change-icon"></iconify-icon>
            </label>
            </form>
        <div class="personal_detail customer-personal-details-form">
            <div class="customer-profile-img-name-container">
                <form method="POST" action="{{route('customer-profile-img.update')}}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="customer-profile-img-container">
                        @if($user_profile_image==null || auth()->user()->profile_id==null)
                            <img class="customer-profile-img" src="{{asset('img/user.jpg')}}">
                        @else
                        <img class="customer-profile-img"
                        src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $user_profile_image->profile_image}}">
                        @endif
                        <label class="customer-profile-img-change-btn">
                            <input type="file" name="profile_image" class="customer-profile-img-change-input">
                            <iconify-icon icon="cil:pen" class="customer-profile-img-change-icon"></iconify-icon>
                        </label>
                    </div>
                    <div class="customer-profile-change-btns-container">
                        <button type="submit" class="customer-primary-btn">{{__('msg.confirm')}}</button>
                        <button type="button" class="customer-secondary-btn customer-profile-change-cancel-btn">{{__('msg.cancel')}}</button>
                    </div>
                </form>
                <form class="" method="POST" action="{{route('customer-profile-name.update')}}">
                    @csrf
                    @method('POST')
                <div class="customer-profile-name-container">
                    <p id="name">{{auth()->user()->name}}</p>
                    <input type="text" value="{{auth()->user()->name}}" class="name" name="name">

                    {{-- <span>(User ID: {{auth()->user()->member_code}})</span> --}}
                    <iconify-icon icon="cil:pen" class="change-name-icon" id="name_edit_pen"></iconify-icon>

                    <div class="customer-change-name-btns-container">
                        <button type="submit" class="customer-primary-btn customer-name-calculate-btn">{{__("msg.save")}}</button>
                        <button type="button" class="customer-secondary-btn customer-name-calculate-btn" id="customer_name_cancel">{{__('msg.cancel')}}</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row d-flex">
        <div class="col-6">
            <form class="customer-bio-form" method="POST" action="{{route('customer-profile-bio.update')}}">
                @csrf
                @method('POST')
                <div class="customer-bio-text">
                    @if (auth()->user()->bio==null)
                        <p class="text-secondary" ></p>
                    @else
                    <p>{{auth()->user()->bio}}</p>
                    @endif
                    <input type="text" name="bio" id="bio" value="{{auth()->user()->bio}}">
                    <iconify-icon icon="cil:pen" class="customer-bio-change-icon" id={{auth()->user()->id}}></iconify-icon>
                </div>
                <div class="customer-bio-btns-container">
                    <button type="submit" class="customer-primary-btn">{{__('msg.confirm')}}</button>
                    <button type="button" class="customer-secondary-btn customer-bio-change-cancel-btn">{{__('msg.cancel')}}</button>
                </div>
            </form>
        </div>
        <div class="col-6 d-flex justify-content-end" style="padding-right: 20px; margin-top:-15px;">
            <button class="customer-primary-btn" id = "delete_account">
                Account Delete
            </button>
        </div>
    </div>
    

    <div class="customer-profile-tabs-container">
        <div class="customer-profile-training-center-tab">
            <iconify-icon icon="fa-solid:dumbbell" class="customer-profile-tab-icon"></iconify-icon>
            <p>{{__("msg.training center")}}</p>
        </div>
        <div class="customer-profile-socialmedia-tab">
            <iconify-icon icon="bi:chat-heart" class="customer-profile-tab-icon"></iconify-icon>
            <p>{{__('msg.social media')}}</p>
        </div>
        <div class="customer-profile-shop-tab">
            <iconify-icon icon="lucide:shopping-cart" class="customer-profile-tab-icon"></iconify-icon>
            <p>{{__('msg.shop')}}</p>
        </div>
    </div>
    <div class="customer-profile-socialmedia-container">
        <div class="customer-profile-social-media-default-container">
            <div class="customer-profile-friends-parent-container">
                <div class="customer-profile-friends-header">
                    @if (count($user_friends)>1)
                    <p>{{count($user_friends)}} {{__('msg.friends')}}</p>
                    @else
                    <p>{{count($user_friends)}} {{__('msg.friend')}}</p>
                    @endif
                    <span class="customer-profile-see-all-fris-btn">
                        {{ __('msg.friends')}}
                        <iconify-icon icon="bi:arrow-right" class="arrow-icon"></iconify-icon>
                    </span>
                </div>

                <div class="customer-profile-friends-container">
                    @forelse ($user_friends as $friend)
                    <div class="customer-profile-friend">

                        <?php $profile=$friend->profiles->first();
                        $profile_id=$friend->profile_id;
                         $img=$friend->profiles->where('id',$profile_id)->first();
                        ?>

                        @if($img==null)
                        <a href="{{route('socialmedia.profile',$friend->id)}}" style="text-decoration:none">
                        <img src="{{asset('img/customer/imgs/user_default.jpg')}}">
                        </a>
                        @else
                        <a href="{{route('socialmedia.profile',$friend->id)}}" style="text-decoration:none">
                            
                        <img
                        src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $img->profile_image}}">
                        </a>
                        @endif
                        <a href="{{route('socialmedia.profile',$friend->id)}}" style="text-decoration:none">
                        <p>{{$friend->name}}</p>
                        </a>
                    </div>
                    @empty
                        <p class="text-secondary p-1">No Friend</p>
                    @endforelse
                </div>
                <div class="row">
                    <div class="col-2">
                        <p href="#" class="social-media-profile-photos-link">{{__('msg.photos')}}</p>
                    </div>
                    <div class="col-6">
                        <p href="#" class="social-media-profile-block-link customer-profile-see-all-block-btn">{{__('msg.block')}}</p>
                    </div>
                </div>
            </div>

            <div class="customer-profile-posts-parent-container">
                <div class="customer-profile-posts-header">
                    <p>{{__("msg.post & activities")}}</p>
                    <select class="customer-profile-selector">
                        <option value="all">{{__('msg.all')}}</option>
                        <option value="saved" class="saved_post_selectbox">{{__("msg.save")}}</option>
                    </select>
                </div>
                <div class="customer-all-posts-container">
                </div>

                {{-- Saved Post Start --}}
                <div class="customer-saved-posts-container">
                </div>
            </div>
        </div>

        <div class="customer-profile-social-media-photoes-container">
            <p class="customer-profile-social-media-photoes-back">
                <iconify-icon icon="material-symbols:arrow-back"></iconify-icon>
                {{__("msg.go back")}}
            </p>
            <div class="social-media-photos-tabs-container">
                <p class="social-media-photos-tab social-media-profiles-tab">{{__("msg.profile photos")}}</p>
                <p class="social-media-photos-tab social-media-covers-tab">{{__("msg.cover photos")}}</p>
            </div>

            <div class="social-media-photos-container social-media-profiles-container">
                @forelse (auth()->user()->profiles->sortByDesc('created_at') as $profile)
                    @if ($profile->cover_photo)

                    @else
                        <div class="social-media-photo">
                            <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $profile->profile_image}}" style="max-width:100%;cursor:pointer"
                            onclick="onClick(this)" id="{{$profile->id}}" class="modal-hover-opacity">
                        </div>
                    @endif
                @empty
                <p>No Profile Photo</p>
                @endforelse
            </div>

            <div class="social-media-photos-container social-media-covers-container">
                @forelse (auth()->user()->profiles->sortByDesc('created_at') as $profile)
                    @if ($profile->profile_image)
                    @else
                        <div class="social-media-photo">
                            <img
                            
                             src="
                             https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $profile->cover_photo}}" style="max-width:100%;cursor:pointer"
                            onclick="onClick(this)" id="{{$profile->id}}" class="modal-hover-opacity">
                        </div>
                    @endif

                @empty
                <p>No Cover Photo</p>
                @endforelse
            </div>

        </div>

        <div class="customer-profile-social-media-fris-container">
            <p class="customer-profile-social-media-photoes-back">
                <iconify-icon icon="material-symbols:arrow-back"></iconify-icon>
                {{__("msg.go back")}}</p>
            <div class="social-media-fris-search">
                <input type="text" placeholder="Search your friends">
                <iconify-icon icon="akar-icons:search" class="search-icon"></iconify-icon>
           </div>

           <div class="social-media-fris-list-container">

           </div>
        </div>

        <div class="customer-profile-social-media-block-container">
            <p class="customer-profile-social-media-photoes-back">
                <iconify-icon icon="material-symbols:arrow-back"></iconify-icon>
                {{__("msg.go back")}}</p>
            <div class="social-media-block-search" style = "">
                <input type="text" placeholder="Search your friends">
                <iconify-icon icon="akar-icons:search" class="search-icon"></iconify-icon>
           </div>

           <div class="social-media-block-list-container">
            {{-- blList --}}
           </div>
        </div>

    </div>
    <div class="customer-profile-shop-container">
        <div class="customer-profile-posts-header">
            <p>{{__("msg.shop & activities")}}</p>
            <select class="customer-shop-profile-selector">
                <option value="shop-all">{{__("msg.all")}}</option>
                <option value="shop-saved" class="saved_post_selectbox">{{__('msg.save')}}</option>
            </select>
        </div>
        <div class="customer-profile-shop-container_data">
        </div>

        {{-- Saved Post Start --}}
        <div class="customer-saved-posts-shop-container">
        </div>
    </div>


    <div class="customer-profile-training-center-container">
        @if(count(auth()->user()->roles)==0)
        {{-- <div class="customer-profile-personaldetails-parent-container"> --}}
        <p class="customer-notraining-message text-secondary">
            You don't have training center information.Please fill information
            <a href="{{route('customer-personal_infos')}}">Training Center</a>
        </p>
        {{-- </div> --}}
        @endif
        @hasanyrole('Platinum|Diamond|Gym Member|Gold|Ruby|Ruby Premium')
        <form class="personal_detail" method="POST" action="{{route('customer-profile.update')}}">
                @csrf
                @method('POST')
            <div class="customer-profile-personaldetails-parent-container">
                <h1>{{__('msg.your profile')}}</h1>
                <div style="float:right;padding-right:40px">
                    <iconify-icon icon="cil:pen" class="change-name-icon" id="pen1"></iconify-icon>
                </div>

                <div class="customer-profile-personaldetails-grid">
                    <div class="customer-profile-personaldetails-left">
                        <div class="customer-profile-personaldetail-container">
                            <p>{{__('msg.age')}}:</p>
                            <div>
                                <input type="number" value="{{auth()->user()->age}}" readonly="readonly" class="age" name="age">
                                <span style = "visibility: hidden;">in</span>
                            </div>
                        </div>
                        <?php
                            $height=auth()->user()->height;
                            $height_ft=floor($height/12);
                            $height_in=$height%12;
                            ?>
                        <div class="customer-profile-personaldetail-container customer-profile-personaldetail-height-container">
                            <p>{{__("msg.height")}}:</p>
                            <select name="height_ft" class="height_ft">
                                <option value="3" {{"3" == $height_ft ? 'selected' : ''}}>3</option>
                                <option value="4" {{"4" == $height_ft ? 'selected' : ''}}>4</option>
                                <option value="5" {{"5" == $height_ft ? 'selected' : ''}}>5</option>
                                <option value="6" {{"6" == $height_ft ? 'selected' : ''}}>6</option>
                            </select>
                            <span>ft</span>
                            <select name="height_in" class="height_in">
                                <option value="0" {{"0" == $height_in ? 'selected' : ''}}>0</option>
                                <option value="1" {{"1" == $height_in ? 'selected' : ''}}>1</option>
                                <option value="2" {{"2" == $height_in ? 'selected' : ''}}>2</option>
                                <option value="3" {{"3" == $height_in ? 'selected' : ''}}>3</option>
                                <option value="4" {{"4" == $height_in ? 'selected' : ''}}>4</option>
                                <option value="5" {{"5" == $height_in ? 'selected' : ''}}>5</option>
                                <option value="6" {{"6" == $height_in ? 'selected' : ''}}>6</option>
                                <option value="7" {{"7" == $height_in ? 'selected' : ''}}>7</option>
                                <option value="8" {{"8" == $height_in ? 'selected' : ''}}>8</option>
                                <option value="9" {{"9" == $height_in ? 'selected' : ''}}>9</option>
                                <option value="10" {{"10" == $height_in ? 'selected' : ''}}>10</option>
                                <option value="11" {{"11" == $height_in ? 'selected' : ''}}>11</option>
                            </select>
                            <span>{{__('msg.in')}}</span>
                        </div>

                        <div class="customer-profile-personaldetail-container">
                            <p>{{__('msg.weight')}}:</p>
                            <div>
                                <input type="number" value="{{auth()->user()->weight}}" class="weight" name="weight" readonly>
                                <span>{{__('msg.lb')}}</span>
                            </div>

                        </div>
                        <div class="customer-profile-personaldetail-container">
                            <p>{{__('msg.neck')}}:</p>
                            <div>
                                <input type="number" value="{{auth()->user()->neck}}" class="neck" name="neck" readonly>
                                <span>{{__('msg.in')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="customer-profile-personaldetails-right">
                        <div class="customer-profile-personaldetail-container">
                            <p>{{__('msg.waist')}}:</p>
                            <div>
                                <input type="number" value="{{auth()->user()->waist}}" name="waist" class="waist" readonly>
                                <span>{{__('msg.in')}}</span>
                            </div>
                        </div>

                        <div class="customer-profile-personaldetail-container ">
                            <p>{{__('msg.hip')}}:</p>
                            <div>
                                <input type="number"  value="{{auth()->user()->hip}}" name="hip" class="hip" readonly>
                                <span>{{__('msg.in')}}</span>
                            </div>
                        </div>

                        <div class="customer-profile-personaldetail-container">
                            <p>{{__("msg.shoulders")}}:</p>
                            <div>
                                <input type="number"  value="{{auth()->user()->shoulders}}" name="shoulders" class="shoulders" readonly>
                                <span>{{__('msg.in')}}</span>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="customer-profile-save-cancel-container">
                    <button type="submit" class="customer-primary-btn customer-bmi-calculate-btn">{{__('msg.save and calculate BMI')}}</button>
                    <button type="button" class="customer-secondary-btn customer-bmi-calculate-btn" id="customer_cancel">{{__('msg.cancel')}}</button>
                </div>


            </div>
        </form>

        <div class="customer-profile-bmi-container">
            <div class="customer-profile-bmi-gradient">
                <div class="percentage-line"></div>
                <div class="percentage-line"></div>
                <div class="percentage-line"></div>
                <div class="customer-profile-bmi-text">
                    <div class="customer-profile-bmi-indicator">
                        <div class="customer-profile-bmi-indicator-line"></div>
                        <div class="customer-profile-bmi-indicator-ball"></div>
                    </div>

                    <?php $bmi=auth()->user()->bmi;
                        if ($bmi <=18.5) {
                            $plan='Weight Gain';
                        }elseif ($bmi>=25) {
                            $plan='Weight Loss';
                        }else {
                            $plan='Body Beauty';
                        }
                    ?>
                    @if ($bmi <=18.5)
                    <p>Your BMI , {{$bmi}} , is underweight.</p>
                    @elseif ($bmi >18.5 && $bmi<=24.9)
                    <p>Your BMI , {{$bmi}} , is normal.</p>
                    @elseif ($bmi >25 && $bmi<=29.9)
                    <p>Your BMI , {{$bmi}} , is overweight.</p>
                    @else
                    <p>Your BMI , {{$bmi}} , is obesity.</p>
                    @endif
                </div>
            </div>
        </div>
        <?php $currentyear=\Carbon\Carbon::now()->format("Y"); ?>
            <select class="weight-chart-filter" onchange="year_filter(this.value)">
                @for ($i = $currentyear; $i >= $year; $i--)
                <option value={{$i}} name="year">{{$i}}</option>
                @endfor
            </select>
        <div class="weight-chart-container" id="weightchart">
            <p>Your Weight History</p>
            <canvas id="myChart"></canvas>
        </div>

        <div class="no-weight-chart" id="weightreview">
            <p style="text-align:center;margin-top:100px;">You don’t have weight history  to review.
                Keep working out.</p>
        </div>
        {{-- @endhasanyrole --}}
            @hasanyrole('Platinum|Diamond|Gym Member')
            <div class="customer-profile-trackers-parent-container">
                <div class="customer-profile-trackers-headers-container">
                    <div class="customer-profile-tracker-header" id="workout">
                        {{__('msg.work out')}}
                    </div>
                    <div class="customer-profile-tracker-header" id="meal">
                        {{__('msg.meal')}}
                    </div>
                    <div class="customer-profile-tracker-header" id="water">
                        {{__('msg.water')}}
                    </div>
                </div>

                <div class="customer-profile-tracker-workout-container">

                    <div id="my-calendar"></div>

                    <form class="customer-profile-days-container customer-profile-workout-days-container">
                        <div class="customer-profile-days-btn" id="workout-today">
                            {{__('msg.today')}}
                        </div>
                        <div class="customer-profile-days-btn" id = "workout-7days">
                           {{__('msg.last 7 days')}}
                        </div>

                        <div class="customer-profile-fromto-inputs-container">
                            <div class="customer-profile-from">
                                <p>{{__('msg.from')}}:</p>
                                <input type="date" id="from_date">
                            </div>
                            <div class="customer-profile-to">
                                <p>{{__('msg.to')}}:</p>
                                <input type="date" id="to_date">
                            </div>
                        </div>

                        <button type="button" class="customer-profile-workout-filter-btn">{{__("msg.filter")}}</button>
                    </form>

                    <div class="customer-profile-workout-list-parent-container">

                    </div>

                </div>
                <div class="customer-profile-tracker-meal-container">
                    <div class="customer-profile-days-container">
                        <div class="customer-profile-days-btn" id="meal-today">
                            {{__('msg.today')}}
                        </div>
                        <div class="customer-profile-days-btn" id = "meal-7days">
                            {{__('msg.last 7 days')}}
                        </div>
                    </div>

                    <div class="customer-7days-filter-meal-container">

                    </div>

                    <div class="customer-7days-meal-tables-container"></div>
                </div>
                {{-- <div class="customer-post-container">
                    <div class="customer-post-header">
                        <div class="customer-post-name-container">
                            <img src="{{asset('image/cover.jpg')}}">
                            <div class="customer-post-name">
                                <p>User Name</p>
                                <span>19 Sep 2022, 11:02 AM</span>
                            </div> --}}




                {{-- </div> --}}
                <div class="customer-profile-tracker-water-container">
                    <div class="customer-profile-days-container">
                        <div class="customer-profile-days-btn" id="water-today">
                            {{__('msg.today')}}
                        </div>
                        <div class="customer-profile-days-btn" id = "water-7days">
                            {{__('msg.last 7 days')}}
                        </div>
                    </div>

                    <div class="customer-7days-filter-water-container">

                    </div>

                    <div class="customer-profile-water-track-history-container">
                        <div class="card-chart">
                            <div class="card-donut water-chart" data-size="100" data-thickness="8"></div>
                            <div class="card-center">
                            <span class="card-value"></span>
                            <div class="card-label"></div>
                            </div>
                        </div>

                        <div class="customer-profile-water-track-history-text">
                            <p></p>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
            @endhasanyrole
        @endhasanyrole
    </div>

</div>

@endsection
@push('scripts')
    @hasanyrole('Platinum|Diamond|Gym Member|Gold|Ruby|Ruby Premium')
    <script>
        let myChart=null;
        function linechart(data){
            var weight_history=data;
            if(weight_history.length<2){
                $(".weight-chart-filter").show();
                $("#weightreview").show();
                $("#weightchart").hide();
            }else{
                //$("#weightchart").show();
                $(".weight-chart-filter").show();
                $("#weightreview").hide();
                $("#weightchart").show();

                let weight = [];
                let date = [];
                for(let i = 0; i < weight_history.length; i++){

                    weight.push(

                    weight_history[i].weight
                    );

                    date.push(

                    weight_history[i].date

                    );

                    }

                    const labels = date;

                    console.log(weight);
                    const data = {
                        labels: labels,
                        datasets: [{
                        label: 'Weight(lb)',
                        fill: true,

                        borderColor: "#4D72E8",
                        backgroundColor:"rgba(77,114,232,0.3)",

                        data:weight,

                        }]
                    };

                    const config = {
                        type: 'line',
                        data: data,
                        options: {
                            maintainAspectRatio: false,
                        }
                    };

                    // const myChart = new Chart(
                    //     document.getElementById('myChart'),
                    //     config
                    // );
                    var ctx=document.getElementById('myChart').getContext("2d");

                    if(myChart!=null){
                    myChart.destroy();
                    }
                    myChart = new Chart(ctx,
                        config
                    );

            }
        }

        function year_filter(value) {
           console.log(value,"year")
            var year=value;
            var url = "{{ route('customer-profile.year', [':year']) }}";
                url = url.replace(':year', year);
            $.ajax({
                        type: "GET",
                        url: url,
                        datatype: "json",
                        success: function(data) {
                            var data=data.weight_history;

                            linechart(data);
                        }
            })
        }

        $( document ).ready(function() {
            //destroyChart();
            var data = @json($weight_history);
            // destroyChart();
            linechart(data);
            var bmi=@json($bmi);
            $('.customer-profile-bmi-text').animate({ left: `+=${bmi}%` }, "slow");
            $(".name").hide();
            $('.customer-name-calculate-btn').hide();
            $(".customer-bmi-calculate-btn").hide();

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');

            var yyyy = today.getFullYear();
            var d = String(today.getDate());
            const monthNames = ["January", "Febuary", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
            ];
            var m = monthNames[today.getMonth()];

            today =  yyyy+'-'+mm+'-'+dd;
            tdy =  d+' '+m+', '+yyyy;

            $('select.height_ft').attr('disabled', true);
            $('select.height_in').attr('disabled', true);
            //on clicking one of the butttons of last 7 days (water)
            $("#pen1").on('click', function(event){
                event.stopPropagation();
                event.stopImmediatePropagation();
                $(".age").removeAttr("readonly");
                $(".weight").removeAttr("readonly");
                $(".neck").removeAttr("readonly");
                $(".waist").removeAttr("readonly");
                $(".hip").removeAttr("readonly");
                $(".shoulders").removeAttr("readonly");
                $(".customer-bmi-calculate-btn").show();
                $('select.height_ft').attr('disabled', false);
                $('select.height_in').attr('disabled', false);
                $('.change-name-icon').hide();
                $('.customer-name-calculate-btn').hide();
                $("#name").show();
                $(".name").hide();
            });

            $("#customer_cancel").on('click', function(event){
                event.stopPropagation();
                event.stopImmediatePropagation();
                $(".age").attr('readonly', true);
                $(".weight").attr('readonly', true);
                $(".neck").attr('readonly', true);
                $(".waist").attr('readonly', true);
                $(".hip").attr('readonly', true);
                $(".shoulders").attr('readonly', true);
                $(".customer-bmi-calculate-btn").hide();
                $('select.height_ft').attr('disabled', true);
                $('select.height_in').attr('disabled', true);
                $('.change-name-icon').show();
                $('.customer-name-calculate-btn').show();
                $("#name").show();
                $(".name").hide();
                $('.customer-name-calculate-btn').hide();
                $('#customer_name_cancel').hide();

            });

            $('#name_edit_pen').on('click',function(){
                $(".name").show();
                $('.customer-name-calculate-btn').show();
                $('#name_edit_pen').hide();
                $("#name").hide();
            })

            $("#customer_name_cancel").on('click',function(event){
                $(".name").hide();
                $('.customer-name-calculate-btn').hide();
                $('#name_edit_pen').show();
                $("#name").show();
            })

            $(".personal_detail").submit(function(){
                $('.customer-bmi-calculate-btn').attr('disabled', true);
            })

            $("#my-calendar").zabuto_calendar({

                data:@json($workout_date)

            });

            const sevenDays = Last7Days()
            // console.log(Last7DaysWithoutformat)

            //adding last 7days buttons
            $.each(sevenDays,function(index,value){
                $(".customer-7days-filter-water-container").append(`
                <div class="customer-7days-day-water-btn">${value}</div>
                `)
                $(".customer-7days-filter-meal-container").append(`
                <div class="customer-7days-day-meal-btn">${value}</div>
                `)
            })

            $(".customer-profile-workout-filter-btn").on('click',function(event){
                to=$('#to_date').val();
                from=$('#from_date').val();
                var url = "{{ route('workout_filter', [':from', ':to']) }}";
                url = url.replace(':from', from);
                url = url.replace(':to', to);
                $.ajax({
                        type: "GET",
                        url: url,
                        datatype: "json",
                        success: function(data) {
                            var workouts= data.workouts;
                            $(".customer-profile-workout-list-parent-container").empty();
                            $(".customer-profile-workout-list-parent-container").append(`
                            <div class="customer-profile-workout-list-header">
                            <p>${data.from} - ${data.to}</p>
                            <div class="customer-profile-workoutdetails-container">
                                <div class="customer-profile-workoutdetail">
                                    <iconify-icon icon="icon-park-outline:time" class="customer-profile-time-icon"></iconify-icon>
                                    <p>${data.time_min}mins ${data.time_sec}sec</p>
                                </div>
                                <div class="customer-profile-workoutdetail">
                                    <iconify-icon icon="codicon:flame" class="customer-profile-flame-icon"></iconify-icon>
                                    <p>${data.cal_sum}</p>
                                </div>
                            </div>
                        </div>

                            ${workouts.map((item,index) => (
                                `<div class="customer-profile-workout-row">
                                <div class="customer-profile-workout-row-namedate-container">
                                    <p>${item.workout_plan_type}</p>
                                    <div class="customer-profile-workout-row-date">
                                        <iconify-icon icon="bx:calendar" class="customer-profile-date-icon"></iconify-icon>
                                        <p>${item.date}</p>
                                    </div>
                                </div>

                                <div class="customer-profile-workoutdetails-container">
                                    <div class="customer-profile-workoutdetail">
                                        <iconify-icon icon="icon-park-outline:time" class="customer-profile-time-icon"></iconify-icon>
                                        <p>${Math.floor(item.time/60)}mins ${item.time%60}sec</p>
                                    </div>
                                    <div class="customer-profile-workoutdetail">
                                        <iconify-icon icon="codicon:flame" class="customer-profile-flame-icon"></iconify-icon>
                                        <p>${item.calories}</p>
                                    </div>
                                </div>
                            </div>`
                            )).join('')}
                        `)

                        }
                    });

            })

            //on clicking one of the butttons of last 7 days (water)
            $(".customer-7days-day-water-btn").on('click', function(event){
                $(".customer-7days-day-water-btn").removeClass("customer-7days-day-btn-active")
                $(this).addClass("customer-7days-day-btn-active")
                event.stopPropagation();
                event.stopImmediatePropagation();
                console.log($(this).text())
                date = $(this).text();
                $.ajax({
                        type: "GET",
                        url: "/customer/lastsevenDay/"+ date,
                        datatype: "json",
                        success: function(data) {

                            if(data.water == null){
                                renderCircle(3000,0)
                            }
                            else{
                                renderCircle(3000,data.water.update_water)
                            }

                        }
                    });
                // renderCircle(3000,600)
            });

            //on clicking one of the butttons of last 7 days (meal)
            $(".customer-7days-day-meal-btn").on('click', function(event){

                $(".customer-7days-day-meal-btn").removeClass("customer-7days-day-btn-active")
                $(this).addClass("customer-7days-day-btn-active")
                $(".customer-7days-meal-tables-container").empty();
                event.stopPropagation();
                event.stopImmediatePropagation();

                console.log($(this).text())
                meal_sevendays($(this).text())

                // renderCircle(3000,600)

            });


            //hide 7days buttons (default)
            $(".customer-7days-filter-water-container").hide()
            $(".customer-7days-filter-meal-container").hide()
            //workout tab active by default
            $('#workout').addClass('customer-profile-tracker-header-active')
            $('.customer-profile-tracker-workout-container').show()
            $('.customer-profile-tracker-meal-container').hide()
            $('.customer-profile-tracker-water-container').hide()

            //show today's meal by default
            $("#meal-today").addClass("customer-profile-days-btn-active")
            meal_sevendays(today)

            //show today's water by default
            $("#water-today").addClass("customer-profile-days-btn-active")
            todaywater()

            function todaywater(){
                $.ajax({
                        type: "GET",
                        url: "/customer/today",
                        datatype: "json",
                        success: function(data) {

                            if(data.water == null){
                                renderTodayCircle(3000,0)
                            }
                            else{
                                renderTodayCircle(3000,data.water.update_water)
                            }

                        }
                    });
            }

            //show today's workout by default
            $("#workout-today").addClass("customer-profile-days-btn-active")
            renderWorkoutList()

            //on clicking workout tab
            $('#workout').click(function(){
                $('#workout').addClass('customer-profile-tracker-header-active')
                $('#meal').removeClass('customer-profile-tracker-header-active')
                $('#water').removeClass('customer-profile-tracker-header-active')

                $('.customer-profile-tracker-workout-container').show()
                $('.customer-profile-tracker-meal-container').hide()
                $('.customer-profile-tracker-water-container').hide()
            })


            //on clicking meal tab
            $('#meal').click(function(){
                $('#workout').removeClass('customer-profile-tracker-header-active')
                $('#meal').addClass('customer-profile-tracker-header-active')
                $('#water').removeClass('customer-profile-tracker-header-active')

                $('.customer-profile-tracker-workout-container').hide()
                $('.customer-profile-tracker-meal-container').show()
                $('.customer-profile-tracker-water-container').hide()
            })


            //on clicking water tab
            $('#water').click(function(){
                $('#workout').removeClass('customer-profile-tracker-header-active')
                $('#meal').removeClass('customer-profile-tracker-header-active')
                $('#water').addClass('customer-profile-tracker-header-active')

                $('.customer-profile-tracker-workout-container').hide()
                $('.customer-profile-tracker-meal-container').hide()
                $('.customer-profile-tracker-water-container').show()
            })

            //on clicking today (meal)
            $("#meal-today").click(function(){

                $("#meal-today").addClass("customer-profile-days-btn-active")
                $("#meal-7days").removeClass("customer-profile-days-btn-active")
                $(".customer-7days-filter-meal-container").hide()
                $(".customer-7days-meal-tables-container").empty();

                meal_sevendays(today)
            })

            function meal_sevendays(date){
                var add_url = "{{ route('meal_sevendays',[':date']) }}";
                add_url = add_url.replace(':date', date);

                $.ajax({
                        type: "GET",
                        url: add_url,
                        datatype: "json",
                        success: function(data) {
                            //
                            var breakFast =data.meal_breafast ? data.meal_breafast : [];
                            var lunch =data.meal_lunch ? data.meal_lunch : [];
                            var snack =data.meal_snack ? data.meal_snack : [];
                            var dinner =data.meal_dinner ? data.meal_dinner : [];
                            $(".customer-7days-meal-tables-container").append(`
            <div class="customer-profile-meal-table-container">
                        <h1>Breakfast</h1>
                        <table class="customer-profile-meal-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Cal</th>
                                    <th>Carb</th>
                                    <th>Protein</th>
                                    <th>Fat</th>
                                    <th>Servings</th>
                                </tr>
                            </thead>

                            <tbody>
                                ${breakFast.map((item,index) => (
                                    `<tr class="meal-table-total">
                                    <td></td>
                                    <td>${index+1}</td>
                                    <td>${item.name}</td>
                                    <td>${item.calories} </td>
                                    <td>${item.carbohydrates}</td>
                                    <td>${item.protein}</td>
                                    <td>${item.fat}</td>
                                    <td>${item.serving}</td>
                                </tr>`
                                )).join('')}
                            </tbody>
                            <tr class="meal-table-total">
                                <td>Total</td>
                                <td></td>
                                <td></td>
                                <td>${data.total_calories_breakfast}</td>
                                <td>${data.total_carbohydrates_breakfast}</td>
                                <td>${data.total_protein_breakfast}</td>
                                <td>${data.total_fat_breakfast}</td>
                                <td>${data.total_serving_breakfast}</td>
                            </tr>
                        </table>
                        <h1>Lunch</h1>
                        <table class="customer-profile-meal-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Cal</th>
                                    <th>Carb</th>
                                    <th>Protein</th>
                                    <th>Fat</th>
                                    <th>Servings</th>
                                </tr>
                            </thead>

                            <tbody>
                                ${lunch.map((item,index) => (
                                    `<tr class="meal-table-total">
                                    <td></td>
                                    <td>${index+1}</td>
                                    <td>${item.name}</td>
                                    <td>${item.calories}</td>
                                    <td>${item.carbohydrates}</td>
                                    <td>${item.protein}</td>
                                    <td>${item.fat}</td>
                                    <td>${item.serving}</td>
                                </tr>`
                                )).join('')}
                            </tbody>
                            <tr class="meal-table-total">
                                <td>Total</td>
                                <td></td>
                                <td></td>
                                <td>${data.total_calories_lunch}</td>
                                <td>${data.total_carbohydrates_lunch}</td>
                                <td>${data.total_protein_lunch}</td>
                                <td>${data.total_fat_lunch}</td>
                                <td>${data.total_serving_lunch}</td>
                            </tr>
                        </table>
                        <h1>Snack</h1>
                        <table class="customer-profile-meal-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Cal</th>
                                    <th>Carb</th>
                                    <th>Protein</th>
                                    <th>Fat</th>
                                    <th>Servings</th>
                                </tr>
                            </thead>

                            <tbody>
                                ${snack.map((item,index) => (
                                    `<tr class="meal-table-total">
                                    <td></td>
                                    <td>${index+1}</td>
                                    <td>${item.name}</td>
                                    <td id = "cal">${item.calories}</td>
                                    <td>${item.carbohydrates}</td>
                                    <td>${item.protein}</td>
                                    <td>${item.fat}</td>
                                    <td>${item.serving}</td>
                                </tr>
                                ` )).join('')}
                            </tbody>
                            <tr class="meal-table-total">
                                <td>Total</td>
                                <td></td>
                                <td></td>
                                <td>${data.total_calories_snack}</td>
                                <td>${data.total_carbohydrates_snack}</td>
                                <td>${data.total_protein_snack}</td>
                                <td>${data.total_fat_snack}</td>
                                <td>${data.total_serving_snack}</td>
                            </tr>
                        </table>
                        <h1>Dinner</h1>
                        <table class="customer-profile-meal-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Cal</th>
                                    <th>Carb</th>
                                    <th>Protein</th>
                                    <th>Fat</th>
                                    <th>Servings</th>
                                </tr>
                            </thead>

                            <tbody>
                                ${dinner.map((item,index) => (
                                    `<tr class="meal-table-total">
                                    <td></td>
                                    <td>${index+1}</td>
                                    <td>${item.name}</td>
                                    <td>${item.calories}</td>
                                    <td>${item.carbohydrates}</td>
                                    <td>${item.protein }</td>
                                    <td>${item.fat}</td>
                                    <td>${item.serving}</td>
                                </tr>`
                                )).join('')}
                            </tbody>
                            <tr class="meal-table-total">
                                <td>Total</td>
                                <td></td>
                                <td></td>
                                <td>${data.total_calories_dinner}</td>
                                <td>${data.total_carbohydrates_dinner}</td>
                                <td>${data.total_protein_dinner}</td>
                                <td>${data.total_fat_dinner}</td>
                                <td>${data.total_serving_dinner}</td>
                            </tr>
                        </table>
                    </div>
            `);
                        }
                    })
            }

            //on clicking last 7 days (meal)
            $("#meal-7days").click(function(){
                $("#meal-today").removeClass("customer-profile-days-btn-active")
                $("#meal-7days").addClass("customer-profile-days-btn-active")
                $(".customer-7days-filter-meal-container").show()
                $(".customer-7days-day-meal-btn").removeClass("customer-7days-day-btn-active")
                $(".customer-7days-day-meal-btn").last().addClass("customer-7days-day-btn-active");
                $(".customer-7days-meal-tables-container").empty();
                console.log($(".customer-7days-day-meal-btn").last().text())
                meal_sevendays(today)
            })

            //on clicking today (water)
            $("#water-today").click(function(){
                $("#water-today").addClass("customer-profile-days-btn-active")
                $("#water-7days").removeClass("customer-profile-days-btn-active")
                $(".customer-7days-filter-water-container").hide()
                // alert("okk");
                todaywater();
            })

            //on clicking last 7 days (water)
            $("#water-7days").click(function(){
                $("#water-today").removeClass("customer-profile-days-btn-active")
                $("#water-7days").addClass("customer-profile-days-btn-active")
                $(".customer-7days-filter-water-container").show()
                $(".customer-7days-day-water-btn").removeClass("customer-7days-day-btn-active")
                $(".customer-7days-day-water-btn").last().addClass("customer-7days-day-btn-active")
                console.log($(".customer-7days-day-water-btn").last().text())
                todaywater();
            })

            //on clicking today (workout)
            $("#workout-today").click(function(){
                $("#workout-today").addClass("customer-profile-days-btn-active")
                $("#workout-7days").removeClass("customer-profile-days-btn-active")
                renderWorkoutList()
            })

            //on clicking last 7 days (water)
            $("#workout-7days").click(function(){
                $("#workout-today").removeClass("customer-profile-days-btn-active")
                $("#workout-7days").addClass("customer-profile-days-btn-active")
                workout_7days()

            })


        });

        function workout_7days(){

            $.ajax({
                        type: "GET",
                        url: "/customer/workout/lastsevenDay/",
                        datatype: "json",
                        success: function(data) {
                            var workouts= data.workouts;
                            $(".customer-profile-workout-list-parent-container").empty()
                            $(".customer-profile-workout-list-parent-container").append(`
                            <div class="customer-profile-workout-list-header">
                            <p>${data.seven} - ${data.current}</p>
                            <div class="customer-profile-workoutdetails-container">
                                <div class="customer-profile-workoutdetail">
                                    <iconify-icon icon="icon-park-outline:time" class="customer-profile-time-icon"></iconify-icon>
                                    <p>${data.time_min}mins ${data.time_sec}sec</p>
                                </div>
                                <div class="customer-profile-workoutdetail">
                                    <iconify-icon icon="codicon:flame" class="customer-profile-flame-icon"></iconify-icon>
                                    <p>${data.cal_sum}</p>
                                </div>
                            </div>
                        </div>

                        ${workouts.map((item,index) => (
                            `<div class="customer-profile-workout-row">
                            <div class="customer-profile-workout-row-namedate-container">
                                <p>${item.workout_plan_type}</p>
                                <div class="customer-profile-workout-row-date">
                                    <iconify-icon icon="bx:calendar" class="customer-profile-date-icon"></iconify-icon>
                                    <p>${item.date}</p>
                                </div>
                            </div>

                            <div class="customer-profile-workoutdetails-container">
                                <div class="customer-profile-workoutdetail">
                                    <iconify-icon icon="icon-park-outline:time" class="customer-profile-time-icon"></iconify-icon>
                                    <p>${Math.floor(item.time/60)}mins ${item.time%60}sec</p>
                                </div>
                                <div class="customer-profile-workoutdetail">
                                    <iconify-icon icon="codicon:flame" class="customer-profile-flame-icon"></iconify-icon>
                                    <p>${item.calories}</p>
                                </div>
                            </div>
                        </div>`
                        )).join('')}


            `)
                        }
                    });

        }
        //getting the last 7 days from today
        function Last7Days () {
            var result = [];
            for (var i=1; i<=7; i++) {
                var d = new Date();
                d.setDate(d.getDate() - i);
                result.push( formatDate(d) )
            }

            return(result);
        }
        //formatting the date of last 7 days
        function formatDate(date){
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ];
            var dd = date.getDate();
            // var mm = monthNames[date.getMonth()];
            var mm = date.getMonth()+1;
            var yyyy = date.getFullYear();
            if(dd<10) {dd='0'+dd}
            if(mm<10) {mm='0'+mm}
            date = yyyy+'-'+mm+'-'+dd;
            return date
        }

        //rendering last 7 days water circle progress
        function renderCircle(total,taken){
            var result = taken / total
            var color
            console.log('last 7 days',taken)

            if(taken < 3000 ){
                console.log("fail")
                $(".customer-profile-water-track-history-text span").text('Mission Failed')
                color = '#FF0000'
                $('.water-chart').circleProgress({
                    startAngle: 1.5 * Math.PI,
                    lineCap: 'round',
                    value: result,
                    emptyFill: '#D9D9D9',
                    fill: { 'color': '#FF0000' }
                });
            }else if(taken >= 3000){
                console.log("complete")
                $(".customer-profile-water-track-history-text  span").text('Mission Complete')
                $('.water-chart').circleProgress({
                    startAngle: 1.5 * Math.PI,
                    lineCap: 'round',
                    value: result,
                    emptyFill: '#D9D9D9',
                    fill: {
                        gradient: ["#3aeabb", "#fdd250"]
                    }
                });
            }

            $(".customer-profile-water-track-history-text p").text(`You Drunk ${taken}/${total} ML of Your Daily Mission.`)

        }

        //rendering today water circle progress
        function renderTodayCircle(total,taken){
            var result = taken / total
            console.log('today',taken)

            if(taken < 3000 ){
                console.log("keep drinking")
                $(".customer-profile-water-track-history-text  span").text('Keep Drinking')
                $('.water-chart').circleProgress({
                    startAngle: 1.5 * Math.PI,
                    lineCap: 'round',
                    value: result,
                    emptyFill: '#D9D9D9',
                    fill: { 'color': "#3CADDD" }
                });

            }else if(taken >= 3000){
                console.log("complete")
                $(".customer-profile-water-track-history-text  span").text('Mission Complete')
                $('.water-chart').circleProgress({
                    startAngle: 1.5 * Math.PI,
                    lineCap: 'round',
                    value: result,
                    emptyFill: '#D9D9D9',
                    fill: {
                        gradient: ["#3aeabb", "#fdd250"]
                    }
                });
            }



            $(".customer-profile-water-track-history-text p").text(`You Drunk ${taken}/${total} ML of Your Daily Mission.`)

        }

        //rendering meal table
        function renderMealTable(){
            $(".customer-7days-meal-tables-container").empty()

            var breakFast = []
            var lunch = []
            var snack = []
            var dinner = []


        }

        //rendering workoutList
        function renderWorkoutList(){
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = today.toLocaleString('default', { month: 'short' });
            var yyyy = today.getFullYear();

            today =  yyyy+'-'+mm+'-'+dd;
            const workouts = @json($workouts);
            const time_sec=@json($time_sec);
            const time_min=@json($time_min);
            const cal_sum=@json($cal_sum);

            $(".customer-profile-workout-list-parent-container").empty()
            $(".customer-profile-workout-list-parent-container").append(`
            <div class="customer-profile-workout-list-header">
                            <p>${dd}, ${mm}, ${yyyy}</p>
                            <div class="customer-profile-workoutdetails-container">
                                <div class="customer-profile-workoutdetail">
                                    <iconify-icon icon="icon-park-outline:time" class="customer-profile-time-icon"></iconify-icon>
                                    <p>${time_min}mins ${time_sec}sec</p>
                                </div>
                                <div class="customer-profile-workoutdetail">
                                    <iconify-icon icon="codicon:flame" class="customer-profile-flame-icon"></iconify-icon>
                                    <p>${cal_sum}</p>
                                </div>
                            </div>
                        </div>

                        ${workouts.map((item,index) => (
                            `<div class="customer-profile-workout-row">
                            <div class="customer-profile-workout-row-namedate-container">
                                <p>${item.workout_plan_type}</p>
                                <div class="customer-profile-workout-row-date">
                                    <iconify-icon icon="bx:calendar" class="customer-profile-date-icon"></iconify-icon>
                                    <p>${item.date}</p>
                                </div>
                            </div>

                            <div class="customer-profile-workoutdetails-container">
                                <div class="customer-profile-workoutdetail">
                                    <iconify-icon icon="icon-park-outline:time" class="customer-profile-time-icon"></iconify-icon>
                                    <p>${Math.floor(item.time/60)}mins ${item.time%60}sec</p>
                                </div>
                                <div class="customer-profile-workoutdetail">
                                    <iconify-icon icon="codicon:flame" class="customer-profile-flame-icon"></iconify-icon>
                                    <p>${item.calories}</p>
                                </div>
                            </div>
                        </div>`
                        )).join('')}


            `)
        }
    </script>
    @endhasanyrole


<script>

 // program to display a text using setTimeout method
        function onClick(element) {

            var profile_id=$(element).attr('id');
            console.log(profile_id);

            document.getElementById("img01").src = element.src;
            document.getElementById("delete-image").name=profile_id;
            document.getElementById("modal01").style.display = "block";
        }

    $(document).ready(function() {

         all_posts();
         shop_all_posts();
            $(document).on('click', '.like', function(e) {
                e.preventDefault();
                $('.staticBackdrop').show();
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

        $(document).on('click', '.viewlikes', function(e) {
            viewlikes(e);
        })

        $(document).on('click', '.customer-post-header-icon', function(e) {
            $(this).next().toggle()
        })

        function viewlikes(e){
            e.preventDefault();
            $(".social-media-all-likes-container").empty();
            $('#staticBackdrop').modal('show');
            var post_id=e.target.id;
            console.log(post_id,'post id');
            var add_url = "{{ route('profile.likes.view', [':post_id']) }}";
            add_url = add_url.replace(':post_id', post_id);

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

        function saved_posts(e){
            e.preventDefault();
            var url="{{route('saved.post')}}"
            $.ajax({
                    method: "GET",
                    url:url,
                    dataType: "json",
                    success: function (data) {

                    console.log(data.save_posts,'saved_posts')
                    var save_posts=data.save_posts;

                        var auth_user={{auth()->user()->id}};

                        let htmlView = '';
                        if(save_posts.length <= 0){
                            htmlView+= `No data found.`;
                        }else{
                            for(let i=0;i<save_posts.length;i++){
                                htmlView += `<div class="customer-post-container">
                                            <div class="customer-post-header">
                                                <div class="customer-post-name-container">`

                                if(save_posts[i].profile_image===null){
                                    htmlView +=`<img class="nav-profile-img" src="{{asset('img/customer/imgs/user_default.jpg')}}"/>`

                                }else{
                                    htmlView +=`<img class="nav-profile-img" src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+save_posts[i].profile_image+`"/>`
                                }
                                htmlView +=`<div class="customer-post-name">
                                                        <p>`+save_posts[i].name+`</p>
                                                        <span>`+save_posts[i].date+`</span>
                                                    </div>
                                                    </div>
                                                    <iconify-icon icon="bi:three-dots-vertical" class="customer-post-header-icon"></iconify-icon>
                                                    <div class="post-actions-container">
                                                    <a style="text-decoration:none" class="post_save" id=`+save_posts[i].id+`>
                                                        <div class="post-action">
                                                            <iconify-icon icon="bi:save" class="post-action-icon"></iconify-icon>
                                                                <p class="save">Unsave</p>
                                                        </div>
                                                    </a>`
                                                    if(auth_user==save_posts[i].user_id){
                                                    htmlView +=`<a id="edit_post" data-id="`+save_posts[i].id+`" data-bs-toggle="modal" >
                                                                    <div class="post-action">
                                                                        <iconify-icon icon="material-symbols:edit" class="post-action-icon"></iconify-icon>
                                                                        <p>Edit</p>
                                                                    </div>
                                                                </a>
                                                                <a id="delete_post" data-id="`+save_posts[i].post_id+`">
                                                                    <div class="post-action">
                                                                    <iconify-icon icon="material-symbols:delete-forever-outline-rounded" class="post-action-icon"></iconify-icon>
                                                                    <p>Delete</p>
                                                                    </div>
                                                                </a>`
                                                    }else{
                                                        htmlView +=``
                                                    }
                                                    htmlView += `</div>
                                                                </div>
                                                                <div class="customer-content-container">
                                                                `
                                                    if(save_posts[i].media===null){
                                                        htmlView +=`<p>`+save_posts[i].caption+`</p>`
                                                    }else{

                                                    var caption =save_posts[i].caption ? save_posts[i].caption : '';
                                                        htmlView +=`<p>`+caption+`</p>
                                                                        <div class="customer-media-container" data-id="`+save_posts[i].post_id+`">
                                                                            `
                                                    var imageFile = save_posts[i].media
                                                    var imageArr = jQuery.parseJSON(imageFile);

                                                    $.each(imageArr,function(key,val){
                                                        var extension = val.substr( (val.lastIndexOf('.') +1) );

                                                        switch(extension) {
                                                                case 'jpg':
                                                                case 'png':
                                                                case 'gif':
                                                                case 'jpeg':
                                                                htmlView += ` <div class="customer-media">
                                                                        <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+val+`">
                                                                        </div>`
                                                                break;
                                                                case 'mp4':
                                                                htmlView += ` <div class="customer-media">
                                                                        <video controls>
                                                                        <source src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+val+`">
                                                                        </video>
                                                                        </div>`
                                                                break;

                                                            }
                                                    });
                                                        htmlView +=  `
                                                                    </div>
                                                                    <div id="slider-wrapper" class="social-media-media-slider">
                                                                        <iconify-icon icon="akar-icons:cross" class="slider-close-icon"></iconify-icon>

                                                                        <div id="image-slider" class="image-slider">
                                                                            <ul class="ul-image-slider">`
                                                    $.each(imageArr,function(k,v){
                                                        var exten = v.substr( (v.lastIndexOf('.') +1) );
                                                        switch(exten) {
                                                                case 'jpg':
                                                                case 'png':
                                                                case 'gif':
                                                                case 'jpeg':
                                                                htmlView += `<li>
                                                                        <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+v+`" alt="" />
                                                                    </li>`
                                                                break;
                                                                case 'mp4':
                                                                htmlView += `<li><video controls>
                                                                            <source src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+v+`">
                                                                            </video> </li>`
                                                                break;
                                                                    }

                                                    });
                                                        htmlView += `</ul>
                                                                        </div>
                                                                        <div id="thumbnail" class="img-slider-thumbnails">
                                                                            <ul>`
                                                    $.each(imageArr,function(k,v){
                                                        var exten = v.substr( (v.lastIndexOf('.') +1) );
                                                        switch(exten) {
                                                                case 'jpg':
                                                                case 'png':
                                                                case 'gif':
                                                                case 'jpeg':
                                                                htmlView += `<li>
                                                                        <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+v+`" alt="" />
                                                                    </li>`
                                                                break;
                                                                case 'mp4':
                                                                htmlView += `<li><video controls>
                                                                            <source src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+v+`">
                                                                            </video> </li>`
                                                                break;
                                                                    }
                                                    });

                                                        htmlView += `</ul></div></div></div>`
                                                    }
                                                    htmlView += ` <div class="customer-post-footer-container">
                                                                    <div class="customer-post-like-container">
                                                                    <a class="like" id="`+save_posts[i].post_id+`">`
                                                    if(save_posts[i].isLike==0){
                                                        htmlView +=`
                                                        <iconify-icon icon="mdi:cards-heart-outline" class="like-icon"></iconify-icon>`

                                                    }else{
                                                        htmlView+=`
                                                        <iconify-icon icon="mdi:cards-heart" style="color: red;" class="like-icon already-liked"></iconify-icon>`
                                                    }
                                                        htmlView +=`</a>
                                                                    <p>
                                                                        <span class="total_likes">
                                                                            `+save_posts[i].total_likes+`
                                                                        </span>
                                                                        <a class="viewlikes" id="`+save_posts[i].post_id+`">`
                                                                    if(save_posts[i].total_likes >1 ){
                                                                        htmlView+=`Likes`
                                                                    }else{
                                                                        htmlView+=`Like`
                                                                    }
                                                                    console.log(save_posts[i].total_comments,'first cmt');
                                                        htmlView +=`</a>
                                                                    </p>
                                                                    </div>
                                                                    <div class="customer-post-comment-container">
                                                                        <a class="viewcomments" id = "`+save_posts[i].post_id+`">
                                                                            <iconify-icon icon="bi:chat-right" class="comment-icon"></iconify-icon>
                                                                            <p id="`+save_posts[i].post_id+`"><span>`+save_posts[i].total_comments+`</span>`
                                                                            if(save_posts[i].total_comments > 1){
                                                                                htmlView+=`Comments`
                                                                            }else{
                                                                                htmlView+=`Comment`
                                                                            }
                                                                            htmlView +=`</p>
                                                                                        </a>
                                                                                    </div>`

                                                                if(save_posts[i].media!=null){
                                                                    htmlView +=`<div class="customer-post-comment-container">
                                                                                    <iconify-icon icon="ic:outline-remove-red-eye" class="comment-icon"></iconify-icon>
                                                                                    <p><span>`+save_posts[i].viewers+`</span>`
                                                                    if(save_posts[i].viewers > 1){
                                                                        htmlView +=` Views`
                                                                    }else{
                                                                        htmlView +=` View`
                                                                    }
                                                                      htmlView+=`</p>
                                                                                </div>`
                                                                }else{

                                                                }
                                                    htmlView+=`</div></div>
                                                                </div>`

                            }
                        }

                        $('.customer-saved-posts-container').html(htmlView);
                        $('.social-media-media-slider').hide();
                        //image slider start
                                console.log($(".image-slider"))

                        $.each($(".ul-image-slider"),function(){
                            console.log($(this).children('li').length)

                            $(this).children('li:first').addClass("active-img")
                        })

                        $.each($(".img-slider-thumbnails ul"),function(){
                            console.log($(this).children('li').length)

                            $(this).children('li:first').addClass("active")
                        })




                        $(document).on('click','.img-slider-thumbnails li',function(){

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

                        // $(document).on('click','.customer-media-container',function(){
                        //     $(this).siblings(".social-media-media-slider").show()
                        //     $(this).hide()
                        // })

                        $(document).on('click','.slider-close-icon',function(){
                            $(this).closest('.social-media-media-slider').hide()
                            $(this).closest('.social-media-media-slider').siblings('.customer-media-container').show()
                        })
                        //image slider end
                    }
            })
        }

        function shop_saved_posts(e){
            e.preventDefault();
            var url="{{route('shop.saved.post')}}"
            $.ajax({
                    method: "GET",
                    url:url,
                    dataType: "json",
                    success: function (data) {
                    var save_posts=data.save_posts;

                        var auth_user={{auth()->user()->id}};

                        let htmlView = '';
                        if(save_posts.length <= 0){
                            htmlView+= `No data found.`;
                        }else{
                            for(let i=0;i<save_posts.length;i++){
                                htmlView += `<div class="customer-post-container">
                                            <div class="customer-post-header">
                                                <div class="customer-post-name-container">`

                                if(save_posts[i].profile_image===null){
                                    htmlView +=`<img class="nav-profile-img" src="{{asset('img/customer/imgs/user_default.jpg')}}"/>`
                                }else{
                                    htmlView +=`<img class="nav-profile-img" src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+save_posts[i].profile_image+`"/>`
                                }
                                htmlView +=`<div class="customer-post-name">
                                                        <p>`+save_posts[i].name+`</p>
                                                        <span>`+save_posts[i].date+`</span>
                                                    </div>
                                                    </div>
                                                    <iconify-icon icon="bi:three-dots-vertical" class="customer-post-header-icon"></iconify-icon>
                                                    <div class="post-actions-container">
                                                    <a style="text-decoration:none" class="post_save" id=`+save_posts[i].id+`>
                                                        <div class="post-action">
                                                            <iconify-icon icon="bi:save" class="post-action-icon"></iconify-icon>
                                                                <p class="save">Unsave</p>
                                                        </div>
                                                    </a>`
                                                    if(auth_user==save_posts[i].user_id){
                                                    htmlView +=`<a id="edit_post" data-id="`+save_posts[i].id+`" data-bs-toggle="modal" >
                                                                    <div class="post-action">
                                                                        <iconify-icon icon="material-symbols:edit" class="post-action-icon"></iconify-icon>
                                                                        <p>Edit</p>
                                                                    </div>
                                                                </a>
                                                                <a id="delete_post" data-id="`+save_posts[i].post_id+`">
                                                                    <div class="post-action">
                                                                    <iconify-icon icon="material-symbols:delete-forever-outline-rounded" class="post-action-icon"></iconify-icon>
                                                                    <p>Delete</p>
                                                                    </div>
                                                                </a>`
                                                    }else{
                                                        htmlView +=``
                                                    }
                                                    htmlView += `</div>
                                                                </div>
                                                                <div class="customer-content-container">
                                                                `
                                                    if(save_posts[i].media===null){
                                                        htmlView +=`<p>`+save_posts[i].caption+`</p>`
                                                    }else{

                                                    var caption =save_posts[i].caption ? save_posts[i].caption : '';
                                                        htmlView +=`<p>`+caption+`</p>
                                                                        <div class="customer-media-container" data-id="`+save_posts[i].post_id+`">
                                                                            `
                                                    var imageFile = save_posts[i].media
                                                    var imageArr = jQuery.parseJSON(imageFile);

                                                    $.each(imageArr,function(key,val){
                                                        var extension = val.substr( (val.lastIndexOf('.') +1) );

                                                        switch(extension) {
                                                                case 'jpg':
                                                                case 'png':
                                                                case 'gif':
                                                                case 'jpeg':
                                                                htmlView += ` <div class="customer-media">
                                                                        <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+val+`">
                                                                        </div>`
                                                                break;
                                                                case 'mp4':
                                                                htmlView += ` <div class="customer-media">
                                                                        <video controls>
                                                                        <source src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+val+`">
                                                                        </video>
                                                                        </div>`
                                                                break;

                                                            }
                                                    });
                                                        htmlView +=  `
                                                                    </div>
                                                                    <div id="slider-wrapper" class="social-media-media-slider">
                                                                        <iconify-icon icon="akar-icons:cross" class="slider-close-icon"></iconify-icon>

                                                                        <div id="image-slider" class="image-slider">
                                                                            <ul class="ul-image-slider">`
                                                    $.each(imageArr,function(k,v){
                                                        var exten = v.substr( (v.lastIndexOf('.') +1) );
                                                        switch(exten) {
                                                                case 'jpg':
                                                                case 'png':
                                                                case 'gif':
                                                                case 'jpeg':
                                                                htmlView += `<li>
                                                                        <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+v+`" alt="" />
                                                                    </li>`
                                                                break;
                                                                case 'mp4':
                                                                htmlView += `<li><video controls>
                                                                            <source src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+v+`">
                                                                            </video> </li>`
                                                                break;
                                                                    }

                                                    });
                                                        htmlView += `</ul>
                                                                        </div>
                                                                        <div id="thumbnail" class="img-slider-thumbnails">
                                                                            <ul>`
                                                    $.each(imageArr,function(k,v){
                                                        var exten = v.substr( (v.lastIndexOf('.') +1) );
                                                        switch(exten) {
                                                                case 'jpg':
                                                                case 'png':
                                                                case 'gif':
                                                                case 'jpeg':
                                                                htmlView += `<li>
                                                                        <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+v+`" alt="" />
                                                                    </li>`
                                                                break;
                                                                case 'mp4':
                                                                htmlView += `<li><video controls>
                                                                            <source src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+v+`">
                                                                            </video> </li>`
                                                                break;
                                                                    }
                                                    });

                                                        htmlView += `</ul></div></div></div>`
                                                    }
                                                    htmlView += ` <div class="customer-post-footer-container">
                                                                    <div class="customer-post-like-container">
                                                                    <a class="like" id="`+save_posts[i].post_id+`">`
                                                    if(save_posts[i].isLike==0){
                                                        htmlView+=`
                                                        <iconify-icon icon="mdi:cards-heart-outline" class="like-icon"></iconify-icon>`

                                                    }else{
                                                        htmlView+=`
                                                        <iconify-icon icon="mdi:cards-heart" style="color: red;" class="like-icon already-liked"></iconify-icon>`
                                                    }
                                                        htmlView +=`</a>
                                                                        <p>
                                                                        <span class="total_likes">
                                                                            `+save_posts[i].total_likes+`
                                                                        </span>
                                                                        <a class="viewlikes" id="`+save_posts[i].post_id+`">`
                                                                    if(save_posts[i].total_likes >1 ){
                                                                        htmlView+=`Likes`
                                                                    }else{
                                                                        htmlView+=`Like`
                                                                    }
                                                        htmlView +=`</a>
                                                                    </p>
                                                                    </div>
                                                                    <div class="customer-post-comment-container">
                                                                        <a class="viewcomments" id = "`+save_posts[i].post_id+`">
                                                                            <iconify-icon icon="bi:chat-right" class="comment-icon"></iconify-icon>
                                                                            <p id="`+save_posts[i].post_id+`"><span>`+save_posts[i].total_comments+`</span>`
                                                                            if(save_posts[i].total_comments > 1){
                                                                                htmlView+=`Comments`
                                                                            }else{
                                                                                htmlView+=`Comment`
                                                                            }
                                                                            htmlView +=`</p>
                                                                        </a>
                                                                    </div>`
                                                    if(save_posts[i].media!=null){
                                                        htmlView+=`<div class="customer-post-comment-container">
                                                                        <iconify-icon icon="ic:outline-remove-red-eye" class="comment-icon"></iconify-icon>
                                                                        <p><span>`+save_posts[i].viewers+`</span>`
                                                                    if(save_posts[i].viewers > 1){
                                                                        htmlView +=` Views`
                                                                    }else{
                                                                        htmlView +=` View`
                                                                    }
                                                                      htmlView+=`</p>
                                                                </div>`
                                                    }else{

                                                    }
                                                    htmlView+=`</div></div>
                                                                </div>`

                            }
                        }

                        $('.customer-saved-posts-shop-container').html(htmlView);
                        $('.social-media-media-slider').hide();
                        //image slider start
                                console.log($(".image-slider"))

                        $.each($(".ul-image-slider"),function(){
                            console.log($(this).children('li').length)

                            $(this).children('li:first').addClass("active-img")
                        })

                        $.each($(".img-slider-thumbnails ul"),function(){
                            console.log($(this).children('li').length)

                            $(this).children('li:first').addClass("active")
                        })




                        $(document).on('click','.img-slider-thumbnails li',function(){

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

                        // $(document).on('click','.customer-media-container',function(){
                        //     $(this).siblings(".social-media-media-slider").show()
                        //     $(this).hide()
                        // })

                        $(document).on('click','.slider-close-icon',function(){
                            $(this).closest('.social-media-media-slider').hide()
                            $(this).closest('.social-media-media-slider').siblings('.customer-media-container').show()
                        })
                        //image slider end
                    }
            })
        }

        function all_posts(){

            var url="{{route('all.post')}}"
            $.ajax({
                    method: "GET",
                    url:url,
                    dataType: "json",
                    success: function (data) {
                    console.log(data.posts,'all posts')
                    var save_posts=data.posts;

                        var auth_user={{auth()->user()->id}};

                        let htmlView = '';
                        if(save_posts.length <= 0){
                            htmlView+= `No data found.`;
                        }else{
                            for(let i=0;i<save_posts.length;i++){
                                htmlView += `<div class="customer-post-container">
                                            <div class="customer-post-header">
                                                <div class="customer-post-name-container">`

                                if(save_posts[i].profile_image===null){
                                    htmlView +=`<img class="nav-profile-img" src="{{asset('img/customer/imgs/user_default.jpg')}}"/>`

                                }else{
                                    htmlView +=`<img class="nav-profile-img" src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+save_posts[i].profile_image+`" />`
                                }
                                htmlView +=`<div class="customer-post-name">
                                                        <p>`+save_posts[i].name+`</p>
                                                        <span>`+save_posts[i].date+`</span>
                                                    </div>
                                                    </div>
                                                    <iconify-icon icon="bi:three-dots-vertical" class="customer-post-header-icon"></iconify-icon>
                                                    <div class="post-actions-container">
                                                    <a style="text-decoration:none" class="post_save" id=`+save_posts[i].id+`>
                                                        <div class="post-action">
                                                            <iconify-icon icon="bi:save" class="post-action-icon"></iconify-icon>`
                                if(save_posts[i].already_saved==1){
                                    htmlView +=`<p class="save">Unsave</p>`
                                }else{
                                    htmlView +=`<p class="save">Save</p>`
                                }


                                htmlView +=`</div>
                                                    </a>`
                                                    if(auth_user==save_posts[i].user_id){
                                                    htmlView +=`<a id="edit_post" data-id="`+save_posts[i].id+`" data-bs-toggle="modal" >
                                                                    <div class="post-action">
                                                                        <iconify-icon icon="material-symbols:edit" class="post-action-icon"></iconify-icon>
                                                                        <p>Edit</p>
                                                                    </div>
                                                                </a>
                                                                <a id="delete_post" data-id="`+save_posts[i].id+`">
                                                                    <div class="post-action">
                                                                    <iconify-icon icon="material-symbols:delete-forever-outline-rounded" class="post-action-icon"></iconify-icon>
                                                                    <p>Delete</p>
                                                                    </div>
                                                                </a>`
                                                    }else{
                                                        htmlView +=``
                                                    }
                                                    htmlView += `</div>
                                                                </div>
                                                                <div class="customer-content-container">
                                                                `
                                                    if(save_posts[i].media===null){
                                                        htmlView +=`<p>`+save_posts[i].caption+`</p>`
                                                    }else{

                                                    var caption =save_posts[i].caption ? save_posts[i].caption : '';
                                                        htmlView +=`<p>`+caption+`</p>
                                                                        <div class="customer-media-container" data-id="`+save_posts[i].post_id+`">
                                                                            `
                                                    var imageFile = save_posts[i].media
                                                    var imageArr = jQuery.parseJSON(imageFile);

                                                    $.each(imageArr,function(key,val){
                                                        var extension = val.substr( (val.lastIndexOf('.') +1) );

                                                        switch(extension) {
                                                                case 'jpg':
                                                                case 'png':
                                                                case 'gif':
                                                                case 'jpeg':
                                                                htmlView += ` <div class="customer-media">
                                                                        <img src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+val+`">
                                                                        </div>`
                                                                break;
                                                                case 'mp4':
                                                                htmlView += ` <div class="customer-media">
                                                                        <video controls>
                                                                        <source src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+val+`">
                                                                        </video>
                                                                        </div>`
                                                                break;

                                                            }
                                                    });
                                                        htmlView +=  `
                                                                    </div>
                                                                    <div id="slider-wrapper" class="social-media-media-slider">
                                                                        <iconify-icon icon="akar-icons:cross" class="slider-close-icon"></iconify-icon>

                                                                        <div id="image-slider" class="image-slider">
                                                                            <ul class="ul-image-slider">`
                                                    $.each(imageArr,function(k,v){
                                                        var exten = v.substr( (v.lastIndexOf('.') +1) );
                                                        switch(exten) {
                                                                case 'jpg':
                                                                case 'png':
                                                                case 'gif':
                                                                case 'jpeg':
                                                                htmlView += `<li>
                                                                        <img src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+v+`" alt="" />
                                                                    </li>`
                                                                break;
                                                                case 'mp4':
                                                                htmlView += `<li><video controls>
                                                                            <source src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+v+`">
                                                                            </video> </li>`
                                                                break;
                                                                    }

                                                    });
                                                        htmlView += `</ul>
                                                                        </div>
                                                                        <div id="thumbnail" class="img-slider-thumbnails">
                                                                            <ul>`
                                                    $.each(imageArr,function(k,v){
                                                        var exten = v.substr( (v.lastIndexOf('.') +1) );
                                                        switch(exten) {
                                                                case 'jpg':
                                                                case 'png':
                                                                case 'gif':
                                                                case 'jpeg':
                                                                htmlView += `<li>
                                                                        <img src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+v+`" alt="" />
                                                                    </li>`
                                                                break;
                                                                case 'mp4':
                                                                htmlView += `<li><video controls>
                                                                            <source src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+v+`">
                                                                            </video> </li>`
                                                                break;
                                                                    }
                                                    });

                                                        htmlView += `</ul></div></div></div>`
                                                    }
                                                    htmlView += ` <div class="customer-post-footer-container">
                                                                    <div class="customer-post-like-container">
                                                                    <a class="like" id="`+save_posts[i].post_id+`">`
                                                    if(save_posts[i].isLike==0){
                                                        htmlView+=`
                                                        <iconify-icon icon="mdi:cards-heart-outline" class="like-icon"></iconify-icon>`

                                                    }else{
                                                        htmlView+=`
                                                        <iconify-icon icon="mdi:cards-heart" style="color: red;" class="like-icon already-liked"></iconify-icon>`
                                                    }
                                                        htmlView +=`</a>
                                                                    <p>
                                                                        <span class="total_likes">
                                                                            `+save_posts[i].total_likes+`
                                                                        </span>
                                                                        <a class="viewlikes" id="`+save_posts[i].post_id+`">`
                                                                    if(save_posts[i].total_likes >1 ){
                                                                        htmlView+=`Likes`
                                                                    }else{
                                                                        htmlView+=`Like`
                                                                    }
                                                        htmlView +=`</a>
                                                                    </p>
                                                                    </div>
                                                                    <div class="customer-post-comment-container">
                                                                        <a class="viewcomments" id = "`+save_posts[i].post_id+`">
                                                                            <iconify-icon icon="bi:chat-right" class="comment-icon"></iconify-icon>
                                                                            <p id="`+save_posts[i].post_id+`"><span>`+save_posts[i].total_comments+`</span>`
                                                                            if(save_posts[i].total_comments > 1){
                                                                                htmlView+=`Comments`
                                                                            }else{
                                                                                htmlView+=`Comment`
                                                                            }
                                                                            htmlView +=`</p>
                                                                        </a>
                                                                    </div>`
                                                    if(save_posts[i].media!=null){
                                                        htmlView+=`<div class="customer-post-comment-container">
                                                                        <iconify-icon icon="ic:outline-remove-red-eye" class="comment-icon"></iconify-icon>
                                                                        <p><span>`+save_posts[i].viewers+`</span>`
                                                                    if(save_posts[i].viewers > 1){
                                                                        htmlView +=` Views`
                                                                    }else{
                                                                        htmlView +=` View`
                                                                    }
                                                                      htmlView+=`</p>
                                                                    </div>`
                                                    }else{

                                                    }
                                                    htmlView+=`</div></div>
                                                                </div>`

                            }
                        }

                        $('.customer-all-posts-container').html(htmlView);
                        $('.social-media-media-slider').hide();
                        //image slider start
                                console.log($(".image-slider"))

                        $.each($(".ul-image-slider"),function(){
                            console.log($(this).children('li').length)

                            $(this).children('li:first').addClass("active-img")
                        })

                        $.each($(".img-slider-thumbnails ul"),function(){
                            console.log($(this).children('li').length)

                            $(this).children('li:first').addClass("active")
                        })




                        $(document).on('click','.img-slider-thumbnails li',function(){

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

                        $(document).on('click','.customer-media-container',function(){
                            $(this).siblings(".social-media-media-slider").show()
                            $(this).hide()
                            var post_id=$(this).data('id');
                            var add_url = "{{ route('user.view.post') }}";
                            $.ajax({
                                    method: "GET",
                                    url: add_url,
                                    data:{ post_id : post_id}
                                })
                        })

                        $(document).on('click','.slider-close-icon',function(){
                            $(this).closest('.social-media-media-slider').hide()
                            $(this).closest('.social-media-media-slider').siblings('.customer-media-container').show()
                        })
                        //image slider end
                    }
            })
        }

        function shop_all_posts(){
        var url="{{route('all.shop.post')}}"
        $.ajax({
        method: "GET",
        url:url,
        dataType: "json",
        success: function (data) {

        console.log(data.posts,'all posts')
        var save_posts=data.posts;

            var auth_user={{auth()->user()->id}};

            let htmlView = '';
            if(save_posts.length <= 0){
                htmlView+= `No data found.`;
            }else{
                for(let i=0;i<save_posts.length;i++){
                    htmlView += `<div class="customer-post-container">
                                <div class="customer-post-header">
                                    <div class="customer-post-name-container">`

                    if(save_posts[i].profile_image===null){
                        htmlView +=`<img class="nav-profile-img" src="{{asset('img/customer/imgs/user_default.jpg')}}"/>`

                    }else{
                        htmlView +=`<img class="nav-profile-img" src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+save_posts[i].profile_image+`" />`
                    }
                    htmlView +=`<div class="customer-post-name">
                                            <p>`+save_posts[i].name+`</p>
                                            <span>`+save_posts[i].date+`</span>
                                        </div>
                                        </div>
                                        <iconify-icon icon="bi:three-dots-vertical" class="customer-post-header-icon"></iconify-icon>
                                        <div class="post-actions-container">
                                        <a style="text-decoration:none" class="post_save" id=`+save_posts[i].id+`>
                                            <div class="post-action">
                                                <iconify-icon icon="bi:save" class="post-action-icon"></iconify-icon>`
                    if(save_posts[i].already_saved==1){
                        htmlView +=`<p class="save">Unsave</p>`
                    }else{
                        htmlView +=`<p class="save">Save</p>`
                    }


                    htmlView +=`</div>
                                        </a>`
                                        if(auth_user==save_posts[i].user_id){
                                        htmlView +=`<a id="edit_post" data-id="`+save_posts[i].id+`" data-bs-toggle="modal" >
                                                        <div class="post-action">
                                                            <iconify-icon icon="material-symbols:edit" class="post-action-icon"></iconify-icon>
                                                            <p>Edit</p>
                                                        </div>
                                                    </a>
                                                    <a id="delete_post" data-id="`+save_posts[i].id+`">
                                                        <div class="post-action">
                                                        <iconify-icon icon="material-symbols:delete-forever-outline-rounded" class="post-action-icon"></iconify-icon>
                                                        <p>Delete</p>
                                                        </div>
                                                    </a>`
                                        }else{
                                            htmlView +=``
                                        }
                                        htmlView += `</div>
                                                    </div>
                                                    <div class="customer-content-container">
                                                    `
                                        if(save_posts[i].media===null){
                                            htmlView +=`<p>`+save_posts[i].caption+`</p>`
                                        }else{

                                        var caption =save_posts[i].caption ? save_posts[i].caption : '';
                                            htmlView +=`<p>`+caption+`</p>
                                                            <div class="customer-media-container" data-id="`+save_posts[i].post_id+`" >
                                                                `
                                        var imageFile = save_posts[i].media
                                        var imageArr = jQuery.parseJSON(imageFile);

                                        $.each(imageArr,function(key,val){
                                            var extension = val.substr( (val.lastIndexOf('.') +1) );

                                            switch(extension) {
                                                    case 'jpg':
                                                    case 'png':
                                                    case 'gif':
                                                    case 'jpeg':
                                                    htmlView += ` <div class="customer-media">
                                                            <img src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+val+`">
                                                            </div>`
                                                    break;
                                                    case 'mp4':
                                                    htmlView += ` <div class="customer-media">
                                                            <video controls>
                                                            <source src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+val+`" >
                                                            </video>
                                                            </div>`
                                                    break;

                                                }
                                        });
                                            htmlView +=  `
                                                        </div>
                                                        <div id="slider-wrapper" class="social-media-media-slider">
                                                            <iconify-icon icon="akar-icons:cross" class="slider-close-icon"></iconify-icon>

                                                            <div id="image-slider" class="image-slider">
                                                                <ul class="ul-image-slider">`
                                        $.each(imageArr,function(k,v){
                                            var exten = v.substr( (v.lastIndexOf('.') +1) );
                                            switch(exten) {
                                                    case 'jpg':
                                                    case 'png':
                                                    case 'gif':
                                                    case 'jpeg':
                                                    htmlView += `<li>
                                                            <img src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+v+`" alt="" />
                                                        </li>`
                                                    break;
                                                    case 'mp4':
                                                    htmlView += `<li><video controls>
                                                                <source src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+v+`">
                                                                </video> </li>`
                                                    break;
                                                        }

                                        });
                                            htmlView += `</ul>
                                                            </div>
                                                            <div id="thumbnail" class="img-slider-thumbnails">
                                                                <ul>`
                                        $.each(imageArr,function(k,v){
                                            var exten = v.substr( (v.lastIndexOf('.') +1) );
                                            switch(exten) {
                                                    case 'jpg':
                                                    case 'png':
                                                    case 'gif':
                                                    case 'jpeg':
                                                    htmlView += `<li>
                                                            <img src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+v+`" alt="" />
                                                        </li>`
                                                    break;
                                                    case 'mp4':
                                                    htmlView += `<li><video controls>
                                                                <source src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/`+v+`">
                                                                </video> </li>`
                                                    break;
                                                        }
                                        });

                                            htmlView += `</ul></div></div></div>`
                                        }
                                        htmlView += ` <div class="customer-post-footer-container">
                                                        <div class="customer-post-like-container">
                                                        <a class="like" id="`+save_posts[i].post_id+`">`
                                        if(save_posts[i].isLike==0){
                                            htmlView+=`
                                            <iconify-icon icon="mdi:cards-heart-outline" class="like-icon"></iconify-icon>`

                                        }else{
                                            htmlView+=`
                                            <iconify-icon icon="mdi:cards-heart" style="color: red;" class="like-icon already-liked"></iconify-icon>`
                                        }
                                            htmlView +=`</a>
                                                        <p>
                                                        <span class="total_likes">
                                                            `+save_posts[i].total_likes+`
                                                        </span>
                                                        <a class="viewlikes" id="`+save_posts[i].post_id+`">`
                                                    if(save_posts[i].total_likes >1 ){
                                                        htmlView+=`Likes`
                                                    }else{
                                                        htmlView+=`Like`
                                                    }

                                        htmlView +=`</a>
                                                    </p>
                                                        </div>
                                                        <div class="customer-post-comment-container">
                                                            <a class="viewcomments" id = "`+save_posts[i].post_id+`">
                                                                <iconify-icon icon="bi:chat-right" class="comment-icon"></iconify-icon>
                                                                <p id="`+save_posts[i].post_id+`"><span>`+save_posts[i].total_comments+`</span>`
                                                                if(save_posts[i].total_comments > 1){
                                                                    htmlView+=`Comments`
                                                                }else{
                                                                    htmlView+=`Comment`
                                                                }
                                                                htmlView +=`</p>
                                                            </a>
                                                        </div>`
                                            if(save_posts[i].media!=null){
                                            htmlView+=`<div class="customer-post-comment-container">
                                                            <iconify-icon icon="ic:outline-remove-red-eye" class="comment-icon"></iconify-icon>
                                                            <p><span>`+save_posts[i].viewers+`</span>`
                                                                    if(save_posts[i].viewers > 1){
                                                                        htmlView +=` Views`
                                                                    }else{
                                                                        htmlView +=` View`
                                                                    }
                                                                      htmlView+=`</p>
                                                    </div>`
                                                    }else{

                                                    }

                                        htmlView+=`</div></div>
                                                    </div>`

                }
            }

            $('.customer-profile-shop-container_data').html(htmlView);
            $('.social-media-media-slider').hide();
            //image slider start
                    console.log($(".image-slider"))

            $.each($(".ul-image-slider"),function(){
                console.log($(this).children('li').length)

                $(this).children('li:first').addClass("active-img")
            })

            $.each($(".img-slider-thumbnails ul"),function(){
                console.log($(this).children('li').length)

                $(this).children('li:first').addClass("active")
            })




            $(document).on('click','.img-slider-thumbnails li',function(){

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

            // $(document).on('click','.customer-media-container',function(){
            //     $(this).siblings(".social-media-media-slider").show()
            //     $(this).hide()
            //     var post_id=$(this).data('id');
            //     var add_url = "{{ route('user.view.post') }}";
            //     $.ajax({
            //             method: "GET",
            //             url: add_url,
            //             data:{ post_id : post_id}
            //         })
            // })

            $(document).on('click','.slider-close-icon',function(){
                $(this).closest('.social-media-media-slider').hide()
                $(this).closest('.social-media-media-slider').siblings('.customer-media-container').show()
            })
            //image slider end
        }
        })
        }

        $(document).on('click', '.post_save', function(e) {
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
                                        saved_posts(e);

                                        e.target.querySelector(".save").innerHTML = `Unsave`;
                                    })

                                }else{
                                    Swal.fire({
                                        text: data.unsave,
                                        timerProgressBar: true,
                                        timer: 5000,
                                        icon: 'success',
                                    }).then((result) => {
                                        saved_posts(e);
                                        shop_saved_posts(e);

                                        e.target.querySelector(".save").innerHTML = `Save`;

                                    })
                                }

                            }
                    })


        })


    // end like

        $(document).on('click', '.social-media-comment-icon', function(e) {
                $(this).next().toggle()
        })

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
                                console.log(data)

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
                                console.log(data)

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


                //edit comment start
                $(document).on('click', '#editCommentModal', function(e) {

                    $('#view_comments_modal').modal('hide')
                        $('#edit_comments_modal').modal('show');
                        var id = $(this).data('id');
                        $(".social-media-all-comments-input-edit").data('id',id)
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

                // //emoji start
                $("#edit-emojis").disMojiPicker()
                $("#edit-emojis").picker(emoji => {
                    // console.log($(".social-media-all-comments-input .mentiony-content"))
                    $(".social-media-all-comments-input-edit .mentiony-content").append(emoji)
                });

                $("#emojis").disMojiPicker()
                $("#emojis").picker(emoji => {
                    // console.log($(".social-media-all-comments-input .mentiony-content"))
                    $(".social-media-all-comments-input .mentiony-content").append(emoji)
                });


                twemoji.parse(document.body);

                $.each($(".emoji-trigger"), function(index,value){
                    // console.log($(this))
                    $(this).click(function(){
                        // console.log($(this).siblings("#emojis"))
                        $(this).siblings("#emojis").toggle()
                        $(this).siblings("#edit-emojis").toggle()
                    })
                })
                // //emoji end

                $(".social-media-all-comments-input-edit").on('submit',function(e){
                e.preventDefault()
                var arr = []
                $.each($('.social-media-all-comments-input-edit .mentiony-link'),function(){
                    arr.push({'id' : $(this).data('item-id'),'name' : $(this).text()})
                    $(this).text(`@${$(this).data('item-id')}`)

                })
                var post_id = $(".social-media-all-comments-input-edit").data('id');

                var comment = $('.social-media-all-comments-input-edit .mentiony-content').text()

                var search_url = "{{ route('post.comment.update') }}";

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
                                            $('#edit_comments_modal').modal('hide');
                                            viewcomments()
                                        })

                            }else{
                                $('#edit_comments_modal').modal('hide');
                                Swal.fire({
                                            text: "Comment Edited",
                                            timerProgressBar: true,
                                            timer: 5000,
                                            icon: 'success',
                                        }).then(() => {
                                           
                                            viewcomments()
                                        })
                                        viewcomments()
                                $('.mentiony-content').empty()
                            }
                        }

                    });

            })


            $(".mentiony-container").attr('style','')
            $(".mentiony-content").attr('style','')
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

            $(".social-media-all-comments-input").on('submit',function(e){
                e.preventDefault()

                var arr = []
                $.each($('.social-media-all-comments-input .mentiony-link'),function(){
                    arr.push({'id' : $(this).data('item-id'),'name' : $(this).text()})
                    $(this).text(`@${$(this).data('item-id')}`)

                })

                var comment = $('.social-media-all-comments-input .mentiony-content').text()

                var search_url = "{{ route('post.comment.store') }}";
                var post_id = $(".social-media-all-comments-input").data('id');

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
                                            viewcomments()
                                        })

                            }else{
                                $('.mentiony-content').empty()
                                viewcomments()
                                all_posts()
                                shop_all_posts()
                                saved_posts(e)
                                shop_saved_posts(e)
                            }

                        }

                    });

            })

            $(document).on('click','.viewcomments',function(e){
            e.preventDefault();
            var post_id = $(this).attr("id");
            var postid = $(".social-media-all-comments-input").data('id',post_id)
            viewcomments()
        })

        function viewcomments(){
            $(".social-media-all-comments").empty();
            $('#view_comments_modal').modal('show');
            var postid = $(".social-media-all-comments-input").data('id');

            var comment_url = "{{ route('comment_list',':id') }}";
            comment_url = comment_url.replace(':id', postid);
            $.post(comment_url,
                    {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    function(data){
                        table_post_comment(data);
            });

            function table_post_comment(res){
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
                                var post_owner=res.comment[i].post_owner;
                                  htmlView += `
                                    <div class="social-media-comment-container">`

                                        if(res.comment[i].profile_image===null){
                                            htmlView+= `<img src="{{ asset('/img/customer/imgs/user_default.jpg') }}" >`
                                        }else{
                                            htmlView+= `<img src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${res.comment[i].profile_image}" >`
                                        }
                                     htmlView+= `
                                      <div class="social-media-comment-box">
                                          <div class="social-media-comment-box-header">
                                              <div class="social-media-comment-box-name">
                                                  <p>`+res.comment[i].name+`</p>
                                                  <span>`+res.comment[i].date+`</span>
                                              </div>`

                                    if(auth_id==post_owner && auth_id==comment_user){

                                    htmlView+=`<iconify-icon icon="bx:dots-vertical-rounded" class="social-media-comment-icon"></iconify-icon>
                                        <div class="comment-actions-container">

                                            <div class="comment-action" id="editCommentModal"
                                            data-id=`+res.comment[i].id+`>
                                                <iconify-icon icon="akar-icons:edit" class="comment-action-icon"></iconify-icon>
                                                <p id="`+res.comment[i].id+`">Edit</p>
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

                                    }

                                    htmlView+=`</div>
                                        <p>`+res.comment[i].Replace+`</p>
                                    </div>
                                </div>
                                    `
                                }
                            $('.social-media-all-comments').html(htmlView);
            }

        }

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
                                        viewcomments();
                                    }
                                })

                        }
                        })
                $('.social-media-left-searched-items-container').empty();
                });

        $(document).on('click', '.profile_addfriend', function(e) {
                e.preventDefault();
                var id = $(this).attr("id")
                var add_url = "{{ route('addUser', [':id']) }}";
                add_url = add_url.replace(':id', id);
                $.ajax({
                    type: "GET",
                    url: add_url,
                    datatype: "json",
                    success: function(data) {
                        viewlikes(e);
                    }
                })
        });

        $(document).on('click', '.profile_cancelrequest', function(e) {
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

                                    var id = $(this).attr("id");
                                    var url = "{{ route('cancelRequest', [':id']) }}";
                                    url = url.replace(':id', id);

                                        $.ajax({
                                            type: "GET",
                                            url: url,
                                            datatype: "json",
                                            success: function(data) {
                                                viewlikes(e);
                                            }
                                        })

                                }
                                })

        });

        $(".customer-saved-posts-container").hide()
        // saved post

        $(".customer-profile-selector").change(function(e){

            if(e.target.value === "all"){
                all_posts();
                $(".customer-all-posts-container").show()
                $(".customer-saved-posts-container").hide()
            }
            if(e.target.value === "saved"){
                saved_posts(e);
                $(".customer-all-posts-container").hide()
                $(".customer-saved-posts-container").show()

            }

        })


        $(".customer-shop-profile-selector").change(function(e){

            if(e.target.value === "shop-all"){
                shop_all_posts();
                $(".customer-profile-shop-container-data").show()
                $(".customer-saved-posts-shop-container").hide()
            }
            if(e.target.value === "shop-saved"){
                shop_saved_posts(e);
                $(".customer-post-container").hide()
                $(".customer-saved-posts-shop-container").show()

            }

        })





        $(".mentiony-container").attr('style','')
        $(".mentiony-content").attr('style','')



        //comment


        $('.social-media-fris-search input').on('keyup', function(){
                            search();
        });

                        search();
                        function search(){
                            var keyword = $('.social-media-fris-search input').val();
                            console.log(keyword);
                            var user_id = {{$user->id}};
                            console.log();
                            var search_url = "{{ route('friend_search',':id') }}";
                            search_url = search_url.replace(':id', user_id);
                            $.post(search_url,
                            {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                keyword:keyword
                            },
                            function(data){
                                table_post_row(data);
                                console.log(data.friends);
                            });
                        }
                        // table row with ajax
                        function table_post_row(res){
                            console.log(res.friends.length)
                        let htmlView = '';
                            if(res.friends.length <= 0){
                                console.log("no data");
                                htmlView+= `
                                No data found.
                                `;
                            }
                            console.log("data");
                            if({{auth()->user()->id}} === {{$user->id}}){
                                for(let i = 0; i < res.friends.length; i++){
                                id = res.friends[i].id;
                                var url = "{{ route('socialmedia.profile', [':id']) }}";
                                    url = url.replace(':id',id);
                                if(res.friends[i].profile_image === null){
                                    htmlView += `
                                    <div class="social-media-fris-fri-row">
                                        <div class="social-media-fris-fri-img">
                                                <img src="{{asset('img/customer/imgs/user_default.jpg')}}">
                                            <p>`+res.friends[i].name+`</p>
                                        </div>


                                        <div class="social-media-fris-fri-btns-container">
                                            <a class="customer-primary-btn">Message</a>

                                            <a href="?id=` + res.friends[i].id+`" class="customer-red-btn"
                                            id = "unfriend">Remove</a>

                                        </div>
                                    </div>                                    `
                                }
                                else{
                                    htmlView += `
                                    <div class="social-media-fris-fri-row">
                                        <div class="social-media-fris-fri-img">
                                                <img src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${res.friends[i].profile_image}">
                                            <p>`+res.friends[i].name+`</p>
                                        </div>


                                        <div class="social-media-fris-fri-btns-container">
                                            <a class="customer-primary-btn">Message</a>

                                            <a href="?id=` + res.friends[i].id+`" class="customer-red-btn"
                                            id = "unfriend">Remove</a>

                                        </div>
                                    </div>
                                    `
                                }
                            }
                            }
                            else{
                                for(let i = 0; i < res.friends.length; i++){
                                id = res.friends[i].id;
                                var url = "{{ route('socialmedia.profile', [':id']) }}";
                                    url = url.replace(':id',id);
                                if(res.friends[i].profile_image === null){
                                    htmlView += `
                                    <div class="social-media-fris-fri-row">
                                        <div class="social-media-fris-fri-img">
                                                <img src="{{asset('img/customer/imgs/user_default.jpg')}}">
                                            <p>`+res.friends[i].name+`</p>
                                        </div>


                                        <div class="social-media-fris-fri-btns-container">
                                            <a href=`+url+` class="customer-primary-btn">View Profile</a>
                                        </div>
                                    </div>
                                    `
                                }
                                else{
                                    htmlView += `
                                    <div class="social-media-fris-fri-row">
                                        <div class="social-media-fris-fri-img">
                                                <img src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${res.friends[i].profile_image}">
                                            <p>`+res.friends[i].name+`</p>
                                        </div>


                                        <div class="social-media-fris-fri-btns-container">
                                            <a href=`+url+` class="customer-primary-btn">View Profile</a>
                                        </div>
                                    </div>
                                    `
                                }
                            }
                            }
                            $('.social-media-fris-list-container').html(htmlView);
                        }

                        //blocking
                        $('.social-media-block-search input').on('keyup', function(){
                            blocking();
                        });
                        blocking();
                        function blocking(){
                            var blocking_url = "{{ route('blockList') }}";
                            var keyword = $('.social-media-block-search input').val();
                            $.post(blocking_url,
                            {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                keyword:keyword
                            },
                            function(data){
                                block(data);
                                console.log(data,'blocking');
                            });
                        }
                        // table row with ajax
                        function block(res){
                             let htmlView = '';
                            if(res.data.length <= 0){
                                console.log("no data");
                                htmlView+= `
                                No data found.
                                `;
                            }
                           
                            if({{auth()->user()->id}} === {{$user->id}}){
                                for(let i = 0; i < res.data.length; i++){
                                id = res.data[i].id;
                                var url = "{{ route('socialmedia.profile', [':id']) }}";
                                    url = url.replace(':id',id);
                                if(res.data[i].profile_image === null){
                                    htmlView += `
                                    <div class="social-media-fris-fri-row">
                                        <div class="social-media-fris-fri-img">
                                                <img src="{{asset('img/customer/imgs/user_default.jpg')}}">
                                            <p>`+res.data[i].name+`</p>
                                        </div>


                                        <div class="social-media-fris-fri-btns-container">
                                            <a href="?id=` + res.data[i].id+`" class="customer-red-btn"
                                            id = "unblock">Unblock</a>

                                        </div>
                                    </div>                                    `
                                }
                                else{
                                    htmlView += `
                                    <div class="social-media-fris-fri-row">
                                        <div class="social-media-fris-fri-img">
                                                <img src = "https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${res.data[i].profile_image}">
                                            <p>`+res.data[i].name+`</p>
                                        </div>


                                        <div class="social-media-fris-fri-btns-container">
                                            <a href="?id=` + res.data[i].id+`" class="customer-red-btn"
                                            id = "unblock">Unblock</a>

                                        </div>
                                    </div>
                                    `
                                }
                            }
                            }
                            $('.social-media-block-list-container').html(htmlView);
                        }

                        $(document).on('click', '#unblock', function(e){
                            e.preventDefault();
                            var url = new URL(this.href);
                            var id = url.searchParams.get("id");
                            var url = "{{ route('unblock', [':id']) }}";
                            url = url.replace(':id', id);

                            $.ajax({
                                    type: "GET",
                                    url: url,
                                    datatype: "json",
                                    success: function(data) {
                                       // console.log(data)
                                        blocking();
                                    }
                                })
                            Swal.fire('Unblocked!', '', 'success')
                        })
                        //blockingend
                $(document).on('click', '#unfriend', function(e){
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
                                        search();
                                    }
                                })
                            Swal.fire('Unfriend!', '', 'success')
                        }
                        })
                $('.social-media-left-searched-items-container').empty();
                });

        //image slider start
        console.log($(".image-slider"))

        $.each($(".ul-image-slider"),function(){
            console.log($(this).children('li').length)

            $(this).children('li:first').addClass("active-img")
        })

        $.each($(".img-slider-thumbnails ul"),function(){
            console.log($(this).children('li').length)

            $(this).children('li:first').addClass("active")
        })



         $(document).on('click','.img-slider-thumbnails li',function(){

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

        // $(document).on('click','.customer-media-container',function(){
        //     $(this).siblings(".social-media-media-slider").show()
        //     $(this).hide()
        // })

        $(document).on('click','.slider-close-icon',function(){
            $(this).closest('.social-media-media-slider').hide()
            $(this).closest('.social-media-media-slider').siblings('.customer-media-container').show()
        })
        //image slider end


        $(".customer-profile-social-media-photoes-container").hide()
        $(".customer-profile-social-media-fris-container").hide()
        $(".social-media-profile-photos-link").click(function(){
            $(".customer-profile-social-media-photoes-container").show()
            $(".customer-profile-social-media-default-container").hide()
            $(".customer-profile-social-media-fris-container").hide()
        })
        $(".customer-profile-see-all-fris-btn").click(function(){
            $(".customer-profile-social-media-photoes-container").hide()
            $(".customer-profile-social-media-default-container").hide()
            $(".customer-profile-social-media-fris-container").show()
        })
        $(".customer-profile-social-media-block-container").hide()
        $(".customer-profile-see-all-block-btn").click(function(){
            $(".customer-profile-social-media-photoes-container").hide()
            $(".customer-profile-social-media-default-container").hide()
            $(".customer-profile-social-media-fris-container").hide()
            $(".customer-profile-social-media-block-container").show()
        })

        $(".customer-profile-social-media-photoes-back").click(function(){
            $(".customer-profile-social-media-photoes-container").hide()
            $(".customer-profile-social-media-fris-container").hide()
            $(".customer-profile-social-media-default-container").show()
        })

        $(".social-media-profiles-tab").addClass("social-media-photos-tab-active")

            $(".social-media-covers-container").hide()

            $(".social-media-profiles-tab").click(function(){
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

        $(".name").hide();
        $('.customer-name-calculate-btn').hide();
        $(".customer-bmi-calculate-btn").hide();
        $('#name_edit_pen').on('click',function(){

            $(".name").show();
            $('.customer-name-calculate-btn').show();
            $('#name_edit_pen').hide();
            $("#name").hide();
        });

        $("#customer_name_cancel").on('click',function(event){
            $(".name").hide();
            $('.customer-name-calculate-btn').hide();
            $('#name_edit_pen').show();
            $("#name").show();
        })

        const profileImgInput = document.querySelector('.customer-profile-img-change-input')
        const profileImg = document.querySelector('.customer-profile-img')

        const coverImgInput = document.querySelector('.customer-cover-img-change-input')
        const coverImg = document.querySelector('.customer-cover-photo')

        profileImgInput.addEventListener('change', (e) =>{
            console.log(profileImgInput.files[0])
            if(profileImgInput.files[0]){
                const reader = new FileReader();
                reader.onload = e => profileImg.setAttribute('src', e.target.result);
                reader.readAsDataURL(profileImgInput.files[0]);
                if(profileImgInput.files.length === 0){
                    $('.customer-profile-change-btns-container').hide()
                }else{
                    $('.customer-profile-change-btns-container').show()
                }
            }else{
                profileImgInput.value = ""
                // profileImg.removeAttribute("src")
                profileImg.setAttribute('src',"{{asset('img/user.jpg')}}");
                $('.customer-profile-change-btns-container').hide()
            }

        });//

        coverImgInput.addEventListener('change', (e) =>{
            console.log(coverImgInput.files[0])
            if(coverImgInput.files[0]){
                const reader = new FileReader();
                reader.onload = e => coverImg.setAttribute('src', e.target.result);
                reader.readAsDataURL(coverImgInput.files[0]);
                if(coverImgInput.files.length === 0){
                    $('.customer-cover-change-btns-container').hide()
                }else{
                    $('.customer-cover-change-btns-container').show()
                }
            }else{
                coverImgInput.value = ""
                // profileImg.removeAttribute("src")
                coverImg.setAttribute('src',"{{asset('image/cover.jpg')}}");
                $('.customer-cover-change-btns-container').hide()
            }

        });//



        $('.customer-profile-change-btns-container').hide()
        $('.customer-cover-change-btns-container').hide()
        $('.customer-bio-btns-container').hide()
        $('.customer-bio-text input').hide()


        $(".customer-profile-change-cancel-btn").click(function(){
                profileImgInput.value = ""
                // profileImg.removeAttribute("src")
                profileImg.setAttribute('src',"{{asset('img/user.jpg')}}");
                $('.customer-profile-change-btns-container').hide()
        })

        $(".customer-cover-change-cancel-btn").click(function(){
            coverImgInput.value = ""
            // profileImg.removeAttribute("src")
            coverImg.setAttribute('src',"{{asset('image/cover.jpg')}}");
            $('.customer-cover-change-btns-container').hide()
        })

        $('.customer-bio-change-icon').click(function(){
            // console.log("hello")
            $('.customer-bio-text input').show()

            $('.customer-bio-change-icon').hide()
            $('.customer-bio-btns-container').show()

            $('.customer-bio-form p').hide()



        })

        $(".customer-bio-change-cancel-btn").click(function(){
            $('.customer-bio-text input').hide()

            $('.customer-bio-change-icon').show()
            $('.customer-bio-btns-container').hide()

            $('.customer-bio-form p').show()
        })

        $('.customer-profile-socialmedia-tab').addClass("customer-profile-training-center-tab-active")
        $('.customer-profile-training-center-container').hide()
        $('.customer-profile-socialmedia-container').show()
        $('.customer-profile-shop-container').hide()

        $('.customer-profile-training-center-tab').click(function(){
            $('.customer-profile-training-center-tab').addClass("customer-profile-training-center-tab-active")
            $('.customer-profile-socialmedia-tab').removeClass("customer-profile-training-center-tab-active")
            $(".customer-profile-shop-tab").removeClass("customer-profile-training-center-tab-active")



            $('.customer-profile-training-center-container').show()
            $('.customer-profile-socialmedia-container').hide()
            $('.customer-profile-shop-container').hide()
        })

        $('.customer-profile-socialmedia-tab').click(function(){
            $('.customer-profile-training-center-tab').removeClass("customer-profile-training-center-tab-active")
            $('.customer-profile-socialmedia-tab').addClass("customer-profile-training-center-tab-active")
            $(".customer-profile-shop-tab").removeClass("customer-profile-training-center-tab-active")

            $('.customer-profile-training-center-container').hide()
            $('.customer-profile-socialmedia-container').show()
            $('.customer-profile-shop-container').hide()
        })

        $(".customer-profile-shop-tab").click(function(){
            $('.customer-profile-training-center-tab').removeClass("customer-profile-training-center-tab-active")
            $('.customer-profile-socialmedia-tab').removeClass("customer-profile-training-center-tab-active")
            $(".customer-profile-shop-tab").addClass("customer-profile-training-center-tab-active")

            $('.customer-profile-training-center-container').hide()
            $('.customer-profile-socialmedia-container').hide()
            $('.customer-profile-shop-container').show()
        })

        $(document).on('click', '#delete_post', function(e){

            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                        text: 'Are you sure to delete this post?',
                        timerProgressBar: true,
                        showCloseButton: true,
                        showCancelButton: true,
                        icon: 'warning',
                    }).then((result) => {
                    if (result.isConfirmed) {
                        var add_url = "{{ route('post.destroy', [':id']) }}";
                            add_url = add_url.replace(':id', id);

                            $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                            });
                            $.ajax({
                                    method: "POST",
                                    url: add_url,
                                    datatype: "json",
                                    success: function(data) {
                                        console.log(data.success);
                                       // window.location.reload();
                                        all_posts();
                                        shop_all_posts();
                                        saved_posts(e);
                                        shop_saved_posts(e);
                                    }
                            })
                    }else{

                    }
                    })

        })

        ///Edit Post

        $(document).on('click','#edit_post',function(e){
            e.preventDefault();
            $(".editpost-photo-video-imgpreview-container").empty();

            dtEdit.clearData()
            document.getElementById('editPostInput').files = dtEdit.files;
            var id = $(this).data('id');

            $('#editPostModal').modal('show');
            var add_url = "{{ route('post.edit', [':id']) }}";
            add_url = add_url.replace(':id', id);

            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            $.ajax({
                    method: "POST",
                    url: add_url,
                    datatype: "json",
                    success: function(data) {
                        if(data.status==400){
                            alert(data.message)
                        }else{
                            $('#editPostCaption').val(data.post.caption);
                            $('#edit_post_id').val(data.post.id);

                            var filesdb =data.post.media ? JSON.parse(data.post.media) : [];
                            // var filesAmount=files.length;
                            var storedFilesdb = filesdb;
                             console.log(data.imageData,'img data a ti naw')
                             var imageDataDb = data.imageData

                            
                            filesdb.forEach(function(f) {
                                fileExtension = f.replace(/^.*\./, '');
                                console.log(fileExtension);
                                if(fileExtension=='mp4') {
                                    var html="<div class='addpost-preview'>\
                                        <iconify-icon icon='akar-icons:cross' data-file='" + f + "' class='delete-preview-db-icon'></iconify-icon>\
                                        <video controls><source src='https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/" + f + "' data-file='" + f+ "' class='selFile' title='Click to remove'>" + f + "<br clear=\"left\"/>\
                                        <video>\
                                    </div>"
                                    $(".editpost-photo-video-imgpreview-container").append(html);

                                }else{
                                    var html = "<div class='addpost-preview'><iconify-icon icon='akar-icons:cross' data-file='" + f + "' class='delete-preview-db-icon'></iconify-icon><img src=' https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/" + f + "' data-file='" + f + "' class='selFile' title='Click to remove'></div>";
                                    $(".editpost-photo-video-imgpreview-container").append(html);
                                }

                            });

                            $("body").on("click", ".delete-preview-db-icon", removeFiledb);

                            function removeFiledb(){
                                var file = $(this).data('file')
                                storedFilesdb = storedFilesdb.filter((item) => {
                                    return file !== item
                                })
                                imageDataDb = imageDataDb.filter((item) => {
                                    return file !== item.name
                                })

                                $(this).parent().remove();
                            }

                            $(".addpost-photovideo-clear-btn").click(function(){
                                storedFilesdb = []
                            })

                            $('#edit_form').submit(function(e){
                                e.preventDefault();
                                $('#editPostModal'). modal('hide');

                            var fileUpload=$('#editPostInput');
                            console.log(storedFilesdb.length );
                            console.log(parseInt(fileUpload.get(0).files.length) );
                            console.log(storedFilesdb);
                            console.log(fileUpload.get(0).files);

                            var totalSize = 0;

                            $("#editPostInput").each(function() {
                                for (var i = 0; i < this.files.length; i++) {
                                totalSize += this.files[i].size;
                                }
                            });

                            for(var j = 0;j < imageDataDb.length;j++){
                                totalSize += imageDataDb[j].size
                            }



                            var valid = totalSize <= 157286400;

                            if(!$('#editPostCaption').val() && (parseInt(fileUpload.get(0).files.length) + storedFilesdb.length) === 0){
                                alert("Cannot post!!")
                            }else{
                                if((parseInt(fileUpload.get(0).files.length))+storedFilesdb.length > 5){
                                    Swal.fire({
                                                text: "You can only upload a maximum of 5 files",
                                                timer: 5000,
                                                icon: 'warning',
                                            });
                                }else if(!valid){
                                        Swal.fire({
                                            text: "You cannot upload more than 150MBs",
                                            timerProgressBar: true,
                                            timer: 5000,
                                            icon: 'warning',
                                        });
                                }
                                else{
                                    e.preventDefault();

                                    var url="{{route('post.update')}}";
                                    let formData = new FormData(edit_form);
                                    var oldimg=storedFilesdb;
                                    var edit_post_id=$('#edit_post_id').val();
                                    var caption=$('#editPostCaption').val();

                                    const totalImages = $("#editPostInput")[0].files.length;
                                    let images = $("#editPostInput")[0];

                                    // for (let i = 0; i < totalImages; i++) {
                                        formData.append('images', images);
                                    // }
                                    formData.append('totalImages', totalImages);
                                    formData.append('caption', caption);
                                    formData.append('oldimg', storedFilesdb);
                                    formData.append('edit_post_id', edit_post_id);

                                    for (const value of formData.values()) {
                                        console.log(value);
                                    }

                                    $.ajaxSetup({
                                                headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                }
                                            });

                                    $.ajax({
                                            type:'POST',
                                            url:url,
                                            data: formData,
                                            processData: false,
                                            cache: false,
                                            contentType: false,
                                            success:function(data){
                                                if(data.ban){
                                                    Swal.fire({
                                                        text: data.ban,
                                                        timer: 5000,
                                                        timerProgressBar: true,
                                                        icon: 'error',
                                                    })
                                                }else{
                                                    Swal.fire({
                                                        text: data.success,
                                                        timer: 5000,
                                                        timerProgressBar: true,
                                                        icon: 'success',
                                                    }).then(() => {
                                                        window.location.reload()
                                                    })
                                                }
                                                }
                                        });
                                }

                            }
                        })

                        }

                    }
                })

        })
       
        $("#addPostInput").on("change", handleFileSelect);

        $("#editPostInput").on("change", handleFileSelectEdit);

        selDiv = $(".addpost-photo-video-imgpreview-container");

        $("body").on("click", ".delete-preview-icon", removeFile);
        $("body").on("click", ".delete-preview-edit-input-icon", removeFileFromEditInput);

    });
   //accDeleteModal
    $(document).on('click','#delete_account',function(e){
            e.preventDefault();
            // $('#accDeleteModal'). modal('show');
            Swal.fire({
                text: 'Are you sure want to delete your account?',
                timerProgressBar: true,
                showCloseButton: true,
                showCancelButton: true,
                icon: 'warning',
            }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/account_delete'
            }

    });
    });

    ////Edit Post
    var selDiv = "";

    var storedFiles = [];
    var storedFilesEdit = [];
    const dt = new DataTransfer();
    const dtEdit = new DataTransfer();

    function handleFileSelect(e) {

        var files = e.target.files;
        console.log(files)

        var filesArr = Array.prototype.slice.call(files);

        var device = $(e.target).data("device");

        filesArr.forEach(function(f) {
            console.log(f);
            if (f.type.match("image.*")) {
                storedFiles.push(f);

                var reader = new FileReader();
                reader.onload = function(e) {
                var html = "<div class='addpost-preview'><iconify-icon icon='akar-icons:cross' data-file='" + f.name + "' class='delete-preview-icon'></iconify-icon><img src=\"" + e.target.result + "\" data-file='" + f.name + "' class='selFile' title='Click to remove'></div>";

                if (device == "mobile") {
                    $("#selectedFilesM").append(html);
                } else {
                    $(".addpost-photo-video-imgpreview-container").append(html);
                }
                }
                reader.readAsDataURL(f);
                dt.items.add(f);
            }else if(f.type.match("video.*")){
                storedFiles.push(f);

                var reader = new FileReader();
                reader.onload = function(e) {
                var html = "<div class='addpost-preview'><iconify-icon icon='akar-icons:cross' data-file='" + f.name + "' class='delete-preview-icon'></iconify-icon><video controls><source src=\"" + e.target.result + "\" data-file='" + f.name + "' class='selFile' title='Click to remove'>" + f.name + "<br clear=\"left\"/><video></div>";

                if (device == "mobile") {
                    $("#selectedFilesM").append(html);
                } else {
                    $(".addpost-photo-video-imgpreview-container").append(html);
                }
                }
                reader.readAsDataURL(f);
                dt.items.add(f);
            }


        });

        document.getElementById('addPostInput').files = dt.files;
        console.log(document.getElementById('addPostInput').files+" Add Post Input")

    }

    function handleFileSelectEdit(e) {

        var files = e.target.files;
        console.log(files)

        var filesArr = Array.prototype.slice.call(files);

        var device = $(e.target).data("device");

        filesArr.forEach(function(f) {

            if (f.type.match("image.*")) {
                storedFilesEdit.push(f);

                var reader = new FileReader();
                reader.onload = function(e) {
                var html = "<div class='addpost-preview'><iconify-icon icon='akar-icons:cross' data-file='" + f.name + "' class='delete-preview-edit-input-icon'></iconify-icon><img src=\"" + e.target.result + "\" data-file='" + f.name + "' class='selFile' title='Click to remove'></div>";

                if (device == "mobile") {
                    $("#selectedFilesM").append(html);
                } else {
                    $(".editpost-photo-video-imgpreview-container").append(html);
                }
                }
                reader.readAsDataURL(f);
                dtEdit.items.add(f);
            }else if(f.type.match("video.*")){
                storedFilesEdit.push(f);

                var reader = new FileReader();
                reader.onload = function(e) {
                var html = "<div class='addpost-preview'><iconify-icon icon='akar-icons:cross' data-file='" + f.name + "' class='delete-preview-edit-input-icon'></iconify-icon><video controls><source src=\"" + e.target.result + "\" data-file='" + f.name + "' class='selFile' title='Click to remove'>" + f.name + "<br clear=\"left\"/><video></div>";

                if (device == "mobile") {
                    $("#selectedFilesM").append(html);
                } else {
                    $(".editpost-photo-video-imgpreview-container").append(html);
                }
                }
                reader.readAsDataURL(f);
                dtEdit.items.add(f);
            }


        });

        document.getElementById('editPostInput').files = dtEdit.files;
        console.log(document.getElementById('editPostInput').files+" Edit Post Input")

    }

    function removeFile(e) {
        var file = $(this).data("file");
        var names = [];
        for(let i = 0; i < dt.items.length; i++){
            if(file === dt.items[i].getAsFile().name){
                dt.items.remove(i);
            }
        }
        document.getElementById('addPostInput').files = dt.files;

        for (var i = 0; i < storedFiles.length; i++) {
            if (storedFiles[i].name === file) {
            storedFiles.splice(i, 1);
            break;
            }
        }
        $(this).parent().remove();
    }
    function removeFileFromEditInput(e) {
        var file = $(this).data("file");
        var names = [];
        for(let i = 0; i < dtEdit.items.length; i++){
            if(file === dtEdit.items[i].getAsFile().name){
                dtEdit.items.remove(i);
            }
        }
        document.getElementById('editPostInput').files = dtEdit.files;

        for (var i = 0; i < storedFilesEdit.length; i++) {
            if (storedFilesEdit[i].name === file) {
            storedFilesEdit.splice(i, 1);
            break;
            }
        }
        $(this).parent().remove();
    }


    function clearAddPost(){
        storedFiles = []
        dt.clearData()
        document.getElementById('addPostInput').files = dt.files;
        $(".addpost-photo-video-imgpreview-container").empty();
    }

    function clearEditPost(){
        storedFilesEdit = []
        dtEdit.clearData()
        document.getElementById('editPostInput').files = dtEdit.files;
        $(".editpost-photo-video-imgpreview-container").empty();

    }

    function deleteImage(element)
   {
    var profile_id=element.name;
    console.log(profile_id+" Profile ID");
    // $( ".close-image" ).load(window.location.href + " .close-image" );

    Swal.fire({
                text: 'Are you sure to delete this photo?',
                timerProgressBar: true,
                showCloseButton: true,
                showCancelButton: true,
                icon: 'warning',
            }).then((result) => {
            if (result.isConfirmed) {
                var add_url = "{{ route('profile.photo.delete') }}";

                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                });
                $.ajax({
                        method: "POST",
                        url: add_url,
                        data:{
                            profile_id:profile_id
                        },
                        success:function(data){
                            if(data.success){
                                window.location.reload();
                                // $(".social-media-profiles-container").load(location.href + " .social-media-profiles-container");
                            }
                        }
                    })
            }
        })

}
</script>
@endpush
