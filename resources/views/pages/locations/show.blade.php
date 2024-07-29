@extends('layouts.app')

@section('header')
    Lokacioni : {{ $location->name }}
@endsection
@section('content')
    <div class="vms_panel">
        @if ($location->deactivated_at !== null)

        <div class="alert alert-danger">
            <p class="text-center mb-0"><strong>Lokacioni eshte i deaktivizuar.</strong></p>
        </div>
    @endif

        <div class="row">
            <div class="col-md-8">
                <div class="bug-table-item-options">
                    <a class="bug-table-item-option ml-2" href="{{ route('locations.edit', ['id' => $location->id]) }}">
                        <i class="fa fa-edit"></i>
                    </a>
                    @if ($location->deactivated_at === null)
                        <a class="bug-table-item-option ml-2" href="{{ route('locations.deactive', ['id' => $location->id]) }}">
                            <i class="fa fa-power-off"></i> 
                        </a>
                    @else
                        <a class="bug-table-item-option ml-2" href="{{ route('locations.active', ['id' => $location->id]) }}">
                            <i class="fa fa-power-off"></i>
                        </a>
                    @endif
                    </div>
                <table>
                    <thead>
                        <tr>
                            <th colspan="2">Informatat:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Id</td>
                            <td>{{ $location->id }}</td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td>{{ $location->name }}</td>
                        </tr>
                        <tr>
                            <td>Owner</td>
                            <td>{{ $location->user->first_name }}</td>
                        </tr>


                    </tbody>
                </table>

                <div>
                    <p>Perdoruesit e Lokacionit</p>
                    <table>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Emri</th>
                                <th>Emaili</th>
                                <th>Telefoni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($location->users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
