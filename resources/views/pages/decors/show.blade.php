@extends('layouts.app')

@section('header')
   Dekor : {{$decor->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">

                    <form action="{{ route('decors.destroy', $decor->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm ms-auto mb-0" type="submit"><i class="fa fa-trash" data-confirm-delete="true"></i> Fshij</button>
                    </form>

                <div class="bug-table-item-options">
                    <a class="bug-table-item-option" href="{{route('decors.edit',['id'=>$decor->id])}}">
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
                            <td>{{ $decor->name }}</td>
                        </tr>
                        <tr>
                            <td>Pershkrimi</td>
                            <td>{{ $decor->description }}</td>
                        </tr>
                        <tr>
                            <td>Foto</td>
                            <td><img style="max-width: 80%" src="{{ $decor->image_url }}" alt=""></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
