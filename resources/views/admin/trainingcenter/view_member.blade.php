@extends('layouts.app')
@section('training-center-active', 'active')
@section('content')

    <div class="customer-main-content-container">
        <div class="trainer-main-content-container">

            <div class="trainer-two-columns-container">
                <div class="trainer-group-chats-parent-container">
                    <p>Groups</p>
                    <div class="trainer-group-chats-container">
                        @forelse ($groups as $group)
                            <a href="{{ route('chat_message', $group['id']) }}" class="tainer-group-chat-name-container"
                                id="group-chat" value="{{ $group->id }}" data-id="{{ $group->id }}"
                                style=" background-color: transparent;background-repeat: no-repeat;border: none;cursor: pointer;overflow: hidden;outline: none; text-decoration: none;">
                                <img src="{{ asset('img/avatar.png') }}" />
                                <p>{{ $group->group_name }}</p>
                            </a>
                        @empty
                            <p class="text-secondary p-1">No Group</p>
                        @endforelse
                    </div>
                </div>
                <div class="group-chat-container">
                    <div class="group-chat-header">
                        <a href="" id="group" class="group-chat-header-name-container"
                            style="text-decoration: none;">
                            <img src="{{ asset('img/avatar.png') }}" />
                            <div class="group-chat-header-name-text-container">
                                <p>{{ $selected_group->group_name }}</p>
                            </div>
                        </a>

                        <a href="{{ route('trainingcenter.view_media', $selected_group['id']) }}"
                            class="group-chat-view-midea-link" style="text-decoration: none;">
                            <p>View Media</p>
                            <iconify-icon icon="akar-icons:arrow-right" class="group-chat-view-midea-link-icon">
                            </iconify-icon>
                        </a>
                    </div>
                    <div id="view_member">
                        <div class="trainer-group-chat-view-members-header">

                            <div class="trainer-view-members-add-delete-btn-contaier">
                                <button id="addMember" value={{ $selected_group->id }} class="trainer-view-members-add-btn">
                                    <iconify-icon icon="akar-icons:circle-plus" class="trainer-view-members-add-icon">
                                    </iconify-icon>
                                    <p>Add Member</p>
                                </button>
                                <form action="{{ route('delete_gp') }}" style="margin-bottom: 0" method="GET">
                                    <input type="text" name="group_id" value="{{ $selected_group->id }}" hidden>
                                    <button class="trainer-view-members-delete-btn customer-red-btn delete-btn" data-id="{{ $selected_group->id }}" >
                                        Delete Group
                                    </button>
                                </form>

                            </div>
                        </div>

                        <div class="trainer-group-chat-members-container">
                            @forelse ($group_members as $m)
                                <div class="trainer-group-chat-member-row">
                                    <div class="trainer-group-chat-member-name">
                                        <img src="{{ asset('img/avatar.png') }}" />
                                        <p>{{ $m->name }}</p>
                                    </div>

                                    <div class="trainer-group-chat-member-btns-container">
                                        <a href="#" class="customer-secondary-btn" style="text-decoration: none">View
                                            Profile</a>
                                        <a href="{{ route('kick_member', $m->id) }}" class="customer-red-btn"
                                            style="text-decoration: none">Kick Member</a>
                                    </div>

                                </div>
                            @empty
                                <p class="text-secondary p-1">No Group Members</p>
                            @endforelse
                        </div>
                    </div>
                    <div id="add_member">
                        <div class="trainer-group-chat-view-members-header">
                            {{-- <a class="back-btn" href="javascript: history.back()">
                        <iconify-icon icon="bi:arrow-left" class="back-btn-icon"></iconify-icon>
                    </a> --}}
                            <div class="trainer-view-members-add-delete-btn-contaier">
                                <form class="add-member-form" action="">
                                    <input type="text" class="form-control" placeholder="Search member" id="search">

                                </form>
                                <form action="{{ route('delete_gp') }}" method="GET">
                                    <input type="text" name="group_id" value="{{ $selected_group->id }}" hidden>
                                    <button class="trainer-view-members-delete-btn customer-red-btn delete-btn" data-id="{{ $selected_group->id }}" >
                                        Delete Group
                                    </button>
                                </form>

                            </div>

                        </div>
                        <div class="trainer-group-chat-members-container">

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
@if (!empty(Session::get('popup')))
    <script>
        $(document).ready(function() {
            console.log("popup");
            $(function() {
                $('#addMemberModal').modal('show');
            });
        });
    </script>
@endif
<script>
    $(document).ready(function() {
        $('#add_member').hide();
        var member_type = @json($members);

        var selected_group = @json($selected_group);

        $(document).on('click', '#addMember', function(e) {
            add_member();
        });

        function add_member() {

            // e.preventDefault();
            $('#view_member').hide();
            $('#add_member').show();
            var id = $('#addMember').val();
            $('#search').on('keyup', function() {
                search();
            });
            search();

            function search() {
                var keyword = $('#search').val();
                // console.log(keyword);
                var group_id = {{ $selected_group->id }};

                var search_url = "{{ route('show_member', ':id') }}";
                search_url = search_url.replace(':id', group_id);
                $.post(search_url, {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        keyword: keyword
                    },
                    function(data) {
                        table_post_row(data);

                    });
            }
            // table row with ajax
            function table_post_row(res) {

                let htmlView = '';
                if (res.members.length <= 0) {
                    htmlView += `
                        No data found.
                        `;
                }
                for (let i = 0; i < res.members.length; i++) {
                    id = res.members[i].id;
                    group_id = {{ $selected_group->id }};

                    var url = "{{ route('add_member', [':id', ':group_id']) }}";
                    url = url.replace(':id', id);
                    url = url.replace(':group_id', group_id);
                    // console.log(url);
                    htmlView += `
                            <div class="add-member-row">
                                <div class="add-member-name-container">
                                                                        <img src="{{ asset('img/avatar.png') }}" />
                                    <p>` + res.members[i].name + `</p>
                                </div>
                                <div class="add-member-row-btns-container">
                                    <a href="?id=` + res.members[i].id +
                        `" class="customer-secondary-btn add-member-btn" id="` + group_id + `">Add</a>
                                    <a class="customer-secondary-btn add-member-view-profile-btn" id="` + res.members[
                            i].id + `">View Profile</a>

                                </div>
                            </div>`
                }
                $('.trainer-group-chat-members-container').html(htmlView);
            }


        }


        $(document).on('click', '.add-member-btn', function(e) {
            e.preventDefault();
            var url = new URL(this.href);

            var id = url.searchParams.get("id");
            var group_id = $(this).attr("id");

            var add_url = "{{ route('add_member', [':id', ':group_id']) }}";
            add_url = add_url.replace(':id', id);
            add_url = add_url.replace(':group_id', group_id);

            $.ajax({
                type: "GET",
                url: add_url,
                datatype: "json",
                success: function(data) {
                    if (data.status == 200) {
                        alert('Add Member Successfully');
                        // $('.add-member-row').load(location.href);
                    } else {
                        alert('Cannot Add Member');
                    }
                    add_member();

                }
            })

        });
            const Toast = Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            @if (Session::has('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ Session::get('success') }}'
                })
            @endif
        });
    </script>
