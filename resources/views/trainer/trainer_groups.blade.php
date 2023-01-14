<div class="trainer-two-columns-container">
    <div class="trainer-group-chats-parent-container">
        <p>Groups</p>
        <div class="trainer-group-chats-container">
            @forelse ($groups as $group)
                <button class="tainer-group-chat-name-container" id="group-chat" value="{{$group->id}}" style=" background-color: transparent;background-repeat: no-repeat;border: none;cursor: pointer;overflow: hidden;outline: none;">
                    <img src="{{ asset('image/default.jpg')}}"/>
                    <p>{{$group->group_name}}</p>
                </button>
            @empty
            <p class="text-secondary p-1">No Group</p>
            @endforelse
        </div>
    </div>
    <div id="content-container">

        <div class="group-chat-container">
            <div class="group-chat-header">

            </div>
            <div class="group-chat-messages-container">
                <div class="group-chat-sender-container" id="trainer_message_el">
                    <p id="p" class="text-secondary p-1" style="text-align:center">Choose group and start chatting</p>
                    <div class="group-chat-sender-text-container" id="send_message">

                    </div>
                    <img src="{{ asset('image/default.jpg') }}" />
                </div>
            </div>
            <div id="send_form">

            </div>

        </div>
    </div>
</div>
