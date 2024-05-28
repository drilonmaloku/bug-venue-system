@extends('layouts.app')
@section('header')
    Backups
@endsection
@section('content')
    <div class="flex justify-between items-center min-h-[44px]">

        <form action="{{ route('common.db-backup') }}" method="get">
            <button type="submit" class="hubers-btn">Download Backup</button>
        </form>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class=" overflow-hidden shadow-xl sm:rounded-lg p-8">
                @if (count($backups) > 0)
                    @foreach ($backups as $backup)
                        <div class="hubers_dashboard_card flex justify-between mb-3">
                            {{ $backup }}
                            <div>
                                <a class="table_btn_option info" href="{{ route('common.db-backup-download', $backup) }}"
                                    download>
                                    <span>Download</span>
                                </a>
                                <a class="table_btn_option info" href="{{ route('common.db-backup-delete', $backup) }}">
                                    <span>Delete</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="hubers-empty-tab">
                        <h5 class="text-center">No Backups found.</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
