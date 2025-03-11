@extends('layouts.absen.absen')
@section('header')
    <!-- App Header -->
<div class="appHeader bg-primary text-light">
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
	.webcam-capture video{
		display: inline-block;
		width: 100% !important;
		margin: auto;
		height: 95% !important;
		border-radius: 15px;
	}

	#map { height: 200px; }.
</style>

	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
	<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
		<div class="row">
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
    			<div id="map"></div>
    		</div>
    	</div>
    	</div>
    </div>

@endsection

@push('myscript')
<script>
	Webcam.set({
		height: 480,
		width: 640,
		image_format:'jpeg',
		jpeg_quality: 80
	});

	Webcam.attach('.webcam-capture');

var lokasi = document.getElementById('lokasi');

	// var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
var map = L.map('map').setView([-6.200000, 106.816666], 18);

	L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
	}).addTo(map);


	var center = L.latLng({{$pegawai->kantor->lokasi}}); //ganti dengan value dari database
	var radius = {{$pegawai->kantor->radius}}; //ganti dengan value dari database

	var circle = L.circle(center, { 
	    color: 'blue',
        fillColor: '#blue',
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

            // Update marker user
            userMarker.setLatLng(userLocation).bindPopup('Lokasi Anda').openPopup();

            // Update map supaya ikut geser
            map.setView(userLocation, 18);

            // Hitung jarak ke pusat radius
            var distance = userLocation.distanceTo(center);
            console.log('Jarak ke pusat: ' + distance + ' meter');

    // Cek apakah dalam radius atau tidak
    if (distance > radius) {
        // alert('⚠️ Anda belum masuk ke dalam radius!');
        $('#capture').prop('disabled', true);
    } else {
        // alert('✅ Anda berada di dalam radius.');
        $('#capture').prop('disabled', false);
    }

	}, function(error) {
            // alert('Gagal mendapatkan lokasi: ' + error.message);
            Swal.fire({
		  title: 'Peringatan!',
		  text: 'Gagal mendapatkan lokasi: ' + error.message + '. Aktifkan lokasi dan refresh halaman ini',
		  icon: 'warning',
		  confirmButtonText: 'Cool'
		})
        }, {
            enableHighAccuracy: true,
            maximumAge: 1000
        });
    } else {
        // alert('Geolocation tidak didukung di browser ini. Aktifkan lokasi dan refresh halaman ini');
        Swal.fire({
		  title: 'Error!',
		  text: 'Geolocation tidak didukung di browser ini. ',
		  icon: 'error',
		  confirmButtonText: 'Cool'
		})
    }

$('#capture').click(function (e) {
	Webcam.snap(function (uri) {
		image = uri;
	});

var lokasi = $('#lokasi').val();

	$.ajax({
		type:'POST',
		url:'/absen/store',
		data:{
			_token: '{{ csrf_token() }}',
			image:image,
			lokasi:lokasi
		},
		cache:false,
		success:function(respond){
			var status = respond.split("|");
			if(status[0] == "success"){
				Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: status[1],
                    allowOutsideClick: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        history.back(); // Redirect ke halaman sebelumnya
                    }
                });

				//nanti set timeout ke halaman depan absensi
				} else {
				Swal.fire({
					  title: 'Error!',
					  text: 'Gagal Absen, Mohon hubungi Admin',
					  icon: 'error',
					  confirmButtonText: 'Cool'
					})
			}
		}
	});

});
</script>
@endpush

{{-- Buat guard login karyawan dan user admin --}}