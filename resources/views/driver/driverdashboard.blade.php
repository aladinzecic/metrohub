@extends('driver.driverview')

@section('main-content')
<div class="dashboard-full">
    <p class="date-time">{{ now()->format('l, F j · H:i') }}</p>
    <h1 class="morning">Good morning, {{ auth()->user()->name }}</h1>

    <div class="dashboard-main">
        <div class="dashboard-prop">
            <h3>VEHICLE</h3>
            @if($vehicle)
                <h1>{{ $vehicle->name }}</h1>
                <p>{{ $vehicle->brand }} {{ $vehicle->model }} {{ $vehicle->year }}</p>
            @else
                <h1>—</h1>
                <p>No vehicle assigned</p>
            @endif
        </div>
        <div class="dashboard-prop">
            <h3>SHIFT</h3>
            @if($shift)
                <h1>{{ $shift->start_time }} – {{ $shift->end_time }}</h1>
                <p>{{ ucfirst($shift->status) }}</p>
            @else
                <h1>—</h1>
                <p>No shift today</p>
            @endif
        </div>
        <div class="dashboard-prop">
            <h3>PLATE</h3>
            @if($vehicle)
                <h1>{{ $vehicle->plate }}</h1>
                <p>{{ ucfirst($vehicle->type) }}</p>
            @else
                <h1>—</h1>
                <p>—</p>
            @endif
        </div>
        <div class="dashboard-prop">
            <h3>DEPOT</h3>
            <h1>{{ auth()->user()->depot ?? '—' }}</h1>
            <p>Home garage</p>
        </div>
    </div>

    <div class="dashboard-down">
        <div class="dashboard-vehicle">
            <div class="dashboard-vehicle-top">
                <h1>Vehicle Status</h1>
                @if($vehicle)
                    <p>✓ {{ ucfirst($vehicle->status) }}</p>
                @else
                    <p>— No vehicle</p>
                @endif
            </div>

            @if($vehicle)
                <div class="line-text">
                    <p>Registration</p>
                    <h3 class="{{ $isExpired ? 'red' : ($daysUntilExpiry < 30 ? 'red' : 'green') }}">
                        {{ $isExpired ? 'Expired' : 'Expires in ' . $daysUntilExpiry . ' days' }}
                    </h3>
                </div>
                <hr>
                <div class="line-text">
                    <p>Fuel type</p>
                    <h3>{{ ucfirst($vehicle->fuel_type) }}</h3>
                </div>
                <hr>
                <div class="line-text">
                    <p>Seat count</p>
                    <h3>{{ $vehicle->seat_count }} seats</h3>
                </div>
                <hr>
                <div class="line-text">
                    <p>Vehicle type</p>
                    <h3>{{ ucfirst($vehicle->type) }}</h3>
                </div>
                <hr>
                <div class="line-text">
                    <p>Year</p>
                    <h3>{{ $vehicle->year }}</h3>
                </div>
            @else
                <div class="line-text">
                    <p>No vehicle assigned for today</p>
                    <h3>Contact dispatcher</h3>
                </div>
            @endif

            <hr>
            <div class="dashboard-vehicle-down">
                <button class="report" onclick="window.location='/reportfault'">⚠ Report Fault</button>
            </div>
        </div>

        <div class="dashboard-route">
            <div class="dashboard-route-top">
                <h1>Shift Info</h1>
                @if($shift)
                    <p>{{ ucfirst($shift->status) }}</p>
                @endif
            </div>

            @if($shift)
                <div class="route-stops">
                    <div class="route-stop">
                        <div class="route-stop-left">
                            <div class="route-dot done"></div>
                            <p class="route-stop-name">Shift start</p>
                        </div>
                        <p class="route-stop-time">{{ $shift->start_time }}</p>
                    </div>
                    <div class="route-line"></div>

                    <div class="route-stop">
                        <div class="route-stop-left">
                            <div class="route-dot next"></div>
                            <p class="route-stop-name">Assigned vehicle</p>
                        </div>
                        <p class="route-stop-time">{{ $vehicle?->name ?? '—' }}</p>
                    </div>
                    <div class="route-line"></div>

                    <div class="route-stop">
                        <div class="route-stop-left">
                            <div class="route-dot next"></div>
                            <p class="route-stop-name">Plate</p>
                        </div>
                        <p class="route-stop-time">{{ $vehicle?->plate ?? '—' }}</p>
                    </div>
                    <div class="route-line"></div>

                    <div class="route-stop">
                        <div class="route-stop-left">
                            <div class="route-dot"></div>
                            <p class="route-stop-name done-text">Shift end</p>
                        </div>
                        <p class="route-stop-time done-text">{{ $shift->end_time }}</p>
                    </div>

                    @if($shift->notes)
                        <div style="margin-top:14px;padding:10px 12px;background:#F5F2ED;border-radius:8px;font-size:12px;color:#555550;">
                            {{ $shift->notes }}
                        </div>
                    @endif
                </div>
            @else
                <div style="padding:20px 0;text-align:center;font-size:13px;color:#999993;">
                    No shift assigned for today.
                </div>
            @endif
        </div>
    </div>




    </div>
</div>
@endsection