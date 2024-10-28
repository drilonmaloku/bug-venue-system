{{-- @extends('layouts.app')

@section('header')
   {{_('Notifications')}}
@endsection

@section('content')
  <div class="vms-panel notification-panel">
    <ul>
      <h5>Notifications List:</h5>
       <hr>
        @foreach ($notifications as $notification)
    <div class="allnotifications">
      @if (is_null($notification->read_at))
      <div class="UnreadNotifications" style="border: 1px solid black; margin-bottom:20px;">
        <h5>
          UnreadNotifictions
        </h5>
        <li>
          {{$notification->created_at}}  -  {{ $notification->data['message'] ?? 'No message available' }} 
          <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
              @csrf
              @method('PATCH')
             <button>Mark as read</button>    
            </form>
            </li>
      </div>
      <div>

      </div>
       @else
       <div class="readNotifictions" style="border: 1px solid black">
        <h5>
          readNotifictions
        </h5>
         <li style="background-color: #d8e2dc;">  
           {{$notification->created_at}}  -  {{ $notification->data['message'] ?? 'No message available' }} 
           <form action="{{ route('notifications.markAsUnread', $notification->id) }}" method="POST">
           @csrf
           @method('PATCH')
             <button class="markasunread">Mark as unread</button>
           </form>
         </li>
       </div>
       </div>
        @endif
        @endforeach
    </ul>
  </div>

@endsection --}}


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

