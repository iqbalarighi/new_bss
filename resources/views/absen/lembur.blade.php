@extends('layouts.absen.absen')

@section('header')
<!-- App Header -->
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Absensi Lembur</div>
    <div class="right"></div>
</div>
<!-- * App Header -->

<style type="text/css">
    .webcam-capture,
    .webcam-capture video {
        display: inline-block;
        width: 100% !important;
        margin: auto;
        height: 90% !important;
        border-radius: 15px;
        position: relative;
        margin-top: 1px;
    }

    .webcam-capture video {
        object-fit: cover;
        aspect-ratio: 3 / 4;
    }

    #map {
        height: 270px;
    }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endsection

@section('content')
<div class="section full mt-4">
    <div class="section-title">Lembur</div>
    <div class="wide-block pt-2 pb-2">
        <div class="row">
            <div class="col" style="margin-bottom: -30px">
                <input type="hidden" id="lokasi">
                <div id="my_camera" class="webcam-capture"></div>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col" style="margin-top: -50px">
                <div id="map" style="z-index: 0;"></div>
            </div>
        </div>
{{--         <div class="row mt-2">
            <div class="col-6">
                <button onclick="mulaiLembur()" class="btn btn-success btn-block">Mulai Lembur</button>
            </div>
            <div class="col-6">
                <button onclick="selesaiLembur()" class="btn btn-danger btn-block">Selesai Lembur</button>
            </div>
        </div> --}}
    </div>
</div>
@endsection

@push('myscript')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

<script>
let peta = null;
let marker = null;

let fotoPreview = null;
let lokasiPreview = null;
let tipeAbsen = '';

function ambilFoto(callback) {
    if (!Webcam.live) {
        Swal.fire('Webcam belum siap. Mohon tunggu beberapa detik.');
        return;
    }

    Webcam.snap(function (data_uri) {
        fotoPreview = data_uri;
        callback(data_uri);
    });
}

function ambilLokasi(callback) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (pos) {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            lokasiPreview = `${lat},${lng}`;
            callback(lokasiPreview);
        }, function () {
            Swal.fire('Gagal ambil lokasi');
        });
    } else {
        Swal.fire('Browser tidak mendukung geolocation');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi Webcam
    Webcam.set({
        width: 480,
        height: 640,
        image_format: 'png',
        png_quality: 90,
        constraints: {
            video: true // Biarkan browser memilih pengaturan terbaik
        }
    });
    Webcam.attach('#my_camera');

    // Inisialisasi Peta
    peta = L.map('map').setView([-6.200000, 106.816666], 15);
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

function tampilkanPreviewDanKirim(url) {
    let judul = (tipeAbsen === 'mulai') ? 'Absen Lembur Masuk' : 'Absen Lembur Selesai';
    Swal.fire({
        title: judul,
        html: `<p><strong><ion-icon name="location" class="text-danger" style="font-size: 20px;"></ion-icon></strong> ${lokasiPreview}</p><img src="${fotoPreview}" style="width: 100%; aspect-ratio: 3 / 4; object-fit: cover; border-radius:8px" />`,
        showCancelButton: true,
        confirmButtonText: 'Kirim',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(url, {
                _token: '{{ csrf_token() }}',
                foto: fotoPreview,
                lokasi: lokasiPreview
            }, function (response) {
                Swal.fire({
                    title: 'Berhasil',
                    text: response.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '{{ route('absen') }}';
                });
            }).fail(function (xhr) {
                Swal.fire('Gagal', xhr.responseJSON.message, 'error');
            });
        }
    });
}

function mulaiLembur() {
    tipeAbsen = 'mulai';
    ambilFoto(function () {
        ambilLokasi(function () {
            tampilkanPreviewDanKirim("/absen/lembur/mulai");
        });
    });
}

function selesaiLembur() {
    tipeAbsen = 'selesai';
    ambilFoto(function () {
        ambilLokasi(function () {
            tampilkanPreviewDanKirim("/absen/lembur/selesai");
        });
    });
}
</script>
@endpush
