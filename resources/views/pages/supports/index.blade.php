@extends('layouts.app')
@section('header')
    Support Tickets
@endsection
@section('header-actions')
    <a class="hubers-btn" href="{{route('support-tickets.create')}}">Krijo Tiket</a>
@endsection
@section('content')
<div class="vms_panel">
    
        @if(count($supportTickets) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th>User</th>
                        <th>Resolver</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Attachment</th>
                        <th>Status</th>
                        <th></th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($supportTickets as $tickets)
                        <tr>
                            <td>
                                {{$tickets->user_id}}
                            </td>
                            <td>
                                {{$tickets->resolver_id}}

                            </td>
                            <td>
                                {{$tickets->title}}

                            </td>
                            <td>
                                {{$tickets->description}}

                            </td>
                            <td>
                                {{$tickets->attachment}}

                            </td>
                            <td>
                                {{$tickets->status}}

                            </td>
                            <td>
                                <div class="bug-table-item-options">
                                    <a class="bug-table-item-option" href="{{route('support-tickets.view',['id'=>$tickets->id])}}">
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
                @if ($is_on_search)
                    <h5 class="text-center">Nuk ka  tiketa sipas search</h5>
                    @else
                    <h5 class="text-center">Nuk ka tiketa momentalisht</h5>
                @endif
            </div>
        @endif
    </div>
@endsection
