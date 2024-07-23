@extends('layouts.app')

@section('header')
{{__('users.profile.title')}} : {{$user->first_name}} {{$user->last_name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <div class="bug-table-item-options">
                    <a class="bug-table-item-option" href="{{route('profile.edit')}}">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a class="bug-table-item-option" href="{{route('profile.password-update')}}">
                        <i class="fa fa-lock"></i>
                    </a>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th colspan="2">{{__('users.single.info')}}:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{__('users.table.username')}}</td>
                            <td>{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <td>{{__('users.table.name')}}</td>
                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        </tr>
                        <tr>
                            <td>{{__('users.table.role')}}:</td>
                            <td>{{ $user->getRoleNames()->first() ?? 'No role assigned' }}</td>
                        </tr>
                        <tr>
                            <td>{{__('users.table.email')}}</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td>{{__('users.table.phone_number')}}</td>
                            <td>{{ $user->phone }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
