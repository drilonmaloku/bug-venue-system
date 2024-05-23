@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => $venue->name])

    @if(count($venue->reservations) == 0)
        <div class="card shadow-lg mx-4 mt-4">
            <div class="card-body p-3">
                <form action="{{ route('venues.destroy', $venue->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm ms-auto mb-0" type="submit">Delete</button>
                </form>
            </div>
        </div>
    @endif
    <div class="card shadow-lg mx-4 mt-4">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            Venue Information:
                        </h5>
                        <p class="mb-0">
                            Name: <strong>{{$venue->name}}</strong> <br>
                            Description: <strong>{{$venue->description}}</strong> <br>
                            Capacity: <strong>{{$venue->capacity}}</strong> <br>
                        </p>
                        <a class="btn btn-behance px--5 mb-0 mt-2" href="{{route('venues.edit',['id'=>$venue->id])}}">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mx-4 mt-4 mb-4">
        <div class="card-header pb-0 d-flex justify-content-between">
            <h6>Reservations</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            @if(count($venue->reservations) > 0)
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                Date</th>
                            <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                Client</th>
                            <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                Description</th>
                            <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                Payment</th>
                            <th width="40" class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($venue->reservations as $reservation)
                            <tr>
                                <td>
                                    <div class="d-flex px-3">
                                        <h6 class="mb-0">{{$reservation->date}}</h6>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex px-3">
                                        <h6 class="mb-0 text-sm">{{$reservation->client->name}}</h6>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex px-3">
                                        <h6 class="mb-0 text-sm">{{$reservation->description}}</h6>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex px-3">
                                        <h6 class="mb-0 text-sm">{{$reservation->current_payment}} / {{$reservation->total_payment}}</h6>
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
    <div id="alert">
        @include('components.alert')
    </div>
@endsection
