<div class="customer-header customer-header-with-shadow">
    <div class="customer-main-content-container customer-navbar-container">
        <div class="customer-logo-language-container">
            <div class="customer-logo">
                {{-- LOGO --}}
            </div>
            <div class="customer-language-container">
                <select class="langChange">
                    <option value="en" {{session()->get('locale') == 'en' ? 'selected' : ''}}>English</option>
                    <option value="mm" {{session()->get('locale') == 'mm' ? 'selected' : ''}}>Myanmar</option>

                </select>
            </div>
            <div class="theme-contaier">
                <select class="theme">
                    <option value="light">Light</option>
                    <option value="dark">Dark</option>
                    <option value="pink">Pink</option>
                </select>
            </div>

        </div>

        <div class="customer-navlinks-container">

            <a href="{{route('home')}}">{{ __('msg.home') }}</a>
            @hasanyrole('System_Admin')
            <a href="{{route('home')}}">Dashboard</a>
            @endhasanyrole
            <a href="{{route('shop')}}">{{__('msg.shop')}}</a>
            @auth
            @if ( auth()->user()->request_type ==null && count(auth()->user()->roles)<1)
                <a href="{{route('customer-personal_infos')}}">{{__('msg.training center')}}</a>

            @elseif(auth()->user()->request_type !=null && auth()->user()->active_status==0)
                <a href="{{route('customer_payment')}}">{{__('msg.training center')}}</a>
            @endif

            @endauth
            @hasanyrole('Diamond|Platinum|Gym Member')
            <a href="{{route('training_center.index')}}">{{__('msg.training center')}}</a>
            @endhasanyrole

            @hasanyrole('Gold|Ruby|Ruby Premium')
            <a href="{{route('groups')}}">{{__('msg.training center')}}</a>
            @endhasanyrole

            @hasanyrole('Trainer')
            <a href="{{route('trainers')}}">{{__('msg.training center')}}</a>
            @endhasanyrole
            @auth
            <div class="customer-dropdown-container">
                <ul>
                    <li class="customer-dropdown">

                    <a href="#" data-toggle="dropdown">

                        @if ($user_profileimage==null)
                            <img class="nav-profile-img" src="{{asset('img/user.jpg')}}"/>
                        @else
                            <img class="nav-profile-img" src="
                            https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/{{ $user_profileimage->profile_image}}"/>
                        @endif
                        <i class="icon-arrow"></i>
                        {{-- <p class="customer-dropdown-name">{{auth()->user()->name}}</p> --}}
                    </a>
                    <ul class="customer-dropdown-menu">
                        <li><a href="{{route('customer-profile')}}">{{__('msg.profile')}}</a></li>
                        <li><form class="dropdown-item" id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="customer-primary-btn customer-login-btn" type="submit">{{__('msg.log out')}}</button>
                        </form></li>

                    </ul>
                    </li>
                </ul>
            </div>
            @endauth
        </div>

        {{-- <div class="customer-navlinks-notiprofile-container">
            <a href="#"><iconify-icon icon="akar-icons:bell" class="nav-icon"></iconify-icon></a>
            <div class="dropdown customer-navlinks-profile-dropdown">
                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img class="nav-profile-img" src="{{asset('img/avatar.jpg')}}"/>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                  <li><a class="dropdown-item" href="#">Profile</a></li>
                  <li><form class="dropdown-item" id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="customer-primary-btn customer-login-btn" type="submit">Logout</button>
                </form></li>

                </ul>
            </div>

        </div> --}}
        <div class="customer-navlinks-notiprofile-container">
            <div class="noti-bell-container">
                <iconify-icon icon="akar-icons:bell" class="nav-icon"></iconify-icon>
                <div class="noti-count"><span id="noti_count">{{ auth()->user()->notifri->where('notification_status',1)->count() }}</span></div>
            </div>
            <iconify-icon icon="pajamas:hamburger" class="burger-icon"></iconify-icon>
            <iconify-icon icon="akar-icons:cross" class="close-nav-icon"></iconify-icon>

            <div class="notis-box-container" id="noti_center">
                <div class="notis-box-header">
                    <p>{{__('msg.notifications')}}</p>
                    <a href="{{ route('notification_center') }}">{{__('msg.see all')}}</a>
                </div>

                <div class="notis-box-notis-container">
                    <?php $count = 0; ?>
                    @foreach(auth()->user()->notifri->sortByDesc('created_at') as $noti)
                    <?php if($count == 10) break; ?>
                    @if($noti->report_id != 0 AND $noti->notification_status == 1)
                    <a href ="?id={{$noti->id}}"  class = "report_noti" id = {{$noti->sender_id}} >
                        <div class="notis-box-noti-row notis-box-unread-noti">
                            <img src="{{asset('img/customer/imgs/report.png')}}"/>
                            <div class="notis-box-noti-row-detail">
                                <span>{{$noti->created_at->diffForHumans()}}
                                </span>
                                <p>{{$noti->description}}</p>
                            </div>
                        </div>
                    </a>
                    @elseif($noti->report_id != 0 AND $noti->notification_status != 1)
                    <a href ="?id={{$noti->id}}"  class = "report_noti" id = {{$noti->sender_id}} >
                        <div class="notis-box-noti-row ">
                            <img src="{{asset('img/customer/imgs/report.png')}}"/>
                            <div class="notis-box-noti-row-detail">
                                <span>{{$noti->created_at->diffForHumans()}}
                                </span>
                                <p>{{$noti->description}}</p>
                            </div>
                        </div>
                    </a>
                    @elseif($noti->notification_status == 1 AND $noti->post_id == null AND $noti->report_id==0)
                        <a href ="?id={{$noti->id}}"  class = "accept" id = {{$noti->sender_id}}>
                            <div class="notis-box-noti-row notis-box-unread-noti">
                                <img src="{{asset('img/avatar.png')}}">
                                <div class="notis-box-noti-row-detail">
                                    <span>{{$noti->created_at->diffForHumans()}}
                                    </span>
                                    <p>{{$noti->description}}</p>
                                </div>
                            </div>
                        </a>
                    @elseif($noti->notification_status != 1 AND $noti->post_id == null AND $noti->report_id==0)
                        <a href ="?id={{$noti->id}}"  class = "accept" id = {{$noti->sender_id}}>
                            <div class="notis-box-noti-row notis-box-read-noti ">
                                <img src="{{asset('img/avatar.png')}}">
                                <div class="notis-box-noti-row-detail">
                                    <span>{{$noti->created_at->diffForHumans()}}</span>
                                    <p>{{$noti->description}}</p>
                                </div>
                            </div>
                        </a>
                    @elseif($noti->notification_status == 1 AND $noti->post_id != null AND $noti->comment_id != null AND $noti->report_id==0)
                        <a href ="?id={{$noti->id}}"  class = "view_comment" id = {{$noti->post_id}}>
                            <div class="notis-box-noti-row notis-box-unread-noti">
                                <img src="{{asset('img/avatar.png')}}">
                                <div class="notis-box-noti-row-detail">
                                    <span>{{$noti->created_at->diffForHumans()}}
                                    </span>
                                    <p>{{$noti->description}}</p>
                                </div>
                            </div>
                        </a>
                    @elseif($noti->notification_status != 1 AND $noti->post_id != null AND $noti->comment_id != null AND $noti->report_id==0)
                    <a href ="?id={{$noti->id}}"  class = "view_comment" id = {{$noti->post_id}}>
                        <div class="notis-box-noti-row notis-box-read-noti ">
                            <img src="{{asset('img/avatar.png')}}">
                            <div class="notis-box-noti-row-detail">
                                <span>{{$noti->created_at->diffForHumans()}}</span>
                                <p>{{$noti->description}}</p>
                            </div>
                        </div>
                    </a>


                    @elseif($noti->notification_status != 1 AND $noti->post_id != null AND $noti->comment_id == null AND $noti->report_id==0)
                    <a href ="?id={{$noti->id}}"  class = "view_like" id = {{$noti->post_id}}>
                        <div class="notis-box-noti-row ">
                            <img src="{{asset('img/avatar.png')}}">
                            <div class="notis-box-noti-row-detail">
                                <span>{{$noti->created_at->diffForHumans()}}</span>
                                <p>{{$noti->description}}</p>
                            </div>
                        </div>
                    </a>
                    @elseif($noti->notification_status == 1 AND $noti->post_id != null AND $noti->comment_id == null AND $noti->report_id==0)
                    <a href ="?id={{$noti->id}}"  class = "view_like" id = {{$noti->sender_id}}>
                        <div class="notis-box-noti-row notis-box-unread-noti ">
                            <img src="{{asset('img/avatar.png')}}">
                            <div class="notis-box-noti-row-detail">
                                <span>{{$noti->created_at->diffForHumans()}}</span>
                                <p>{{$noti->description}}</p>
                            </div>
                        </div>
                    </a>

                    
                        @endif
                        <?php $count++; ?>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>
