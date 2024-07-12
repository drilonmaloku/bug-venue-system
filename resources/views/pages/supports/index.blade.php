@extends('layouts.app')
@section('header')
    Support Tickets
@endsection
@section('header-actions')
    <a class="hubers-btn" href="{{ route('support-tickets.create') }}">Krijo Tiket</a>
@endsection
@section('content')
    <div class="vms_panel">

        @if (count($supportTickets) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Resolver</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Attachment</th>
                            <th>Status</th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supportTickets as $tickets)
                            <tr>
                                <td>
                                    {{ $tickets->user->username }}
                                </td>
                                <td>
                                    {{ $tickets->resolver_id }}

                                </td>
                                <td>
                                    {{ $tickets->title }}

                                </td>
                                <td>
                                    {{ $tickets->description }}

                                </td>
                                <td>
                                    {{ $tickets->attachment }}

                                </td>
                                <td>
                                    <span
                                        class="
                                            @if ($tickets->status == 1) bg-red
                                            @elseif ($tickets->status == 2)
                                                bg-blue
                                            @elseif ($tickets->status == 3)
                                                bg-green @endif
                                        ">
                                        @if ($tickets->status == 1)
                                            Pending
                                        @elseif ($tickets->status == 2)
                                            Open
                                        @elseif ($tickets->status == 3)
                                            Resolved
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="bug-table-item-options">
                                        <a class="bug-table-item-option"
                                            href="{{ route('support-tickets.view', ['id' => $tickets->id]) }}">
                                            <i class="fa fa-eye"></i>
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
                @if ($is_on_search)
                    <h5 class="text-center">Nuk ka tiketa sipas search</h5>
                @else
                    <h5 class="text-center">Nuk ka tiketa momentalisht</h5>
                @endif
            </div>
        @endif
    </div>
@endsection


<style>
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
