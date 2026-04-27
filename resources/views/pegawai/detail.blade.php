@extends('layouts.side.side')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mw-100">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header fw-bold">{{ __('Data Pegawai') }}
                    <button class="btn btn-sm btn-secondary float-end" onclick="history.back();">Kembali</button>
                </div>

                <div class="card-body">
                    
<div class="row d-flex justify-content-around">
            <div class="col-md-4 text-center mb-2">
                <div class="card p-3">
                    <h5>Foto Pegawai</h5>
                    <div id="foto-container">
                        <!-- Jika foto tersedia -->
                        @if($detail->foto != null)
                        <img id="fotoPegawai" src="{{asset('storage/foto_pegawai/'.$detail->nip.'/'.$detail->foto)}}" alt="Foto Pegawai" class="img-fluid rounded" style="max-height: 200px;">
                        {{-- <button class="btn btn-warning mt-2">Ganti Foto</button> --}}
                        @else
                        <!-- Jika foto tidak tersedia -->
                         @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr><th>Nama</th><td>{{$detail->nama_lengkap}}</td></tr>
                    <tr><th>NIP</th><td>{{$detail->nip}}</td></tr>
                    <tr><th>Ubah Password</th><td><button class="btn btn-secondary" onclick="ubahPassword()">Ubah</button></td></tr>
                    <tr><th>Tanggal Lahir</th><td>{{Carbon\Carbon::parse($detail->tgl_lahir)->locale('id')->isoFormat('D MMMM Y')}}</td></tr>
                    <tr><th>Alamat</th><td>{{$detail->alamat}}</td></tr>
                    <tr><th>Alamat Domisili</th><td>{{$detail->domisili}}</td></tr>
                    <tr><th>No Telepon</th><td>{{$detail->no_hp}}</td></tr>
                    <tr><th>BPJS TK</th><td>{{$detail->bpjs_tk}}</td></tr>
                    <tr><th>BPJS Kesehatan</th><td>{{$detail->bpjs_sehat}}</td></tr>
                    <tr><th>Kontak Darurat</th><td>{{$detail->ko_drat}}</td></tr>
                    {{-- <tr><th>Perusahaan</th><td>{{$detail->perusa->perusahaan}}</td></tr> --}}
                    <tr><th>Unit Kerja</th><td>{{$detail->kantor->nama_kantor}}</td></tr>
                    <tr><th>Satuan Kerja</th><td>{{$detail->sat->satuan_kerja}}</td></tr>
                    <tr><th>Posisi</th><td>{{$detail->jabat->jabatan}}</td></tr>
                    <tr><th>Status Pegawai</th><td>{{$detail->statpegawai}}</td></tr>
                    <tr><th>Verifikasi Wajah</th>
                        <td>
                            @if($detail->face_embedding != null)
                                Sudah verifikasi &nbsp; <button class="btn btn-danger bi bi-trash" id="btnDeleteFace"></button>
                            @else
                                Belum Verifikasi
                            @endif

                        </td>
                    </tr>
                </table>
            </div>
        </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function ubahPassword() {
        Swal.fire({
            title: 'Ubah Password',
            input: 'password',
            inputLabel: 'Masukkan Password Baru',
            inputPlaceholder: 'Password baru',
            inputAttributes: {
                minlength: 6,
                autocapitalize: 'off',
                autocorrect: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            preConfirm: (password) => {
                if (!password) {
                    Swal.showValidationMessage('Password tidak boleh kosong');
                } else {
                    // Kirim password ke server

                    return fetch('{{route('pegawai.upass')}}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ 
                        password, 
                        pegawai_id: "{{ $detail->id }}" 
                    })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    });
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Berhasil!', 'Password telah diperbarui.', 'success');
            }
        });
    }
</script>
<script>
$(document).on('click', '#btnDeleteFace', function () {

    const pegawaiId = "{{ $detail->id }}"; // ambil dari data-id

    Swal.fire({
        title: 'Hapus Data Wajah?',
        text: 'Data face recognition akan dihapus dan harus registrasi ulang.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        allowOutsideClick: false,
    }).then((result) => {

        if (!result.isConfirmed) return;

        Swal.fire({
            title: 'Menghapus...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: `/pegawai/face/delete/${pegawaiId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                Swal.fire({
                    title: 'Berhasil',
                    text: res.message ?? 'Data wajah berhasil dihapus',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            },
            error: function (xhr) {
                Swal.fire(
                    'Gagal',
                    xhr.responseJSON?.message || 'Gagal menghapus data wajah',
                    'error'
                );
            }
        });
    });
});
</script>
@endsection