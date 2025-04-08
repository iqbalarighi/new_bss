@extends('layouts.side.side')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder@3.1.0/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder@3.1.0/dist/Control.Geocoder.js"></script>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col mw-100">
                <div class="card">
                    <div class="card-header">
                        {{ __('Daftar Kantor') }}
                        <button class="btn btn-sm btn-primary float-right" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="bi bi-building-add"></i>
                        </button>
                    </div>

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
                                min-height: 300px;
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

                                            @if(Auth::user()->role == 0)
                                            <div class="mb-3">
                                                <label for="tenantName" class="form-label">Nama Perusahaan</label>
                                                <select name="usaha" id="tenantName" class="form-select" required>
                                                    <option selected disabled value="">Pilih Perusahaan</option>
                                                    @foreach($perusahaan as $usaha)
                                                    <option value="{{$usaha->id}}">{{$usaha->perusahaan}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endif
                                            
                                            <div class="mb-3">
                                                <label for="kantor" class="form-label">Nama Kantor / Gedung</label>
                                                <input type="text" class="form-control" id="kantor" name="kantor" placeholder="Masukkan nama kantor" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="alamat" class="form-label">Alamat</label>
                                                <input type="text" id="alamat" class="form-control" name="alamat" placeholder="Masukkan alamat" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="radius" class="form-label">Radius</label>
                                                <input type="text" id="radius" class="form-control" name="radius" placeholder="Jarak lokasi absen (meter)" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="lokasi" class="form-label">Lokasi</label>
                                                <input type="text" id="lokasi" class="form-control" name="lokasi" placeholder="Lokasi" readonly required>
                                            </div>
                                            <div class="mb-3">
                                                <div id="map"></div>
                                            </div>
                                            <script>
                                                var map = L.map('map').setView([-6.174767872117399, 106.82602018117908], 15);
                                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                                    maxZoom: 19,
                                                    minZoom: 5,
                                                    attribution: '© OpenStreetMap'
                                                }).addTo(map);

                                                $('#exampleModal').on('shown.bs.modal', function () {
                                                    map.invalidateSize();
                                                });

                                                var currentMarker = null;

                                                var geocoder = L.Control.geocoder({ defaultMarkGeocode: false })
                                                .on('markgeocode', function(e) {
                                                    var latlng = e.geocode.center;
                                                    if (currentMarker) map.removeLayer(currentMarker);
                                                    currentMarker = L.marker(latlng).addTo(map).bindPopup(e.geocode.name).openPopup();
                                                    map.setView(latlng, 18);
                                                    document.getElementById('lokasi').value = latlng.lat + ',' + latlng.lng;
                                                })
                                                .addTo(map);

                                                map.on('click', function(e) {
                                                    var lat = e.latlng.lat;
                                                    var lng = e.latlng.lng;
                                                    if (currentMarker) map.removeLayer(currentMarker);
                                                    currentMarker = L.marker([lat, lng]).addTo(map);
                                                    document.getElementById('lokasi').value = lat + ',' + lng;
                                                });
                                            </script>
                                            
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="card-body" style="overflow: auto;"> 
                        <table class="table table-striped table-bordered table-hover">
                            <thead class="text-center table-dark">
                                <tr>
                                    <th >No</th>
                                    @if(Auth::user()->role == 0)
                                        <th>Tenant</th>
                                    @endif
                                    <th>Nama Kantor</th>
                                    <th>Alamat</th>
                                    <th>Jarak Absen</th>
                                @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                                    <th>Aksi</th>
                                @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kantor as $key => $kan)
                                <tr>
                                    <td>{{ $kantor->firstitem() + $key }}</td>
                                @if(Auth::user()->role == 0)
                                    <td>{{$kan->perusa->perusahaan}}</td>
                                @endif
                                    <td>{{$kan->nama_kantor}}</td>
                                    <td>{{$kan->alamat}}</td>
                                    <td>{{$kan->radius}} meter</td>
                                @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                                    <td class="align-middle text-center">
                                        <button class="btn btn-primary btn-sm" onclick="location.href='/kantor/edit/{{ $kan->id }}'">Edit</button>
                                        <button class="btn btn-danger btn-sm del-btn" data-id="{{$kan->id}}">Hapus</button>
                                    </td>
                                @endif
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
@push('script')
<script type="text/javascript">
    // Hapus Data
    $('.del-btn').click(function () {
        var userId = $(this).data('id'); // Ambil ID user dari atribut data-id

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/kantor/hapus/' + userId, // Sesuaikan dengan route di Laravel
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Data user telah dihapus.',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            location.reload(); // Reload halaman setelah berhasil dihapus
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan!',
                            text: 'Gagal menghapus data.'
                        });
                    }
                });
            }
        });
    });
</script>
@endpush