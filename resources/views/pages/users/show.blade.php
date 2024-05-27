@extends('layouts.app')

@section('header')
   User : {{$user->username}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <div class="bug-table-item-options">
                    <form action="{{ route('users.destroy', ['user' => $user->id]) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <span type="submit" class="bug-table-item-option">
                            <i class="fa fa-trash"></i>
                        </span>
                    </form>
                    <a class="ml-2 bug-table-item-option" href="{{route('users.edit',['id'=>$user->id])}}">
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
                            <td>{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <td>Emri</td>
                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        </tr>
                        <tr>
                            <td>Roli:</td>
                            <td>{{ $user->getRoleNames()->first() ?? 'No role assigned' }}</td>
                        </tr>
                        <tr>
                            <td>Emaili</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td>Telefoni</td>
                            <td>{{ $user->phone }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
