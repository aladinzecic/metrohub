@extends('controller.controllerview')

@section('main-content')
<div class="ctrl-full">
    <p class="date-time">{{ now()->format('l, F j · H:i') }}</p>
    <h1 class="ctrl-title">Tickets</h1>

    <div class="ctrl-stats">
        <div class="ctrl-stat">
            <h3>ACTIVE</h3>
            <h1>{{ $activeCount }}</h1>
            <p>Valid tickets</p>
        </div>
        <div class="ctrl-stat">
            <h3>EXPIRED</h3>
            <h1>{{ $expiredCount }}</h1>
            <p>Expired tickets</p>
        </div>
        <div class="ctrl-stat">
            <h3>CANCELLED</h3>
            <h1>{{ $cancelledCount }}</h1>
            <p>Cancelled tickets</p>
        </div>
        <div class="ctrl-stat">
            <h3>TOTAL</h3>
            <h1>{{ $tickets->count() }}</h1>
            <p>All tickets</p>
        </div>
    </div>

    <div class="ctrl-two">

        {{-- Scan forma --}}
        <div class="ctrl-card">
            <div class="ctrl-card-top">
                <h2>Validate ticket</h2>
            </div>

            <form action="{{ route('controller.tickets.validate') }}" method="POST">
                @csrf
                <div style="margin-bottom:14px;">
                    <label class="ctrl-label">QR code / Ticket ID</label>
                    <input type="text" name="qr_code" class="ctrl-input" placeholder="e.g. TICKET-ABC123XYZ" required>
                </div>
                <button type="submit" class="ctrl-submit-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:16px;height:16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5Z" />
                    </svg>
                    Validate
                </button>
            </form>

            @if(session('scan_result'))
                @php $result = session('scan_result'); @endphp
                <div class="ctrl-scan-result {{ $result['status'] === 'valid' ? 'ctrl-result-valid' : 'ctrl-result-invalid' }}" style="margin-top:16px;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                        <div class="ctrl-result-icon {{ $result['status'] === 'valid' ? 'ctrl-icon-valid' : 'ctrl-icon-invalid' }}">
                            @if($result['status'] === 'valid')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p style="font-size:14px;font-weight:600;">{{ $result['status'] === 'valid' ? 'Valid' : 'Invalid' }}</p>
                            <p style="font-size:12px;">{{ $result['message'] }}</p>
                        </div>
                    </div>

                    @if(isset($result['ticket']))
                        @php $t = $result['ticket']; @endphp
                        <div class="ctrl-hr" style="margin:10px 0;"></div>
                        <div class="ctrl-row"><span class="ctrl-rl">Ticket ID</span><span class="ctrl-rv">{{ $t->qr_code }}</span></div>
                        <div class="ctrl-row"><span class="ctrl-rl">Type</span><span class="ctrl-rv">{{ ucfirst($t->type) }}</span></div>
                        <div class="ctrl-row"><span class="ctrl-rl">Zone</span><span class="ctrl-rv">{{ $t->zone }}</span></div>
                        <div class="ctrl-row"><span class="ctrl-rl">Valid until</span><span class="ctrl-rv">{{ $t->valid_until->format('d M Y') }}</span></div>
                        <div class="ctrl-row"><span class="ctrl-rl">Passenger</span><span class="ctrl-rv">{{ $t->user->name }}</span></div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Lista karata --}}
        <div class="ctrl-card">
            <div class="ctrl-card-top">
                <h2>All tickets</h2>
            </div>

            @forelse($tickets->take(10) as $ticket)
            <div class="ctrl-val-item">
                <div class="ctrl-val-info">
                    <p class="ctrl-val-name">{{ ucfirst($ticket->type) }} · Zone {{ $ticket->zone }}</p>
                    <p class="ctrl-val-desc">
                        {{ $ticket->user->name }} ·
                        {{ $ticket->valid_from->format('d M') }} –
                        {{ $ticket->valid_until->format('d M Y') }}
                    </p>
                    <p style="font-size:10px;color:#999993;">{{ $ticket->qr_code }}</p>
                </div>
                @if($ticket->status === 'active')
                    <span class="ctrl-badge-green">Active</span>
                @elseif($ticket->status === 'expired')
                    <span class="ctrl-badge-gray">Expired</span>
                @else
                    <span class="ctrl-badge-red">Cancelled</span>
                @endif
            </div>
            @if(!$loop->last)<hr class="ctrl-hr">@endif
            @empty
            <div class="ctrl-empty">No tickets found.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection