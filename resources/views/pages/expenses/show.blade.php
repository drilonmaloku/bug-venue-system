@extends('layouts.app')

@section('header')
{{__('expenses.title.single')}} : {{$expense->id}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <div class="bug-table-item-options">
                    <form  method="POST" action="{{ route('expenses.destroy', ['id' => $expense->id]) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bug-table-item-option">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                    <a class="bug-table-item-option ml-2" href="{{route('expenses.edit',['id'=>$expense->id])}}">
                        <i class="fa fa-edit"></i>
                    </a>
                </div>
                <table>
                    <thead>
                    <tr>
                        <th colspan="2">{{__('expenses.title.single.info')}}:</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{__('expenses.table.user')}}</td>
                            <td>{{ $expense->user->first_name }}</td>
                        </tr>
                        <tr>
                            <td>{{__('expenses.table.date')}}</td>
                            <td>{{ $expense->date }}</td>
                        </tr>
                        <tr>
                            <td>{{__('expenses.table.description')}}</td>
                            <td>{{ $expense->description }}</td>
                        </tr>
                        <tr>
                            <td>{{__('expenses.table.amount')}}</td>
                            <td>{{ $expense->amount }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

