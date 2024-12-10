@extends('layouts.app')

@section('header')
{{'Kitchen Dashboard'}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <div class="px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Menu Contents</h2>
                    <div class="space-y-2">
                        @forelse($menuContents as $menuContent)
                            <div class="flex items-center bg-gray-100 p-3 rounded-md">
                                <span class="text-gray-600">{{ $menuContent }}</span>
                            </div>
                        @empty
                           <div class="hubers-empty-tab">
                    <h5 class="text-center">Nuk ka rezervim</h5>
                    @endforelse
                    </div>
                    </div>

                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Number of Guests</h2>
                    <div class="space-y-2">
                        @forelse($guests as $guestCount)
                            <div class="flex items-center justify-between bg-gray-100 p-3 rounded-md">
                                <span class="text-gray-600">Table</span>
                                <span class="font-semibold text-indigo-600">{{ $guestCount }} guests</span>
                            </div>
                        @empty
                        
                    @endforelse
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection