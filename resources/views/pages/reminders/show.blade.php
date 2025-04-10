@extends('layouts.app')

@section('header')
   {{__('reminders.show.title')}} : {{$reminder->title}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <div class="bug-table-item-options">
                    <a class="bug-table-item-option mr-2" href="{{ route('reminders.edit', ['reservationId' => $reservation->id, 'id' => $reminder->id]) }}">
                        <i class="fa fa-edit"></i>
                    </a>
                    <button class="bug-table-item-option danger" data-toggle="modal" data-target="#deleteModal">
                        <i class="fa fa-trash"></i>
                    </button>
                    <a class="bug-table-item-option" href="{{ route('reservations.view', ['id' => $reservation->id]) }}">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </div>
                <div class="bug-table-item-options">
                    <table>
                        <thead>
                            <tr>
                                <th colspan="2">{{__('reminders.show.details')}}:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{__('reminders.form.title')}}</td>
                                <td>{{ $reminder->title }}</td>
                            </tr>
                            <tr>
                                <td>{{__('reminders.form.reminder_date')}}</td>
                                <td>{{ $reminder->reminder_date->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr>
                                <td>{{__('reminders.form.type')}}</td>
                                <td>
                                    <span class="badge {{ $reminder->type === 'in_app' ? 'badge-info' : ($reminder->type === 'email' ? 'badge-primary' : 'badge-success') }}">
                                        {{ $reminder->type }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>{{__('reminders.form.status')}}</td>
                                <td>
                                    <span class="badge {{ $reminder->is_sent ? 'badge-success' : 'badge-warning' }}">
                                        {{ $reminder->is_sent ? __('reminders.status.sent') : __('reminders.status.pending') }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>{{__('reminders.form.message')}}</td>
                                <td>{!! nl2br(e($reminder->message)) !!}</td>
                            </tr>
                            <tr>
                                <td>{{__('reminders.form.created_by')}}</td>
                                <td>{{ $reminder->creator->name }}</td>
                            </tr>
                            <tr>
                                <td>{{__('reminders.form.created_at')}}</td>
                                <td>{{ $reminder->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            @if($reminder->is_sent)
                                <tr>
                                    <td>{{__('reminders.form.sent_at')}}</td>
                                    <td>{{ $reminder->sent_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">{{__('reminders.confirm_delete')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{__('reminders.confirm_delete')}}
                </div>
                <div class="modal-footer">
                    <form action="{{ route('reminders.destroy', ['reservationId' => $reservation->id, 'id' => $reminder->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('general.cancel_btn')}}</button>
                        <button type="submit" class="btn btn-danger">{{__('general.delete_btn')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 