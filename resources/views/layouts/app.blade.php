<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <link href={{ asset('css/adminchat/trainingcenter.css') }} rel="stylesheet" />

    {{-- //// --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!-- MDB -->

    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/5.0.0/mdb.min.css" rel="stylesheet" />



    <!-- Datatable -->

    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" href=" https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">



    <!-- Datepicker -->

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    {{-- <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}"> --}}



    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



    <!-- Font Awesome -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />



    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <link rel="stylesheet" href="{{ asset('css/workout.css') }}">

    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminchat/trainingcenter.css') }}">

    <!--social media -->
    <link href="{{ asset('css/socialMedia.css') }}" rel="stylesheet" />


    @yield('styles')

</head>


<body>

    <div class="wrapper">

        <nav id="sidebar" class="sidebar js-sidebar">

            <div class="sidebar-content js-simplebar">

                <a class="sidebar-brand" href="index.html">

                    <span class="align-middle">GYM</span>

                </a>

                <ul class="sidebar-nav">

                    <li class="sidebar-header">

                        Pages

                    </li>

                    @hasanyrole('Admin')

                  
                     
                    <li class="sidebar-item @yield('dashboard-active')">

                        <a class="sidebar-link" href="{{ route('home') }}">

                            <i class="fa-solid fa-layer-group align-middle "></i>

                            <span class="align-middle">Dashboard</span>

                        </a>

                    </li>
                    <li class="sidebar-item @yield('messages-active')">

                        <a class="sidebar-link" href="{{ route('admin.chat_with_admin') }}">

                            <i class="fa-solid fa-layer-group align-middle "></i>

                            <span class="align-middle">Messages</span>

                        </a>

                    </li>
                    <li class="sidebar-item @yield('request-active')">

                        <a class="sidebar-link" href="{{ route('request.index') }}">

                            <i class="fa-solid fa-user-group align-middle"></i> <span
                                class="align-middle">Request</span>
                            @if ($memberRequest_count < 1)
                                <span></span>
                            @else
                                <span class="badge badge-light">{{ $memberRequest_count }}</span>
                            @endif
                        </a>

                    </li>

                    <li class="sidebar-item @yield('shop_request-active')">

                        <a class="sidebar-link" href="{{ route('admin.shop_request') }}">

                            <i class="fa-solid fa-shop"></i> <span

                                class="align-middle">Shop Request</span>
                                @if ($shopRequest_count < 1)
                                <span></span>
                               @else
                               <span class="badge badge-light">{{ $shopRequest_count }}</span>
                               @endif

                        </a>

                    </li>

                    
                    <li class="sidebar-item @yield('user-active') ">

                        <a class="sidebar-link" href="{{ route('user.index') }}">

                            <i class="fa-solid fa-users align-middle "></i> <span class="align-middle">Users</span>
                        </a>
                    </li>

                    <li class="sidebar-item ">

                        <a class="sidebar-link" href="#collapseExample" data-mdb-toggle="collapse"
                            aria-expanded="false" aria-controls="collapseExample">

                            <i class="fa-solid fa-m"></i>

                            <span class="align-middle">Member</span>

                        </a>

                    <li class="collapse mt-3" id="collapseExample">

                        <a class="sidebar-link text-white" href="{{ route('member.user_member') }}">

                            <i class="fa-solid fa-user-group  align-middle"></i>

                            <span class="align-middle">Members</span>

                        </a>

                        <a class="sidebar-link text-white" href="{{ route('member.index') }}">

                            <i class="fa-solid fa-plus"></i>

                            <span class="align-middle">Create Member Type</span>

                        </a>

                    </li>
            
                    @endhasanyrole
                    @hasanyrole('System_Admin|King|Queen')
                    <li class="sidebar-item @yield('dashboard-active')">

                        <a class="sidebar-link" href="{{ route('home') }}">

                            <i class="fa-solid fa-layer-group align-middle "></i>

                            <span class="align-middle">Dashboard</span>

                        </a>

                    </li>



                    <li class="sidebar-item @yield('request-active')">

                        <a class="sidebar-link" href="{{ route('request.index') }}">

                            <i class="fa-solid fa-user-group align-middle"></i> <span
                                class="align-middle">Request</span>
                            @if ($memberRequest_count < 1)
                                <span></span>
                            @else
                                <span class="badge badge-light">{{ $memberRequest_count }}</span>
                            @endif
                        </a>

                    </li>

                    <li class="sidebar-item @yield('shop_request-active')">

                        <a class="sidebar-link" href="{{ route('admin.shop_request') }}">

                            <i class="fa-solid fa-shop"></i> <span

                                class="align-middle">Shop Request</span>
                                @if ($shopRequest_count < 1)
                                <span></span>
                               @else
                               <span class="badge badge-light">{{ $shopRequest_count }}</span>
                               @endif

                        </a>

                    </li>

                    <li class="sidebar-item @yield('transction-active')">

                        <a class="sidebar-link" href="{{ route('payment.transction') }}">

                            <i class="fa-solid fa-money-bill-transfer"></i> <span class="align-middle">Payment
                                Transaction</span>

                        </a>

                    </li>



                    <li class="sidebar-item @yield('user-active') ">

                        <a class="sidebar-link" href="{{ route('user.index') }}">

                            <i class="fa-solid fa-users align-middle "></i> <span class="align-middle">Users</span>
                        </a>
                    </li>



                    <li class="sidebar-item @yield('trainer-active') ">
                        <a class="sidebar-link" href="{{ route('trainer.index') }}">

                            <i class="fa-solid fa-person fs-4"></i> <span
                                class="align-middle">Trainers</span>
                        </a>
                    </li>

                    <li class="sidebar-item @yield('shopmember-active') ">
                        <a class="sidebar-link" href="{{ route('shop-member.index') }}">

                            <i class="fa-brands fa-stripe-s"></i> <span class="align-middle">Shop
                                MemberPlan</span>
                        </a>
                    </li>


                    <li class="sidebar-item @yield('training-center-active') ">
                        <a class="sidebar-link" href="{{ route('traininggroup.index') }}">

                            <i class="fa-solid fa-dumbbell align-middle "></i> <span class="align-middle">Training
                                Center</span>
                        </a>
                    </li>

                    <li class="sidebar-item"  @yield('training-group-active')>
                        <a class="sidebar-link" href="{{ route('traininggroup.create') }}">
                            <i class="fa-solid fa-people-group"></i> <span class="align-middle">Training Group</span>
                        </a>
                    </li>
                    <li class="sidebar-item ">

                        <a class="sidebar-link" href="#collapseExample" data-mdb-toggle="collapse"
                            aria-expanded="false" aria-controls="collapseExample">

                            <i class="fa-solid fa-m"></i>

                            <span class="align-middle">Member</span>

                        </a>

                    <li class="collapse mt-3" id="collapseExample">

                        <a class="sidebar-link text-white" href="{{ route('member.user_member') }}">

                            <i class="fa-solid fa-user-group  align-middle"></i>

                            <span class="align-middle">Members</span>

                        </a>

                        <a class="sidebar-link text-white" href="{{ route('member.index') }}">

                            <i class="fa-solid fa-plus"></i>

                            <span class="align-middle">Create Member Type</span>

                        </a>

                    </li>
                    </li>

                    {{-- <li class="sidebar-item @yield('mealplan-active')">

                        <a class="sidebar-link" href="{{ route('mealplan.index') }}">

                            <i class="fa-solid fa-utensils  align-middle"></i> <span class="align-middle">Meal

                                Plans</span>

                        </a>

                    </li> --}}



                    <li class="sidebar-item @yield('meal-active')">

                        <a class="sidebar-link" href="{{ route('meal.index') }}">

                            <i class="fa-solid fa-burger align-middle"></i> <span class="align-middle">Meals</span>

                        </a>

                    </li>





                    <li class="sidebar-item @yield('permission-active')">

                        <a class="sidebar-link" href="{{ route('permission.index') }}">

                            <i class="fa-solid fa-shield-halved align-middle"></i> <span
                                class="align-middle">Permissions</span>

                        </a>

                    </li>



                    <li class="sidebar-item @yield('role-active')">

                        <a class="sidebar-link" href="{{ route('role.index') }}">

                            <i class="fa-solid fa-user-shield align-middle"></i> <span
                                class="align-middle">Roles</span>

                        </a>

                    </li>



                    <li class="sidebar-item @yield('workoutplan-active')">

                        <a class="sidebar-link" href="{{ route('workoutview') }}">

                            <i class="fa-solid fa-clipboard-list"></i>

                            <span class="align-middle">Workout</span>

                        </a>

                    </li>

                    <li class="sidebar-item @yield('free-video-active')">

                        <a class="sidebar-link" href="{{ route('free_video.index') }}">

                            <i class="fa-solid fa-clipboard-list"></i>

                            <span class="align-middle">Free Videos</span>

                        </a>

                    </li>




                    <li class="sidebar-item @yield('bankinginfo-active')">

                        <a class="sidebar-link" href="{{ route('bankinginfo.index') }}">

                            <i class="fa-solid fa-layer-group align-middle "></i>

                            <span class="align-middle">Banking Info</span>

                        </a>

                    </li>

                    <li class="sidebar-item @yield('banwords-active')">

                        <a class="sidebar-link" href="{{ route('banwords.index') }}">

                            <i class="fa-solid fa-ban align-middle"></i>
                            <span class="align-middle">Ban Words</span>

                        </a>

                    </li>

                    <li class="sidebar-item @yield('social-report-active')">

                        <a class="sidebar-link" href="{{ route('report.index') }}">

                            <i class="fa-regular fa-file-lines"></i>
                            <span class="align-middle">Social Media Reports</span>

                        </a>

                    </li>
                    @endhasanyrole
                </ul>

            </div>

        </nav>

        <div class="main">

            <nav class="navbar navbar-expand navbar-light navbar-bg d-flex justify-content-between">

                <a class="sidebar-toggle js-sidebar-toggle">

                    <i class="hamburger align-self-center"></i>

                </a>
                <div class="customer-navlinks-notiprofile-container">
                    <iconify-icon icon="akar-icons:bell" class="nav-icon">
                    </iconify-icon>

                    <div class="notis-box-container">
                        <div class="notis-box-header">
                            <p>Notifications</p>
                            <a href="{{ route('notification_center') }}">See All</a>
                        </div>

                        <div class="notis-box-notis-container">
                            <?php $count = 0; ?>
                            @foreach (auth()->user()->notifri->sortByDesc('created_at') as $noti)
                                <?php if ($count == 10) {
                                    break;
                                } ?>
                                @if ($noti->report_id != 0 and $noti->notification_status == 1)
                                    <a href="{{ route('admin.view.report', $noti->report_id) }}">
                                        <div class="notis-box-noti-row notis-box-unread-noti">
                                            <img src="{{ asset('img/customer/imgs/report.png') }}" />
                                            <div class="notis-box-noti-row-detail">
                                                <span>{{ $noti->created_at->diffForHumans() }}
                                                </span>
                                                <p>{{ $noti->description }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @elseif($noti->report_id != 0 and $noti->notification_status != 1)
                                    <a href="{{ route('admin.view.report', $noti->report_id) }}">
                                        <div class="notis-box-noti-row notis-box-read-noti">
                                            <img src="{{ asset('img/customer/imgs/report.png') }}" />
                                            <div class="notis-box-noti-row-detail">
                                                <span>{{ $noti->created_at->diffForHumans() }}
                                                </span>
                                                <p>{{ $noti->description }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @endif

                                <?php $count++; ?>
                            @endforeach
                        </div>

                    </div>


                    <div class="dropdown">

                        <img src="{{ asset('img/avatar.png') }}" style="cursor: pointer;"
                            class="rounded-circle me-2" width="35" alt="">

                        <span class="mb-0 me-4 dropdown-toggle" style="cursor: pointer;" data-mdb-toggle="dropdown">

                            {{ auth()->user()->name }} <i class="fa-solid fa-angle-down fa-sm"></i></span>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                            <li><a class="dropdown-item" href="{{ route('admin-profile') }}">Profile</a></li>

                            {{-- <li><a class="dropdown-item logout-btn">Logout</a></li> --}}
                            <li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>

                        </ul>

                    </div>

                </div>
            </nav>

            <main class="content">

                <div class="container-fluid p-0">

                    @yield('content')

                </div>

            </main>

        </div>

    </div>



    <!-- JQuery -->

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>



    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


    <script src=" https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Scripts -->

    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/new.js') }}"></script>

    <!-- MDB -->

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/5.0.0/mdb.min.js"></script>



    <!-- Datatable -->

    <script src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.12.0/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.datatables.net/fixedheader/3.2.3/js/dataTables.fixedHeader.min.js"></script>

    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>

    <script src="https://cdn.datatables.net/responsive/2.3.0/js/responsive.bootstrap.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>


    <!--chart js-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    {{-- datepicker --}}

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>



    <!--iconify-->
    <script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>



    <!-- Sweet Alert -->

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <!-- Select 2 -->

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>



    <!-- Laravel Javascript Validation -->

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>

    {{-- axios --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.1.3/axios.min.js"
        integrity="sha512-0qU9M9jfqPw6FKkPafM3gy2CBAvUWnYVOfNPDYKVuRTel1PrciTj+a9P3loJB+j0QmN2Y0JYQmkBBS8W+mbezg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- pusher --}}
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    {{-- emoji --}}
    <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>

    <script src={{ asset('js/notify.js') }}></script>

    {{-- pusher --}}
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    @stack('scripts')

    <script>
        $('.nav-icon').click(function() {
            $('.notis-box-container').toggle()
        })

        var user_id = {{ auth()->user()->id }};
        console.log(user_id);
        var pusher = new Pusher('{{ env('MIX_PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });
        var channel = pusher.subscribe('friend_request.' + user_id);
        channel.bind('friendRequest', function(data) {
            console.log(data);
            $.notify(data, "success", {
                position: "left"
            });
        });

        $(document).ready(function() {


            $(document).on('click', '.accept', function(e) {
                e.preventDefault();
                var url = new URL(this.href);
                var id = url.searchParams.get("id");
                console.log(id, "noti_id");

            });

            let token = document.head.querySelector('meta[name="csrf-token"]');
            if (token) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': token.content
                    }
                });
            } else {
                console.error('CSRF TOKEN not found!');
            }
            $(document).on('click', '.previousLink', function(e) {

                e.preventDefault();

                window.history.back();

            })

            $(document).on('click', '.logout-btn', function(e) {

                e.preventDefault();
                swal({
                        text: "Are you sure you want to Logout?",
                        buttons: true,
                        dangerMode: true,

                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                method: "POST",
                                url: `/logout`
                            })
                            location.reload();
                        } else {
                            swal("You still Login");
                        }

                    });
            })

            $(document).on('submit', 'form', function() {
                $('button').attr('disabled', 'disabled');
            });
            $(".ninja-select").select2();

        });
    </script>

</body>



</html>
