@extends('layouts.app')
@section('header')
    Aktiviteti
@endsection
@section('content')
    <div class="vms_panel">
        @if(count($logs) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Perdoruesi</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Data</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Mesazhi</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Konteksti</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>
                                {{$log->user->firstname}} {{$log->user->lastname}}
                            </td>
                            <td>
                                {{$log->created_at->format('d-m-Y H:i')}}
                            </td>
                            <td>
                                {{$log->message}}
                            </td>
                            <td>
                                {{$log->getContext($log)}}
                            </td>
                            <td>
                                <div class="bug-table-item-options">
                                    <a class="bug-table-item-option" href="{{route('logs.view',['id'=>$log->id])}}">
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
            <h6 class="text-center">Nuk ka aktivitet momentalisht</h6>
        @endif
    </div>
@endsection
