@extends('layouts.absen.absen')
@section('header')
    <!-- App Header -->
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Absensi Karyawan</div>
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
       /* transform: scaleX(-1);  Membalik webcam menjadi mirror */
    }

    .webcam-capture video {
        object-fit: cover;
        aspect-ratio: 3 / 4;
    }

    #map { height: 270px; }

</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endsection

@section('content')
{{-- {{dd($absenTerakhir)}} --}}


<div class="section full mt-4">
    <div class="section-title">Absensi</div>
    <div class="wide-block pt-2 pb-2">
        <div class="row">
            <div class="col" style="margin-bottom: -30px">
                <input type="hidden" id="lokasi">
                <input type="text" id="confirm" hidden disabled>
                <input type="text" id="shift" hidden disabled>
            <div class="webcam-capture"></div>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col" style="margin-top: -50px">
                <div id="map" style="z-index: 0;"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

@if($cek == null && Auth::guard('pegawai')->user()->shift == null)
<script>
Swal.fire({
    title: 'Pilih Shift',
    html: `
    <div style="display: flex; flex-direction: column; gap: 10px;">
    @foreach($shift as $s)
        <button type="button" class="swal2-confirm swal2-styled"
            onclick="pilihShift('{{ $s->id }}', '{{ $s->shift }}')">
            {{ $s->shift." (".\Carbon\Carbon::parse($s->jam_masuk)->format('H:i')." - ".\Carbon\Carbon::parse($s->jam_keluar)->format('H:i')." WIB)" }}
        </button>
    @endforeach
    </div>`,
    showConfirmButton: false,
    allowOutsideClick: false
});

function pilihShift(id, nama) {
    $('#shift').val(id).prop('disabled', false).attr('name', 'shift');

    Swal.close();
    Swal.fire({
        icon: 'success',
        title: 'Shift dipilih',
        text: 'Kamu memilih shift: ' + nama,
        timer: 1500,
        showConfirmButton: false
    });
}
</script>
@endif

@if($absenTerakhir && $absenTerakhir->jam_out == null)
<script>
let msg = "{{$absenTerakhir->tgl_absen}}";

Swal.fire({
    icon: 'warning',
    title: 'Oops!',
    html: `Anda belum melakukan absen pulang pada tanggal ${msg}. Lanjut absen pulang!`,
    confirmButtonText: 'Ya, Absen',
    allowOutsideClick: false
}).then((result) => {
    if (result.isConfirmed) {
        $('#confirm').val(1).prop('disabled', false).attr('name', 'confirm');
    }
});
</script>
@endif

<script>
// =======================
// 📷 WEBCAM
// =======================
Webcam.set({
    width: 480,
    height: 640,
    image_format: 'png',
    png_quality: 90
});
Webcam.attach('.webcam-capture');

// =======================
// 🗺️ MAP
// =======================
var lokasi = document.getElementById('lokasi');

var map = L.map('map').setView([-6.200000, 106.816666], 18);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
}).addTo(map);

var center = L.latLng({{ $pegawai->kantor->lokasi ?? '-6.200000,106.816666' }});
var radius = {{ $pegawai->kantor->radius ?? 100 }};

L.circle(center, {
    color: 'blue',
    fillColor: '#0000FF',
    fillOpacity: 0.2,
    radius: radius
}).addTo(map);

var userMarker = L.marker(center)
    .addTo(map)
    .bindPopup('Mengambil lokasi...')
    .openPopup();

// =======================
// 🔥 FIX GPS STABIL
// =======================
let retryCount = 0;
const maxRetry = 5;

function ambilLokasiAwal() {
    if (!navigator.geolocation) {
        Swal.fire('Error', 'Geolocation tidak didukung', 'error');
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function (pos) {
            updateLokasi(pos);

            navigator.geolocation.watchPosition(updateLokasi, handleError, {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 10000
            });
        },
        handleError,
        {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0
        }
    );
}

function updateLokasi(position) {
    retryCount = 0;

    let lat = position.coords.latitude;
    let lng = position.coords.longitude;

    lokasi.value = lat + "," + lng;

    let userLoc = L.latLng(lat, lng);

    userMarker.setLatLng(userLoc)
        .bindPopup('Lokasi Anda')
        .openPopup();

    map.setView(userLoc, 18);

    let distance = userLoc.distanceTo(center);
    const $btn = $('#capture');

    if (distance > radius) {
        $btn.addClass('bg-secondary btnInfo out-radius');
    } else {
        $btn.removeClass('bg-secondary btnInfo out-radius');
    }

    $btn.prop('disabled', false);
}

function handleError(error) {
    retryCount++;

    if (retryCount <= maxRetry) {
        setTimeout(ambilLokasiAwal, 2000);
        return;
    }

    let msg = 'Gagal mendapatkan lokasi';

    if (error.code === 1) msg = 'Izin lokasi ditolak';
    if (error.code === 2) msg = 'Lokasi tidak tersedia';
    if (error.code === 3) msg = 'GPS timeout';

    Swal.fire({
        icon: 'warning',
        title: 'Lokasi Error',
        text: msg,
        confirmButtonText: 'Coba Lagi'
    }).then(() => {
        retryCount = 0;
        ambilLokasiAwal();
    });
}

ambilLokasiAwal();

// =======================
// ⚙️ ABSEN LOGIC
// =======================
const idPegawaiAktif = parseInt("{{ Auth::guard('pegawai')->user()->id }}");
const daftarPengecualian = @json($pengecualian);

$(document).ready(function () {
    $('#capture').on('click', function () {

        let lokasiVal = $('#lokasi').val();

        if (!lokasiVal) {
            Swal.fire('Tunggu', 'Sedang mengambil lokasi...', 'info');
            return;
        }

        const isOutRadius = $(this).hasClass('out-radius');
        const absenStatus = $(this).data('absen');
        const absenStat = $(this).data('stat');
        let confirm = $('#confirm').val();

        const boleh = daftarPengecualian.includes(idPegawaiAktif);

        if (isOutRadius) {
            if (absenStat === 'pulang' && confirm == 1) {
                ambilFotoDanAbsen();
            } else if (absenStat === 'pulang') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Di luar radius!',
                    text: 'Tetap absen?',
                    showCancelButton: true
                }).then(r => r.isConfirmed && ambilFotoDanAbsen());
            } else if (boleh) {
                ambilFotoDanAbsen();
            } else {
                outofrad();
            }
        } else if (absenStatus === 'sudah') {
            showAbsenAlert();
        } else {
            ambilFotoDanAbsen();
        }
    });
});

// =======================
// 📷 FOTO + AJAX
// =======================
function dataURItoBlob(dataURI) {
    const byteString = atob(dataURI.split(',')[1]);
    const mimeString = dataURI.split(',')[0].match(/:(.*?);/)[1];
    const ab = new ArrayBuffer(byteString.length);
    const ia = new Uint8Array(ab);

    for (let i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }

    return new Blob([ab], { type: mimeString });
}

function ambilFotoDanAbsen() {
    Webcam.snap(function (uri) {

        let lokasi = $('#lokasi').val();
        let confirm = $('#confirm').val();
        let shift = $('#shift').val();

        Swal.fire({
            html: `Lokasi: ${lokasi}`,
            imageUrl: uri,
            showCancelButton: true
        }).then(result => {

            if (!result.isConfirmed) return;

            let formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('pegawai_id', $('#pegawai_id').val());
            formData.append('lokasi', lokasi);
            formData.append('confirm', confirm);
            formData.append('shift', shift);
            formData.append('image', dataURItoBlob(uri), 'absen.png');

            $.ajax({
                type: 'POST',
                url: '/absen/store',
                data: formData,
                processData: false,
                contentType: false,
                success: function () {
                    Swal.fire('Berhasil', 'Absen berhasil', 'success')
                        .then(() => location.reload());
                },
                error: function () {
                    Swal.fire('Error', 'Absen gagal', 'error');
                }
            });
        });
    });
}

// =======================
// 🔔 ALERT
// =======================
function outofrad() {
    Swal.fire('Oops!', 'Di luar radius', 'info');
}

function showAbsenAlert() {
    Swal.fire({
        icon: 'info',
        title: 'Sudah absen!',
        text: 'Lanjut lembur?',
        showCancelButton: true
    }).then(r => {
        if (r.isConfirmed) {
            window.location.href = '{{ route('absen.lembur') }}';
        }
    });
}
</script>
@endpush
