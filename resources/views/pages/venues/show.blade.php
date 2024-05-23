@extends('layouts.app')

@section('header')
    Salla: {{$venue->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <table>
                    <thead>
                    <tr>
                        <th colspan="2">Informacioni:</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>EMRI</td>
                        <td>{{ $venue->name }}</td>
                    </tr>
                    <tr>
                        <td>Përshkrimi</td>
                        <td>{{ $venue->description }} </td>
                    </tr>
                    <tr>
                        <td>Kapaciteti</td>
                        <td>{{ $venue->capacity }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
