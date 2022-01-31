
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>

    @foreach ($users as $user)
    <form method="POST" action="{{route('chat')}}">
    @csrf
    <p>This is user {{ $user->id }}</p>
    <input name="entity" type="hidden" value="user">
    <input name="entity_id" type="hidden" value="{{ $user->id }}">
    <button type="submit" class="btn btn-primary">
        Submit
    </button>
    </form>
    @endforeach

    @foreach ($admins as $admin)
    <form method="POST" action="{{route('chat')}}">
    @csrf
    <p>This is admin {{ $admin->id }}</p>
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
<p id="messages">PLZ<p>

<script src="{{ asset('js/app.js') }}" ></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.smin.js"></script>

<script type="text/javascript" defer>
  window.onload = function() {

console.log(document.getElementById("messages").innerHTML);
  }
window.Echo.channel('channel-name').listen('Chat', (e) =>{
    document.getElementById("messages").innerHTML += e.msg
});
</script>
