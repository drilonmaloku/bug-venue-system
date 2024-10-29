@extends('layouts.app')

@section('header')
   {{ __('Notifications') }}
@endsection

@section('content')

  <div class="vms_panel notification-panel">
    <h5>Notifications List:</h5>
    <hr>
    
    <ul>
      @foreach ($notifications as $notification)
        <div class="allnotifications">
          
          {{-- Check if the notification is unread --}}
          @if (is_null($notification->read_at))
            {{-- Unread Notification --}}
            
              <li>
                {{ $notification->created_at }} - {{ $notification->data['message'] ?? 'No message available' }}
                
                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button >Mark as read</button>
                </form>
              </li>
     

          @else
            {{-- Read Notification --}}
      
              <li style="background-color: #d8e2dc;">
                {{ $notification->created_at }} - {{ $notification->data['message'] ?? 'No message available' }}
                
                <form action="{{ route('notifications.markAsUnread', $notification->id) }}" method="POST" style="display: inline;">
                  @csrf
                  @method('PATCH')
                  <button>Mark as unread</button>
                </form>
              </li>
       
          @endif
          
        </div>
      @endforeach
    </ul>
  </div>
@endsection

