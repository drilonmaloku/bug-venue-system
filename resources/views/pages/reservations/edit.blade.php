@extends('layouts.app')
@section('header')
    Edito Rezervim
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <form role="form" method="POST" action={{ route('reservations.update',['id'=> $reservation->id]) }} enctype="multipart/form-data" onsubmit="return disableSubmitButton();">
                    @csrf
                    @method('PUT')
                    <h6><strong>Informatat e Rezervimit</strong></h6>
                    <input type="hidden" id="initialReservationData" value="{{ $reservation->venue_id }},{{ $reservation->reservation_type }}">
                    <input type="hidden" id="initialDate" value="{{ $reservation->date }}">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Data*</label>
                                <input class="bug-text-input" type="date" name="date" required id="dateInput" value="{{$reservation->date}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Numri i të ftuarve*</label>
                                <input id="numberOfGuests" class="bug-text-input" type="number" name="number_of_guests" required value="{{$reservation->number_of_guests}}" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" name="venue_id" id="venueID">
                            Sallat:
                            <div class="row venue-items">
                                @foreach($venues as $venue)
                                    <div class="col-md-4" >
                                        <div data-venue="{{$venue->id}}" class="venue-item venue-id">
                                                <span class="venue-name">
                                                          {{$venue->name}},{{$venue->capacity}}
                                                </span>
                                            <span class="venue-slots">

                                                </span>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Menu*</label>
                                <select id="menuId" class="bug-text-input" name="menu_id">
                                    <option value="">Selekto Menun</option>
                                    @foreach($menus as $menu)
                                        <option value="{{ $menu->id }}" {{ $menu->id == $reservation->menu_id ? 'selected' : '' }}>{{$menu->name}},{{$menu->price}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Qmimi i Menus*</label>
                                <input id="menuPrice" class="bug-text-input" type="number" name="menu_price" value="{{$reservation->menu_price}}" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Shenime</label>
                                <textarea class="bug-text-input" type="text" name="description"  value="{{$reservation->description}}">{{$reservation->description}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Selekto Menagjerin*</label>
                                <select  class="bug-text-input" name="menager_id">
                                    <option value="">Selekto Menagjerin</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $user->id == $reservation->menager_id ? 'selected' : '' }}>{{ $user->first_name }}</option>
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <h5>Totali: <span id="totalPrice">0</span></h5>
                            </div>
                        </div>
                    </div>
                    <h6><strong>Informatat mbi klientin:</strong></h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Emri*</label>
                                <input class="bug-text-input" type="text" name="name" value="{{$reservation->client->name}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Emaili</label>
                                <input class="bug-text-input" type="text" name="email" value="{{$reservation->client->email}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Telefoni*</label>
                                <input class="bug-text-input" type="text" name="phone_number" value="{{$reservation->client->phone_number}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Telefoni Opsional</label>
                                <input class="bug-text-input" type="text" name="additional_phone_number" value="{{$reservation->client->additional_phone_number}}">
                            </div>
                        </div>
                    </div>
                    <button id="submitBtn" type="submit" class="hubers-btn">Ruaj</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuSelect = document.getElementById('menuId');
            const menuPriceInput = document.getElementById('menuPrice');
            const numberOfGuestsInput = document.getElementById('numberOfGuests');
            const totalPriceDisplay = document.getElementById('totalPrice');
            const initialReservationData = document.getElementById('initialReservationData');
            const dateInput = document.getElementById('dateInput');
            const initialDate = document.getElementById('initialDate');


            function generateReservationTypeOptions(venueId, availability) {
                const venue = document.querySelector(`[data-venue="${venueId}"]`);
                venue.classList.remove('available');
                venue.classList.remove('partially-available');
                venue.classList.remove('not-available');

                let availableCount = 0;
                const keyValuePairs = [];
                for (const availabilityItem of Object.entries(availability)) {
                    keyValuePairs.push({ key: availabilityItem[0], value: availabilityItem[1] });
                    if (availabilityItem[1] === true) {
                        availableCount++;
                    }
                }
                if (availableCount == 3) {
                    venue.classList.add('available');
                } else if (availableCount == 1 || availableCount == 2) {
                    venue.classList.add('partially-available');
                } else {
                    venue.classList.add('not-available');
                }
                const slotsContainer = venue.querySelector('.venue-slots');
                slotsContainer.innerHTML = ''; // Clear the slots container before populating with new slots
                const labels = {
                    1: 'Ditë e Plotë',
                    2: 'Mëngjes',
                    3: 'Mbrëmje'
                };

                keyValuePairs.forEach(slot => {
                    if(slot['value'] == true || (initialReservationData.value == `${venueId},${slot['key']}` && dateInput.value == initialDate.value) ) {
                        const slotDiv = document.createElement('div');
                        slotDiv.classList.add('slot');
                        const input = document.createElement('input');
                        input.classList.add('bug-checkbox-input');
                        input.setAttribute('type', 'radio');
                        input.setAttribute('name', `reservation`);
                        input.setAttribute('value', `${venueId},${slot['key']}`);
                        if(initialReservationData.value == `${venueId},${slot['key']}` && dateInput.value == initialDate.value ){
                            slotDiv.classList.add('bug-checkbox-input-pre-date');
                            input.setAttribute('checked', `checked`);
                        }
                        const label = document.createElement('label');
                        label.classList.add('form-control-label');
                        label.textContent = labels[slot['key']] ;
                        slotDiv.appendChild(input);
                        slotDiv.appendChild(label);
                        slotsContainer.appendChild(slotDiv);
                    }

                });
            }

            function updateMenuPrice() {
                const selectedOption = menuSelect.options[menuSelect.selectedIndex];
                const menuPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                menuPriceInput.value = menuPrice;
                updateTotalPrice();
            }

            function updateTotalPrice() {
                const menuPrice = parseFloat(menuPriceInput.value) || 0;
                const numberOfGuests = parseInt(numberOfGuestsInput.value) || 0;
                const totalPrice = menuPrice * numberOfGuests;

                totalPriceDisplay.textContent = `${totalPrice.toFixed(2)}`;


            }

            function checkAvailabilityAndUpdateTotal() {
                fetch('/reservation/check-availability', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    body: JSON.stringify({
                        reservation_id: {{ $reservation->id }}, // Y-m-d format
                        date: dateInput.value, // Y-m-d format
                    })
                })
                    .then(response => response.json())
                    .then(res => {

                        res.data.forEach(venue => {
                            generateReservationTypeOptions(venue.id,venue.availability);
                        });

                    })
                    .catch(error => {
                        console.error('Error:', error);
                        totalPriceDisplay.textContent = 'Error checking availability.';
                    });
            }

            menuSelect.addEventListener('change', updateMenuPrice);
            dateInput.addEventListener('input', checkAvailabilityAndUpdateTotal);
            menuPriceInput.addEventListener('input', updateTotalPrice);
            numberOfGuestsInput.addEventListener('input', updateTotalPrice);

            checkAvailabilityAndUpdateTotal();

        });



        function disableSubmitButton() {
            document.getElementById("submitBtn").disabled = true;
            return true;
        }
    </script>
@endsection
