@extends('layouts.app')

@section('header')
    Tiketa : {{ $ticket->title }}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">

            <div class="col-md-12">
                <div class="d-flex justify-content-end"> <a class="hubers-btn">Mbylle Tiketen</a>
                </div>
                <div class="bug-table-item-options">

                    {{-- <form  method="POST"  style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bug-table-item-option">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form> --}}

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
                            <td>{{ $ticket->user_id }}</td>
                        </tr>
                        <tr>
                            <td>Resolver</td>
                            <td>{{ $ticket->user_id }}</td>

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
                            <td>{{ $ticket->attachment }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>{{ $ticket->status }}</td>
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
                    <p class="comment-user"><strong>Komentuesi:</strong> {{$comment->user_id}}</p>
                    
                </div>
                <div class="comment-body">
                    <p class="comment-text"><strong>Komenti:</strong> {{$comment->comment}}</p>
                    <p class="comment-text"><strong>Fotografija:</strong> {{$comment->attachment}}</p>

                </div>
                <p class="comment-date"><strong>Data e krijimit:</strong> {{$comment->created_at}}</p>
            </div>
            @endforeach
            <form  action="{{ route('support-tickets.comment.store', ['id' => $ticket->id]) }}" method="POST" method="POST">
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

    .comments-box{
        border: 1px solid rgb(197, 195, 195);
        width: 500px;
        border-radius: 10px;
        padding: 10px;
        margin: 5px;
    }
</style>
