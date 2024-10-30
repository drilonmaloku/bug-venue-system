<form action="{{ route('notifications.preferences.update') }}" method="POST">
    @csrf
    @method('PATCH')

    <label>
        <input type="checkbox" name="preferences[comment-added]" {{ (isset($preferences['comment-added']) && $preferences['comment-added']) ? 'checked' : '' }}>
        Comment Added
    </label>

    <label>
    <input type="checkbox" name="preferences[comment-deleted]" {{ isset($preferences['comment-deleted']) && $preferences['comment-deleted'] ? 'checked' : '' }}>
    Comment Deleted
</label>

<label>
    <input type="checkbox" name="preferences[discount-added]" {{ isset($preferences['discount-added']) && $preferences['discount-added'] ? 'checked' : '' }}>
    Discount Added 
</label>

<label>
    <input type="checkbox" name="preferences[discount-updated]" {{ isset($preferences['discount-updated']) && $preferences['discount-updated'] ? 'checked' : '' }}>
    Discount Updated 
</label>

<label>
    <input type="checkbox" name="preferences[discount-deleted]" {{ isset($preferences['discount-deleted']) && $preferences['discount-deleted'] ? 'checked' : '' }}>
    Discount Deleted 
</label>

<label>
    <input type="checkbox" name="preferences[invoices-added]" {{ isset($preferences['invoices-added']) && $preferences['invoices-added'] ? 'checked' : '' }}>
    Invoices Added
</label>

<label>
    <input type="checkbox" name="preferences[invoices-deleted]" {{ isset($preferences['invoices-deleted']) && $preferences['invoices-deleted'] ? 'checked' : '' }}>
    Invoices Deleted
</label>

<label>
    <input type="checkbox" name="preferences[reservation-added]" {{ isset($preferences['reservation-added']) && $preferences['reservation-added'] ? 'checked' : '' }}>
    Reservation Added
</label>

<label>
    <input type="checkbox" name="preferences[reservation-deleted]" {{ isset($preferences['reservation-deleted']) && $preferences['reservation-deleted'] ? 'checked' : '' }}>
    Reservation Deleted
</label>

<label>
    <input type="checkbox" name="preferences[reservation-updated]" {{ isset($preferences['reservation-updated']) && $preferences['reservation-updated'] ? 'checked' : '' }}>
    Reservation Updated
</label>

<label>
    <input type="checkbox" name="preferences[staff-added]" {{ isset($preferences['staff-added']) && $preferences['staff-added'] ? 'checked' : '' }}>
    Staff Added
</label>

<label>
    <input type="checkbox" name="preferences[staff-deleted]" {{ isset($preferences['staff-deleted']) && $preferences['staff-deleted'] ? 'checked' : '' }}>
    Staff Deleted
</label>

    <button type="submit">Save Preferences</button>
</form>
