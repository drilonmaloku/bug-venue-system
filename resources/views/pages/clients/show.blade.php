@extends('layouts.app')

@section('header')
   Klienti : {{$client->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <div class="bug-table-item-options">
                    <a class="bug-table-item-option" href="{{route('clients.edit',['id'=>$client->id])}}">
                        <i class="fa fa-edit"></i>
                    </a>
                </div>
                <table>
                    <thead>
                    <tr>
                        <th colspan="2">Informatat:</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Emri</td>
                        <td>{{ $client->name }}</td>
                    </tr>
                    <tr>
                        <td>Emaili</td>
                        <td>{{ $client->email }}</td>
                    </tr>
                    <tr>
                        <td>Telefoni</td>
                        <td>{{ $client->phone_number }}</td>
                    </tr>

                    <tr>
                        <td>Telefoni Opsional</td>
                        <td>{{ $client->additional_phone_number }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
