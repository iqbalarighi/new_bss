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
                <input type="text" id="confirm" hidden disabled>
                <input type="text" id="area_kerja" hidden disabled>
                <input type="text" id="uraian" hidden disabled>
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
@if($ceklem && $ceklem->jam_out == null)
<script type="text/javascript">
    let msg = "{{$ceklem->tgl_absen}}";

    Swal.fire({
            icon: 'warning',
            title: 'Oops!',
            html: `
                  Anda belum menyelesaikan jam lembur pada tanggal ` + msg + `. Selesaikan jam lembur!`,
            confirmButtonText: 'Lanjutkan!',
            allowOutsideClick: false,
        }).then((result) => {
                    if (result.isConfirmed) {
                            $('#confirm').prop('disabled', false);
                            $('#confirm').attr('name', 'confirm');
                            $('#confirm').attr('value', 1);
                    } else {
                        $('#confirm').removeAttr('name');
                        $('#confirm').removeAttr('value');
                    }
                });
</script>
@elseif($lem == 0)
<script type="text/javascript">
Swal.fire({
    title: 'Data Lembur',
    html: `
        <style>
            .swal2-input, .swal2-textarea {
                display: block;
                margin: 10px auto;
                width: 100% !important;
                max-width: 100%;
            }
        </style>
        <input type="text" id="swal-input-area" class="swal2-input" placeholder="Area Kerja">
        <textarea id="swal-input-uraian" class="swal2-textarea" placeholder="Keperluan Lembur"></textarea>
    `,
    focusConfirm: false,
    showCancelButton: false,
    confirmButtonText: 'Simpan',
    allowOutsideClick: false,
    preConfirm: () => {
        const areaKerja = document.getElementById('swal-input-area')?.value.trim();
        const uraian = document.getElementById('swal-input-uraian')?.value.trim();

        if (!areaKerja || !uraian) {
            Swal.showValidationMessage('Area Kerja dan Keperluan lembur wajib diisi!');
            return false;
        }

        return { area_kerja: areaKerja, uraian: uraian };
    }
}).then((result) => {
    if (result.isConfirmed) {
        const data = result.value;
        console.log("Data disimpan:", data.area);
            $('#area_kerja').prop('disabled', false);
            $('#area_kerja').attr('name', 'area_kerja');
            $('#area_kerja').attr('value', data.area_kerja);

            $('#uraian').prop('disabled', false);
            $('#uraian').attr('name', 'uraian');
            $('#uraian').attr('value', data.uraian);

            Swal.fire({
              icon: "success",
              title: "Oke",
              text: "Klik tombol kamera dibawah untuk memulai lembur",
              showConfirmButton: false,
              timer: 4000
            });
        } else {
            $('#area_kerja').removeAttr('name');
            $('#area_kerja').removeAttr('value');

            $('#uraian').removeAttr('name');
            $('#uraian').removeAttr('value');
        }
});
</script>
@endif
<script>
let peta = null;
let marker = null;
let fotoPreview = null;
let lokasiPreview = null;
let tipeAbsen = '';

// Inisialisasi Webcam
Webcam.set({
    width: 480,
    height: 640,
    image_format: 'png',
    png_quality: 80,
    constraints: {
        video: true,
        facingMode: "user"
    }
});
Webcam.attach('#my_camera');

// Inisialisasi Peta
document.addEventListener('DOMContentLoaded', function () {
    peta = L.map('map').setView([-6.200000, 106.816666], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(peta);

    ambilLokasi(function (lokasi) {
        const [lat, lng] = lokasi.split(',').map(parseFloat);
        lokasiPreview = `${lat},${lng}`;

        if (marker) peta.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(peta)
            .bindPopup("Lokasi Anda").openPopup();

        peta.setView([lat, lng], 17);
    });
});

// Fungsi Ambil Foto
function ambilFoto(callback) {
    if (!Webcam.live) {
        Swal.fire('Webcam belum siap. Mohon tunggu beberapa detik.');
        return;
    }

    Webcam.snap(function (data_uri) {
        fotoPreview = data_uri;
        Swal.close();
        callback(data_uri);
    });

    Swal.fire({
        title: 'Mengambil Foto...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

// Fungsi Ambil Lokasi
function ambilLokasi(callback) {
    if (!navigator.geolocation) {
        Swal.fire('Browser tidak mendukung Geolocation.');
        return;
    }

    navigator.geolocation.getCurrentPosition(function (pos) {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;
        callback(`${lat},${lng}`);
    }, function (err) {
        Swal.fire('Gagal mengambil lokasi', err.message, 'error');
    }, {
        enableHighAccuracy: true,
        maximumAge: 1000
    });
}

// Preview Foto & Konfirmasi
function tampilkanPreviewDanKirim(url) {
    const judul = tipeAbsen === 'mulai' ? 'Absen Lembur Masuk' : 'Absen Lembur Selesai';
    const confirm = $('#confirm').val();
    const area_kerja = $('#area_kerja').val();
    const uraian = $('#uraian').val();

    if (!fotoPreview || !lokasiPreview) {
        Swal.fire('Data tidak lengkap!', 'Foto atau lokasi belum tersedia.', 'warning');
        return;
    }

    const img = new Image();
    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d");

    img.onload = function () {
        canvas.width = img.width;
        canvas.height = img.height;

        // Buat mirror horizontal
        ctx.translate(img.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(img, 0, 0);

        const mirroredImage = canvas.toDataURL('image/png', 0.8);

        Swal.fire({
            title: judul,
            html: `<p>
                        <strong>
                            <ion-icon name="location" class="text-danger" style="font-size: 20px;"></ion-icon>
                        </strong> ${lokasiPreview}
                   </p>`,
            imageUrl: mirroredImage,
            imageWidth: 300,
            imageAlt: 'Preview Foto',
            showCancelButton: true,
            confirmButtonText: 'Kirim',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                kirimData(url, mirroredImage, lokasiPreview, confirm, area_kerja, uraian);
            }
        });
    };

    img.src = fotoPreview;
}

// Kirim Data via AJAX
function kirimData(url, foto, lokasi, confirm, area_kerja, uraian) {
    Swal.fire({
        title: 'Mengirim Data...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    $.post(url, {
        _token: '{{ csrf_token() }}',
        foto: foto,
        lokasi: lokasi,
        confirm: confirm,
        area_kerja: area_kerja,
        uraian: uraian
    }, function (response) {
        Swal.fire({
            title: 'Berhasil',
            text: response.message,
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            window.location.href = '{{ route('absen') }}';
        });
    }).fail(function (xhr) {
        const msg = xhr.responseJSON?.message || 'Gagal mengirim data.';
        Swal.fire('Gagal', msg, 'error');
    });
}

// Fungsi Trigger Absen
function mulaiLembur() {
    tipeAbsen = 'mulai';
    ambilFoto(() => {
        ambilLokasi(() => {
            tampilkanPreviewDanKirim("/absen/lembur/mulai");
        });
    });
}

function selesaiLembur() {
    tipeAbsen = 'selesai';
    ambilFoto(() => {
        ambilLokasi(() => {
            tampilkanPreviewDanKirim("/absen/lembur/selesai");
        });
    });
}
</script>

@endpush
