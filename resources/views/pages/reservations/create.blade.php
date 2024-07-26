@extends('layouts.app')
@section('header')
    {{__('reservations.create.title')}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action={{ route('reservations.store') }} enctype="multipart/form-data" onsubmit="return disableSubmitButton();">
                        @csrf
                        <h6><strong>{{__('reservations.create.reservation_information')}}</strong></h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">{{__('reservations.create.date')}}*</label>
                                    <input class="bug-text-input" type="date" name="date" required id="dateInput">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">{{__('reservations.create.number_of_guests')}}*</label>
                                    <input id="numberOfGuests" class="bug-text-input" type="number" placeholder="{{__('reservations.create.number_of_guests')}}*" name="number_of_guests" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" name="venue_id" id="venueID">
                                {{__('reservations.create.venues')}}:
                                <div class="row venue-items">
                                    @foreach($venues as $venue)
                                        <div class="col-md-4" >
                                            <div data-venue="{{$venue->id}}" class="venue-item venue-id">
                                                <span class="venue-name">{{$venue->name}},{{$venue->capacity}}</span>
                                                <span class="venue-slots">
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">{{__('reservations.create.menu')}}*</label>
                                    <select required id="menuId" class="bug-text-input" name="menu_id">
                                        <option value="">{{__('reservations.create.select_menu')}}</option>
                                        @foreach($menus as $menu)
                                            <option  data-price="{{$menu->price}}" value="{{$menu->id}}">{{$menu->name}},{{$menu->price}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">{{__('reservations.create.menu_price')}}*</label>
                                    <input id="menuPrice" class="bug-text-input" required type="number" name="menu_price" >
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">{{__('reservations.create.notes')}}</label>
                                    <textarea class="bug-text-input" type="text" required  name="description" ></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">{{__('reservations.create.select_manager')}}</label>
                                    <select required id="menuId" class="bug-text-input" name="menager_id">
                                        <option value="">{{__('reservations.create.select_manager')}}</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->first_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h5>{{__('reservations.create.total')}}: <span id="totalPrice">0</span></h5>
                                </div>
                            </div>
                        </div>
                        <h6><strong>{{__('reservations.create.client.information')}}:</strong></h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">{{__('reservations.create.client.name')}}*</label>
                                    <input class="bug-text-input" type="text" name="client_name" required >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">{{__('reservations.create.client.email')}}</label>
                                    <input class="bug-text-input" type="text" name="client_email" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">{{__('reservations.create.client.phone')}}*</label>
                                    <input class="bug-text-input" type="text" required name="client_phone_number" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">{{__('reservations.create.client.additional_phone')}}</label>
                                    <input class="bug-text-input" type="text" name="client_additional_phone_number" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">{{__('reservations.create.payment.date')}}*</label>
                                    <input class="bug-text-input" type="date" required name="payment_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">{{__('reservations.create.payment.amount')}}*</label>
                                    <input class="bug-text-input" type="number" required name="initial_payment_value">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">{{__('reservations.create.payment.notes')}}</label>
                                    <textarea class="bug-text-input" type="text"  name="payment_notes" ></textarea>
                                </div>
                            </div>
                        </div>
                        <button id="submitBtn" type="submit" class="hubers-btn">{{__('general.save_btn')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuSelect = document.getElementById('menuId');
            const menuPriceInput = document.getElementById('menuPrice');
            const numberOfGuestsInput = document.getElementById('numberOfGuests');
            const totalPriceDisplay = document.getElementById('totalPrice');
            const dateInput = document.getElementById('dateInput');


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
                    if(slot['value']) {
                        const slotDiv = document.createElement('div');
                        slotDiv.classList.add('slot');
                        const input = document.createElement('input');
                        input.classList.add('bug-checkbox-input');
                        input.setAttribute('type', 'radio');
                        input.setAttribute('name', `reservation`);
                        input.setAttribute('value', `${venueId},${slot['key']}`);
                        const label = document.createElement('label');
                        label.classList.add('form-control-label');
                        label.textContent = labels[slot['key']];
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
        });



        function disableSubmitButton() {
            document.getElementById("submitBtn").disabled = true;
            return true;
        }
    </script>
@endsection
