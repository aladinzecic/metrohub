@extends('mechanic.mechanicview')

@section('main-content')
<div class="wo-full">
    <p class="date-time">{{ now()->format('l, F j · H:i') }}</p>
    <h1 class="wo-title">Work Orders</h1>

    @if(session('success'))
        <div class="wo-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="wo-error">{{ session('error') }}</div>
    @endif

    <div class="wo-stats">
        <div class="wo-stat">
            <h3>AVAILABLE</h3>
            <h1>{{ $availableFaults->count() }}</h1>
            <p>Open faults</p>
        </div>
        <div class="wo-stat">
            <h3>MY ACTIVE</h3>
            <h1>{{ $myFaults->count() }}</h1>
            <p>In progress</p>
        </div>
        <div class="wo-stat">
            <h3>COMPLETED</h3>
            <h1>{{ $completedFaults->count() }}</h1>
            <p>Recently resolved</p>
        </div>
        <div class="wo-stat">
            <h3>CRITICAL</h3>
            <h1>{{ $availableFaults->where('severity', 'critical')->count() }}</h1>
            <p>Need immediate action</p>
        </div>
    </div>

    {{-- Dostupni kvarovi --}}
    <div class="wo-card">
        <div class="wo-card-top">
            <h2>Available faults</h2>
            <span class="wo-badge-amber">{{ $availableFaults->count() }} open</span>
        </div>

        @forelse($availableFaults as $fault)
        <div class="wo-item">
            <div class="wo-item-icon" style="background-color:{{ $fault->severity === 'critical' ? '#fef2f2' : ($fault->severity === 'moderate' ? '#fffbeb' : '#F5F2ED') }};">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="{{ $fault->severity === 'critical' ? '#991b1b' : ($fault->severity === 'moderate' ? '#92400e' : '#555550') }}" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
            <div class="wo-item-info">
                <p class="wo-item-title">{{ $fault->vehicle->name }} — {{ ucfirst($fault->type) }}</p>
                <p class="wo-item-desc">{{ Str::limit($fault->description, 80) }} · Reported by {{ $fault->reporter->name }} · {{ $fault->reported_at->diffForHumans() }}</p>
            </div>
            <div class="wo-item-meta">
                @if($fault->severity === 'critical')
                    <span class="wo-badge-red">Critical</span>
                @elseif($fault->severity === 'moderate')
                    <span class="wo-badge-amber">Moderate</span>
                @else
                    <span class="wo-badge-gray">Minor</span>
                @endif
                <form action="{{ route('mechanic.faults.accept', $fault->id) }}" method="POST" style="margin-top:6px;">
                    @csrf
                    <button type="submit" class="wo-btn-start">Accept</button>
                </form>
            </div>
        </div>
        @if(!$loop->last)<hr class="wo-hr">@endif
        @empty
        <div class="wo-empty">No available faults at the moment.</div>
        @endforelse
    </div>

    {{-- Moji aktivni kvarovi --}}
    <div class="wo-card" style="margin-top:2vh;">
        <div class="wo-card-top">
            <h2>My active orders</h2>
            <span class="wo-badge-blue">{{ $myFaults->count() }} active</span>
        </div>

        @forelse($myFaults as $fault)
        <div class="wo-item">
            <div class="wo-item-icon" style="background-color:#fffbeb;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#92400e" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l5.654-4.654m5.292-5.292 4.654-5.654a2.548 2.548 0 0 1 3.586 3.586L12.43 8.906" />
                </svg>
            </div>
            <div class="wo-item-info">
                <p class="wo-item-title">{{ $fault->vehicle->name }} — {{ ucfirst($fault->type) }}</p>
                <p class="wo-item-desc">{{ Str::limit($fault->description, 80) }} · {{ $fault->reported_at->diffForHumans() }}</p>
            </div>
            <div class="wo-item-meta">
                <span class="wo-badge-amber">In progress</span>
                <form action="{{ route('mechanic.faults.complete', $fault->id) }}" method="POST" style="margin-top:6px;">
                    @csrf
                    <button type="submit" class="wo-btn-complete">✓ Complete</button>
                </form>
            </div>
        </div>
        @if(!$loop->last)<hr class="wo-hr">@endif
        @empty
        <div class="wo-empty">No active orders.</div>
        @endforelse
    </div>

    {{-- Završeni kvarovi --}}
    <div class="wo-card" style="margin-top:2vh;">
        <div class="wo-card-top">
            <h2>Recently completed</h2>
        </div>

        @forelse($completedFaults as $fault)
        <div class="wo-item">
            <div class="wo-item-icon" style="background-color:#f0fdf4;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#15803d" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div class="wo-item-info">
                <p class="wo-item-title">{{ $fault->vehicle->name }} — {{ ucfirst($fault->type) }}</p>
                <p class="wo-item-desc">Resolved {{ $fault->resolved_at->diffForHumans() }}</p>
            </div>
            <div class="wo-item-meta">
                <span class="wo-badge-green">Resolved</span>
            </div>
        </div>
        @if(!$loop->last)<hr class="wo-hr">@endif
        @empty
        <div class="wo-empty">No completed orders yet.</div>
        @endforelse
    </div>

</div>
@endsection