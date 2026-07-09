@extends('mechanic.mechanicview')

@section('main-content')
<div class="insp-full">
    <p class="date-time">{{ now()->format('l, F j · H:i') }}</p>
    <h1 class="insp-title">My Inspections</h1>

    <div class="insp-layout">

        {{-- LIJEVO — lista mojih kvarova --}}
        <div class="insp-list-panel">
            <div class="insp-list-header">
                <h2>My assigned faults</h2>
                <div class="insp-filters">
                    <button class="insp-filter active" onclick="setFilter(this,'all')">All</button>
                    <button class="insp-filter" onclick="setFilter(this,'in_progress')">In progress</button>
                    <button class="insp-filter" onclick="setFilter(this,'resolved')">Resolved</button>
                </div>
            </div>

            <div class="insp-list">
                @forelse($myFaults as $fault)
                <div class="insp-card active-card" data-status="in_progress" onclick="selectFault(this, {{ $fault->id }})">
                    <div class="insp-card-top">
                        <div>
                            <p class="insp-card-name">{{ $fault->vehicle->name }} — {{ ucfirst($fault->type) }}</p>
                            <p class="insp-card-sub">Reported by {{ $fault->reporter->name }} · {{ $fault->reported_at->diffForHumans() }}</p>
                        </div>
                        @if($fault->severity === 'critical')
                            <span class="insp-badge-red">Critical</span>
                        @elseif($fault->severity === 'moderate')
                            <span class="insp-badge-amber">Moderate</span>
                        @else
                            <span class="insp-badge-gray">Minor</span>
                        @endif
                    </div>
                    <div class="insp-card-meta">
                        <span class="insp-card-time">{{ $fault->vehicle->plate }}</span>
                        <span class="insp-badge-blue">In progress</span>
                    </div>
                </div>
                @endforeach

                @forelse($completedFaults as $fault)
                <div class="insp-card" data-status="resolved" onclick="selectFault(this, {{ $fault->id }})">
                    <div class="insp-card-top">
                        <div>
                            <p class="insp-card-name">{{ $fault->vehicle->name }} — {{ ucfirst($fault->type) }}</p>
                            <p class="insp-card-sub">Resolved {{ $fault->resolved_at->diffForHumans() }}</p>
                        </div>
                        <span class="insp-badge-green">Resolved</span>
                    </div>
                    <div class="insp-card-meta">
                        <span class="insp-card-time">{{ $fault->vehicle->plate }}</span>
                    </div>
                </div>
                @empty
                @endforelse

                @if($myFaults->isEmpty() && $completedFaults->isEmpty())
                    <div class="insp-empty">No assigned faults yet.</div>
                @endif
            </div>
        </div>

        {{-- DESNO — detalj --}}
        <div class="insp-detail-panel">
            <div class="insp-detail-header">
                <div>
                    <p class="insp-detail-eyebrow">Fault detail</p>
                    <h2 class="insp-detail-title" id="detail-title">Select a fault</h2>
                    <p class="insp-detail-sub" id="detail-sub">Click on a fault from the list</p>
                </div>
            </div>

            <div class="insp-detail-body" id="detail-body">
                <div style="display:flex;align-items:center;justify-content:center;height:200px;">
                    <p style="font-size:13px;color:#999993;">Select a fault to see details</p>
                </div>
            </div>

            <div class="insp-detail-footer" id="detail-footer" style="display:none;">
                <form id="complete-form" action="" method="POST" style="width:100%;">
                    @csrf
                    <button type="submit" class="insp-btn-submit" style="width:100%;justify-content:center;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:16px;height:16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Mark as resolved
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
const faults = {
    @foreach($myFaults as $fault)
    {{ $fault->id }}: {
        id: {{ $fault->id }},
        title: '{{ $fault->vehicle->name }} — {{ ucfirst($fault->type) }}',
        sub: '{{ ucfirst($fault->status) }} · {{ $fault->vehicle->plate }}',
        vehicle: '{{ $fault->vehicle->name }} — {{ $fault->vehicle->brand }} {{ $fault->vehicle->model }}',
        plate: '{{ $fault->vehicle->plate }}',
        type: '{{ ucfirst($fault->type) }}',
        severity: '{{ ucfirst($fault->severity) }}',
        description: '{{ addslashes($fault->description) }}',
        reporter: '{{ $fault->reporter->name }}',
        reported: '{{ $fault->reported_at->diffForHumans() }}',
        status: '{{ $fault->status }}',
    },
    @endforeach
    @foreach($completedFaults as $fault)
    {{ $fault->id }}: {
        id: {{ $fault->id }},
        title: '{{ $fault->vehicle->name }} — {{ ucfirst($fault->type) }}',
        sub: 'Resolved · {{ $fault->vehicle->plate }}',
        vehicle: '{{ $fault->vehicle->name }} — {{ $fault->vehicle->brand }} {{ $fault->vehicle->model }}',
        plate: '{{ $fault->vehicle->plate }}',
        type: '{{ ucfirst($fault->type) }}',
        severity: '{{ ucfirst($fault->severity) }}',
        description: '{{ addslashes($fault->description) }}',
        reporter: '{{ $fault->reporter->name }}',
        reported: '{{ $fault->reported_at->diffForHumans() }}',
        status: 'resolved',
    },
    @endforeach
};

function selectFault(el, id) {
    document.querySelectorAll('.insp-card').forEach(c => c.classList.remove('active'));
    el.classList.add('active');

    const f = faults[id];
    if (!f) return;

    document.getElementById('detail-title').textContent = f.title;
    document.getElementById('detail-sub').textContent   = f.sub;

    document.getElementById('detail-body').innerHTML = `
        <div class="insp-info-row">
            <div class="insp-info-box"><p class="insp-info-label">Vehicle</p><p class="insp-info-val">${f.vehicle}</p></div>
            <div class="insp-info-box"><p class="insp-info-label">Plate</p><p class="insp-info-val">${f.plate}</p></div>
            <div class="insp-info-box"><p class="insp-info-label">Type</p><p class="insp-info-val">${f.type}</p></div>
        </div>
        <div class="insp-info-row" style="margin-top:10px;">
            <div class="insp-info-box"><p class="insp-info-label">Severity</p><p class="insp-info-val">${f.severity}</p></div>
            <div class="insp-info-box"><p class="insp-info-label">Reported by</p><p class="insp-info-val">${f.reporter}</p></div>
            <div class="insp-info-box"><p class="insp-info-label">Reported</p><p class="insp-info-val">${f.reported}</p></div>
        </div>
        <div style="margin-top:14px;">
            <p class="insp-section-label">Description</p>
            <div style="background:#F5F2ED;border-radius:10px;padding:12px 14px;font-size:13px;color:#555550;line-height:1.6;margin-top:8px;">${f.description}</div>
        </div>
    `;

    const footer = document.getElementById('detail-footer');
    const form   = document.getElementById('complete-form');

    if (f.status !== 'resolved') {
        footer.style.display = 'block';
        form.action = `/mechanic/faults/${id}/complete`;
    } else {
        footer.style.display = 'none';
    }
}

function setFilter(btn, status) {
    document.querySelectorAll('.insp-filter').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('.insp-card').forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endsection