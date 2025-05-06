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
@if($absenTerakhir && $absenTerakhir->jam_out == null)
<script type="text/javascript">
    let msg = "{{$absenTerakhir->tgl_absen}}";

    Swal.fire({
            icon: 'warning',
            title: 'Oops!',
            html: `
                  Anda belum melakukan absen pulang pada tanggal ` + msg + `. Apakah Anda ingin melakukan absen pulang ?`,
            confirmButtonText: 'OK', 
            showCancelButton: true,
            confirmButtonText: 'Ya, Absen',
            reverseButtons: true,
            cancelButtonText: 'Tidak',
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
@endif
<script>
    Webcam.set({
        width: 480,
        height: 640,
        image_format: 'png',
        png_quality: 90,
        constraints: {
            video: true // Biarkan browser memilih pengaturan terbaik
        }
    });

    Webcam.attach('.webcam-capture');

    var lokasi = document.getElementById('lokasi');

    var map = L.map('map').setView([-6.200000, 106.816666], 18);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    var center = L.latLng({{ $pegawai->kantor->lokasi ?? '-6.200000, 106.816666' }});
    var radius = {{ $pegawai->kantor->radius ?? 100 }};

    var circle = L.circle(center, { 
        color: 'blue',
        fillColor: '#0000FF',
        fillOpacity: 0.2,
        radius: radius
    }).addTo(map);

    var userMarker = L.marker(center).addTo(map).bindPopup('Menunggu lokasi...');

    if(navigator.geolocation){
        navigator.geolocation.watchPosition(function (position) {
            lokasi.value = position.coords.latitude + "," + position.coords.longitude;

            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            var userLocation = L.latLng(lat, lng);

            userMarker.setLatLng(userLocation).bindPopup('Lokasi Anda').openPopup();
            map.setView(userLocation, 18);

            var distance = userLocation.distanceTo(center);

            const $btn = $('#capture');

    if (distance > radius) {
        $btn
            .addClass('bg-secondary btnInfo out-radius')
            .prop('disabled', false); // tetap bisa diklik
    } else {
        $btn
            .removeClass('bg-secondary btnInfo out-radius')
            .prop('disabled', false);
    }

    // Tambah warna merah jika sudah absen
    if ($btn.data('absen') == 'sudah') {
        $btn.removeClass('bg-danger');
    } else {
        
    }

        }, function(error) {
            Swal.fire({
                title: 'Peringatan!',
                text: 'Gagal mendapatkan lokasi: ' + error.message + '. Aktifkan lokasi dan refresh halaman ini',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }, {
            enableHighAccuracy: true,
            maximumAge: 1000
        });
    } else {
        Swal.fire({
            title: 'Error!',
            text: 'Geolocation tidak didukung di browser ini.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }



$(document).ready(function () {
        $('#capture').on('click', function () {
            const isOutRadius = $(this).hasClass('out-radius');
            const absenStatus = $(this).data('absen'); // 'sudah' / 'belum'
            const absenStat = $(this).data('stat'); // 'sudah' / 'belum'
            let confirm = $('#confirm').val();

            if (isOutRadius) {
                if(absenStat === 'pulang' && confirm == 1){
                    ambilFotoDanAbsen();
                } else if(absenStat === 'pulang') {
                    Swal.fire({
                    icon: 'warning',
                    title: 'Anda di luar radius!',
                    text: 'Apakah Anda yakin ingin tetap melakukan absen?',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, lanjut',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        ambilFotoDanAbsen(); // lanjut absen walau di luar radius
                    }
                });
                } else {
                outofrad();
                }
            } else if (absenStatus === 'sudah') {
                showAbsenAlert();
            } else {
                // Aksi ambil foto atau absen di sini
                    ambilFotoDanAbsen();
            }
        });
    });

function ambilFotoDanAbsen() {
        Webcam.snap(function (uri) {
            let canvas = document.createElement("canvas");
            let ctx = canvas.getContext("2d");
            let img = new Image();

            let lokasi = $('#lokasi').val();
            let confirm = $('#confirm').val();

            img.onload = function () {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.translate(img.width, 0);
                ctx.scale(-1, 1);
                ctx.drawImage(img, 0, 0);

                let mirroredImage = canvas.toDataURL('image/png');

                Swal.fire({
                    html: `
                        Lokasi Absen <br>
                        <ion-icon name="location" class="text-danger" style="font-size: 20px;"></ion-icon>&nbsp;
                        ${lokasi}
                    `,
                    imageUrl: mirroredImage,
                    imageWidth: 300,
                    imageAlt: 'Preview Foto',
                    showCancelButton: true,
                    confirmButtonText: 'Kirim',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (lokasi === "") {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Lokasi tidak terdeteksi. Aktifkan GPS atau reload halaman!',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }

                        Swal.fire({
                            title: 'Mengirim Data...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            type: 'POST',
                            url: '/absen/store',
                            data: {
                                _token: '{{ csrf_token() }}',
                                image: mirroredImage,
                                lokasi: lokasi,
                                confirm: confirm,
                            },
                            cache: false,
                            success: function (respond) {
                                Swal.close();
                                const status = respond.split("|");

                                if (status[0] === "success") {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: status[1],
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        window.location.href = '{{ url('/absen') }}';
                                    });
                                } else if (status[0] === "absplg") {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: status[1],
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: status[1] || 'Gagal Absen, mohon hubungi Admin',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            }
                        });
                    }
                });
            };

            img.src = uri;
        });
    }

function outofrad() {
        Swal.fire({
            icon: 'info',
            title: 'Oops!',
            text: 'Anda berada di luar Radius',
            confirmButtonText: 'OK'
        });
    }

    function showAbsenAlert() {
        Swal.fire({
            icon: 'info',
            title: 'Oops!',
            text: 'Anda sudah absen hari ini! Lanjut absen lembur?',
            showCancelButton: true,
                    confirmButtonText: 'Ya, lanjut',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('absen.lembur') }}'; 
                    }
                });
    }
</script>
@endpush
