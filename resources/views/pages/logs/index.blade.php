@extends('layouts.app')
@section('header')
    Aktiviteti
@endsection
@section('content')
    <div class="vms_panel">
        <form class="filter-items" action="/logs" method="GET" >
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
                        <label>Perdoruesi:</label>
                        <select class="hubers-select-input white medium" name="user" id="">
                            <option value="">Selekto</option>
                            @foreach($users as $user)
                                <option @if(app('request')->input('user') == $user->id) selected @endif  value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="hubers-filter-group">
                        <label>Konteksti:</label>
                        <select class="hubers-select-input white medium" name="context" id="">
                            <option value="">Selekto</option>
                            <option @if(app('request')->input('context') == 1) selected @endif value="1">Rezervimet</option>
                            <option @if(app('request')->input('context') == 2) selected @endif value="2">Pagesat</option>
                            <option @if(app('request')->input('context') == 3) selected @endif value="3">Klientat</option>
                            <option @if(app('request')->input('context') == 4) selected @endif value="4">Sallat</option>
                            <option @if(app('request')->input('context') == 5) selected @endif value="5">Userat</option>
                            <option @if(app('request')->input('context') == 6) selected @endif value="6">Raportet</option>
                            <option @if(app('request')->input('context') == 7) selected @endif value="7">Menu</option>

                        </select>
                    </div>
                    <div class="hubers-filter-group">
                        <label>Search:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="text" name="search" value="{{old('search',app('request')->input('search'))}}">
                    </div>
                    <div class="hubers-filter-group">
                        <label>Data:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="date" name="date" value="{{old('date',app('request')->input('data'))}}">
                    </div>
                </div>
                <div class="hubers-filter-list-actions">
                    <button type="submit" class="hubers-btn mr-2">Filtro</button>
                    <a href="/logs" class="hubers-btn inverse">Reset</a>
                </div>
            </div>
        </form>
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
                               <a class="hubers-link" href="{{route('users.view',['id'=>$log->user->id])}}"> {{$log->user->first_name}} {{$log->user->last_name}}</a>
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
            {{$logs->links()}}
        @else
            <div class="hubers-empty-tab">
                @if ($is_on_search)
                    <h5 class="text-center">Nuk ka aktivitet sipas search</h5>
                @else
                    <h5 class="text-center">Nuk ka aktivitet momentalisht</h5>
                @endif
            </div>
        @endif
    </div>
@endsection
