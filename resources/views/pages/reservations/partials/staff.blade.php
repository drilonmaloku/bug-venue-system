<div class="vms_panel">
    <div class="d-flex align-items-center justify-content-between">
        <h5>{{__('reservations.view.staff')}}:</h5>
        <a class="btn hubers-btn" data-toggle="modal" data-target="#reservationModalMembers">{{__('reservations.view.add_staff')}}</a>
    </div>
    <div>
        @if (count($reservation->reservationStaff) > 0)
            <table class="hubers-table mt-4">
                <thead>
                <tr>
                    <th>{{__('reservations.view.staff.table.name')}}</th>
                    <th width="60"></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($reservation->reservationStaff as $staff)
                    <tr>
                        <td>{{ $staff->user->first_name }} {{ $staff->user->last_name }}</td>

                        <td>
                            <div class="hubers-item-options mt-3">
                                <form action="{{ route('reservations.staff.delete', $staff->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm " type="submit"><i
                                                class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="hubers-empty-tab">
                <h5 class="text-center">{{__('reservations.view.no_staff')}}</h5>
            </div>
        @endif
    </div>
</div>
<div class="modal fade" id="reservationModalMembers" tabindex="-1" role="dialog"
     aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservationModalMembersLabel">{{__('reservations.view.add_staff')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <form role="form"  action="{{ route('reservations.addMember', ['reservationId' => $reservation->id]) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">{{__('reservations.view.select_staff')}}*</label>

                                <select required id="userid" class="bug-text-input" name="user_id">
                                    <option value="">{{__('reservations.view.select_staff')}}</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{__('general.save_btn')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('general.close_btn')}}</button>
                </div>
            </form>

        </div>
    </div>
</div>