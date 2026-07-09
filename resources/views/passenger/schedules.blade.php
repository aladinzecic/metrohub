@extends('passenger.mainview')

@section('main-content')
<div class="schedules-full">
    <p class="date-time">{{ now()->format('l, F j · H:i') }}</p>
    <h1 class="schedules-title">Schedules</h1>

    <div class="schedules-stats">
        <div class="schedules-stat">
            <h3>ACTIVE LINES</h3>
            <h1>{{ $lines->count() }}</h1>
            <p>In service today</p>
        </div>
        <div class="schedules-stat">
            <h3>STATIONS</h3>
            <h1>{{ $stations->count() }}</h1>
            <p>Total stations</p>
        </div>
        <div class="schedules-stat">
            <h3>BUS LINES</h3>
            <h1>{{ $lines->where('type', 'bus')->count() }}</h1>
            <p>Active bus lines</p>
        </div>
        <div class="schedules-stat">
            <h3>TRAM LINES</h3>
            <h1>{{ $lines->where('type', 'tram')->count() }}</h1>
            <p>Active tram lines</p>
        </div>
    </div>

    <div class="schedules-layout">

        <div class="schedules-card">
            <div class="schedules-card-top">
                <h2>All lines</h2>
                <div style="display:flex;gap:8px;">
                    <button class="schedules-filter active" onclick="filterLines(this, 'all')">All</button>
                    <button class="schedules-filter" onclick="filterLines(this, 'bus')">Bus</button>
                    <button class="schedules-filter" onclick="filterLines(this, 'tram')">Tram</button>
                </div>
            </div>

            <div id="lines-list">
                @forelse($lines as $line)
                <div class="schedules-line-item" data-type="{{ $line->type }}" onclick="showLine({{ $line->id }}, '{{ $line->code }}', '{{ $line->name }}', '{{ $line->type }}')">
                    <div class="schedules-line-num {{ $line->type === 'tram' ? 'schedules-line-tram' : '' }}">
                        {{ $line->code }}
                    </div>
                    <div class="schedules-line-info">
                        <p class="schedules-line-name">{{ $line->name }}</p>
                        <p class="schedules-line-type">{{ ucfirst($line->type) }}</p>
                    </div>
                    <span class="schedules-badge-green">Active</span>
                </div>
                @if(!$loop->last)<hr class="schedules-hr">@endif
                @empty
                <div class="schedules-empty">No active lines.</div>
                @endforelse
            </div>
        </div>

        <div class="schedules-card" id="line-detail" style="display:none;">
            <div class="schedules-card-top">
                <h2 id="detail-title">—</h2>
                <button onclick="closeDetail()" style="background:#F5F2ED;border:1px solid #e2ddd8;border-radius:6px;width:28px;height:28px;cursor:pointer;font-size:13px;">✕</button>
            </div>

            <div class="schedules-detail-info">
                <div class="schedules-detail-box">
                    <p class="schedules-detail-label">Line code</p>
                    <p class="schedules-detail-val" id="detail-code">—</p>
                </div>
                <div class="schedules-detail-box">
                    <p class="schedules-detail-label">Type</p>
                    <p class="schedules-detail-val" id="detail-type">—</p>
                </div>
                <div class="schedules-detail-box">
                    <p class="schedules-detail-label">Status</p>
                    <p class="schedules-detail-val" style="color:#15803d;">Active</p>
                </div>
            </div>

            <div style="font-size:12px;font-weight:500;color:#0a0a0a;margin-bottom:8px;margin-top:16px;">Stations</div>
            <div id="detail-stations">
                <div class="schedules-empty">Select a line to see stations.</div>
            </div>
        </div>

        <div class="schedules-card" id="line-placeholder">
            <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:200px;gap:12px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" style="width:40px;height:40px;color:#e2ddd8;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                </svg>
                <p style="font-size:13px;color:#999993;">Select a line to see details</p>
            </div>
        </div>

    </div>

    <div class="schedules-layout" style="margin-top:16px;">
        <div class="schedules-card">
            <div class="schedules-card-top">
                <h2>All stations</h2>
            </div>
            <div class="schedules-stations-grid">
                @foreach($stations as $station)
                <div class="schedules-station-item">
                    <div class="schedules-station-dot"></div>
                    <div class="schedules-station-info">
                        <p class="schedules-station-name">{{ $station->name }}</p>
                        <p class="schedules-station-zone">Zone {{ $station->zone }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

<script>
const lineStations = @json($lines->map(function($line) {
    return [
        'id'       => $line->id,
        'stations' => $line->stations ? $line->stations->map(function($s) {
            return ['name' => $s->name, 'zone' => $s->zone];
        }) : []
    ];
})->keyBy('id'));

function showLine(id, code, name, type) {
    document.getElementById('line-placeholder').style.display = 'none';
    document.getElementById('line-detail').style.display      = 'block';

    document.getElementById('detail-title').textContent = name;
    document.getElementById('detail-code').textContent  = code;
    document.getElementById('detail-type').textContent  = type.charAt(0).toUpperCase() + type.slice(1);

    const stationsDiv = document.getElementById('detail-stations');
    const data        = lineStations[id];

    if (!data || !data.stations || data.stations.length === 0) {
        stationsDiv.innerHTML = '<div class="schedules-empty">No stations data available.</div>';
        return;
    }

    stationsDiv.innerHTML = data.stations.map((s, i) => `
        <div style="display:flex;align-items:center;gap:10px;padding:8px 0;${i < data.stations.length - 1 ? 'border-bottom:1px solid #e2ddd8;' : ''}">
            <div style="width:8px;height:8px;border-radius:50%;background:#0a0a0a;flex-shrink:0;"></div>
            <p style="font-size:13px;color:#0a0a0a;flex:1;">${s.name}</p>
            <span style="font-size:11px;padding:2px 8px;border-radius:20px;background:#F5F2ED;color:#555550;border:1px solid #e2ddd8;">Zone ${s.zone}</span>
        </div>
    `).join('');
}

function closeDetail() {
    document.getElementById('line-detail').style.display      = 'none';
    document.getElementById('line-placeholder').style.display = 'block';
}

function filterLines(btn, type) {
    document.querySelectorAll('.schedules-filter').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('.schedules-line-item').forEach(item => {
        if (type === 'all' || item.dataset.type === type) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });

    document.querySelectorAll('.schedules-hr').forEach(hr => {
        hr.style.display = type === 'all' ? 'block' : 'none';
    });
}
</script>
@endsection