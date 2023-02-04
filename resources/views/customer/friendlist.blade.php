@extends('customer.layouts.app_home')

@section('content')
@include('sweetalert::alert')

<div class="social-media-right-container ">
    <h3 style = "text-align: center"><b>{{$user->name}}'s</b> {{__('msg.friends')}} </h3>
    <div class="social-media-fris-search">
         <input type="text" placeholder="Search your friends" id = "search">
         <iconify-icon icon="akar-icons:search" class="search-icon"></iconify-icon>
    </div>

    <div class="social-media-fris-list-container">


    </div>
 </div>

@endsection
@push('scripts')
<script>
        $(document).ready(function() {
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
                                            <a href=`+url+`>
                                                <img src="{{asset('img/customer/imgs/user_default.jpg')}}">
                                            <p>`+res.friends[i].name+`</p>
                                            </a>
                                        </div>


                                        <div class="social-media-fris-fri-btns-container">
                                            <a href="#" class="customer-primary-btn">Message</a>

                                            <a href="?id=` + res.friends[i].id+`" class="customer-red-btn"
                                            id = "unfriend">Remove</a>

                                        </div>
                                    </div>                                    `
                                }
                                else{
                                    htmlView += `
                                    <div class="social-media-fris-fri-row">
                                        <div class="social-media-fris-fri-img">
                                            <a href=`+url+`>
                                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${res.friends[i].profile_image}">
                                            <p>`+res.friends[i].name+`</p>
                                            </a>
                                        </div>


                                        <div class="social-media-fris-fri-btns-container">
                                            <a href="#" class="customer-primary-btn">Message</a>

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
                                                <img src="https://yc-fitness.sgp1.cdn.digitaloceanspaces.com/public/post/${res.friends[i].profile_image}">
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
                                        window.location.reload();
                                    }
                                })
                        }
                        })
                $('.social-media-left-searched-items-container').empty();
                });

        })
</script>
@endpush
