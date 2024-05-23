@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between">
                        <h6>Reservations</h6>
                        <a class="btn btn-primary text-white inline-block" href="{{route('reservations.create')}}">Create</a>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if(count($reservations) > 0)
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Date</th>
                                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Venue</th>
                                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Description</th>
                                        <th width="40" class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reservations as $reservation)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3">
                                                    <h6 class="mb-0">{{$reservation->date}}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-3">
                                                    <h6 class="mb-0 text-sm">{{$reservation->venue->name}}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-3">
                                                    <h6 class="mb-0 text-sm">{{$reservation->description}}</h6>
                                                </div>
                                            </td>
                                            <td>
{{--                                                <div class="d-flex px-3">--}}
{{--                                                    <a href="{{route('venues.view',['id'=>$reservation->id])}}">--}}
{{--                                                        <i class="fa fa-eye"></i>--}}
{{--                                                    </a>--}}
{{--                                                </div>--}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <h6 class="text-center">There are no reservations currently</h6>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
