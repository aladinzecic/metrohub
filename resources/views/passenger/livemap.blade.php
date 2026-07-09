@extends('passenger.mainview')

@section('main-content')
<div class="map-div">

    <div class="map-overlay">
        <input type="text" class="map-search" placeholder="Search for a station or line...">
    </div>

    <div class="map-stops-div" id="vehicle-panel" style="display:none;">
        <button class="map-panel-close" onclick="document.getElementById('vehicle-panel').style.display='none'">✕</button>
        <div class="map-stops-title" id="vehicle-panel-title">—</div>
        <div class="map-stops-h3" id="vehicle-panel-sub">—</div>
        <div class="map-stops" id="vehicle-panel-stations"></div>
    </div>

    <div id="map"></div>
</div>
@endsection

@push('scripts')
<script>
    window.LINES = {!! json_encode($linesJson) !!};
</script>
@endpush