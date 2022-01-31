@extends('layouts.app')

@section('content')

    @foreach ($users as $user)
    <form method="POST" action="{{route('user.chat')}}">
    @csrf
    <p>This is user {{ $user->id }}</p>
    <p>This is user {{ $user->id }}</p>

    <p>Name :  {{ $user->name }}</p>
    <input name="entity" type="hidden" value="user">
    <input name="entity_id" type="hidden" value="{{ $user->id }}">
    <button type="submit" class="btn btn-primary">
        Submit
    </button>
    </form>
    @endforeach

    @foreach ($admins as $admin)
    <form method="POST" action="{{route('user.chat')}}">
    @csrf
    <p>This is admin {{ $admin->id }}</p>
    <p>Name :  {{ $admin->name }}</p>
    <input name="entity" type="hidden" value="admin">
    <input name="entity_id" type="hidden" value="{{ $admin->id }}">
    <button type="submit" class="btn btn-primary">
        Submit
    </button>
    </form>
    @endforeach



    <form method="POST" action="{{ route('admin.sendmessage')}}">
    @csrf <!-- {{ csrf_field() }} -->
    <input name="message">
    <button type="submit" class="btn btn-primary">
        Submit
    </button>
    </form>
</div>

<script src="{{ asset('js/app.js') }}" ></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.smin.js"></script>

@endsection
