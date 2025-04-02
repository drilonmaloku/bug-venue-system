@extends('layouts.app')

@section('header')
    Preferencat e njoftimeve
@endsection


@section('content')


    <div class="vms_panel">
        <form action="{{ route('notifications.preferences.update') }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="vms-panel-bordered">
                <h5 class="mb-0">Rezervimet:</h5>
                <hr>
                <div class="d-flex flex-column">
                    <label>
                        <input type="hidden" name="preferences[reservation-added]" value="0">
                        <input type="checkbox"
                               name="preferences[reservation-added]" {{ isset($preferences['reservation-added']) && $preferences['reservation-added'] ? 'checked' : '' }}>
                        Shto Rezervim
                    </label>
                    <label>
                        <input type="hidden" name="preferences[reservation-updated]" value="0">
                        <input type="checkbox"
                               name="preferences[reservation-updated]" {{ isset($preferences['reservation-updated']) && $preferences['reservation-updated'] ? 'checked' : '' }}>
                        Përditso Rezervim
                    </label>
                    <label>
                        <input type="hidden" name="preferences[reservation-deleted]" value="0">
                        <input type="checkbox"
                               name="preferences[reservation-deleted]" {{ isset($preferences['reservation-deleted']) && $preferences['reservation-deleted'] ? 'checked' : '' }}>
                        Fshij Rezervim
                    </label>
                    <label>
                        <input type="hidden" name="preferences[reservation-updated-status]" value="0">
                        <input type="checkbox"
                               name="preferences[reservation-updated-status]" {{ isset($preferences['reservation-updated-status']) && $preferences['reservation-updated-status'] ? 'checked' : '' }}>
                        Përditso Statusin e Rezervimit
                    </label>
                    <label>
                        <input type="hidden" name="preferences[comment-added]" value="0">
                        <input type="checkbox"
                               name="preferences[comment-added]" {{ (isset($preferences['comment-added']) && $preferences['comment-added']) ? 'checked' : '' }}>
                        Shto Koment
                    </label>
                    <label>
                        <input type="hidden" name="preferences[comment-deleted]" value="0">
                        <input type="checkbox"
                               name="preferences[comment-deleted]" {{ isset($preferences['comment-deleted']) && $preferences['comment-deleted'] ? 'checked' : '' }}>
                        Fshij Koment
                    </label>
                    <label>
                        <input type="hidden" name="preferences[staff-added]" value="0">
                        <input type="checkbox"
                               name="preferences[staff-added]" {{ isset($preferences['staff-added']) && $preferences['staff-added'] ? 'checked' : '' }}>
                        Staff Added
                    </label>
                    <label>
                        <input type="hidden" name="preferences[staff-deleted]" value="0">
                        <input type="checkbox"
                               name="preferences[staff-deleted]" {{ isset($preferences['staff-deleted']) && $preferences['staff-deleted'] ? 'checked' : '' }}>
                        Staff Deleted
                    </label>
                    <label>
                        <input type="hidden" name="preferences[discount-added]" value="0">
                        <input type="checkbox"
                               name="preferences[discount-added]" {{ isset($preferences['discount-added']) && $preferences['discount-added'] ? 'checked' : '' }}>
                        Discount Added
                    </label>
                    <label>
                        <input type="hidden" name="preferences[discount-updated]" value="0">
                        <input type="checkbox"
                               name="preferences[discount-updated]" {{ isset($preferences['discount-updated']) && $preferences['discount-updated'] ? 'checked' : '' }}>
                        Discount Updated
                    </label>
                    <label>
                        <input type="hidden" name="preferences[discount-deleted]" value="0">
                        <input type="checkbox"
                               name="preferences[discount-deleted]" {{ isset($preferences['discount-deleted']) && $preferences['discount-deleted'] ? 'checked' : '' }}>
                        Discount Deleted
                    </label>
                    <label>
                        <input type="hidden" name="preferences[invoices-added]" value="0">
                        <input type="checkbox"
                               name="preferences[invoices-added]" {{ isset($preferences['invoices-added']) && $preferences['invoices-added'] ? 'checked' : '' }}>
                        Invoices Added
                    </label>
                    <label>
                        <input type="hidden" name="preferences[invoices-deleted]" value="0">
                        <input type="checkbox"
                               name="preferences[invoices-deleted]" {{ isset($preferences['invoices-deleted']) && $preferences['invoices-deleted'] ? 'checked' : '' }}>
                        Invoices Deleted
                    </label>

                </div>
            </div>

            <div class="vms-panel-bordered">
                <h5 class="mb-0">Pagesat:</h5>
                <hr>
                <div class="d-flex flex-column">
                    <label>
                        <input type="hidden" name="preferences[payment-added]" value="0">
                        <input type="checkbox"
                               name="preferences[payment-added]" {{ (isset($preferences['payment-added']) && $preferences['payment-added']) ? 'checked' : '' }}>
                        Shtim Pagese
                    </label>

                    <label>
                        <input type="hidden" name="preferences[payment-updated]" value="0">
                        <input type="checkbox"
                               name="preferences[payment-updated]" {{ (isset($preferences['payment-updated']) && $preferences['payment-added']) ? 'checked' : '' }}>
                        Perditsim Pagese
                    </label>

                    <label>
                        <input type="hidden" name="preferences[payment-deleted]" value="0">
                        <input type="checkbox"
                               name="preferences[payment-deleted]" {{ isset($preferences['payment-deleted']) && $preferences['payment-deleted'] ? 'checked' : '' }}>
                        Fshirje Pagese
                    </label>
                </div>

            </div>

            <div class="vms-panel-bordered">
                <h5 class="mb-0">Sallat:</h5>
                <hr>
                <div class="d-flex flex-column">
                    <label>
                        <input type="hidden" name="preferences[venue-added]" value="0">
                        <input type="checkbox"
                               name="preferences[venue-added]" {{ (isset($preferences['venue-added']) && $preferences['venue-added']) ? 'checked' : '' }}>
                        Shto Sallë
                    </label>
                    <label>
                        <input type="hidden" name="preferences[venue-updated]" value="0">
                        <input type="checkbox"
                               name="preferences[venue-updated]" {{ isset($preferences['venue-updated']) && $preferences['venue-updated'] ? 'checked' : '' }}>
                        Përditso Sallë
                    </label>
                    <label>
                        <input type="hidden" name="preferences[venue-deleted]" value="0">
                        <input type="checkbox"
                               name="preferences[venue-deleted]" {{ isset($preferences['venue-deleted']) && $preferences['venue-deleted'] ? 'checked' : '' }}>
                        Fshij Sallë
                    </label>
                </div>

            </div>

            <div class="vms-panel-bordered">
                <h5 class="mb-0">Menu:</h5>
                <hr>
                <div class="d-flex flex-column">
                    <label>
                        <input type="hidden" name="preferences[menu-added]" value="0">
                        <input type="checkbox"
                               name="preferences[menu-added]" {{ (isset($preferences['menu-added']) && $preferences['menu-added']) ? 'checked' : '' }}>
                        Shto Menu
                    </label>
                    <label>
                        <input type="hidden" name="preferences[menu-updated]" value="0">
                        <input type="checkbox"
                               name="preferences[menu-updated]" {{ isset($preferences['menu-updated']) && $preferences['menu-updated'] ? 'checked' : '' }}>
                        Përditso Menu
                    </label>
                    <label>
                        <input type="hidden" name="preferences[menu-deleted]" value="0">
                        <input type="checkbox"
                               name="preferences[menu-deleted]" {{ isset($preferences['menu-deleted']) && $preferences['menu-deleted'] ? 'checked' : '' }}>
                        Fshij Menu
                    </label>
                </div>

            </div>

            <div class="vms-panel-bordered">
                <h5 class="mb-0">Klientat:</h5>
                <hr>
                <div class="d-flex flex-column">
                    <label>
                        <input type="hidden" name="preferences[client-updated]" value="0">
                        <input type="checkbox"
                               name="preferences[client-updated]" {{ isset($preferences['client-updated']) && $preferences['client-updated'] ? 'checked' : '' }}>
                        Përditso Klient
                    </label>
                </div>

            </div>

            <div class="vms-panel-bordered">
                <h5 class="mb-0">Shpenzimet:</h5>
                <hr>
                <div class="d-flex flex-column">
                    <label>
                        <input type="hidden" name="preferences[expenses-added]" value="0">
                        <input type="checkbox"
                               name="preferences[expenses-added]" {{ (isset($preferences['expenses-added']) && $preferences['expenses-added']) ? 'checked' : '' }}>
                        Shto Shpenzim
                    </label>
                    <label>
                        <input type="hidden" name="preferences[expenses-deleted]" value="0">
                        <input type="checkbox"
                               name="preferences[expenses-deleted]" {{ isset($preferences['expenses-deleted']) && $preferences['expenses-deleted'] ? 'checked' : '' }}>
                        Përditso Shpenzim
                    </label>
                    <label>
                        <input type="hidden" name="preferences[expenses-updated]" value="0">
                        <input type="checkbox"
                               name="preferences[expenses-updated]" {{ isset($preferences['expenses-updated']) && $preferences['expenses-updated'] ? 'checked' : '' }}>
                        Fshij Shpenzim
                    </label>
                </div>

            </div>

            <button class="hubers-btn w-auto" type="submit">Ruaj Preferencat</button>
        </form>
    </div>



@endsection