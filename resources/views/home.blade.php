<h1>Customer view</h1>
{{-- @foreach ($mem as $m)
   Member id - {{$m->id}}<br>
   member_type - {{$m->member_type}}<br>
   duration - {{$m->duration}}<br>
   <hr>
@endforeach --}}
{{-- @foreach ($users as $user)
    @foreach($user->members as $mm)
    User Name : {{ $user->name}}<br>
    Date:{{$user->created_at}}<br>
    Member Type: {{$mm->member_type}}<hr>
    @endforeach
@endforeach --}}
<form action="{{route('data.save')}}" method="POST">
    @csrf
    @method('POST')
   Name: <input type="text" name="name">
   Email:<input type="email" name="email">
   Phone : <input type="number" name="phone">
   Password:<input type="password" name="password">
   Member Type : <input type="text" name="member_id" value="1">
   Member Type Level: <input type="text" name="member_type_level" value="Beginner">
   <button type="submit" class="btn btn-info">Submit</button>
</form>
{{-- @foreach ($users as $u) --}}
    {{-- @foreach ($u->member_histories as $hist)
        UserID - {{$u->name}} <br>
        Level - {{$hist->member_type}} <br>
        {{-- Member_id - {{$hist->member->id}} <br> --}}
        {{-- Type - {{$hist->member->member_type}} <br> --}}

        {{-- <hr>
    @endforeach --}}
    {{-- @foreach ($membHist as $latest)
    Current Type - {{$latest->user->name}} <br>
    Current Type - {{$latest->member->id}} <br>
    Current Type - {{$latest->member_type_level}} <br> <hr>

    @endforeach --}}
{{-- @endforeach --}}




