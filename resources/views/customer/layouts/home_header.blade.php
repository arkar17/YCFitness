<div class="overlay">

</div>
<div class="customer-header index-page-header">
    <div class="customer-main-content-container customer-navbar-container">
        <div class="customer-logo-language-container">
            <div class="customer-logo">
            </div>
            <div class="customer-language-container">
                <div class="customer-language-flag-container">
                    <img src="../imgs/ukflag.png">
                </div>

                <select>
                    <option value="">Myanmar</option>
                    <option value="">English</option>
                </select>
            </div>
            @auth
            <div class="theme-contaier">
                <select class="theme">
                    <option selected value="light">Light</option>
                    <option value="dark">Dark</option>
                    <option value="pink">Pink</option>
                </select>
            </div>
            @endauth

        </div>
        <div class="customer-navlinks-container">
            <a href="{{route('home')}}">Home</a>
            <a href="#">Shop</a>
            @auth
            <a href="#">Search</a>
            @endauth
            @hasanyrole('Diamond|Platinum|Gym Member')
            <a href="{{route('training_center.index')}}">Training Center</a>
            @endhasanyrole
            @hasanyrole('Gold|Ruby|Ruby Premium')
            <a href="{{route('groups')}}">Training Center</a>
            @endhasanyrole
            @hasanyrole('Trainer')
            <a href="{{route('trainers')}}">Training Center</a>
            @endhasanyrole
            @hasanyrole('System_Admin|King|Queen')
            <a href="{{route('home')}}">Dashboard</a>
            @endhasanyrole
            @auth
            <a href="#">Notifications</a>
            <a href="#">Account</a>
            @endauth
        </div>

        <div class="customer-nav-btns-container">
            @guest
          <a href="{{ route('login') }}" class="customer-primary-btn customer-login-btn">Log In</a>
          <a href="{{route('customer_register')}}" class="customer-secondary-btn customer-signup-btn">Sign Up</a>
          @endguest
          @if(Auth::user())

          <p>{{Auth()->user()->name}}</p>

          <form id="logout-form" action="{{ route('logout') }}" method="POST">
              @csrf
              <button class="customer-primary-btn customer-login-btn" type="submit">Logout</button>
          </form>

          @endif
        </div>
    </div>
</div>

