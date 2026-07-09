@extends('passenger.mainview')

@section('main-content')
<div class="tickets-full">
    <p class="date-time">{{ now()->format('l, F j · H:i') }}</p>
    <h1 class="tickets-title">My Tickets</h1>

    @if(session('success'))
        <div class="tickets-success">{{ session('success') }}</div>
    @endif

    <div class="tickets-stats">
        <div class="tickets-stat">
            <h3>ACTIVE</h3>
            <h1>{{ $activeTickets->count() }}</h1>
            <p>Valid tickets</p>
        </div>
        <div class="tickets-stat">
            <h3>TOTAL</h3>
            <h1>{{ $tickets->count() }}</h1>
            <p>All time</p>
        </div>
        <div class="tickets-stat">
            <h3>EXPIRED</h3>
            <h1>{{ $expiredTickets->count() }}</h1>
            <p>Past tickets</p>
        </div>
    </div>

    <div class="tickets-layout">

        <div class="tickets-card">
            <div class="tickets-card-top">
                <h2>Buy a ticket</h2>
            </div>

            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf

                <div class="tickets-form-group">
                    <label class="tickets-label">Ticket type</label>
                    <div class="tickets-type-grid">
                        <div class="tickets-type-card {{ old('type') == 'single' ? 'active' : '' }}" onclick="setType(this, 'single')">
                            <p class="tickets-type-name">Single</p>
                            <p class="tickets-type-sub">One ride</p>
                            <p class="tickets-type-price">89 RSD</p>
                        </div>
                        <div class="tickets-type-card {{ old('type') == 'day' ? 'active' : '' }}" onclick="setType(this, 'day')">
                            <p class="tickets-type-name">Day pass</p>
                            <p class="tickets-type-sub">24 hours</p>
                            <p class="tickets-type-price">250 RSD</p>
                        </div>
                        <div class="tickets-type-card {{ old('type') == 'weekly' ? 'active' : '' }}" onclick="setType(this, 'weekly')">
                            <p class="tickets-type-name">Weekly</p>
                            <p class="tickets-type-sub">7 days</p>
                            <p class="tickets-type-price">800 RSD</p>
                        </div>
                        <div class="tickets-type-card {{ old('type') == 'monthly' ? 'active' : '' }}" onclick="setType(this, 'monthly')">
                            <p class="tickets-type-name">Monthly</p>
                            <p class="tickets-type-sub">30 days</p>
                            <p class="tickets-type-price">2400 RSD</p>
                        </div>
                    </div>
                    <input type="hidden" name="type" id="type-input" value="{{ old('type') }}">
                </div>

                <div class="tickets-form-group">
                    <label class="tickets-label">Zone</label>
                    <div class="tickets-zone-grid">
                        <div class="tickets-zone-card {{ old('zone') == 'A' ? 'active' : '' }}" onclick="setZone(this, 'A')">
                            <p class="tickets-zone-name">Zone A</p>
                            <p class="tickets-zone-sub">City center</p>
                        </div>
                        <div class="tickets-zone-card {{ old('zone') == 'B' ? 'active' : '' }}" onclick="setZone(this, 'B')">
                            <p class="tickets-zone-name">Zone B</p>
                            <p class="tickets-zone-sub">Suburbs</p>
                        </div>
                        <div class="tickets-zone-card {{ old('zone') == 'A+B' ? 'active' : '' }}" onclick="setZone(this, 'A+B')">
                            <p class="tickets-zone-name">Zone A+B</p>
                            <p class="tickets-zone-sub">All zones</p>
                        </div>
                    </div>
                    <input type="hidden" name="zone" id="zone-input" value="{{ old('zone') }}">
                </div>

                <div class="tickets-price-preview" id="price-preview" style="display:none;">
                    <p>Total price</p>
                    <h2 id="price-amount">—</h2>
                </div>

                <button type="submit" class="tickets-buy-btn" id="buy-btn" disabled>
                    Purchase ticket
                </button>
            </form>
        </div>

        <div class="tickets-card">
            <div class="tickets-card-top">
                <h2>My tickets</h2>
            </div>

            @forelse($tickets as $ticket)
            <div class="tickets-item">
                <div class="tickets-item-left">
                    <div class="tickets-item-type">{{ ucfirst($ticket->type) }}</div>
                    <div class="tickets-item-info">
                        <p class="tickets-item-zone">Zone {{ $ticket->zone }}</p>
                        <p class="tickets-item-date">
                            {{ $ticket->valid_from->format('d M') }} –
                            {{ $ticket->valid_until->format('d M Y') }}
                        </p>
                        <p class="tickets-item-qr">{{ $ticket->qr_code }}</p>
                    </div>
                </div>
                <div class="tickets-item-right">
                    <p class="tickets-item-price">{{ number_format($ticket->price, 0) }} RSD</p>
                    @if($ticket->status === 'active')
                        <span class="tickets-badge-green">Active</span>
                    @elseif($ticket->status === 'expired')
                        <span class="tickets-badge-gray">Expired</span>
                    @else
                        <span class="tickets-badge-red">Cancelled</span>
                    @endif
                </div>
            </div>
            @if(!$loop->last)<hr class="tickets-hr">@endif
            @empty
            <div class="tickets-empty">No tickets yet. Buy your first ticket!</div>
            @endforelse
        </div>

    </div>
</div>

<script>
const prices = {
    single:  { A: 89,   B: 89,   'A+B': 139  },
    day:     { A: 250,  B: 250,  'A+B': 390  },
    weekly:  { A: 800,  B: 800,  'A+B': 1200 },
    monthly: { A: 2400, B: 2400, 'A+B': 3600 },
};

let selectedType = null;
let selectedZone = null;

function setType(el, type) {
    document.querySelectorAll('.tickets-type-card').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    selectedType = type;
    document.getElementById('type-input').value = type;
    updatePrice();
}

function setZone(el, zone) {
    document.querySelectorAll('.tickets-zone-card').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    selectedZone = zone;
    document.getElementById('zone-input').value = zone;
    updatePrice();
}

function updatePrice() {
    const preview = document.getElementById('price-preview');
    const amount  = document.getElementById('price-amount');
    const btn     = document.getElementById('buy-btn');

    if (selectedType && selectedZone) {
        const price = prices[selectedType][selectedZone];
        preview.style.display = 'flex';
        amount.textContent = price + ' RSD';
        btn.disabled = false;
        btn.style.opacity = '1';
    } else {
        preview.style.display = 'none';
        btn.disabled = true;
        btn.style.opacity = '0.5';
    }
}
</script>
@endsection