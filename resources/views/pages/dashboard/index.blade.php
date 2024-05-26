@extends('layouts.app')
@section('header')
    Dashboard
@endsection
@section('content')
    <div class="vms_panel">
        <div id='calendar'></div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
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
                            $('#date-input').val(info.dateStr);
                            $('#reservationModal').modal('show');
                        }

                    }
                });

                calendar.render();
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Data</label>
                                        <input class="form-control" type="date" name="date" id="date-input" >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Full Day</label>
                                        <input name="full_day" type="checkbox" id="rememberMe">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Salla</label>
                                        <select class="form-control" name="venue_id" id="">
                                            @foreach($venues as $venue)
                                                <option value="{{$venue->id}}">{{$venue->name}},{{$venue->capacity}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Numri i te ftuarve</label>
                                        <input class="form-control" type="number" name="number_of_guests" >
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Notes</label>
                                        <textarea class="form-control" type="text" name="description" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <p class="text-uppercase text-sm">Client Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Name</label>
                                        <input class="form-control" type="text" name="client_name" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Email</label>
                                        <input class="form-control" type="text" name="client_email" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Telefoni</label>
                                        <input class="form-control" type="text" name="client_phone_number" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Telefoni Opsional</label>
                                        <input class="form-control" type="text" name="client_additional_phone_number" >
                                    </div>
                                </div>
                            </div>
                            <p class="text-uppercase text-sm">Payment Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Date</label>
                                        <input class="form-control" type="date" name="payment_date" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Initial Payment</label>
                                        <input class="form-control" type="number" name="initial_payment_value" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Total Payment</label>
                                        <input class="form-control" type="number" name="total_payment_value" >
                                    </div>
                                </div>
                            </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Ruaj</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

@endsection
