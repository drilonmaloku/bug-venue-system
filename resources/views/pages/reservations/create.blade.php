@extends('layouts.app')
@section('header')
    Krijo Rezervim
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action={{ route('reservations.store') }} enctype="multipart/form-data">
                        @csrf
                        <h6><strong>Informatat e Rezervimit</strong></h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Data</label>
                                    <input class="bug-text-input" type="date" name="date" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Numri i te ftuarve</label>
                                    <input class="bug-text-input" type="number" name="number_of_guests" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group flex-col">
                                    <label for="example-text-input" class="form-control-label">Dit e Plot</label>
                                    <input name="full_day" type="checkbox" id="rememberMe">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group flex-col">
                                    <label for="example-text-input" class="form-control-label">Mengjes</label>
                                    <input name="full_day" type="checkbox" id="rememberMe">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group flex-col">
                                    <label for="example-text-input" class="form-control-label">Mbremje</label>
                                    <input name="full_day" type="checkbox" id="rememberMe">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Salla</label>
                                    <select class="bug-text-input" name="venue_id" id="">
                                        @foreach($venues as $venue)
                                            <option value="{{$venue->id}}">{{$venue->name}},{{$venue->capacity}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Menu</label>
                                    <select class="bug-text-input" name="venue_id" id="">
                                        @foreach($venues as $venue)
                                            <option value="{{$venue->id}}">{{$venue->name}},{{$venue->capacity}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Notes</label>
                                    <textarea class="bug-text-input" type="text" name="description" ></textarea>
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
                        </div>
                        <p class="text-uppercase text-sm">Payment Information</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Date</label>
                                    <input class="bug-text-input" type="date" name="payment_date" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Initial Payment</label>
                                    <input class="bug-text-input" type="number" name="initial_payment_value" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Total Payment</label>
                                    <input class="bug-text-input" type="number" name="total_payment_value" >
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="hubers-btn">Ruaj</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
