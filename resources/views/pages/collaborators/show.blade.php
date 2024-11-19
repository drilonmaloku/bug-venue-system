@extends('layouts.app')

@section('header')
   Bashkpuntoret : {{$collaborator->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">

                    <form action="{{ route('collaborators.destroy', $collaborator->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm ms-auto mb-0" type="submit"><i class="fa fa-trash" data-confirm-delete="true"></i> Fshij</button>
                    </form>
                    
                <div class="bug-table-item-options">
                    <a class="bug-table-item-option" href="{{route('collaborators.edit',['id'=>$collaborator->id])}}">
                        
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
                        <td>Emri</td>
                        <td>{{ $collaborator->name }}</td>
                    </tr>
                    <tr>
                        <td>Emaili</td>
                        <td>{{ $collaborator->email }}</td>
                    </tr>
                    <tr>
                        <td>Numri i telefonit</td>
                        <td>{{ $collaborator->phone_number }}</td>
                    </tr>
                    <tr>
                        <td>Lloji</td>
                        <td>{{ $collaborator->typeof }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
