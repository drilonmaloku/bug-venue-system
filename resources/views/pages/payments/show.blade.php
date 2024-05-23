@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => $client->name])
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            Client Information:
                        </h5>
                        <p class="mb-0">
                            Name: <strong>{{$client->name}}</strong> <br>
                            Email: <strong>{{$client->email}}</strong> <br>
                            Phone Number: <strong>{{$client->phone_number}}</strong> <br>
                            Additional Phone Number: <strong>{{$client->additional_phone_number}}</strong> <br>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">Reservations</h6>
            </div>
            <div class="card-body p-3">
                <ul class="list-group">
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                <i class="ni ni-mobile-button text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Devices</h6>
                                <span class="text-xs">250 in stock, <span class="font-weight-bold">346+
                                                sold</span></span>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button
                                    class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                        class="ni ni-bold-right" aria-hidden="true"></i></button>
                        </div>
                    </li>
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                <i class="ni ni-tag text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Tickets</h6>
                                <span class="text-xs">123 closed, <span class="font-weight-bold">15
                                                open</span></span>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button
                                    class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                        class="ni ni-bold-right" aria-hidden="true"></i></button>
                        </div>
                    </li>
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                <i class="ni ni-box-2 text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Error logs</h6>
                                <span class="text-xs">1 is active, <span class="font-weight-bold">40
                                                closed</span></span>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button
                                    class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                        class="ni ni-bold-right" aria-hidden="true"></i></button>
                        </div>
                    </li>
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                <i class="ni ni-satisfied text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Happy users</h6>
                                <span class="text-xs font-weight-bold">+ 430</span>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button
                                    class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                        class="ni ni-bold-right" aria-hidden="true"></i></button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">Payments</h6>
            </div>
            <div class="card-body p-3">
                <ul class="list-group">
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                <i class="ni ni-mobile-button text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Devices</h6>
                                <span class="text-xs">250 in stock, <span class="font-weight-bold">346+
                                                sold</span></span>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button
                                    class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                        class="ni ni-bold-right" aria-hidden="true"></i></button>
                        </div>
                    </li>
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                <i class="ni ni-tag text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Tickets</h6>
                                <span class="text-xs">123 closed, <span class="font-weight-bold">15
                                                open</span></span>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button
                                    class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                        class="ni ni-bold-right" aria-hidden="true"></i></button>
                        </div>
                    </li>
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                <i class="ni ni-box-2 text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Error logs</h6>
                                <span class="text-xs">1 is active, <span class="font-weight-bold">40
                                                closed</span></span>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button
                                    class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                        class="ni ni-bold-right" aria-hidden="true"></i></button>
                        </div>
                    </li>
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                <i class="ni ni-satisfied text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Happy users</h6>
                                <span class="text-xs font-weight-bold">+ 430</span>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button
                                    class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                        class="ni ni-bold-right" aria-hidden="true"></i></button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="alert">
        @include('components.alert')
    </div>
@endsection
