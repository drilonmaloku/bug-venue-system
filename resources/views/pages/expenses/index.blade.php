@extends('layouts.app')
@section('header')
    Shpenzimet
@endsection
@section('header-actions')
    <a class="hubers-btn" href="{{route('expenses.create')}}">Krijo</a>
@endsection
@section('content')
    <div class="vms_panel">
        <form action="/expenses" method="GET" >
            <div class="hubers-filter-options">
                <div class="hubers-filter-list-options">
                    <div class="hubers-filter-group">
                        <label>Search:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="text" name="search" value="{{ request('search') }}">
                    </div>
                    <div class="hubers-filter-group">
                        <label>Data:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="date" name="date" value="{{old('date',app('request')->input('date'))}}">
                    </div>
                </div>
               
                <div class="hubers-filter-list-actions">
                    <button type="submit" class="hubers-btn mr-2">Filtro</button>
                    <a href="/expenses" class="hubers-btn inverse">Reset</a>
                </div>
            </div>
        </form>
        @if(count($expenses) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th>Data</th>
                        <th>Pershkrimi</th>
                        <th>Shuma</th>
                        {{-- <th></th> --}}
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($expenses as $expense)
                        <tr>
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
                    <h5 class="text-center">Nuk ka shpenzime sipas search</h5>
                    @else
                    <h5 class="text-center">Nuk ka shpenzime momentalisht</h5>
                @endif
            </div>
        @endif
    </div>
@endsection
