@extends('layouts.app')
@section('header')
    Përditso Reservimin: {{$reservation->id}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action="{{ route('reservations.update', ['id' => $reservation->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Numri i të ftuarve*</label>
                                    <input id="numberOfGuests" class="bug-text-input" type="number" name="number_of_guests" value="{{$reservation->number_of_guests}}" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Cmimi i menus*</label>
                                    <input id="menuPrice" class="bug-text-input" type="number" name="menu_price" value="{{$reservation->menu_price}}" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Selekto Menagjerin</label>
                                    <select id="menuId" class="bug-text-input" name="menager_id">
                                        @foreach($users as $user)
                                        <option value="{{$user->id}}" {{$user->id == $reservation->menager_id ? 'selected' : ''}}>{{$user->first_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Shpenzimet e Stafit*</label>
                                    <input id="staffExpenses" class="bug-text-input" type="number" name="staff_expenses" value="{{$reservation->staff_expenses}}" >
                                </div>
                            </div>
                         
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Data*</label>
                                    <input class="bug-text-input" type="date" name="date" required id="dateInput" value="{{$reservation->date}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                Sallat:
                                <div class="row venue-items">
                                    @foreach($venues as $venue)
                                        <div class="col-md-4">
                                            <div data-venue="{{$venue->id}}" class="venue-item venue-id">
                                                <span class="venue-name">
                                                    {{$venue->name}},{{$venue->capacity}}
                                                </span>
                                                <span class="venue-slots"></span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="venue_id" id="venueID" value="{{ $reservation->venue_id }}">
                        <input type="hidden" name="selected_venue_slot" id="selectedVenueSlot" value="{{ $reservation->venue_id }},{{ $reservation->slot }}">
                        <input type="hidden" name="reservation_type" id="reservationType" value="{{ $reservation->reservation_type }}">
                        <button type="submit" class="hubers-btn">Ruaj</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
    const selectedVenueSlotInput = document.getElementById('selectedVenueSlot');
    const venueIDInput = document.getElementById('venueID');
    const reservationTypeInput = document.getElementById('reservationType');
    const selectedVenueSlot = selectedVenueSlotInput.value.split(',');

    function generateReservationTypeOptions(venueId, reservationType, availability) {
        const venue = document.querySelector(`[data-venue="${venueId}"]`);
        venue.classList.remove('available', 'partially-available', 'not-available');

        if (availability.length === 3) {
            venue.classList.add('available');
        } else if (availability.length === 1 || availability.length === 2) {
            venue.classList.add('partially-available');
        } else {
            venue.classList.add('not-available');
        }

        const slotsContainer = venue.querySelector('.venue-slots');
        slotsContainer.innerHTML = '';
        const labels = {
            1: 'Ditë e Plotë',
            2: 'Mëngjes',
            3: 'Mbrëmje'
        };

        availability.forEach(slot => {
            const slotDiv = document.createElement('div');
            slotDiv.classList.add('slot');
            const input = document.createElement('input');
            input.classList.add('bug-checkbox-input');
            input.setAttribute('type', 'radio');
            input.setAttribute('name', `reservation_${venueId}`);
            input.setAttribute('value', `${venueId},${slot}`);
            if (venueId == selectedVenueSlot[0] && slot == selectedVenueSlot[1]) {
                input.checked = true;
            }
            input.addEventListener('change', () => {
                selectedVenueSlotInput.value = `${venueId},${slot}`;
                venueIDInput.value = venueId;
                reservationTypeInput.value = slot; // Update reservation type based on the selected slot
                // Deselect all reservation type options across all venues
                const allSlotInputs = document.querySelectorAll('input[name^="reservation_"]');
                allSlotInputs.forEach(otherInput => {
                    otherInput.checked = false;
                });
                input.checked = true;
            });
            const label = document.createElement('label');
            label.classList.add('form-control-label');
            label.textContent = labels[slot];
            slotDiv.appendChild(input);
            slotDiv.appendChild(label);
            slotsContainer.appendChild(slotDiv);
        });

        // Pre-select the reservation type if it matches the current venue ID
        const slotInputs = slotsContainer.querySelectorAll(`input[name="reservation_${venueId}"]`);
        slotInputs.forEach(input => {
            if (input.value.split(',')[1] == reservationType) {
                input.checked = true;
            }
        });
    }

    function checkAvailabilityAndUpdateTotal() {
        const dateInput = document.getElementById('dateInput');
        fetch('/reservation/check-availability', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                date: dateInput.value,
            })
        })
        .then(response => response.json())
        .then(res => {
            res.data.forEach(venue => {
                generateReservationTypeOptions(venue.id, reservationTypeInput.value, venue.availability);
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    const dateInput = document.getElementById('dateInput');
    dateInput.addEventListener('input', checkAvailabilityAndUpdateTotal);

    // Call the function initially to set up the slots and pre-select the correct values
    checkAvailabilityAndUpdateTotal();
});

    </script>
@endsection
