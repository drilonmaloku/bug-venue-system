@extends('layouts.app')
@section('header')
    Klientat
@endsection
@section('content')
    <div class="vms_panel">
        <form class="filter-items" action="/clients" method="GET" >
            <div class="filter-options">
                <div class="huber-filter-btn  @if ($is_on_search) active @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter vue-feather__content"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    <p>Filtro</p>
                    <span class="huber-filter-btn-arrow">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L12 15L18 9" stroke="black" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="hubers-filter-options @if ($is_on_search) active @endif">
                <div class="hubers-filter-list-options">
                    <div class="hubers-filter-group">
                        <label>Search:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="text" name="search" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="hubers-filter-list-actions">
                    <button type="submit" class="hubers-btn mr-2">Filtro</button>
                    <a href="/clients" class="hubers-btn inverse">Reset</a>
                </div>
            </div>
        </form>
        @if(count($clients) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th>Emri</th>
                        <th>Emaili</th>
                        <th>Telefoni</th>
                        <th>Telefoni Opsional</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>
                                {{$client->name}}
                            </td>
                            <td>
                                {{$client->email}}
                            </td>
                            <td>
                                {{$client->phone_number}}
                            </td>
                            <td>
                                {{$client->additional_phone_number}}
                            </td>
                            <td>
                                <div class="bug-table-item-options">
                                    <a class="bug-table-item-option" href="{{route('clients.view',['id'=>$client->id])}}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a class="bug-table-item-option" href="{{route('clients.edit',['id'=>$client->id])}}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{$clients->links()}}
            </div>
        @else
            <div class="hubers-empty-tab">
                @if ($is_on_search)
                    <h5 class="text-center">Nuk ka klienta sipas search</h5>
                    @else
                    <h5 class="text-center">Nuk ka klienta momentalisht</h5>
                @endif
            </div>
        @endif
    </div>
@endsection
