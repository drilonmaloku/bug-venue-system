@extends('layouts.app')

@section('header')
    {{__('reports.report.title')}}: {{request('starting_date')}} / {{request('ending_date')}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <table>
                    <thead>
                        <tr>
                            <th colspan="2">{{__('reports.report.information')}}:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{__('reports.report.days_count')}}:</td>
                            <td>{{ $reportsData['days_count'] }}</td>
                        </tr>
                        <tr>
                            <td>{{__('reports.report.new_clients')}}:</td>
                            <td>{{ $reportsData['clients_count'] }}</td>
                        </tr>
                        <tr>
                            <td>{{__('reports.report.reservation_count')}}:</td>
                            <td>{{ $reportsData['reservations_count'] }}</td>
                        </tr>
                        <tr>
                            <td>{{__('reports.report.payment_count')}}:</td>
                            <td>{{ $reportsData['payments_count'] }}</td>
                        </tr>
                        <tr>
                            <td>{{__('reports.report.payment_amount')}}:</td>
                            <td>{{ $reportsData['payments_sum'] }}€</td>
                        </tr>
                        <tr>
                            <td>{{__('reports.report.venues_with_reservations')}}:</td>
                            <td>{{ $reportsData['venues_with_reservations_count'] }}/{{ $reportsData['total_venues'] }}</td>
                        </tr>
                        <tr>
                            <td>{{__('reports.report.overall_expenses')}}:</td>
                            <td>{{ $reportsData['expenses_sum'] }}€</td>
                        </tr>
                        <tr>
                            <td>{{__('reports.report.staff_expenses')}}:</td>
                            <td>{{ $reportsData['staff_expenses'] }}€</td>
                        </tr>
                        <tr>
                            <td>{{__('reports.report.total_discount')}}:</td>
                            <td>{{ $reportsData['discount_sum'] }}€</td>
                        </tr>

                        <tr>
                            <td>{{__('reports.report.total_services')}}:</td>
                            <td>{{ $reportsData['service_sum'] }}€</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <h6><strong>{{__('reports.report.information_by_venue')}}:</strong></h6>
                @foreach($reportsData['venues'] as $venueData)
                    <div class="vms_border_panel">
                        <h6><strong>{{$venueData['name']}}</strong></h6>
                        <hr>
                        <p>
                            {{__('reports.report.information_by_venue.reservation_count')}}: {{$venueData['reservations_count']}}<br>
                            {{__('reports.report.information_by_venue.payments_finished')}}: {{$venueData['current_payment_sum']}}€<br>
                            {{__('reports.report.information_by_venue.payments_coming')}}: {{$venueData['payments_due']}}€<br>
                            {{__('reports.report.information_by_venue.overall_expenses')}}: {{$venueData['staff_expenses']}}€<br>
                            {{__('reports.report.information_by_venue.total_payment')}}: {{$venueData['total_payment_sum']}}€
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection