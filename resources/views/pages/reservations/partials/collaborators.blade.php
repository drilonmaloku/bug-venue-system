<div class="vms_panel">
    <div class="d-flex align-items-center justify-content-between">
        <h5>Collaborators</h5>
        <a class="btn hubers-btn" data-toggle="modal" data-target="#reservationCollaborators">Add Collaborator</a>
    </div>
    <div>
        @if ($reservation->collaborators->isNotEmpty())
            <table class="hubers-table mt-4">
                <thead>
                <tr>
                    <th>Name</th>
                    <th width="60"></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($reservation->collaborators as $collaborator)
                    <tr>
                        <td>{{ $collaborator->name }}</td>
                        <td>
                            <div class="hubers-item-options mt-3">
                                <form action="{{ route('reservations.delete-collaborator', ['reservationId' => $reservation->id, 'collaboratorId' => $collaborator->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="hubers-empty-tab">
                <h5 class="text-center">{{__('no_collaborators') }}</h5>
            </div>
        @endif
    </div>
</div>
<div class="modal fade" id="reservationCollaborators" tabindex="-1" role="dialog"
     aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservationModalMembersLabel">Add Collaborator</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <form role="form"  action="{{ route('reservations.addCollaborator', ['reservationId' => $reservation->id]) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Select Collaborator*</label>

                                <select required id="collaboratorid" class="bug-text-input" name="collaborator_id">
                                    <option value="">Select Collaborator</option>
                                    @foreach($collaborators as $collaborator)

                                        @if(!$reservation->collaborators->contains('id', $collaborator->id))
                                            <option value="{{$collaborator->id}}">{{$collaborator->name}}</option>
                                        @endif
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