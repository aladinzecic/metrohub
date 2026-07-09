@extends('driver.driverview')

@section('main-content')
<div class="fault-full">
    <p class="date-time">{{ now()->format('l, F j · H:i') }}</p>
    <h1 class="fault-title">Report Fault</h1>

    @if(session('success'))
        <div class="fault-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="fault-error">{{ $errors->first() }}</div>
    @endif

    <div class="fault-layout">

        <div class="fault-card">
            <div class="fault-card-top">
                <h2>Report a fault</h2>
                @if($vehicle)
                    <span class="fault-badge-gray">{{ $vehicle->name }} · {{ $vehicle->plate }}</span>
                @endif
            </div>

            @if($vehicle)
                <form action="{{ route('driver.reportfault.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

                    <div class="fault-form-group">
                        <label class="fault-label">Fault type</label>
                        <select name="type" class="fault-select" required>
                            <option value="">Select type...</option>
                            <option value="brakes">Brakes</option>
                            <option value="engine">Engine</option>
                            <option value="ac">Air conditioning</option>
                            <option value="validator">Ticket validator</option>
                            <option value="doors">Doors</option>
                            <option value="seats">Seats</option>
                            <option value="lights">Lights</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="fault-form-group">
                        <label class="fault-label">Severity</label>
                        <div class="fault-severity-grid">
                            <div class="fault-sev-card" onclick="setSev(this, 'minor')" data-sev="minor">
                                <p class="fault-sev-name">Minor</p>
                                <p class="fault-sev-sub">Can wait</p>
                            </div>
                            <div class="fault-sev-card" onclick="setSev(this, 'moderate')" data-sev="moderate">
                                <p class="fault-sev-name">Moderate</p>
                                <p class="fault-sev-sub">Schedule soon</p>
                            </div>
                            <div class="fault-sev-card" onclick="setSev(this, 'critical')" data-sev="critical">
                                <p class="fault-sev-name">Critical</p>
                                <p class="fault-sev-sub">Immediate</p>
                            </div>
                        </div>
                        <input type="hidden" name="severity" id="severity-input" value="">
                    </div>

                    <div class="fault-form-group">
                        <label class="fault-label">Description</label>
                        <textarea name="description" class="fault-textarea" placeholder="Describe the fault in detail — what happened, when, how severe it looks..." required></textarea>
                    </div>

                    <div class="fault-warning" id="critical-warning" style="display:none;">
                        ⚠ Critical faults will immediately notify the dispatcher and may pull the vehicle from service.
                    </div>

                    <button type="submit" class="fault-submit-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:16px;height:16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        Submit fault report
                    </button>
                </form>
            @else
                <div class="fault-empty">
                    No vehicle assigned for today. Contact your dispatcher.
                </div>
            @endif
        </div>

        <div class="fault-card">
            <div class="fault-card-top">
                <h2>Recent reports</h2>
            </div>

            @forelse($recentFaults as $fault)
                <div class="fault-recent-item">
                    <div class="fault-recent-info">
                        <p class="fault-recent-name">{{ $fault->vehicle->name }} — {{ ucfirst($fault->type) }}</p>
                        <p class="fault-recent-desc">{{ Str::limit($fault->description, 60) }}</p>
                        <p class="fault-recent-time">{{ $fault->reported_at->diffForHumans() }}</p>
                    </div>
                    @if($fault->severity === 'critical')
                        <span class="fault-badge-red">Critical</span>
                    @elseif($fault->severity === 'moderate')
                        <span class="fault-badge-amber">Moderate</span>
                    @else
                        <span class="fault-badge-gray">Minor</span>
                    @endif
                </div>
                @if(!$loop->last)<hr class="fault-hr">@endif
            @empty
                <div class="fault-empty">No recent fault reports.</div>
            @endforelse
        </div>

    </div>
</div>

<script>
function setSev(el, level) {
    document.querySelectorAll('.fault-sev-card').forEach(c => {
        c.classList.remove('sev-minor', 'sev-moderate', 'sev-critical');
    });
    el.classList.add('sev-' + level);
    document.getElementById('severity-input').value = level;
    document.getElementById('critical-warning').style.display = level === 'critical' ? 'block' : 'none';
}
</script>
@endsection