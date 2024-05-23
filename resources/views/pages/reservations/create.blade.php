@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Create Reservation'])
    <div id="alert">
        @include('components.alert')
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <form role="form" method="POST" action={{ route('reservations.store') }} enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Create Reservation</p>
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">Reservation Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Date</label>
                                        <input class="form-control" type="date" name="date" >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group flex-col">
                                        <label for="example-text-input" class="form-control-label">Full Day</label>
                                        <input name="full_day" type="checkbox" id="rememberMe">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Number of Guests</label>
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
                                        <label for="example-text-input" class="form-control-label">Phone Number</label>
                                        <input class="form-control" type="text" name="client_phone_number" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Optional Phone Number</label>
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
                            <p class="text-uppercase text-sm">Venue Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Name</label>
                                        <select class="form-control" name="venue_id" id="">
                                            @foreach($venues as $venue)
                                                <option value="{{$venue->id}}">{{$venue->name}},{{$venue->capacity}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
