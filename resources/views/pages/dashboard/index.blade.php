@extends('layouts.app')
@section('header')
    Dashboard
@endsection
@section('content')
    <div class="vms_panel dashboard_panel">
        <div id='calendar'></div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

            });
        </script>
        <div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reservationModalLabel">Krijo Rezervim</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form role="form" method="POST" action={{ route('reservations.store') }}
                        enctype="multipart/form-data">
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
                                        <label for="example-text-input" class="form-control-label">Numri i të
                                            ftuarve*</label>
                                        <input id="numberOfGuests" class="bug-text-input" required type="number"
                                            name="number_of_guests" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="venue_id" id="venueID">
                                    Sallat:
                                    <div class="row venue-items">
                                        @foreach ($venues as $venue)
                                            <div class="col-md-4">
                                                <div data-venue="{{ $venue->id }}" class="venue-item venue-id">
                                                    <span class="venue-name">
                                                        {{ $venue->name }},{{ $venue->capacity }}
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
                                        <select required id="menuId" class="bug-text-input" name="menu_id">
                                            <option value="">Selekto Menun</option>
                                            @foreach ($menus as $menu)
                                                <option data-price="{{ $menu->price }}" value="{{ $menu->id }}">
                                                    {{ $menu->name }},{{ $menu->price }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Qmimi i Menus*</label>
                                        <input required id="menuPrice" class="bug-text-input" type="number" name="menu_price">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Shenime</label>
                                        <textarea class="bug-text-input" type="text" name="description"></textarea>
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
                                        <input required class="bug-text-input" type="text" name="client_name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Emaili</label>
                                        <input class="bug-text-input" type="text" name="client_email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Telefoni*</label>
                                        <input required class="bug-text-input" type="text" name="client_phone_number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Telefoni
                                            Opsional</label>
                                        <input class="bug-text-input" type="text" name="client_additional_phone_number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Data e Pagesës</label>
                                        <input required class="bug-text-input" type="date" name="payment_date">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Vlera e Pagesës</label>
                                        <input class="bug-text-input" type="number" name="initial_payment_value">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Shenime</label>
                                        <textarea class="bug-text-input" type="text" name="payment_notes"></textarea>
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
        <div class="modal fade" id="informationModal" tabindex="-1" role="dialog"
            aria-labelledby="reservationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reservationModalLabel">Rezervim</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="informationModalContent" class="modal-body">

                        <table>
                            <thead>
                                <tr>
                                    <th colspan="2">Informatat:</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Data</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Salla</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Numri i te ftuarve</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Klienti</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Koha:</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Pagesa Momentale:</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Pagesa E Mbetur:</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Shuma Totale :</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
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
            var events = @json($events); 
            var menusCount = @json(count($menus));
            var venuesCount = @json(count($venues));
            console.log(events);
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: {
                     url:'/dashboard/events',
                     method: 'GET',
                     
                },

                height: "auto",

                dateClick: function(info) {
                    // Check if the date has an event
                    $('#dateInput').val(info.dateStr);
                    checkAvailabilityAndUpdateTotal();
                    if(venuesCount> 0 && menusCount > 0){
                        $('#reservationModal').modal('show');
                    }else {
                    alert('Duhet te keni nje menu te pakten dhe nje salle per ta krijuar nje rezervim');
                }

                },
                eventClick: function(info) {
                    // An event was clicked, open the information modal

                    // Optionally, load additional information via AJAX if necessary
                    fetch(`/reservations/json/${info.event.id}`)
                        .then(response => response.json())
                        .then(data => {
                            const reservation = data.data.reservation;
                            const tableContent = `
                    <thead>
                        <tr>
                            <th colspan="2">Informatat:</th>
                            <th>    
                                <a class="bug-table-item-option" href="/reservations/${reservation.id}">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Data</td>
                            <td>${reservation.date}</td>
                            <td></td>

                        </tr>
                        <tr>
                            <td>Salla</td>
                            <td>${reservation.venue.name}</td>
                            <td></td>

                        </tr>
                        <tr>
                            <td>Numri i te ftuarve</td>
                            <td>${reservation.number_of_guests}</td>
                            <td></td>

                        </tr>
                        <tr>
                            <td>Klienti</td>
                            <td>${reservation.client.name}</td>
                            <td></td>

                        </tr>
                        <tr>
                            <td>Koha:</td>
                            <td>${reservation.reservation_type_name}</td>
                            <td></td>

                        </tr>
                        <tr>
                            <td>Pagesa Momentale:</td>
                            <td>${reservation.current_payment}€</td>
                            <td></td>

                        </tr>
                        <tr>
                            <td>Pagesa E Mbetur:</td>
                            <td>${reservation.total_payment - reservation.current_payment}€</td>
                            <td></td>

                        </tr>
                        <tr>
                            <td>Shuma Totale :</td>
                            <td>${reservation.total_payment}€</td>
                            <td></td>

                        </tr>
                    </tbody>
                `;

                            document.getElementById('informationModalContent').innerHTML =
                                `<table>${tableContent}</table>`;
                        })
                        .catch(error => {
                            console.error('Error fetching event information:', error);
                        });
                    $('#informationModal').modal('show');
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
                console.log(
                    JSON.stringify({
                        date: dateInput.value, // Y-m-d format
                    })
                )
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
                        console.log('dtz',res.data);
                        res.data.forEach(venue => {
                            generateReservationTypeOptions(venue.id, venue.availability);
                        });

                    })
                    .catch(error => {
                        console.error('Error:', error);
                        totalPriceDisplay.textContent = 'Error checking availability.';
                    });
            }

            menuSelect.addEventListener('change', updateMenuPrice);
            dateInput.addEventListener('input', checkAvailabilityAndUpdateTotal);
            dateInput.addEventListener('change', checkAvailabilityAndUpdateTotal);
            dateInput.addEventListener('keyup', checkAvailabilityAndUpdateTotal);
            menuPriceInput.addEventListener('input', updateTotalPrice);
            numberOfGuestsInput.addEventListener('input', updateTotalPrice);
        });
    </script>
@endsection
