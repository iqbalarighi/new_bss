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
        #map { height: 200px; }.
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
                            <select name="usaha" class="form-control" required>
                                <option selected disabled value="">Pilih Perusahaan</option>
                                @foreach($perusahaan as $usaha)
                                <option value="{{$usaha->id}}">{{$usaha->perusahaan}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tenantName" class="form-label">Nama Kantor</label>
                            <input type="text" class="form-control" name="kantor" placeholder="Masukkan nama kantor" required>
                        </div>
                        <div class="mb-3">
                            <label for="tenantAddress" class="form-label">Alamat</label>
                            <input type="text" class="form-control"name="alamat" placeholder="Masukkan alamat" required>
                        </div>
                        <div class="mb-3">
                            <label for="tenantAddress" class="form-label">Radius</label>
                            <input type="text" class="form-control" name="radius" placeholder="Jarak lokasi absen (meter)" required>
                        </div>
                        <div class="mb-3">
                            <label for="tenantAddress" class="form-label">lokasi</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="lokasi" readonly required>
                        </div>
                        <div class="mb-3">
                            <div id="map"></div>

<script>
    var map = L.map('map').setView([-6.176560963605854, 106.82827323675157], 18); // Titik awal map

    // Tambah tile map
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Tambah fitur pencarian lokasi
      var geocoder = L.Control.geocoder({
        defaultMarkGeocode: false
    })
    .on('markgeocode', function(e) {
        var latlng = e.geocode.center;
        // alert("Hasil Pencarian:\nLatitude: " + latlng.lat + "\nLongitude: " + latlng.lng);
        $('#lokasi').val(latlng.lat+','+latlng.lng);
        L.marker(latlng).addTo(map)
            .bindPopup(e.geocode.name)
            .openPopup();
        map.setView(latlng, 15);
    })
    .addTo(map);

    // Event klik pada peta untuk ambil lat dan lng
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        // alert("Latitude: " + lat  "\nLongitude: " + lng);

        $('#lokasi').val(lat+','+lng);
    });
</script>
                        </div>
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

            <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Kantor</th>
                    <th>Alamat</th>
                    <th>No. Telepon</th>
                    <th>Jarak Absen</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kantor as $key => $kan)
                <tr>
                    <td>{{$kantor->firstitem()+$key}}</td>
                    <td>{{$kan->perusahaan}}</td>
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
@endsection