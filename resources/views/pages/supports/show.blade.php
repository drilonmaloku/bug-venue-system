@extends('layouts.app')

@section('header')
    Tiketa : {{ $ticket->title }}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-12">
                <div class="bug-table-item-options">
                    @if ($ticket->status == 1)
                        <form action="{{ route('ticket-status-open.update', ['id' => $ticket->id]) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="buttons">
                                Hape Tiketen
                            </button>
                        </form>
                    @elseif ($ticket->status == 2)
                        <form action="{{ route('ticket-status.update', ['id' => $ticket->id]) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="buttons">
                                Mbylle Tiketen
                            </button>
                        </form>
                    @endif
                </div>
                <table>
                    <thead>
                        <tr>
                            <th colspan="2">Informatat:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>User</td>
                            <td>{{ $ticket->user->username }}</td>
                        </tr>
                        <tr>
                            <td>Resolver</td>
                            <td>{{ $ticket->user->username }}</td>

                        </tr>
                        <tr>
                            <td>Title</td>
                            <td>{{ $ticket->title }}</td>

                        </tr>
                        <tr>
                            <td>Description</td>
                            <td>{{ $ticket->description }}</td>
                        </tr>
                        <tr>
                            <td>Attachment</td>
                            <td>
                                @if ($filePath)
                                    <a href="{{ asset($filePath) }}" target="_blank">
                                        <img src="{{ asset($filePath) }}" alt="Attachment"
                                            style="max-width: 100px; height: auto;">
                                    </a>
                                @else
                                    No attachment
                                @endif
                            </td>
                        </tr>

                        
                        <tr>
                            <td>Status</td>
                            <td>
                                <span class="
            @if ($ticket->status == 1)
                bg-red
            @elseif ($ticket->status == 2)
                bg-blue
            @elseif ($ticket->status == 3)
                bg-green
            @endif
        ">
            @if ($ticket->status == 1)
                Pending
            @elseif ($ticket->status == 2)
                Open
            @elseif ($ticket->status == 3)
                Resolved
            @endif
        </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="vms_panel">
        <h4>Fusha e komenteve</h4>
        <div id="commentForm" class="hubers-panel-bordered" style="display: block;">
            @foreach ($ticket->comments as $comment)
                <div class="comments-box">
                    <div class="comment-header">
                        <p class="comment-user"><strong>Komentuesi:</strong> {{ $comment->user_id }}</p>

                    </div>
                    <div class="comment-body">


                        <p class="comment-text"><strong>Komenti:</strong> {{ $comment->comment }}</p>
                        <p class="comment-text"><strong>Fotografija:</strong>
                            @if ($comment->attachment)
                                <a href="{{ asset($comment->attachment) }}" target="_blank">
                                    <img src="{{ asset($comment->attachment) }}" alt="Attachment"
                                        style="max-width: 100px; height: auto;">
                                </a>
                            @else
                                No attachment
                            @endif
                        </p>
                    </div>
                    <p class="comment-date"><strong>Data e krijimit:</strong> {{ $comment->created_at }}</p>
                </div>
            @endforeach
            <form action="{{ route('support-tickets.comment.store', ['id' => $ticket->id]) }}" method="POST"
                method="POST">
                @csrf
                <div class="commentArea mt-4">
                    <h5 for="comment">Shto koment per kete tiket:</h5>
                    <textarea name="comment" id="comment" required placeholder="Shto komentin per rezervim ketu"></textarea>
                </div>
                <div class="file-upload">
                    <input type="file" name="attachment">
                </div>
                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="hubers-btn" id="submitBtn">Shto</button>
                </div>
            </form>
        </div>

    </div>
@endsection



<style>
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        resize: vertical;
        min-height: 100px;
    }

    .comment {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
    }

    .comment p {
        margin: 0;
    }

    .file-upload input[type=file] {
        display: block;
        margin-top: 5px;
    }

    .comments-box {
        border: 1px solid rgb(197, 195, 195);
        width: 500px;
        border-radius: 10px;
        padding: 10px;
        margin: 5px;
    }

    .buttons {
        border: none;
        background-color: #0063b2;
        padding: 10px;
        border-radius: 5px;
        color: white;
    }
  
    .bg-red {
    background-color: rgb(146, 215, 255);
    padding: 5px;
    border-radius: 5px;
    color: #fff;
}

.bg-blue {
    background-color: orange;
    padding: 5px;
    border-radius: 5px;
    color: #fff;
}

.bg-green {
    background-color: green;
    padding: 5px;
    border-radius: 5px;
    color: #fff;
}

</style>
