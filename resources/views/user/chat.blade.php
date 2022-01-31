@inject('User', 'App\Models\User')
@inject('Admin', 'App\Models\Admin')
@php

$user = auth('user')->user();

if ( ($chat_room->second_entity == "user" && $chat_room->second_entity_id == $user->id) )
{
    $receiver_entity = $chat_room->first_entity;
    $receiver_id = $chat_room->first_entity_id;

}else if( $chat_room->first_entity == "user" && $chat_room->first_entity_id == $user->id )
{
    $receiver_entity = $chat_room->second_entity;
    $receiver_id = $chat_room->second_entity_id;
}

if ($receiver_entity == "user")
{
    $receiver = $User::find($receiver_id);
}else {
    $receiver = $Admin::find($receiver_id);
}

@endphp

@extends('layouts.app')
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/chat.css') }}" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    </head>

    <body>
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
        <div class="container">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card chat-app">         
                    <div class="chat">
                        <div class="chat-header clearfix">
                            <div class="row">
                                <div class="col-lg-6">
                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                        <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                                    </a>
                                    <div class="chat-about">
                                        <h6 class="m-b-0">{{ $receiver->name}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-history">

                            <ul class="m-b-0">
                            @if (is_array($messages) || is_object($messages))
                                @foreach($messages as $message)
                                    @if ($message->sender == "user" && $message->sender_id == $user->id)
                                        <li class="clearfix">
                                            <div class="message-data">
                                                <span class="message-data-time">{{ date('d M y, h:i a', strtotime($message->created_at)) }}</span>
                                            </div>
                                            <div class="message my-message">{{ $message->message}}</div>                                    
                                        </li> 
                                    @else
                                    <li class="clearfix">
                                        <div class="message-data float-right">
                                            <span class="message-data-time">{{ date('d M y, h:i a', strtotime($message->created_at)) }}</span>
                                            <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="avatar">
                                        </div>
                                        <div class="message other-message float-right"> {{ $message->message}} </div>
                                    </li>
                                    @endif
                                @endforeach
                            @endif
                            </ul>
                        </div>

                        <div class="chat-message clearfix">
                            <form id="send-form" method="POST" action="{{route('user.sendmessage')}}" >
                                @csrf
                                <input id="chat_room_id" name="chat_room_id" type="hidden" value="{{ $chat_room->id }}">
                                <input id="sender" name="sender" type="hidden" value="user">
                                <input id="sender_id" name="sender_id" type="hidden" value="{{ $user->id }}">
                                <input id="receiver" name="receiver" type="hidden" value="{{ $receiver_entity }}">
                                <input id="receiver_id" name="receiver_id" type="hidden" value="{{ $receiver_id }}">

                                <div class="input-group mb-0">
                                    <input id="message" name="message" type="text" class="form-control" placeholder="Type a message">
                                    <button type="submit" class="btn btn-primary submit"> Send </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
<script src="{{ asset('js/app.js') }}" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script type="text/javascript">
$("#send-form").submit(function(e){

    e.preventDefault();

    if ($.trim($("#message").val()) === "") {
            alert('Please enter a message.');
            return false;
    };   

    let url = $(this).attr('action');

    $.ajax({
        type:'POST',
        url: url,
        datatype:'json',
        data:   
        {
        '_token': '{{csrf_token()}}',
        chat_room_id: $("#chat_room_id").val(),
        message: $("#message").val(),
        receiver: $("#receiver").val(),
        receiver_id: $("#receiver_id").val(),
        sender: $("#sender").val(),
        sender_id: $("#sender_id").val()
        }
    });

    $("#message").val('');
});

window.Echo.private('messages.' + {{ $chat_room->id }}).listen('Chat', (e) =>{

    if (e.message.sender == "user" && e.message.sender_id == {{ $user->id }})
        {
            $( ".chat-history" ).first().append(
                '<li class="clearfix">' +
                    '<div class="message-data">'+
                        '<span class="message-data-time">' + moment(new Date(e.message.created_at).toString()).format('h:mmA') +'</span>' +
                    '</div>'+
                    '<div class="message my-message">' + e.message.message + '</div>' +
                '</li>'
            )
        }else
        {
            $( ".chat-history" ).first().append(
                '<li class="clearfix">' +
                    '<div class="message-data float-right">'+
                        '<span class="message-data-time">' + moment(new Date(e.message.created_at).toString()).format('h:mmA') + '</span>'+
                    '</div>'+
                    '<img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="avatar">'
                    +
                + '<div class="message other-message float-right">' + e.message.message +'</div>'+
            '</li>'
            )
        }

});

</script>
