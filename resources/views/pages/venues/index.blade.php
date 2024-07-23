@extends('layouts.app')
@section('header')
{{__('venue.title.single')}}
@endsection
@section('header-actions')
    <a class="hubers-btn" href="{{route('venues.create')}}">{{__('venues.forms.create_btn')}}</a>
@endsection
@section('content')
    <div class="vms_panel">
        <div class="export-options" onclick="exportOptions.export('/venues/export')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        </div>
        @if(count($venues) > 0)
            <div class="table-responsive p-0 mt-3">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th width="40">
                            <input class="main-checkbox bug-checkbox-input" type="checkbox">
                        </th>
                        <th>{{__('venues.table.name')}}</th>
                        <th>{{__('venues.table.description')}}</th>
                        <th>{{__('venues.table.capacity')}}</th>
                        <th width="40"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($venues as $venue)
                        <tr>
                            <td>
                                <input class="table-checkbox bug-checkbox-input" type="checkbox" value="{{$venue->id}}">
                            </td>
                            <td>
                                <a class="bug-link" href="{{route('venues.view',['id'=>$venue->id])}}" class="mb-0">{{$venue->name}}</a>
                            </td>
                            <td>{{$venue->description}}</td>
                            <td>{{$venue->capacity}}</td>
                            <td>
                                <div class="bug-table-item-options">
                                    <a class="bug-table-item-option" href="{{route('venues.edit',['id'=>$venue->id])}}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a class="bug-table-item-option" href="{{route('venues.view',['id'=>$venue->id])}}">
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
                    <h5 class="text-center">{{__('venue.table.not_found_with_search')}}</h5>
                @else
                    <h5 class="text-center">{{__('venue.table.not_found_without_search')}}</h5>
                @endif
            </div>
        @endif
    </div>
@endsection
