@extends('layouts.absen.absen')
@section('header')
    <!-- App Header -->
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Absen Lembur Karyawan</div>
    <div class="right"></div>
</div>
<!-- * App Header -->


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endsection

@section('content')
<div class="container">
    <h3>Absen Lembur</h3>

    <div class="text-center my-4">
        <div id="my_camera" class="mb-3" style="width: 320px; height: 240px; margin: auto;"></div>
        <button class="btn btn-success me-2" onclick="mulaiLembur()">Mulai Lembur</button>
        <button class="btn btn-danger" onclick="selesaiLembur()">Selesai Lembur</button>
    </div>

    <h5>Lokasi Saat Ini</h5>
    <div id="map" style="height: 300px;"></div>
</div>
@endsection

@push('myscript')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

<script>
let peta = null;
let marker = null;

document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi Webcam
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'png',
        png_quality: 90
    });
    Webcam.attach('#my_camera');

    // Inisialisasi Peta
    peta = L.map('map').setView([-6.200000, 106.816666], 15); // Default Jakarta
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(peta);

    ambilLokasi(function(lokasi) {
        let [lat, lng] = lokasi.split(',');
        lat = parseFloat(lat);
        lng = parseFloat(lng);

        peta.setView([lat, lng], 17);
        marker = L.marker([lat, lng]).addTo(peta)
            .bindPopup("Lokasi Anda").openPopup();
    });
});



    // Inisialisasi Webcam
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'png',
        png_quality: 90
    });

document.addEventListener('DOMContentLoaded', function () {
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'png',
        png_quality: 90
    });
    Webcam.attach('#my_camera');
});

function ambilFoto(callback) {
    if (!Webcam.live) {
        Swal.fire('Webcam belum siap. Mohon tunggu beberapa detik.');
        return;
    }

    Webcam.snap(function (data_uri) {
        callback(data_uri);
    });
}

    function ambilLokasi(callback) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                callback(`${lat},${lng}`);
            }, function () {
                Swal.fire('Gagal ambil lokasi');
            });
        } else {
            Swal.fire('Browser tidak mendukung geolocation');
        }
    }

    function mulaiLembur() {
        Swal.fire({
            title: 'Yakin mulai lembur?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
        }).then((result) => {
            if (result.isConfirmed) {
                ambilFoto(function (foto) {
                    ambilLokasi(function (lokasi) {
                        $.post("/absen/lembur/mulai", {
                            _token: '{{ csrf_token() }}',
                            foto: foto,
                            lokasi: lokasi
                        }, function (response) {
                            Swal.fire(response.message);
                        }).fail(function (xhr) {
                            Swal.fire('Gagal', xhr.responseJSON.message, 'error');
                        });
                    });
                });
            }
        });
    }

    function selesaiLembur() {
        Swal.fire({
            title: 'Yakin selesai lembur?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
        }).then((result) => {
            if (result.isConfirmed) {
                ambilFoto(function (foto) {
                    ambilLokasi(function (lokasi) {
                        $.post("/absen/lembur/selesai", {
                            _token: '{{ csrf_token() }}',
                            foto: foto,
                            lokasi: lokasi
                        }, function (response) {
                            Swal.fire(response.message);
                        }).fail(function (xhr) {
                            Swal.fire('Gagal', xhr.responseJSON.message, 'error');
                        });
                    });
                });
            }
        });
    }
</script>
@endpush
