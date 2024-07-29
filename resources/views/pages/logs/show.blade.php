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
                            <th colspan="2">{{__('logs.table.information')}}:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{__('logs.table.user')}}</td>
                            <td>
                                <a class="hubers-link" href="{{route('users.view',['id'=>$log->user->id])}}"> {{$log->user->first_name}} {{$log->user->last_name}}</a>
                            </td>
                        </tr>
                        <tr>
                            <td>{{__('logs.table.date')}}</td>
                            <td> {{$log->created_at->format('d-m-Y H:i')}}</td>
                        </tr>
                        <tr>
                            <td>{{__('logs.table.message')}}</td>
                            <td>{{ $log->message }}</td>
                        </tr>

                        <tr>
                            <td>{{__('logs.table.context')}}</td>
                            <td>{{ $log->getContext($log) }}</td>
                        </tr>

                        <tr>
                            <td>{{__('logs.table.previous_data')}}:</td>
                            <td>{{ $log->previous_data }}</td>
                        </tr>
                        <tr>
                            <td>{{__('logs.table.updated_data')}}: </td>
                            <td>{{ $log->updated_data }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
