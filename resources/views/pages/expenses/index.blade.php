@extends('layouts.app')
@section('header')
    {{__('expenses.main.title')}}
@endsection
@section('header-actions')
    <a class="hubers-btn" href="{{route('expenses.create')}}">{{__('general.create_btn')}}</a>
@endsection
@section('content')
    <div class="vms_panel">
        <form class="filter-items" action="/expenses" method="GET" >
            <div class="filter-options">
                <div class="huber-filter-btn  @if ($is_on_search) active @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter vue-feather__content"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    <p>{{__('general.filter_btn')}}</p>
                    <span class="huber-filter-btn-arrow">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L12 15L18 9" stroke="black" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
                <div class="export-options" onclick="exportOptions.export('/expenses/export','expenses-export.xlsx')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
            </div>
            <div class="hubers-filter-options @if ($is_on_search) active @endif">
                <div class="hubers-filter-list-options">
                    <div class="hubers-filter-group">
                        <label>{{__('expenses.table.search')}}:</label>
                        <input placeholder="{{__('expenses.table.search')}}" class="hubers-text-input white medium" type="text" name="search" value="{{ request('search') }}">
                    </div>
                    <div class="hubers-filter-group">
                        <label>{{__('expenses.filter.start_date')}}:</label>
                        <input class="hubers-text-input white medium" type="date" name="start_date" value="{{old('date',app('request')->input('start_date'))}}">
                    </div>
                    <div class="hubers-filter-group">
                        <label>{{__('expenses.filter.end_date')}}:</label>
                        <input class="hubers-text-input white medium" type="date" name="end_date" value="{{old('date',app('request')->input('end_date'))}}">
                    </div>
                </div>
                <div class="hubers-filter-list-actions">
                    <button type="submit" class="hubers-btn mr-2">{{__('general.filter_btn')}}</button>
                    <a href="/expenses" class="hubers-btn inverse">{{__('general.filter_reset_btn')}}</a>
                </div>
            </div>
        </form>
        @if(count($expenses) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th width="40">
                            <input class="main-checkbox bug-checkbox-input" type="checkbox">
                        </th>
                        <th>{{__('expenses.table.user')}}</th>
                        <th>{{__('expenses.table.date')}}</th>
                        <th>{{__('expenses.table.description')}}</th>
                        <th>{{__('expenses.table.amount')}}</th>
                        {{-- <th></th> --}}
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($expenses as $expense)
                        <tr>
                            <td>
                                <input class="table-checkbox bug-checkbox-input" type="checkbox" value="{{$expense->id}}">
                            </td>
                            <td>
                                {{$expense->user->first_name }}
                            </td>
                            <td>
                                {{$expense->date}}
                            </td>
                            <td>
                                {{$expense->description}}
                            </td>
                            <td>
                                {{$expense->amount}}
                            </td>
                            <td>
                                <div class="bug-table-item-options">
                                    <a class="bug-table-item-option" href="{{route('expenses.view',['id'=>$expense->id])}}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a class="bug-table-item-option" href="{{route('expenses.edit',['id'=>$expense->id])}}" >
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
                    <h5 class="text-center">{{__('expenses.table.not_found_with_search')}}</h5>
                    @else
                    <h5 class="text-center">{{__('expenses.table.not_found_without_search')}}</h5>
                @endif
            </div>
        @endif
    </div>
@endsection
