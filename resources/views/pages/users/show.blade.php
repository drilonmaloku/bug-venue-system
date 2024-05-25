@extends('layouts.app')

@section('header')
   User : {{$users->username}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <div class="bug-table-item-options">
                    <a class="bug-table-item-option" href="{{route('users.edit',['id'=>$users->id])}}">
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
                            <td>Username</td>
                            <td>{{ $users->username }}</td>
                        </tr>
                    <tr>
                        <td>Emri</td>
                        <td>{{ $users->first_name }}</td>
                    </tr>
                   <tr>
                        <td>Mbiemri</td>
                        <td>{{ $users->last_name }}</td>
                    </tr>
                 

                    <tr>
                        <td>Emaili</td>
                        <td>{{ $users->email }}</td>
                    </tr> 
                    <tr>
                        <td>Telefoni</td>
                        <td>{{ $users->phone }}</td>
                    </tr> 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
