@extends('layouts.side.side')

@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Daftar Kantor') }}
                    <button class="btn btn-sm btn-primary float-right" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-building-add"></i></button>
                </div>

                <div class="card-body">
@if (Session::get('status'))
<script>
        Swal.fire({
          title: "Berhasil",
          icon: "success",
          showConfirmButton: false,
          timer: 1500
        });
</script>
@endif

<style>
        .modal.fade .modal-dialog {
            transform: scale(0.8);
            transition: transform 0.3s ease-out;
        }
        .modal.show .modal-dialog {
            transform: scale(1);
        }
        #map {
            width: 100%;
            height: 50vh;
        }
    </style>
    <!-- Modal Bootstrap -->                    
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered animate__animated animate__zoomIn">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Daftar Kantor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/kantor/tambah" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="tenantName" class="form-label">Nama Perusahaan</label>
                            {{-- <input type="text" class="form-control"name="usaha" placeholder="Masukkan nama kantor" required> --}}
                            <select name="usaha" id="tenantName" class="form-select" required>
                                <option selected disabled value="">Pilih Perusahaan</option>
                                @foreach($perusahaan as $usaha)
                                <option value="{{$usaha->id}}">{{$usaha->perusahaan}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kantor" class="form-label">Nama Kantor / Gedung</label>
                            <input type="text" class="form-control" id="kantor" name="kantor" placeholder="Masukkan nama kantor" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" id="alamat" class="form-control"name="alamat" placeholder="Masukkan alamat" required>
                        </div>
                        <div class="mb-3">
                            <label for="radius" class="form-label">Radius</label>
                            <input type="text" id="radius" class="form-control" name="radius" placeholder="Jarak lokasi absen (meter)" required>
                        </div>
                        <div class="mb-3">
                            <label for="lokasi" class="form-label">lokasi</label>
                            <input type="text" id="lokasi" class="form-control" id="lokasi" name="lokasi" placeholder="lokasi" readonly required>
                        </div>
                        <div class="mb-3">
                            <div id="map"></div>
                        </div>

<script>
    var map = L.map('map').setView([-6.174767872117399,106.82602018117908], 15); // Titik awal map

    // Tambah tile map
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
      minZoom: 5,
      attribution: '© OpenStreetMap',
      subdomains: ['a', 'b', 'c'],
      errorTileUrl: 'https://example.com/error-tile.png',
      opacity: 0.9,
      detectRetina: true
    }).addTo(map);

setTimeout(function() {
  map.invalidateSize();
}, 100);
    // Variabel marker utama
    var currentMarker = null;

    // Variabel untuk marker hasil geocoder
    var geocodeMarker = null;

    // Tambahkan Geocoder (search lokasi)
    var geocoder = L.Control.geocoder({
        defaultMarkGeocode: false
    })
    .on('markgeocode', function(e) {
        var latlng = e.geocode.center;

        // Reset marker geocode sebelumnya
        if (geocodeMarker) {
            map.removeLayer(geocodeMarker);
        }

        geocodeMarker = L.marker(latlng).addTo(map)
            .bindPopup(e.geocode.name)
            .openPopup();

        map.setView(latlng, 18);

        $('#lokasi').val(latlng.lat+','+latlng.lng);
        // $('#alamat').val(e.geocode.name);
    })
    .addTo(map);

    // Event klik pada peta untuk ambil lat dan lng
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        // alert("Latitude: " + lat  "\nLongitude: " + lng);
    // Hapus marker sebelumnya jika ada
        if (currentMarker) {
            map.removeLayer(currentMarker);
        }

        // Hapus marker hasil geocoder jika ada
        if (geocodeMarker) {
            map.removeLayer(geocodeMarker);
            geocodeMarker = null;
        }

        // Tambahkan marker baru
        currentMarker = L.marker([lat, lng]).addTo(map);

        $('#lokasi').val(lat+','+lng);
    });

    
 // Adjust the value (in ms)
</script>
                        
                        {{-- <div class="mb-3">
                            <label for="tenantPhone" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" name="telp" placeholder="Masukkan nomor telepon" required>
                        </div> --}}
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submmit" class="btn btn-primary">Simpan</button>
                </div> 
            </form>
            </div>
        </div>
    </div>
    <!-- Modal Bootstrap -->
    <div style="overflow: auto;">
            <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Tenant</th>
                    <th>Nama Kantor</th>
                    <th>Alamat</th>
                    <th>Jarak Absen</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody> 
                @foreach($kantor as $key => $kan)
                <tr>
                    <td>{{$kantor->firstitem()+$key}}</td>
                    <td>{{$kan->perusa->perusahaan}}</td>
                    <td>{{$kan->nama_kantor}}</td>
                    <td>{{$kan->alamat}}</td>
                    <td>{{$kan->radius}} meter</td>
                    <td>
                        <button class="btn btn-primary btn-sm">Edit</button>
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection