@extends('layouts.app')

@section('header')
   {{ __('Notifications Preferences') }}
@endsection


@section('content')


<div class="vms_panel notifications_preferences" >

<h2>Choose which notifications you'd like to receive:</h2>
<p>Select the types of updates you want to be notified about.</p>

  <form action="{{ route('notifications.preferences.update') }}" method="POST" style="display: flex; flex-direction:column">
      @csrf
      @method('PATCH')
  
<div class="reservations">
    <h4>Reservations Notifications</h4>
    <label>      
        <input type="hidden" name="preferences[reservation-added]" value="0">
        <input type="checkbox" name="preferences[reservation-added]" {{ isset($preferences['reservation-added']) && $preferences['reservation-added'] ? 'checked' : '' }}>
        Reservation Added
    </label>
    
    <label>
        <input type="hidden" name="preferences[reservation-deleted]" value="0">
        <input type="checkbox" name="preferences[reservation-deleted]" {{ isset($preferences['reservation-deleted']) && $preferences['reservation-deleted'] ? 'checked' : '' }}>
        Reservation Deleted
    </label>
    
    <label>
        <input type="hidden" name="preferences[reservation-updated]" value="0">
        <input type="checkbox" name="preferences[reservation-updated]" {{ isset($preferences['reservation-updated']) && $preferences['reservation-updated'] ? 'checked' : '' }}>
        Reservation Updated
    </label>
  </div>
    <div class="invoices">
    <h4>
      Invoices Notifications
    </h4>
    <label>
        <input type="hidden" name="preferences[invoices-added]" value="0">
        <input type="checkbox" name="preferences[invoices-added]" {{ isset($preferences['invoices-added']) && $preferences['invoices-added'] ? 'checked' : '' }}>
        Invoices Added
    </label>
    
    <label>
        <input type="hidden" name="preferences[invoices-deleted]" value="0">
        <input type="checkbox" name="preferences[invoices-deleted]" {{ isset($preferences['invoices-deleted']) && $preferences['invoices-deleted'] ? 'checked' : '' }}>
        Invoices Deleted
    </label>
  </div>

      <div class="comments">
        <h4>Comments Notifications</h4>
        <label>
          <input type="hidden" name="preferences[comment-added]" value="0">
            <input type="checkbox" name="preferences[comment-added]" {{ (isset($preferences['comment-added']) && $preferences['comment-added']) ? 'checked' : '' }}>
            Comment Added
          </label>

          <label>
            <input type="hidden" name="preferences[comment-deleted]" value="0">
          <input type="checkbox" name="preferences[comment-deleted]" {{ isset($preferences['comment-deleted']) && $preferences['comment-deleted'] ? 'checked' : '' }}>
          Comment Deleted
      </label>
      </div>
  
  <div class="dicounts">
        <h4>Discount Notifications</h4>

    <label>
        <input type="hidden" name="preferences[discount-added]" value="0">
        <input type="checkbox" name="preferences[discount-added]" {{ isset($preferences['discount-added']) && $preferences['discount-added'] ? 'checked' : '' }}>
        Discount Added 
    </label>
    
    <label>
        <input type="hidden" name="preferences[discount-updated]" value="0">
        <input type="checkbox" name="preferences[discount-updated]" {{ isset($preferences['discount-updated']) && $preferences['discount-updated'] ? 'checked' : '' }}>
        Discount Updated 
    </label>
    
    <label>
        <input type="hidden" name="preferences[discount-deleted]" value="0">
        <input type="checkbox" name="preferences[discount-deleted]" {{ isset($preferences['discount-deleted']) && $preferences['discount-deleted'] ? 'checked' : '' }}>
        Discount Deleted 
    </label>
    
  </div>

  <div class="expenses">
     <h4>Expenses Notifications</h4>
        <label>
          <input type="hidden" name="preferences[expenses-added]" value="0">
            <input type="checkbox" name="preferences[expenses-added]" {{ (isset($preferences['expenses-added']) && $preferences['expenses-added']) ? 'checked' : '' }}>
            Expenses Added
          </label>

          <label>
            <input type="hidden" name="preferences[expenses-deleted]" value="0">
          <input type="checkbox" name="preferences[expenses-deleted]" {{ isset($preferences['expenses-deleted']) && $preferences['expenses-deleted'] ? 'checked' : '' }}>
          Expenses Deleted
      </label>
      <label>
            <input type="hidden" name="preferences[expenses-updated]" value="0">
          <input type="checkbox" name="preferences[expenses-updated]" {{ isset($preferences['expenses-updated']) && $preferences['expenses-updated'] ? 'checked' : '' }}>
          Expenses updated
      </label>
  </div>
<div class="Payments">
     <h4>Payments Notifications</h4>
        <label>
          <input type="hidden" name="preferences[payments-added]" value="0">
            <input type="checkbox" name="preferences[payments-added]" {{ (isset($preferences['payments-added']) && $preferences['payments-added']) ? 'checked' : '' }}>
            Payments Added
          </label>

          <label>
            <input type="hidden" name="preferences[payments-deleted]" value="0">
          <input type="checkbox" name="preferences[payments-deleted]" {{ isset($preferences['payments-deleted']) && $preferences['payments-deleted'] ? 'checked' : '' }}>
          Payments Deleted
      </label>
    
  </div>

  <div class="venues">
     <h4>Venues Notifications</h4>
        <label>
          <input type="hidden" name="preferences[venue-added]" value="0">
            <input type="checkbox" name="preferences[venue-added]" {{ (isset($preferences['venue-added']) && $preferences['venue-added']) ? 'checked' : '' }}>
            Venue Added
          </label>
          <label>
            <input type="hidden" name="preferences[venue-updated]" value="0">
          <input type="checkbox" name="preferences[venue-updated]" {{ isset($preferences['venue-updated']) && $preferences['venue-updated'] ? 'checked' : '' }}>
          Venue Updated
      </label>
          <label>
            <input type="hidden" name="preferences[venue-deleted]" value="0">
          <input type="checkbox" name="preferences[venue-deleted]" {{ isset($preferences['venue-deleted']) && $preferences['venue-deleted'] ? 'checked' : '' }}>
          Venue Deleted
      </label>
  </div>

  <div class="clients">
      <label>
            <input type="hidden" name="preferences[client-updated]" value="0">
          <input type="checkbox" name="preferences[client-updated]" {{ isset($preferences['client-updated']) && $preferences['client-updated'] ? 'checked' : '' }}>
          Client Updated
      </label>
  </div>
  
<div class="menu">
     <h4>Menu Notifications</h4>
        <label>
          <input type="hidden" name="preferences[menu-added]" value="0">
            <input type="checkbox" name="preferences[menu-added]" {{ (isset($preferences['menu-added']) && $preferences['menu-added']) ? 'checked' : '' }}>
            Menu Added
          </label>
          <label>
            <input type="hidden" name="preferences[menu-updated]" value="0">
          <input type="checkbox" name="preferences[menu-updated]" {{ isset($preferences['menu-updated']) && $preferences['menu-updated'] ? 'checked' : '' }}>
          Menu Updated
      </label>
          <label>
            <input type="hidden" name="preferences[menu-deleted]" value="0">
          <input type="checkbox" name="preferences[menu-deleted]" {{ isset($preferences['menu-deleted']) && $preferences['menu-deleted'] ? 'checked' : '' }}>
          Menu Deleted
      </label>
  </div>

  <div class="staff">
    <h4>Staff Notifications</h4>
    <label>
      <input type="hidden" name="preferences[staff-added]" value="0">
        <input type="checkbox" name="preferences[staff-added]" {{ isset($preferences['staff-added']) && $preferences['staff-added'] ? 'checked' : '' }}>
        Staff Added
    </label>
    
    <label>
      <input type="hidden" name="preferences[staff-deleted]" value="0">
        <input type="checkbox" name="preferences[staff-deleted]" {{ isset($preferences['staff-deleted']) && $preferences['staff-deleted'] ? 'checked' : '' }}>
        Staff Deleted
    </label>
  </div>
      <button type="submit">Save Preferences</button>
  </form>
</div>



@endsection