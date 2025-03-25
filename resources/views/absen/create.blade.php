@extends('layouts.absen.absen')
@section('header')
    <!-- App Header -->
<div class="appHeader bg-danger text-light">
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
        height: 95% !important;
        border-radius: 15px;
        position: relative;
       /* transform: scaleX(-1);  Membalik webcam menjadi mirror */
    }

    .webcam-capture video {
        object-fit: cover;
        aspect-ratio: 3 / 4;
    }

    #map { height: 200px; }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<div class="section full mt-2">
    <div class="section-title">Title</div>
    <div class="wide-block pt-2 pb-2">
        <div class="row">
            <div class="col">
                <input type="hidden" id="lokasi">
            <div class="webcam-capture"></div>
            </div>
        </div>
        <div class="row" style="margin-top: -80px;">
            <div class="col">
                @if($cek == 1)
                    @if($cek2->jam_out == null)
                        <button id="capture" class="btn btn-danger btn-block" disabled>
                        <ion-icon name="camera-outline"></ion-icon>
                        Absen Pulang
                    </button>
                @else
                <button class="btn btn-secondary btn-block" disabled>
                    Terima Kasih &nbsp;
                    <ion-icon name="thumbs-up"></ion-icon>
                </button>
                @endif
            @else
                <button id="capture" class="btn btn-primary btn-block" disabled>
                    <ion-icon name="camera-outline"></ion-icon>
                    Absen Masuk
                </button>
            @endif
        </div>
        </div>
        <div class="row mt-2">
            <div class="col">
                <div id="map" style="z-index: 0;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
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

            if (distance > radius) {
                $('#capture').prop('disabled', true);
            } else {
                $('#capture').prop('disabled', false);
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

    $('#capture').click(function (e) {
        Webcam.snap(function (uri) {
            let canvas = document.createElement("canvas");
            let ctx = canvas.getContext("2d");
            let img = new Image();

            img.onload = function() {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.translate(img.width, 0);
                ctx.scale(-1, 1);
                ctx.drawImage(img, 0, 0);

                let mirroredImage = canvas.toDataURL('image/png');

                Swal.fire({
                    title: 'Preview Foto',
                    imageUrl: mirroredImage,
                    imageWidth: 300,
                    imageAlt: 'Preview Foto',
                    showCancelButton: true,
                    confirmButtonText: 'Kirim',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Mengirim Data...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        var lokasi = $('#lokasi').val();

                        $.ajax({
                            type: 'POST',
                            url: '/absen/store',
                            data: {
                                _token: '{{ csrf_token() }}',
                                image: mirroredImage,
                                lokasi: lokasi
                            },
                            cache: false,
                            success: function (respond) {
                                Swal.close();
                                var status = respond.split("|");
                                if (status[0] == "success") {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: status[1],
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        window.location.href = '{{ url('/absen') }}';
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: status[1] || 'Gagal Absen, Mohon hubungi Admin',
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
    });
</script>
@endpush
