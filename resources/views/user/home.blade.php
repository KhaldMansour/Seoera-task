@extends('layouts.app')

@section('content')

<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">{{ config('app.name', 'Laravel') }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
             @if(auth('user')->user())
            <li class="nav-item active">
                <a class="nav-link" href="{{route('users.index')}}">Home <span class="sr-only">({{auth('user')->user()->name}})</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('users.logout')}}">Logout</a>
            </li>
            @else
            <li class="nav-item active">
                <a class="nav-link" href="{{route('users.create')}}">Register <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('users.loginform')}}">Login</a>
            </li>
            @endif
            </form>
        </div>
        </nav>

    <h3> Users </h3>
    @foreach ($users as $user)
        <form method="POST" action="{{route('user.chat')}}">
        @csrf
        <p>  Name : {{ $user->name }}</p>
        <input name="entity" type="hidden" value="user">
        <input name="entity_id" type="hidden" value="{{ $user->id }}">
        <button type="submit" class="btn btn-primary">
            Chat...
        </button>
        </form>
    @endforeach

    <h3> Admins </h3>
    @foreach ($admins as $admin)
        <form method="POST" action="{{route('user.chat')}}">
        @csrf
        <p>  Name : {{ $admin->name }}</p>
        <input name="entity" type="hidden" value="admin">
        <input name="entity_id" type="hidden" value="{{ $admin->id }}">
        <button type="submit" class="btn btn-success">
            Chat...
        </button>
        </form>
    @endforeach
</div>

<script src="{{ asset('js/app.js') }}" ></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.smin.js"></script>

@endsection
