@extends('dispatcher.dispatcherview')

@section('main-content')
<div class="fleet-full">
<p class="date-time" id="dateTime"></p>
    <h1 class="fleet-title">Fleet</h1>

    <div class="fleet-stats">
        <div class="fleet-stat">
            <h3>IN SERVICE</h3>
            <h1>{{$vehicleCount}}</h1>
            <p>Active on routes</p>
        </div>
        <div class="fleet-stat">
            <h3>IN GARAGE</h3>
            <h1>{{$vehicleGarageCount}}</h1>
            <p>Maintenance / fault</p>
        </div>
        <div class="fleet-stat">
            <h3>RESERVE</h3>
            <h1>{{$vehicleReserveCount}}</h1>
            <p>Ready to deploy</p>
        </div>
        <div class="fleet-stat">
            <h3>CLEANING</h3>
            <h1>{{$vehicleCleaningCount}}</h1>
            <p>Scheduled</p>
        </div>
    </div>

    <div class="fleet-card">
        <div class="fleet-card-top">
            <h2>All vehicles</h2>
            <button class="fleet-add-btn" onclick="openModal()">+ Add vehicle</button>
        </div>
        @forelse($vehicles as $v)
        <div class="fleet-item">
            <div class="fleet-item-num">{{$v->number}}</div>
            <div class="fleet-item-info">
                <p class="fleet-item-name"><span style="text-transform: uppercase;">{{$v->type}} {{$v->number}} </span>— {{$v->brand}} {{$v->model}} {{$v->year}}</p>
                <p class="fleet-item-desc">{{$v->plate}} · {{$v->seat_count}} seats · {{$v->fuel_type}} · Reg. expires in {{$v->registration_expires_at}} days</p>
            </div>
            <span class="fleet-badge-green">{{$v->status}}</span>
        </div>
        @if(!$loop->last)<hr class="fleet-hr">@endif
        @empty
        <div class="fleet-empty">No vehicles at the moment.</div>
        @endforelse
        
    </div>
</div>

<!-- MODAL -->
<div class="fleet-modal-overlay" id="fleet-modal-overlay">
    <div class="fleet-modal">
        <div class="fleet-modal-header">
            <div>
                <p class="fleet-modal-eyebrow">Fleet management</p>
                <h2 class="fleet-modal-title">Add new vehicle</h2>
            </div>
            <button class="fleet-modal-close" onclick="closeModal()">✕</button>
        </div>

        <form action="{{ route('fleet.store') }}" method="POST">
            @csrf
            <div class="fleet-modal-body">

                <div class="fleet-modal-two-col">
                    <div>
                        <p class="fleet-modal-label">Vehicle number</p>
                        <input type="text" name="number" class="fleet-modal-input" placeholder="e.g. 42">
                    </div>
                    <div>
                        <p class="fleet-modal-label">Licence plate</p>
                        <input type="text" name="plate" class="fleet-modal-input" placeholder="e.g. BG-123-AB">
                    </div>
                </div>

                <div class="fleet-modal-two-col">
                    <div>
                        <p class="fleet-modal-label">Make</p>
                        <input type="text" name="brand" class="fleet-modal-input" placeholder="e.g. Mercedes">
                    </div>
                    <div>
                        <p class="fleet-modal-label">Model</p>
                        <input type="text" name="model" class="fleet-modal-input" placeholder="e.g. Citaro">
                    </div>
                </div>

                <div class="fleet-modal-three-col">
                    <div>
                        <p class="fleet-modal-label">Year</p>
                        <input type="number" name="year" class="fleet-modal-input" placeholder="2019" min="1990" max="2030">
                    </div>
                    <div>
                        <p class="fleet-modal-label">Seat count</p>
                        <input type="number" name="seat_count" class="fleet-modal-input" placeholder="42" min="1">
                    </div>
                    <div>
                        <p class="fleet-modal-label">Type</p>
                        <select name="type" class="fleet-modal-input">
                            <option value="bus">Bus</option>
                            <option value="tram">Tram</option>
                        </select>
                    </div>
                </div>

                <div class="fleet-modal-two-col">
                    <div>
                        <p class="fleet-modal-label">Fuel type</p>
                        <select name="fuel_type" class="fleet-modal-input">
                            <option value="diesel">Diesel</option>
                            <option value="electric">Electric</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                    </div>
                    <div>
                        <p class="fleet-modal-label">Status</p>
                        <select name="status" class="fleet-modal-input">
                            <option value="in_service">In service</option>
                            <option value="garage">Garage</option>
                            <option value="reserve">Reserve</option>
                            <option value="cleaning">Cleaning</option>
                        </select>
                    </div>
                </div>

                <div>
                    <p class="fleet-modal-label">Registration expires</p>
                    <input type="date" name="registration_expires_at" class="fleet-modal-input">
                    <input type="text" name="name" class="hidden-input" value="12">                
                </div>

            </div>

            <div class="fleet-modal-footer">
                <button type="button" class="fleet-modal-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="fleet-modal-submit">+ Add vehicle</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('fleet-modal-overlay').classList.add('open');
}
function closeModal() {
    document.getElementById('fleet-modal-overlay').classList.remove('open');
}
window.onclick = function(e) {
    if (e.target.id === 'fleet-modal-overlay') closeModal();
}
</script>
@endsection