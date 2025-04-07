@extends('layouts.absen.absen')

@section('header')
	<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" class="ion-icon"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Profile</div>
    <div class="right"></div>
</div>
<style type="text/css">
    #swal2-html-container {
        padding: 5px;
    }
.swal2-input {
    margin: 5px;
}
</style>
@endsection

@section('content')
<div style="margin-top: 3.5rem;" class="bg-light">
	
	<div class="container py-3" style="margin-bottom: 3rem;">
        <div class="card shadow-sm rounded-4 mb-2 border-0">

            <div class="card-body text-center bg-white" style="padding: 20px;">
              
                <div onclick="return showUploadOptions()" class="rounded-circle overflow-hidden shadow-sm bg-secondary text-white d-inline-flex justify-content-center align-items-center" style="width: 80px; height: 80px;">
                    @if($profile->foto == null)
                    <img src="https://ui-avatars.com/api/?name={{$profile->nama_lengkap}}" alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <img id="fotoProfil" src={{asset('storage/foto_pegawai/'.Auth::guard('pegawai')->user()->nip.'/'.$profile->foto)}} alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
                    @endif
                </div>
                 <a href="#" class="text-warning text-decoration-none d-block mt-2">Ubah Foto</a>

                <input type="file" id="fileInput" accept="image/*" style="display: none;" onchange="uploadFile(event)">
            </div>
        </div>

        <div class="card shadow-sm rounded-4 mb-2 border-0">
            <div class="card-body p-3">
                 <h6 class="fw-bold mb-2">Data Akun {{--<span class="float-right text-muted">version: 1.2.7</span>--}}</h6> 
                <div class="list-group">
                    <div id="namaField"  class="list-group-item border-0 px-0 d-flex align-items-center justify-content-between" style="gap: 1rem;">
                        <div class="d-flex align-items-center" style="gap: 1rem;">
                            <ion-icon name="id-card-outline" class="text-primary" style="font-size: 20px;"></ion-icon>
                            <div>
                                <small>Nama</small>
                                <p id="namadisini" class="mb-0 fw-bold">{{ $profile->nama_lengkap }}</p>
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
                        {{-- <ion-icon name="chevron-forward-outline" class="text-muted" style="font-size: 20px;"></ion-icon> --}}
                    </div>
                    <div id="nowaField" class="list-group-item border-0 px-0 d-flex align-items-center justify-content-between" style="gap: 1rem;">
                        <div class="d-flex align-items-center" style="gap: 1rem;">
                            <ion-icon name="logo-whatsapp" class="text-success" style="font-size: 20px;"></ion-icon>
                            <div>
                                <small>Nomor WhatsApp</small>
                                <p id="nowa" class="mb-0 fw-bold">{{$profile->no_hp}}</p>
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
                        <ion-icon name="business-outline" class="text-black" style="font-size: 20px;"></ion-icon>
                        <div>
                            <small>Unit Kerja</small>
                            <p class="mb-0 fw-bold">{{$profile->kantor->nama_kantor}}</p>
                        </div>
                    </div>
                </div>
                <div class="list-group-item border-0 px-0 d-flex align-items-center justify-content-between" style="gap: 1rem;">
                    <div class="d-flex align-items-center" style="gap: 1rem;">
                        <ion-icon name="people-outline" class="text-black" style="font-size: 20px;"></ion-icon>
                        <div>
                            <small>Departemen</small>
                            <p class="mb-0 fw-bold">{{$profile->deptmn->nama_dept}}</p>
                        </div>
                    </div>
                </div>
                <div class="list-group-item border-0 px-0 d-flex align-items-center justify-content-between" style="gap: 1rem;">
                    <div class="d-flex align-items-center" style="gap: 1rem;">
                        <ion-icon name="star-outline" class="text-black" style="font-size: 20px;"></ion-icon>
                        <div>
                            <small>Satuan Kerja</small>
                            <p class="mb-0 fw-bold">{{$profile->sat->satuan_kerja}}</p>
                        </div>
                    </div>
                </div>
                <div class="list-group-item border-0 px-0 d-flex align-items-center justify-content-between" style="gap: 1rem;">
                    <div class="d-flex align-items-center" style="gap: 1rem;">
                        <ion-icon name="person-outline" class="text-black" style="font-size: 20px;"></ion-icon>
                        <div>
                            <small>Posisi</small>
                            <p class="mb-0 fw-bold">{{$profile->jabat->jabatan}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm rounded-4 mb-2 border-0">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-2">Keamanan</h6>
                <div class="list-group">
                    <div id="passField" class="list-group-item border-0 px-0 d-flex align-items-center justify-content-between" style="gap: 1rem;">
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

        <div class="card shadow-sm rounded-4 mb-2 border-0">
            <div class="card-body p-3">
                <div class="list-group">
                    <div id="logoutBtn" class="list-group-item border-0 px-0 d-flex align-items-center justify-content-between" style="gap: 1rem; cursor: pointer;">
                        <div class="d-flex align-items-center" style="gap: 1rem;">
                            <ion-icon name="exit-outline" class="text-danger fw-bold"  style="font-size: 20px;"></ion-icon>
                            <div>
                                <p class="mb-0 fw-bold text-danger">Logout</p>
                            </div>
                            <form id="logoutForm" action="{{ route('absen.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                        <ion-icon name="chevron-forward-outline" class="text-muted" style="font-size: 20px;"></ion-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const logoutBtn = document.getElementById('logoutBtn');

        logoutBtn.addEventListener('click', function () {
            Swal.fire({
                title: 'Yakin ingin logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        });
    });
</script>

<script>
    function showUploadOptions() {
        $('#fileInput').click(); // Trigger klik input file
    }

    function uploadFile(event) {
        const file = event.target.files[0]; // Ambil file dari input

        if (file) {
            const maxSize = 2 * 1024 * 1024; // Maksimal 4MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

            // Validasi ukuran file
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran File Terlalu Besar',
                    text: 'Maksimal ukuran file adalah 2MB.',
                });
                return;
            }

            // Validasi tipe file
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tipe File Tidak Valid',
                    text: 'Hanya file JPG, PNG, dan GIF yang diperbolehkan.',
                });
                return;
            }

            const formData = new FormData();
            formData.append('profile_image', file);

            // Tampilkan loading sebelum proses
            Swal.fire({
                title: 'Mengunggah Foto...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); // Menampilkan loading
                }
            });

            // Kirim request ke server dengan jQuery
            $.ajax({
                url: '/absen/profile-image',
                type: 'POST',
                data: formData,
                processData: false,  // Jangan proses data
                contentType: false,  // Jangan set content-type
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Foto berhasil diunggah!',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                           //  location.reload(); Reload halaman setelah sukses
                            document.getElementById('fotoProfil').src = response.file_url;
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message || 'Terjadi kesalahan saat mengunggah.',
                        });
                    }
                },
                error: function (xhr) {
                    let errorMessage = 'Terjadi kesalahan pada server.';
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON?.errors;
                        if (errors) {
                            errorMessage = Object.values(errors).flat().join('<br>');
                        }
                    } else if (xhr.status === 500) {
                        errorMessage = xhr.responseJSON?.message || 'Kesalahan pada server.';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errorMessage,
                    });
                }
            });
        }
    }


    document.getElementById('namaField').addEventListener('click', function () {
        Swal.fire({
            title: 'Ubah Nama',
            input: 'text',
            inputValue: document.getElementById('namadisini').innerText,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) {
                    return 'Nama tidak boleh kosong!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim perubahan ke server dengan AJAX
                fetch('/absen/update-nama', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ nama: result.value })
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || 'Nama berhasil diubah!',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                           //  location.reload(); Reload halaman setelah sukses
                            document.getElementById('namadisini').innerText = data.name;
                        });
                    } else {
                        Swal.fire('Error!', 'Gagal memperbarui nama.', 'error');
                    }
                }).catch(() => {
                    Swal.fire('Error!', 'Terjadi kesalahan saat mengupdate nama.', 'error');
                });
            }
        });
    });

    document.getElementById('nowaField').addEventListener('click', function () {
        Swal.fire({
        title: 'Ubah Nomor Telepon',
        input: 'tel',
        inputAttributes: {
            maxlength: 15,
            inputmode: 'numeric',
            pattern: '\\d*'
        },
        inputValue: document.getElementById('nowa').innerText,
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
        inputValidator: (value) => {
            if (!value) {
                return 'Nomor telepon tidak boleh kosong!';
            } else if (!/^\d{10,15}$/.test(value)) {
                return 'Nomor telepon harus berisi 10-15 digit angka!';
            }
        }
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim perubahan ke server dengan AJAX
                fetch('/absen/update-nowa', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ nowa: result.value })
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || 'Nomor berhasil diubah!',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                           //  location.reload(); Reload halaman setelah sukses
                            document.getElementById('nowa').innerText = data.name;
                        });
                    } else {
                        Swal.fire('Error!', data.message || 'Gagal memperbarui nomor telepon.', 'error');
                    }
                }).catch(() => {
                    Swal.fire('Error!', 'Terjadi kesalahan saat mengupdate nama.', 'error');
                });
            }
        });
    });

    // Fungsi untuk menampilkan popup
   $(document).on('click', '#passField', function() {
        Swal.fire({
            title: 'Ubah Kata Sandi',
            html: `
            <input type="password" id="currentPassword" class="swal2-input" placeholder="Password Lama" style="width: 95%; max-width: 100%;">
            <input type="password" id="newPassword" class="swal2-input" placeholder="Password Baru" style="width: 95%; max-width: 100%;">
            <input type="password" id="confirmPassword" class="swal2-input" placeholder="Konfirmasi Password" style="width: 95%; max-width: 100%;">
        `,
            confirmButtonText: 'Update',
            focusConfirm: false,
            preConfirm: () => {
                const current = $('#currentPassword').val();
                const newPass = $('#newPassword').val();
                const confirm = $('#confirmPassword').val();

                if (!current || !newPass || !confirm) {
                    Swal.showValidationMessage('Semua bidang harus diisi');
                } else if (newPass !== confirm) {
                    Swal.showValidationMessage('Kata sandi tidak cocok');
                } else {
                    return { current, newPass, confirm };
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/absen/update-pass',
                    method: 'POST',
                    data: {
                        old_password: result.value.current,
                        new_password: result.value.newPass,
                        new_password_confirmation: result.value.confirm,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Berhasil', response.message, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON.message, 'error');
                    }
                });
            }
        });
    });
</script>
@endpush