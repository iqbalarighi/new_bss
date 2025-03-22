@extends('layouts.absen.absen')

@section('header')
<meta name="csrf_token" content="{{ csrf_token() }}" />
	<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" class="ion-icon"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Profile</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div style="margin-top: 3.5rem;" class="bg-light">
	
	<div class="container py-3" style="margin-bottom: 3rem;">
        <div class="card shadow-sm rounded-4 mb-2 border-0">

            <div class="card-body text-center bg-white" style="padding: 20px;">
              
                <div onclick="return showUploadOptions()" class="rounded-circle overflow-hidden shadow-sm bg-secondary text-white d-inline-flex justify-content-center align-items-center" style="width: 80px; height: 80px;">
                    @if($profile->foto == null)
                    <img src="https://ui-avatars.com/api/?name={{$profile->nama_lengkap}}" alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <img src={{asset('storage/'.$profile->foto)}} alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
                    @endif
                </div>
                 <a href="#" class="text-warning text-decoration-none d-block mt-2">Ubah Foto</a>

                <input type="file" id="fileInput" accept="image/*" capture="environment" style="display: none;" onchange="uploadFile(event)">
            </div>
        </div>

        <div class="card shadow-sm rounded-4 mb-2 border-0">
            <div class="card-body p-3">
                 <h6 class="fw-bold mb-2">Data Akun {{--<span class="float-right text-muted">version: 1.2.7</span>--}}</h6> 
                <div class="list-group">
                    <div class="list-group-item border-0 px-0 d-flex align-items-center justify-content-between" style="gap: 1rem;">
                        <div class="d-flex align-items-center" style="gap: 1rem;">
                            <ion-icon name="id-card-outline" class="text-primary" style="font-size: 20px;"></ion-icon>
                            <div>
                                <small>Nama</small>
                                <p class="mb-0 fw-bold">{{ $profile->nama_lengkap }}</p>
                            </div>
                        </div>
                        <ion-icon name="chevron-forward-outline" class="text-muted" style="font-size: 20px;"></ion-icon>
                    </div>
                    <div class="list-group-item border-0 px-0 d-flex align-items-center justify-content-between" style="gap: 1rem;">
                        <div class="d-flex align-items-center" style="gap: 1rem;">
                            <ion-icon name="card-outline" class="text-primary" style="font-size: 20px;"></ion-icon>
                            <div>
                                <small>NIK Pegawai</small>
                                <p class="mb-0 fw-bold">{{ $profile->nip }}</p>
                            </div>
                        </div>
                        <ion-icon name="chevron-forward-outline" class="text-muted" style="font-size: 20px;"></ion-icon>
                    </div>
                    <div class="list-group-item border-0 px-0 d-flex align-items-center justify-content-between" style="gap: 1rem;">
                        <div class="d-flex align-items-center" style="gap: 1rem;">
                            <ion-icon name="logo-whatsapp" class="text-success" style="font-size: 20px;"></ion-icon>
                            <div>
                                <small>Nomor WhatsApp</small>
                                <p class="mb-0 fw-bold">{{$profile->no_hp}}</p>
                            </div>
                        </div>
                        <ion-icon name="chevron-forward-outline" class="text-muted" style="font-size: 20px;"></ion-icon>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="card shadow-sm rounded-4 mb-2 border-0">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-2">Rekening Bank</h6>
                <p class="mb-0">Belum terdaftar</p>
                <small>-</small>
            </div>
        </div> --}}

        <div class="card shadow-sm rounded-4 mb-2 border-0">
            <div class="card-body p-3">
            	<h6 class="fw-bold mb-2">Penempatan</h6>
                <div class="list-group-item border-0 px-0 d-flex align-items-center justify-content-between" style="gap: 1rem;">
                    <div class="d-flex align-items-center" style="gap: 1rem;">
                        <ion-icon name="person" class="text-black" style="font-size: 20px;"></ion-icon>
                        <div>
                            <small>Posisi</small>
                            <p class="mb-0 fw-bold">{{$profile->jabat->jabatan}}</p>
                        </div>
                    </div>
                </div>
                <div class="list-group-item border-0 px-0 d-flex align-items-center justify-content-between" style="gap: 1rem;">
                    <div class="d-flex align-items-center" style="gap: 1rem;">
                        <ion-icon name="business-outline" class="text-black" style="font-size: 20px;"></ion-icon>
                        <div>
                            <small>Unit Kerja</small>
                            <p class="mb-0 fw-bold">{{$profile->kantor->nama_kantor}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm rounded-4 mb-2 border-0">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-2">Keamanan</h6>
                <div class="list-group">
                    <div class="list-group-item border-0 px-0 d-flex align-items-center justify-content-between" style="gap: 1rem;">
                        <div class="d-flex align-items-center" style="gap: 1rem;">
                            <ion-icon name="lock-closed-outline" class="text-dark fw-bold" style="font-size: 20px;"></ion-icon>
                            <div>
                                <p class="mb-0 fw-bold">Ubah kata sandi</p>
                            </div>
                        </div>
                        <ion-icon name="chevron-forward-outline" class="text-muted" style="font-size: 20px;"></ion-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    function showUploadOptions() {
        document.getElementById('fileInput').click();
    }

    function uploadFile(event) {
        const file = event.target.files[0];
        if (file) {
            const formData = new FormData();
            formData.append('profile_image', file);

            fetch('/absen/profile-image', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Foto berhasil diunggah!');
                    location.reload();
                } else {
                    alert('Terjadi kesalahan saat mengunggah.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan pada server. ' + error.message);
            });
        }
    }
</script>
@endsection