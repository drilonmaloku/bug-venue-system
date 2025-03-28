@extends('layouts.app')

@section('header')
   Seating Plan : {{$seatingPlan->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                
                <div class="bug-table-item-options">
                    <a class="bug-table-item-option mr-2" href="{{route('seating-plans.edit',['id'=>$seatingPlan->id])}}">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form action="{{ route('seating-plans.destroy', $seatingPlan->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm ms-auto mb-0" type="submit"><i class="fa fa-trash" data-confirm-delete="true"></i></button>
                    </form>
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
                            <td>{{ $seatingPlan->name }}</td>
                        </tr>
                        <tr>
                            <td>Pershkrimi</td>
                            <td>{{ $seatingPlan->description }}</td>
                        </tr>
                        <tr>
                            <td>Foto</td>
                            <td><img style="max-width: 80%" src="{{ $seatingPlan->image_url }}" alt=""></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection 