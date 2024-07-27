@extends('layouts.app')

@section('header')
{{__('venue.title.reservation.venue')}}: {{$venue->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                @if(count($venue->reservations) == 0)
                    <form action="{{ route('venues.destroy', $venue->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm ms-auto mb-0" type="submit"><i class="fa fa-trash"></i> Fshij</button>
                    </form>
                @endif
                <table>
                    <thead>
                    <tr>
                        <th colspan="2">{{__('venue.title.information')}}:</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{__('venues.table.name')}}</td>
                        <td>{{ $venue->name }}</td>
                    </tr>
                    <tr>
                        <td>{{__('venues.table.description')}}</td>
                        <td>{{ $venue->description }} </td>
                    </tr>
                    <tr>
                        <td>{{__('venues.table.capacity')}}</td>
                        <td>{{ $venue->capacity }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="vms_panel">
        <h5>{{__('venue.title.reservation')}}:</h5>
        @if(count($venue->reservations) > 0)
            <div class="export-options" onclick="exportOptions.export('/reservations/export','reservations-export.xlsx')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
            <div class="table-responsive ">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th width="40">
                            <input class="main-checkbox bug-checkbox-input" type="checkbox">
                        </th>
                        <th class="">{{__('venue.title.reservation.date')}}</th>
                        <th>
                            {{__('venue.title.reservation.venue')}}</th>
                        <th>
                            {{__('venue.title.reservation.description')}}</th>
                        <th>
                            {{__('venue.title.reservation.payment')}}
                        </th>
                        <th>
                            {{__('venue.title.reservation.client')}}
                        </th>
                        <th width="40">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($venue->reservations as $reservation)
                        <tr>
                            <td>
                                <input class="table-checkbox bug-checkbox-input" type="checkbox" value="{{$reservation->id}}">
                            </td>
                            <td>
                                {{$reservation->date}}
                            </td>
                            <td>
                                {{$reservation->venue->name}}
                            </td>
                            <td>
                                {{$reservation->description}}
                            </td>
                            <td>
                                {{$reservation->current_payment}} $
                            </td>
                            <td>
                                {{$reservation->client->name}}
                            </td>
                            <td>
                                <div class="d-flex px-3">
                                    <a class="bug-table-item-option" href="{{route('reservations.view',['id'=>$reservation->id])}}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="hubers-empty-tab">
                <h5 class="text-center"> {{__('venue.table.not_found_without_search.reservation')}}</h5>
            </div>
        @endif
    </div>
@endsection
