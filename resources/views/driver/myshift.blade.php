@extends('driver.driverview')

@section('main-content')
<div class="shift-full">
    <p class="date-time">{{ now()->format('l, F j · H:i') }}</p>
    <h1 class="shift-title">My Shift</h1>

    <div class="shift-stats">
        <div class="shift-stat">
            <h3>START</h3>
            <h1>{{ $shift?->start_time ?? '—' }}</h1>
            <p>{{ now()->format('F j, Y') }}</p>
        </div>
        <div class="shift-stat">
            <h3>END</h3>
            <h1>{{ $shift?->end_time ?? '—' }}</h1>
            <p>8 hours total</p>
        </div>
        <div class="shift-stat">
            <h3>PROGRESS</h3>
            <h1>{{ $progress }}%</h1>
            <p>{{ $elapsedTime ?? '—' }} elapsed</p>
        </div>
        <div class="shift-stat">
            <h3>REMAINING</h3>
            <h1>{{ $remainingTime ?? '—' }}</h1>
            <p>Until shift end</p>
        </div>
    </div>

    <div class="shift-progress-card">
        <div class="shift-progress-top">
            <h2>Shift Progress</h2>
            @if($shift)
                @if($progress >= 100)
                    <span class="shift-badge-gray">Completed</span>
                @elseif($progress > 0)
                    <span class="shift-badge-green">Active</span>
                @else
                    <span class="shift-badge-amber">Scheduled</span>
                @endif
            @else
                <span class="shift-badge-gray">No shift</span>
            @endif
        </div>
        <div class="shift-progress-labels">
            <p>{{ $shift?->start_time ?? '—' }} Start</p>
            <p>Now · {{ now()->format('H:i') }}</p>
            <p>{{ $shift?->end_time ?? '—' }} End</p>
        </div>
        <div class="shift-progress-bar">
            <div class="shift-progress-fill" style="width: {{ $progress }}%;"></div>
            <div class="shift-progress-dot" style="left: {{ $progress }}%;"></div>
        </div>
        <p class="shift-progress-sub">
            {{ $progress }}% complete
            @if($remainingTime) · {{ $remainingTime }} remaining @endif
        </p>
    </div>

    <div class="shift-down">
        <div class="shift-card">
            <div class="shift-card-top">
                <h2>Vehicle Details</h2>
            </div>
            @if($vehicle)
                <div class="shift-info-row">
                    <p>Vehicle</p>
                    <h3>{{ $vehicle->name }}</h3>
                </div>
                <hr>
                <div class="shift-info-row">
                    <p>Make / Model</p>
                    <h3>{{ $vehicle->brand }} {{ $vehicle->model }}</h3>
                </div>
                <hr>
                <div class="shift-info-row">
                    <p>Year</p>
                    <h3>{{ $vehicle->year }}</h3>
                </div>
                <hr>
                <div class="shift-info-row">
                    <p>Plate</p>
                    <h3>{{ $vehicle->plate }}</h3>
                </div>
                <hr>
                <div class="shift-info-row">
                    <p>Seat count</p>
                    <h3>{{ $vehicle->seat_count }} seats</h3>
                </div>
                <hr>
                <div class="shift-info-row">
                    <p>Fuel type</p>
                    <h3>{{ ucfirst($vehicle->fuel_type) }}</h3>
                </div>
                <hr>
                <div class="shift-info-row">
                    <p>Registration</p>
                    <h3 class="{{ $isExpired ? 'shift-red' : ($daysUntilExpiry < 30 ? 'shift-red' : 'shift-green') }}">
                        {{ $isExpired ? 'Expired' : 'Valid · ' . $daysUntilExpiry . ' days left' }}
                    </h3>
                </div>
            @else
                <div class="shift-info-row">
                    <p>No vehicle assigned for today</p>
                    <h3>Contact dispatcher</h3>
                </div>
            @endif
        </div>

        <div class="shift-card">
            <div class="shift-card-top">
                <h2>Shift Details</h2>
            </div>
            @if($shift)
                <div class="shift-info-row">
                    <p>Date</p>
                    <h3>{{ Carbon\Carbon::parse($shift->date)->format('d M Y') }}</h3>
                </div>
                <hr>
                <div class="shift-info-row">
                    <p>Start time</p>
                    <h3>{{ $shift->start_time }}</h3>
                </div>
                <hr>
                <div class="shift-info-row">
                    <p>End time</p>
                    <h3>{{ $shift->end_time }}</h3>
                </div>
                <hr>
                <div class="shift-info-row">
                    <p>Status</p>
                    <h3>{{ ucfirst($shift->status) }}</h3>
                </div>
                @if($shift->notes)
                <hr>
                <div class="shift-info-row">
                    <p>Notes</p>
                    <h3>{{ $shift->notes }}</h3>
                </div>
                @endif
            @else
                <div class="shift-info-row">
                    <p>No shift assigned for today</p>
                    <h3>Contact dispatcher</h3>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection