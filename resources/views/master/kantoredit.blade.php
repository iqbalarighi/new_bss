@extends('layouts.side.side')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder@3.1.0/dist/Control.Geocoder.css" />
<div class="container mw-100">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header fw-bold text-uppercase">{{ __('Edit Data Kantor') }}
                    <button class="btn btn-sm btn-primary float-right" onclick="history.back()">Kembali</button>
                </div>

                <div class="card-body d-flex justify-content-center">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="col-md-8">
                    <form action="{{route('kantor').'/edit/'.$kantor->id}}" method="POST">
                        @csrf
                        @method('PUT')
                        @if(Auth::user()->role != 0)
                        <div class="mb-3" hidden>
                            {{-- ini untuk level 1&3 --}}
                            <label class="form-label">Tenant</label>
                            <input type="text" name="tenant_name" class="form-control" value="{{ Auth::user()->perusahaan }}" required>
                        </div>
                        @else
                        <div class="mb-3">
                            <label class="form-label">Tenant</label>
                            {{-- <input type="text" class="form-control" value="{{ $kantor->perusa->perusahaan }}" required>
                            <input type="text" name="tenant_name" value="{{ $kantor->perusahaan }}" hidden required> --}}
                        <select class="form-select" name="tenant_name">
                            <option value="" disabled>Pilih Tenant</option>
                        @foreach($perusahaan as $item)
                            <option @if($kantor->perusahaan == $item->id) selected @endif value="{{$item->id}}">{{$item->perusahaan}}</option>
                        @endforeach
                        </select>
                        </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label">Nama Kantor</label>
                            <input type="text" name="office_name" class="form-control" value="{{ $kantor->nama_kantor }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control" required>{{ $kantor->alamat }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jarak Absen (meter)</label>
                            <input type="number" name="attendance_distance" class="form-control" value="{{ $kantor->radius }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi (Latitude, Longitude)</label>
                            <input type="text" id="location" name="location" class="form-control" value="{{ $kantor->lokasi }}" readonly>
                        </div>
                        
                        <div id="map" style="height: 400px; z-index: 0;"></div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary mt-3">Update</button>
                        </div>
                    </form>
                    </div>
                </div>
@endsection
@push('script')
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                    <script src="https://unpkg.com/leaflet-control-geocoder@3.1.0/dist/Control.Geocoder.js"></script>

                <script>
                    var map = L.map('map').setView([{{ $kantor->lokasi }}], 18);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        minZoom: 5,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);

                    var currentMarker = L.marker([{{ $kantor->lokasi }}]).addTo(map)
                    var center = L.latLng({{ $kantor->lokasi ?? '-6.200000, 106.816666' }});
                        var radius = "{{ $kantor->radius ?? 100 }}";
                        var circle = L.circle(center, { 
                            color: 'blue',
                            fillColor: '#0000FF',
                            fillOpacity: 0.2,
                            radius: radius
                        }).addTo(map);

                    var geocoder = L.Control.geocoder({ defaultMarkGeocode: false })
                    .on('markgeocode', function(e) {
                        var latlng = e.geocode.center;
                        if (currentMarker) map.removeLayer(currentMarker);
                        currentMarker = L.marker(latlng).addTo(map).bindPopup(e.geocode.name).openPopup();
                        map.setView(latlng, 18);
                        document.getElementById('location').value = latlng.lat + ',' + latlng.lng;
                    })
                    .addTo(map);

                    map.on('click', function(e) {
                        var lat = e.latlng.lat;
                        var lng = e.latlng.lng;
                        if (currentMarker) map.removeLayer(currentMarker);
                        currentMarker = L.marker([lat, lng]).addTo(map);
                        document.getElementById('location').value = lat + ',' + lng;
                    });
                </script>
                </div>
            </div>
        </div>
    </div>
</div>

@endpush