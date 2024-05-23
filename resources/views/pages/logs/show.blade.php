@extends('layouts.app')

@section('header')
    Log: LGID - {{$log->id}}
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
                            <td>Mesazhi</td>
                            <td>{{ $log->message }}</td>
                        </tr>
                        <tr>
                            <td>Perdoruesi</td>
                            <td>{{ $log->user->firstanme }} {{ $log->user->lastname }}</td>
                        </tr>
                        <tr>
                            <td>Konteksti</td>
                            <td>{{ $log->getContext($log) }}</td>
                        </tr>

                        <tr>
                            <td>Te dhenat paraprake:</td>
                            <td>{{ $log->previous_data }}</td>
                        </tr>
                        <tr>
                            <td>Te dhenat e perditsuara: </td>
                            <td>{{ $log->updated_data }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
