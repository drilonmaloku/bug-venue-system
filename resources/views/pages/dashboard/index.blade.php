@extends('layouts.app')
@section('header')
    Dashboard
@endsection
@section('content')
    <div class="vms_panel">
        <div id='calendar'></div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {

            });
        </script>
        <div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reservationModalLabel">Krijo Rezervim</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form role="form" method="POST" action={{ route('reservations.store') }} enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <h6><strong>Informatat e Rezervimit</strong></h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Data*</label>
                                        <input class="bug-text-input" type="date" name="date" required id="dateInput">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Numri i të ftuarve*</label>
                                        <input id="numberOfGuests" class="bug-text-input" type="number" name="number_of_guests" required>
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
                                        <label for="example-text-input" class="form-control-label">Menu</label>
                                        <select id="menuId" class="bug-text-input" name="menu_id">
                                            <option value="">Selekto Menun</option>
                                            @foreach($menus as $menu)
                                                <option data-price="{{$menu->price}}" value="{{$menu->id}}">{{$menu->name}},{{$menu->price}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Qmimi i Menus</label>
                                        <input id="menuPrice" class="bug-text-input" type="number" name="menu_price" >
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Shenime</label>
                                        <textarea class="bug-text-input" type="text" name="description" ></textarea>
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
                                        <label for="example-text-input" class="form-control-label">Emri</label>
                                        <input class="bug-text-input" type="text" name="client_name" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Emaili</label>
                                        <input class="bug-text-input" type="text" name="client_email" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Telefoni</label>
                                        <input class="bug-text-input" type="text" name="client_phone_number" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Telefoni Opsional</label>
                                        <input class="bug-text-input" type="text" name="client_additional_phone_number" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Data e Pagesës</label>
                                        <input class="bug-text-input" type="date" name="payment_date" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Vlera e Pagesës</label>
                                        <input class="bug-text-input" type="number" name="initial_payment_value" >
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Shenime</label>
                                        <textarea class="bug-text-input" type="text" name="payment_notes" ></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Ruaj</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Mbyll</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="modal fade" id="informationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reservationModalLabel">Rezervim</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                      test
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var events = {!! json_encode($events) !!};
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: events,
                dateClick: function (info) {
                    // Open a modal or form to add a new event
                    var event = events.find(e => e.start === info.dateStr);

                    if (event) {
                        console.log('test2',event);
                        $('#informationModal').modal('show');
                    } else {
                        console.log('test23',event);
                        // Open the reservation modal
                        $('#dateInput').val(info.dateStr);
                        $('#reservationModal').modal('show');
                        checkAvailabilityAndUpdateTotal();
                    }

                }
            });

            calendar.render();

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

                if(availability.length == 3) {
                    venue.classList.add('available');
                }else if(availability.length == 1 || availability.length == 2){
                    venue.classList.add('partially-available');
                }
                else {
                    venue.classList.add('not-available');
                }
                const slotsContainer = venue.querySelector('.venue-slots');
                slotsContainer.innerHTML = ''; // Clear the slots container before populating with new slots
                const labels = {
                    1: 'Ditë e Plotë',
                    2: 'Mëngjes',
                    3: 'Mbrëmje'
                };
                availability.forEach(slot => {
                    console.log(slot);
                    const slotDiv = document.createElement('div');
                    slotDiv.classList.add('slot');
                    const input = document.createElement('input');
                    input.classList.add('bug-checkbox-input');
                    input.setAttribute('type', 'radio');
                    input.setAttribute('name', `reservation`);
                    input.setAttribute('value', `${venueId},${slot}`);
                    const label = document.createElement('label');
                    label.classList.add('form-control-label');
                    label.textContent = labels[slot] ;
                    slotDiv.appendChild(input);
                    slotDiv.appendChild(label);
                    slotsContainer.appendChild(slotDiv);
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
    </script>
@endsection
