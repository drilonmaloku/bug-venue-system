@extends('layouts.app')

@section('header')
    Preferencat e njoftimeve
@endsection

@section('content')
    <div class="vms_panel">
        <form action="{{ route('notifications.preferences.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <!-- Global Select All -->
            <div class="vms-panel-bordered">
                <label>
                    <input type="checkbox" id="global-select-all"> Zgjidh të gjitha (Global)
                </label>
            </div>

            <!-- Rezervimet Section -->
            <div class="vms-panel-bordered">
                <h5 class="mb-1"> 
                    <label class="mb-1">
                        <input type="checkbox" class="select-all" data-group="rezervimet"> 
                    </label> Rezervimet: </h5>
                <div class="d-flex flex-column rezervimet">
                    
                    <label>
                        <input type="hidden" name="preferences[reservation-added]" value="0">
                        <input type="checkbox" name="preferences[reservation-added]" {{ isset($preferences['reservation-added']) && $preferences['reservation-added'] ? 'checked' : '' }}>
                        Shto Rezervim
                    </label>
                    <label>
                        <input type="hidden" name="preferences[reservation-updated]" value="0">
                        <input type="checkbox" name="preferences[reservation-updated]" {{ isset($preferences['reservation-updated']) && $preferences['reservation-updated'] ? 'checked' : '' }}>
                        Përditso Rezervim
                    </label>
                    <label>
                        <input type="hidden" name="preferences[reservation-deleted]" value="0">
                        <input type="checkbox" name="preferences[reservation-deleted]" {{ isset($preferences['reservation-deleted']) && $preferences['reservation-deleted'] ? 'checked' : '' }}>
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
                        <input type="checkbox" name="preferences[comment-added]" {{ (isset($preferences['comment-added']) && $preferences['comment-added']) ? 'checked' : '' }}>
                        Shto Koment
                    </label>
                    <label>
                        <input type="hidden" name="preferences[comment-deleted]" value="0">
                        <input type="checkbox" name="preferences[comment-deleted]" {{ isset($preferences['comment-deleted']) && $preferences['comment-deleted'] ? 'checked' : '' }}>
                        Fshij Koment
                    </label>
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
            </div>

            <!-- Pagesat Section -->
            <div class="vms-panel-bordered">
            <h5 class="mb-1"> 
                    <label> 
                        <input type="checkbox" class="select-all" data-group="pagesat">  
                    </label> Pagesat:</h5>
                <div class="d-flex flex-column pagesat">
                   
                    <label>
                        <input type="hidden" name="preferences[payment-added]" value="0">
                        <input type="checkbox" name="preferences[payment-added]" {{ (isset($preferences['payment-added']) && $preferences['payment-added']) ? 'checked' : '' }}>
                        Shtim Pagese
                    </label>
                    <label>
                        <input type="hidden" name="preferences[payment-updated]" value="0">
                        <input type="checkbox" name="preferences[payment-updated]" {{ (isset($preferences['payment-updated']) && $preferences['payment-updated']) ? 'checked' : '' }}>
                        Përditso Pagese
                    </label>
                    <label>
                        <input type="hidden" name="preferences[payment-deleted]" value="0">
                        <input type="checkbox" name="preferences[payment-deleted]" {{ isset($preferences['payment-deleted']) && $preferences['payment-deleted'] ? 'checked' : '' }}>
                        Fshirje Pagese
                    </label>
                </div>
            </div>

            <!-- Sallat Section -->
            <div class="vms-panel-bordered">
                <h5 class="mb-1">
                    <label class="mb-1">
                        <input type="checkbox" class="select-all" data-group="sallat">
                    </label> Sallat:</h5>
                <div class="d-flex flex-column sallat">
                   
                    <label>
                        <input type="hidden" name="preferences[venue-added]" value="0">
                        <input type="checkbox" name="preferences[venue-added]" {{ (isset($preferences['venue-added']) && $preferences['venue-added']) ? 'checked' : '' }}>
                        Shto Sallë
                    </label>
                    <label>
                        <input type="hidden" name="preferences[venue-updated]" value="0">
                        <input type="checkbox" name="preferences[venue-updated]" {{ isset($preferences['venue-updated']) && $preferences['venue-updated'] ? 'checked' : '' }}>
                        Përditso Sallë
                    </label>
                    <label>
                        <input type="hidden" name="preferences[venue-deleted]" value="0">
                        <input type="checkbox" name="preferences[venue-deleted]" {{ isset($preferences['venue-deleted']) && $preferences['venue-deleted'] ? 'checked' : '' }}>
                        Fshij Sallë
                    </label>
                </div>
            </div>

            <!-- Menu Section -->
            <div class="vms-panel-bordered">
                <h5 class="mb-1"><label class="mb-1">
                        <input type="checkbox" class="select-all" data-group="menu"> 
                    </label> Menu:</h5>
                <div class="d-flex flex-column menu">
                    <label>
                        <input type="hidden" name="preferences[menu-added]" value="0">
                        <input type="checkbox" name="preferences[menu-added]" {{ (isset($preferences['menu-added']) && $preferences['menu-added']) ? 'checked' : '' }}>
                        Shto Menu
                    </label>
                    <label>
                        <input type="hidden" name="preferences[menu-updated]" value="0">
                        <input type="checkbox" name="preferences[menu-updated]" {{ isset($preferences['menu-updated']) && $preferences['menu-updated'] ? 'checked' : '' }}>
                        Përditso Menu
                    </label>
                    <label>
                        <input type="hidden" name="preferences[menu-deleted]" value="0">
                        <input type="checkbox" name="preferences[menu-deleted]" {{ isset($preferences['menu-deleted']) && $preferences['menu-deleted'] ? 'checked' : '' }}>
                        Fshij Menu
                    </label>
                </div>
            </div>

            <!-- Klientat Section -->
            <div class="vms-panel-bordered">
                <h5 class="mb-1"> 
                    <label class="mb-1">
                    <input type="checkbox" class="select-all" data-group="klientat">
                    </label> Klientat:</h5>
                <div class="d-flex flex-column klientat">
                    <label>
                        <input type="hidden" name="preferences[client-updated]" value="0">
                        <input type="checkbox" name="preferences[client-updated]" {{ isset($preferences['client-updated']) && $preferences['client-updated'] ? 'checked' : '' }}>
                        Përditso Klient
                    </label>
                </div>
            </div>

            <!-- Shpenzimet Section -->
            <div class="vms-panel-bordered">
                <h5 class="mb-1">
                    <label class="mb-1">
                        <input type="checkbox" class="select-all" data-group="shpenzimet"> 
                    </label> Shpenzimet:</h5>
                <div class="d-flex flex-column shpenzimet">
                  
                    <label>
                        <input type="hidden" name="preferences[expenses-added]" value="0">
                        <input type="checkbox" name="preferences[expenses-added]" {{ (isset($preferences['expenses-added']) && $preferences['expenses-added']) ? 'checked' : '' }}>
                        Shto Shpenzim
                    </label>
                    <label>
                        <input type="hidden" name="preferences[expenses-deleted]" value="0">
                        <input type="checkbox" name="preferences[expenses-deleted]" {{ isset($preferences['expenses-deleted']) && $preferences['expenses-deleted'] ? 'checked' : '' }}>
                        Fshij Shpenzim
                    </label>
                    <label>
                        <input type="hidden" name="preferences[expenses-updated]" value="0">
                        <input type="checkbox" name="preferences[expenses-updated]" {{ isset($preferences['expenses-updated']) && $preferences['expenses-updated'] ? 'checked' : '' }}>
                        Përditso Shpenzim
                    </label>
                </div>
            </div>

            <button class="hubers-btn w-auto" type="submit">Ruaj Preferencat</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Global Select All
            const globalSelectAll = document.getElementById('global-select-all');
            globalSelectAll.addEventListener('change', (e) => {
                const allCheckboxes = document.querySelectorAll('input[type="checkbox"]:not(#global-select-all)');
                allCheckboxes.forEach(checkbox => {
                    checkbox.checked = e.target.checked;
                });
            });

            // Section-wise Select All
            const sectionSelectAlls = document.querySelectorAll('.select-all');
            sectionSelectAlls.forEach(selectAll => {
                selectAll.addEventListener('change', (e) => {
                    const group = e.target.getAttribute('data-group');
                    const groupCheckboxes = document.querySelectorAll(`.${group} input[type="checkbox"]`);
                    groupCheckboxes.forEach(checkbox => {
                        checkbox.checked = e.target.checked;
                    });
                });
            });
        });
    </script>
@endsection
