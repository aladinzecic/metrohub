@extends('dispatcher.dispatcherview')

@section('main-content')
<div class="requests-full">
<p class="date-time" id="dateTime"></p>    <h1 class="requests-title">Registration Requests</h1>

    @if(session('success'))
        <div class="requests-success">{{ session('success') }}</div>
    @endif

    <div class="requests-stats">
        <div class="requests-stat">
            <h3>PENDING</h3>
            <h1>{{$pendingCount}}</h1>
            <p>Awaiting approval</p>
        </div>
        <div class="requests-stat">
            <h3>APPROVED</h3>
            <h1>{{$approvedCount}}</h1>
            <p>This month</p>
        </div>
        <div class="requests-stat">
            <h3>REJECTED</h3>
            <h1>{{$rejectedCount}}</h1>
            <p>This month</p>
        </div>
    </div>

    <div class="requests-card">
        <div class="requests-card-top">
            <h2>Pending requests</h2>
            <span class="requests-badge-amber">{{$pendingCount}} pending</span>
        </div>

        @forelse($pendingUsers as $u)
            <div class="requests-item">
    <div class="requests-avatar">
        {{ strtoupper(substr($u->name, 0, 2)) }}
    </div>
    <div class="requests-info">
        <p class="requests-name">{{ $u->name }}</p>
        <p class="requests-desc">{{ $u->email }} · Registered {{ $u->created_at->diffForHumans() }}</p>
    </div>
    <span class="requests-role-pill">{{ ucfirst($u->role) }}</span>
    <div class="requests-actions">
        <form action="/dispatcher/requests/{{ $u->id }}/approve" method="POST">
            @csrf
            <button type="submit" class="requests-approve-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                Approve
            </button>
        </form>
        <form action="/dispatcher/requests/{{$u->id}}/reject" method="POST">
            @csrf
            <button type="submit" class="requests-reject-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
                Reject
            </button>
        </form>
    </div>
</div>
@if(!$loop->last)<hr class="requests-hr">@endif
@empty
<div class="requests-empty">No pending requests at the moment.</div>
        @endforelse

    </div>

    <div class="requests-card">
        <div class="requests-card-top">
            <h2>Recently processed</h2>
        </div>


        @forelse($processedUsers as $p)
            <div class="requests-item">
            <div class="requests-avatar requests-avatar-green">{{ strtoupper(substr($p->name, 0, 2)) }}</div>
            <div class="requests-info">
                <p class="requests-name">{{$p->name}}</p>
                @if($p->status=='active')
                <p class="requests-desc">{{$p->email}} · {{$p->role}} · Approved {{$p->updated_at->diffForHumans()}}</p>
                @else
                <p class="requests-desc">{{$p->email}} · {{$p->role}} · Rejected {{$p->updated_at->diffForHumans()}}</p>
                @endif
            </div>
            <span class="requests-role-pill">{{$p->role}}</span>
@if($p->status == 'active')
    <span class="requests-badge-green">
        {{ $p->status }}
    </span>
@else
    <span class="requests-badge-red">
        {{ $p->status }}
    </span>
@endif        </div>

        @if(!$loop->last)<hr class="requests-hr">@endif
        @empty
<div class="requests-empty">No recently processed users at the moment.</div>
        @endforelse
    </div>

</div>
@endsection