@extends('layouts.app')

@section('header')
   Rezervimi : {{$reservation->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <form action="{{ route('reservation.destroy',$reservation->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm ms-auto mb-0" type="submit"><i class="fa fa-trash"></i> Fshij</button>
                </form>
                <div class="bug-table-item-options">
                <table>
                    <thead>
                    <tr>
                        <th colspan="2">Informatat:</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Data</td>
                            <td>{{ $reservation->date }}</td>
                        </tr>
                        <tr>
                            <td>Salla</td>
                            <td>{{ $reservation->venue->name }}</td>
                        </tr>
                        <tr>
                            <td>Numri i te ftuarve</td>
                            <td>{{ $reservation->number_of_guests}}</td>

                        </tr>
                        <tr>
                            <td>Klienti</td>
                            <td>{{ $reservation->client->name }}</td>
                        </tr>
                        <tr>
                            <td>Koha:</td>
                            <td>{{ $reservation->reservation_type_name}}</td>
                        </tr>
                        <tr>
                            <td>Pagesa Momentale:</td>
                            <td>{{ $reservation->current_payment}}€</td>
                        </tr>
                        <tr>
                            <td>Pagesa Totale:</td>
                            <td>{{ $reservation->total_payment}}€</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    <div class="vms_panel">
        <h5>Pagesat:</h5>
        @if(count($payments) > 0)
            <div class="table-responsive ">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th>ID</th>

                        <th>Vlera</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Data</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Pershkrimi</th>
                        <th width="40" class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>
                                {{$payment->id}}
                            </td>
                            <td>
                                {{$payment->value}}€
                            </td>
                            <td>
                                {{$payment->date}}
                            </td>
                            <td>
                                {{$payment->notes}}
                            </td>
                            <td>
                                {{$payment->description}}
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        @else
            <div class="hubers-empty-tab">
                <h5 class="text-center">Nuk ka Pagesa për këte rezervim.</h5>
            </div>
        @endif
    </div>
@endsection
