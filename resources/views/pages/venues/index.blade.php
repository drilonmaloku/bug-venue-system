@extends('layouts.app')
@section('header')
    Sallat

@endsection
@section('header-actions')
    <a class="hubers-btn" href="{{route('venues.create')}}">Krijo</a>
@endsection
@section('content')
    <div class="vms_panel">
        @if(count($venues) > 0)
            <div class="bug-table-wrapper">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Emri</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Përshkrimi</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Kapaciteti</th>
                        <th width="40" class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($venues as $venue)
                        <tr>
                            <td>
                                <a class="bug-link" href="{{route('venues.view',['id'=>$venue->id])}}" class="mb-0">{{$venue->name}}</a>
                            </td>
                            <td>{{$venue->description}}</td>
                            <td>{{$venue->capacity}}</td>
                            <td>
                                <div class="bug-table-item-options">
                                    <a class="bug-table-item-option" href="{{route('venues.edit',['id'=>$venue->id])}}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a class="bug-table-item-option" href="{{route('venues.view',['id'=>$venue->id])}}">
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
            <h6 class="text-center">Nuk ka salla momentalisht</h6>
        @endif
    </div>
@endsection
