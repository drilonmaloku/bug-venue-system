@extends('layouts.app')

@section('header')
    Raporti per periudhen: {{request('starting_date')}} / {{request('ending_date')}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <table>
                    <thead>
                        <tr>
                            <th colspan="2">Te dhenat:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Numri i ditëve:</td>
                            <td>{{ $reportsData['days_count'] }}</td>
                        </tr>
                        <tr>
                            <td>Klienta të rinj:</td>
                            <td>{{ $reportsData['clients_count'] }}</td>
                        </tr>
                        <tr>
                            <td>Numri i Rezervimeve:</td>
                            <td>{{ $reportsData['reservations_count'] }}</td>
                        </tr>
                        <tr>
                            <td>Numri i pagesave:</td>
                            <td>{{ $reportsData['payments_count'] }}</td>
                        </tr>
                        <tr>
                            <td>Vlera e pagesave:</td>
                            <td>{{ $reportsData['payments_sum'] }}€</td>
                        </tr>
                        <tr>
                            <td>Sallat me rezervime:</td>
                            <td>{{ $reportsData['venues_with_reservations_count'] }}/{{ $reportsData['total_venues'] }}</td>
                        </tr>
                        <tr>
                            <td>Shpenzimet Gjenerale:</td>
                            <td>{{ $reportsData['expenses_sum'] }}€</td>
                        </tr>
                        <tr>
                            <td>Shpenzimet për staf:</td>
                            <td>{{ $reportsData['staff_expenses'] }}€</td>
                        </tr>
                        <tr>
                            <td>Totali Zbritja:</td>
                            <td>{{ $reportsData['discount_sum'] }}€</td>
                        </tr>

                        <tr>
                            <td>Totali Sherbimi:</td>
                            <td>{{ $reportsData['service_sum'] }}€</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <h6><strong>Të dhënat sipas Sallav:</strong></h6>
                @foreach($reportsData['venues'] as $venueData)
                    <div class="vms_border_panel">
                        <h6><strong>{{$venueData['name']}}</strong></h6>
                        <hr>
                        <p>
                            Numri i Rezervimeve: {{$venueData['reservations_count']}}<br>
                            Pagesa të perfunduara: {{$venueData['current_payment_sum']}}€<br>
                            Pagesa në pritje: {{$venueData['payments_due']}}€<br>
                            Shpenzimet e staffit: {{$venueData['staff_expenses']}}€<br>
                            Totali i pagesave: {{$venueData['total_payment_sum']}}€


                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection