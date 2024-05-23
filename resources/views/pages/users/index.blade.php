@extends('layouts.app')
@section('header')
    Perdoruesit
@endsection
@section('header-actions')
    <a class="hubers-btn" href="{{route('users.create')}}">Krijo</a>
@endsection
@section('content')
    <div class="vms_panel">
        <form action="/users" method="GET" >
            <div class="hubers-filter-options">
                <div class="hubers-filter-list-options">
                    <div class="hubers-filter-group">
                        <label>Search:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="text" name="search">
                    </div>
                </div>
                <div class="hubers-filter-list-actions">
                    <button type="submit" class="hubers-btn mr-2">Filtro</button>
                    <a href="/users" class="hubers-btn inverse">Reset</a>
                </div>
            </div>
        </form>
        @if(count($users) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th>UserName</th>
                        <th>Emri</th>
                        <th>Mbiemri</th>
                        <th>Emaili</th>
                        <th>Telefoni</th>

                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                {{$user->username}}
                            </td>
                            <td>
                                {{$user->first_name}}
                            </td>
                            <td>
                                {{$user->last_name}}
                            </td>
                            <td>
                                {{$user->email}}
                            </td>
                            <td>
                                {{$user->phone}}
                            </td>
                         
                            <td>
                                <div class="bug-table-item-options">
                                    <a class="bug-table-item-option" href="{{route('users.view',['id'=>$user->id])}}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a class="bug-table-item-option" href="{{route('users.edit',['id'=>$user->id])}}">
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
                    <h5 class="text-center">Nuk ka perdorues sipas search</h5>
                    @else
                    <h5 class="text-center">Nuk ka perdorues momentalisht</h5>
                @endif
            </div>
        @endif
    </div>
@endsection
