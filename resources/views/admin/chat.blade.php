@inject('User', 'App\Models\User')
@inject('Admin', 'App\Models\Admin')

@php

$admin = auth('admin')->user();

if ( ($chat_room->first_entity == "admin" && $chat_room->first_entity_id == $admin->id) )
{
    $receiver_entity = $chat_room->second_entity;

    $receiver_id = $chat_room->second_entity_id;

}else if( $chat_room->second_entity == "admin" && $chat_room->second_entity_id == $admin->id )
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
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">{{config('app.name', 'Laravel')}}</a>
    
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
  
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Dropdown
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
            </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
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
                                    @if ($message->sender == "admin" && $message->sender_id == $admin->id)
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
                            <form id="send-form" method="POST" action="{{route('admin.sendmessage')}}" >
                                @csrf
                                <input id="chat_room_id" name="chat_room_id" type="hidden" value="{{ $chat_room->id }}">
                                <input id="sender" name="sender" type="hidden" value="admin">
                                <input id="sender_id" name="sender_id" type="hidden" value="{{ auth('admin')->user()->id }}">
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
                }   
                
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

        window.Echo.channel('messages.' + {{ $chat_room->id }} ).listen('Chat', (e) =>{

            if (e.message.sender == "admin" && e.message.sender_id == {{ $admin->id }})
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
                    + '<div class="message other-message float-right">' + e.message.message +'</div>'+
                '</li>'
                )
            }

        })
        </script>
</body>
