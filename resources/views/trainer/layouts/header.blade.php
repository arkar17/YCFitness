<div class="customer-header customer-header-with-shadow">
    <div class="customer-main-content-container customer-navbar-container">
        <div class="customer-logo-language-container">
            <div class="customer-logo">
                {{-- LOGO --}}
            </div>
            <div class="customer-language-container">

                <select>
                    <option value="">Myanmar</option>
                    <option value="">English</option>
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
            <a href="{{route('home')}}">Home</a>
            <a href="#">Shop</a>
            <a href="#">Search</a>
            <a href="{{route('trainers')}}">Training Center</a>
            <div class="customer-dropdown-container">
                <ul>
                    <li class="customer-dropdown">
                    <a href="#" data-toggle="dropdown">
                        <img class="nav-profile-img" src="{{asset('img/avatar.png')}}"/>
                        <i class="icon-arrow"></i></a>
                    <ul class="customer-dropdown-menu">
                        <li><a href="#">Profile</a></li>
                        <li><form class="dropdown-item" id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="customer-primary-btn customer-login-btn" type="submit">Logout</button>
                        </form></li>

                    </ul>
                    </li>
                </ul>
            </div>
        </div>

        <div class="customer-navlinks-notiprofile-container">
            <div class="noti-bell-container">
                <a href="#"><iconify-icon icon="akar-icons:bell" class="nav-icon"> </iconify-icon></a>
                <div class="noti-count">0</div>
            </div>
            <iconify-icon icon="pajamas:hamburger" class="burger-icon"></iconify-icon>
            <iconify-icon icon="akar-icons:cross" class="close-nav-icon"></iconify-icon>
        </div>
    </div>
</div>

