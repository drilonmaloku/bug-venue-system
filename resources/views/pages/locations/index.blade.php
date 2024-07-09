@extends('layouts.app')
@section('header')
    Location
@endsection
@section('header-actions')
    <a class="hubers-btn" href="{{route('locations.create')}}">Krijo</a>
@endsection
@section('content')
    <div class="vms_panel">
     
        @if(count($locations) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>UUID</th>
                        <th>Name</th>
                        <th>Owner</th>
                        {{-- <th></th> --}}
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($locations as $location)
                        <tr>
                            <td>
                                {{$location->id }}
                            </td>
                            <td>
                                {{$location->uuid}}
                            </td>
                            <td>
                                {{$location->name}}
                            </td>
                            <td>
                                {{$location->user->first_name}}
                            </td>
                            <td>
                                <div class="bug-table-item-options">
                                    <a class="bug-table-item-option" href="{{route('locations.view',['id'=>$location->id])}}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a class="bug-table-item-option" href="{{route('locations.edit',['id'=>$location->id])}}" >
                                        <i class="fa fa-edit"></i>
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
                    <h5 class="text-center">Nuk ka location sipas search</h5>
                    @else
                    <h5 class="text-center">Nuk ka location momentalisht</h5>
                @endif
            </div>
        @endif
    </div>
@endsection
