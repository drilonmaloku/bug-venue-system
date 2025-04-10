@extends('layouts.app')
@section('header')
    {{__('reminders.title')}}
@endsection
@section('header-actions')
    <a class="hubers-btn" href="{{route('reminders.create', ['reservationId' => $reservation->id])}}">{{__('reminders.create_btn')}}</a>
@endsection
@section('content')
    <div class="vms_panel">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>{{__('reminders.reservation_title')}}: {{$reservation->title}}</h4>
            <a href="{{route('reservations.view', ['id' => $reservation->id])}}" class="hubers-btn inverse">
                <i class="fa fa-arrow-left mr-2"></i> {{__('general.back_to_reservation')}}
            </a>
        </div>

        @if(count($reminders) > 0)
            <div class="table-responsive">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th width="40">
                            <input class="main-checkbox bug-checkbox-input" type="checkbox">
                        </th>
                        <th>{{__('reminders.table.title')}}</th>
                        <th>{{__('reminders.table.message')}}</th>
                        <th>{{__('reminders.table.reminder_date')}}</th>
                        <th>{{__('reminders.table.type')}}</th>
                        <th>{{__('reminders.table.status')}}</th>
                        <th>{{__('reminders.table.created_by')}}</th>
                        <th>{{__('reminders.table.created_at')}}</th>
                        <th width="40"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reminders as $reminder)
                        <tr>
                            <td>
                                <input class="table-checkbox bug-checkbox-input" type="checkbox" value="{{$reminder->id}}">
                            </td>
                            <td>
                                {{$reminder->title}}
                            </td>
                            <td>
                                {{Str::limit($reminder->message, 50)}}
                            </td>
                            <td>
                                {{$reminder->reminder_date->format('Y-m-d H:i')}}
                            </td>
                            <td>
                                <span class="badge {{ $reminder->type === 'in_app' ? 'badge-info' : ($reminder->type === 'email' ? 'badge-primary' : 'badge-success') }}">
                                    {{$reminder->type}}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $reminder->is_sent ? 'badge-success' : 'badge-warning' }}">
                                    {{$reminder->is_sent ? __('reminders.status.sent') : __('reminders.status.pending')}}
                                </span>
                            </td>
                            <td>
                                {{$reminder->creator->name}}
                            </td>
                            <td>
                                {{$reminder->created_at->format('Y-m-d H:i')}}
                            </td>
                            <td>
                                <div class="bug-table-item-options">
                                    <a class="bug-table-item-option" href="{{route('reminders.edit', ['reservationId' => $reservation->id, 'id' => $reminder->id])}}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a class="bug-table-item-option" href="#" onclick="deleteReminder({{$reminder->id}})">
                                        <i class="fa fa-trash"></i>
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
                <h5 class="text-center">{{__('reminders.table.not_found')}}</h5>
            </div>
        @endif
    </div>

    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('scripts')
<script>
    function deleteReminder(id) {
        if (confirm('{{__('reminders.confirm_delete')}}')) {
            const form = document.getElementById('delete-form');
            form.action = "{{ route('reminders.destroy', ['reservationId' => $reservation->id, 'id' => ':id']) }}".replace(':id', id);
            form.submit();
        }
    }
</script>
@endsection 