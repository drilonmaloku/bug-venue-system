@extends('layouts.app')
@section('header')
    Perdoruesit
@endsection
@section('header-actions')
    <a class="hubers-btn" href="{{route('users.create')}}">Krijo</a>
@endsection
@section('content')
    <div class="vms_panel">
        <form class="filter-items" action="/users" method="GET" >
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
                    <div class="hubers-filter-group">
                        <label>Roli:</label>
                        <select class="hubers-select-input white medium" name="role" id="">
                            <option value="">Selekto</option>
                            <option @if(app('request')->input('role') == 1) selected @endif value="admin">Admin</option>
                            <option @if(app('request')->input('context') == 2) selected @endif value="super-admin">Super Admin</option>
                        </select>
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
                        <th>Username</th>
                        <th>Emri</th>
                        <th>Roli</th>
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
                                {{$user->first_name}} {{$user->last_name}}
                            </td>
                            <td>
                                {{ $user->getRoleNames()->first() ?? 'No role assigned' }}
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
            {{$users->links()}}
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
