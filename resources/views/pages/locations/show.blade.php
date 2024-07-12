@extends('layouts.app')

@section('header')
   Shpenzimi : {{$location->id}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <div class="bug-table-item-options">
                    {{-- <form  method="POST" action="{{ route('expenses.destroy', ['id' => $expense->id]) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bug-table-item-option">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form> --}}
                    <a class="bug-table-item-option ml-2" href="{{route('locations.edit',['id'=>$location->id])}}">
                        <i class="fa fa-edit"></i>
                    </a>
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
                        <td>{{ $location->user->first_name}}</td>
                    </tr>
               

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

