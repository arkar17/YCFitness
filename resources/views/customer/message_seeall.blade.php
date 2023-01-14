@extends('customer.layouts.app_home')

@section('content')
    <div class="social-media-right-container ">

        <div class="modal fade" id="createGroupModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{__('msg.create group')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('socialmedia.group.create') }}" class="create-group-form" method="POST">
                            @csrf
                            <div class="create-group-name">
                                <p>{{__('msg.group name')}}</p>
                                <input type="text" name="group_name" required>
                            </div>
                            <div class="create-group-addfris">
                                <p>{{__('msg.add your friends')}}</p>
                                <select class="js-example-basic-multiple" name="members[]" multiple="multiple">

                                    @forelse ($friends as $friend)
                                        <option value="{{ $friend->id }}">{{ $friend->name }}</option>
                                    @empty
                                        <p>You have not friend. Please friend request to other people.</p>
                                    @endforelse

                                </select>
                            </div>
                            <button type="submit" class="customer-primary-btn create-group-submit-btn">{{__('msg.create')}}</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <div class="social-media-allchats-header">
            <p>{{__('msg.messages')}}</p>
            <div class="social-media-allchats-header-btn-container">
                <button type="button" class="social-media-allchats-header-add-btn customer-primary-btn"
                    data-bs-toggle="modal" data-bs-target="#createGroupModal">
                    <iconify-icon icon="akar-icons:circle-plus" class="social-media-allchats-header-plus-icon">
                    </iconify-icon>
                    <p>{{__('msg.group')}}</p>
                </button>
            </div>
        </div>

        <div class="social-media-allchats-messages-container">
            {{-- @forelse ($messages as $list)
                    <div class="social-media-allchats-message-row-container">
                        <a href="{{route('message.chat',$list->id)}}" class="social-media-allchats-message-row">
                            <div class="social-media-allchats-message-img">
                                @if ($list->profile_image == null)
                                                <img  class="nav-profile-img" src="{{asset('img/customer/imgs/user_default.jpg')}}"/>
                                            @else
                                                <img  class="nav-profile-img" src="{{asset('storage/post/'.$list->profile_image)}}"/>
                                            @endif

                                <p>{{$list->name}}</p>
                            </div>

                            <p>{{$list->text}}</p>

                            <span>{{ \Carbon\Carbon::parse($list->created_at)->format('d M, g:i A')}}</span>
                        </a>

                        <div class="social-media-allchats-actions-container">
                            <iconify-icon icon="mdi:dots-vertical" class="social-media-allchats-actions-toggle"></iconify-icon>
                            <div class="social-media-allchats-actions-box">
                                <div  data-id="{{$list->from_id}}" class="converstion_delete" id="{{$list->to_id}}">
                                    <iconify-icon icon="tabler:trash" class="social-media-allchats-action-icon"></iconify-icon>
                                    <span>Delete</span>
                                </div>
                                <a href="{{route('socialmedia.profile',$list->id)}}" style="text-decoration:none">
                                    <iconify-icon icon="material-symbols:person" class="social-media-allchats-action-icon"></iconify-icon>
                                    Profile
                                </a>
                            </div>

                        </div>
                    </div>

                    @empty
                        <p>No Message</p>
                    @endforelse --}}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.social-media-allchats-actions-toggle', function() {
                $(".social-media-allchats-actions-box").not($(this).next(
                    ".social-media-allchats-actions-box")).hide()
                $(this).next('.social-media-allchats-actions-box').toggle()
            })
            $(document).on('click', '.converstion_delete', function(e) {
                var from_id = $(this).data('id');
                var to_id = $(this).attr('id');

                Swal.fire({
                    text: "Delete Conversation?",
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

                }).then((result) =>{
                    if (result.isConfirmed) {
                        var add_url = "{{ route('message.all.delete')}}";
                    $.ajax({
                        method: "GET",
                        url: add_url,
                        data: {
                            from_id: from_id,
                            to_id: to_id
                        },
                        success: function(data) {
                            Swal.fire({
                                text: data.success,
                                timerProgressBar: true,
                                timer: 5000,
                                icon: 'success',
                            })
                            messages()
                        }
                    })
                }
            })

            })


            var user_id = {{ auth()->user()->id }};
            console.log(user_id);
            var pusher = new Pusher('{{ env('MIX_PUSHER_APP_KEY') }}', {
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                encrypted: true
            });

            var channel = pusher.subscribe('all_message.' + user_id);
            channel.bind('all', function(data) {
                console.log(data, "ted");
                messages()
            });

            messages()
            //
            function messages() {
                var latest_messages = "{{ route('socialmedia.latest_messages') }}";
                $.get(latest_messages, {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    function(data) {
                        table_post_row(data);
                    });
            }
            // table row with ajax
            function table_post_row(res) {

                let htmlView = '';
                if (res.data.length <= 0) {
                    htmlView += `
                        No data found.
                        `;
                }
                for (let i = 0; i < res.data.length; i++) {
                    id = res.data[i].id;
                    var url = "{{ route('message.chat', ':id') }}";
                    url = url.replace(':id', id);
                    var group_url = "{{ route('socialmedia.group', ':id') }}";
                    group_url = group_url.replace(':id', id);

                    var social_media = "{{ route('socialmedia.profile', [':id']) }}";
                    social_media = social_media.replace(':id',id);

                    var group_detail = "{{ route('socialmedia.group.detail', [':id']) }}";
                    group_detail = group_detail.replace(':id',id);

                    if(res.data[i].is_group == 0){
                        htmlView += `
                            <div class="social-media-allchats-message-row-container">
                        <a href=` + url + ` class="social-media-allchats-message-row">
                            <div class="social-media-allchats-message-img">`
                        if(res.data[i].profile_image==null){
                            htmlView +=` <img  class="nav-profile-img" src="{{asset('img/customer/imgs/user_default.jpg')}}"/>`
                        }else{
                            htmlView +=` <img  class="nav-profile-img" src="{{asset('storage/post/`+res.data[i].profile_image+`')}}"/>`
                        }

                        htmlView +=` <p>` + res.data[i].name + `</p>
                            </div>

                            <p>` + res.data[i].text + `</p>

                            <span>` + res.data[i].date + `</span>
                        </a>

                        <div class="social-media-allchats-actions-container">
                            <iconify-icon icon="mdi:dots-vertical" class="social-media-allchats-actions-toggle"></iconify-icon>
                            <div class="social-media-allchats-actions-box">
                                <div  data-id=` + res.data[i].from_id + ` class="converstion_delete"
                                id= ` + res.data[i].to_id + `>
                                    <iconify-icon icon="tabler:trash" class="social-media-allchats-action-icon"></iconify-icon>
                                    <span>Delete</span>
                                </div>
                                <a href=` + social_media + ` >
                                    <iconify-icon icon="material-symbols:person" class="social-media-allchats-action-icon"></iconify-icon>
                                    Profile
                                </a>
                            </div>

                        </div>
                    </div>`

                    } else {
                        htmlView += `
                            <div class="social-media-allchats-message-row-container">
                                <a href=` + group_url + ` class="social-media-allchats-message-row">
                                    <div class="social-media-allchats-message-img">
                                    <img  class="nav-profile-img" src="{{asset('img/customer/imgs/group_default.png')}}"/>
                                        <p>` + res.data[i].name + `</p>
                                    </div>

                                    <p>` + res.data[i].text + `</p>

                                    <span>` + res.data[i].date + `</span>
                                </a>

                        <div class="social-media-allchats-actions-container">
                            <iconify-icon icon="mdi:dots-vertical" class="social-media-allchats-actions-toggle"></iconify-icon>
                            <div class="social-media-allchats-actions-box">
                                <div  data-id=` + res.data[i].from_id + ` class="leave_group"
                                id= `+ res.data[i].to_id + `>
                                    <iconify-icon icon="tabler:trash" class="social-media-allchats-action-icon"></iconify-icon>
                                    <span>Leave</span>
                                </div>
                                <a  href=` + group_detail + `>
                                    <iconify-icon icon="material-symbols:person" class="social-media-allchats-action-icon"></iconify-icon>
                                    Detail
                                </a>
                            </div>

                        </div>
                            </div>
                            `
                    }
                }
                $('.social-media-allchats-messages-container').html(htmlView);
            }


            $('.social-media-seeallmessage-header-icon').click(function() {
                $(this).next().toggle()
            })

            $('.js-example-basic-multiple').select2({
                dropdownParent: "#createGroupModal"
            });

            $('.select2-container').attr('style', '');
        })
    </script>
@endpush
