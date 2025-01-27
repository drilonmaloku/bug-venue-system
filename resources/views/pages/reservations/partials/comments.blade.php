<div class="vms_panel">
    <div class="d-flex align-items-center justify-content-between">
        <h5>{{__('reservations.view.comments')}}:</h5>
        <a class="btn hubers-btn" data-toggle="modal" data-target="#reservationModalComment">{{__('reservations.view.add_comment')}}</a>
    </div>
    <div>
        @if (count($reservation->comments) > 0)
            <table class="hubers-table mt-4">
                <thead>
                <tr>
                    <th>{{__('"reservations.view.comments.table.user"')}}</th>
                    <th>{{__('"reservations.view.comments.table.comment"')}}</th>
                    <th>{{__('"reservations.view.comments.table.date"')}}</th>
                    <th width="60"></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($reservation->comments as $comment)
                    <tr>
                        <td>{{ $comment->user->first_name }}</td>
                        <td>
                            <p>{{ $comment->comment }}</p>
                        </td>
                        <td>{{ $comment->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if ($comment->user_id == auth()->id())
                                <div class="hubers-item-options">
                                    <form action="{{ route('reservations.comment.delete', $comment->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm " type="submit"><i
                                                    class="fa fa-trash"></i></button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="hubers-empty-tab">
                <h5 class="text-center">{{__('reservations.view.no_comments')}}</h5>
            </div>
        @endif
    </div>
</div>
<div class="modal fade" id="reservationModalComment" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservationModalCommentLabel">{{__('reservations.view.add_comment')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form role="form"  action="{{ route('reservations.comment.store', ['id' => $reservation->id]) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="invoice_description" class="form-control-label">{{__("reservations.view.form.comment")}}</label>
                                <textarea id="comment" required class="bug-text-input" name="comment"></textarea>
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
