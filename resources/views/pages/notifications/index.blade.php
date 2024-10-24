@extends('layouts.app')

@section('header')
   <h5>Notifications</h5>
@endsection

@section('content')

<h5>Notifications List:</h5>

<ul>
    @foreach ($notifications as $notification)
    <div style="display: flex">

      <li style="width: 100%; display: flex; justify-content:space-between; background-color:aqua; border-radius:10px; padding:20px;">
           {{$notification->created_at}}  -  {{ $notification->data['message'] ?? 'No message available' }} 
        @if (is_null($notification->read_at))
                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button style="background:transparent; border: none;  border-radius:6px;">Mark as read</button>
                </form>
            @else
            <button class="markasunread" style="display: none">Mark as unread</button>
            @endif
        </form>
      </li>
      

    </div>
    @endforeach
</ul>

@endsection
