@extends('layouts.side.side')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header">{{ __('Daftar Jabatan') }}
                    <button class="btn btn-sm btn-primary float-right" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-building-add"></i></button>
                </div>

                <div class="card-body">
@if (Session::get('status'))
<script>
    Swal.fire({
      title: "Berhasil",
      text: "{{Session::get('status')}}",
      icon: "success",
      timer: 1500
    });
</script>
@endif
<style>
    .modal.fade .modal-dialog {
        transform: scale(0.8);
        transition: transform 0.3s ease-out;
    }
    .modal.show .modal-dialog {
        transform: scale(1);
    }
    #map {
        width: 100%;
        height: 50vh;
        min-height: 300px;
    }
</style>
<!-- Modal Tambah Jabatan -->                    
<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                    <form action="{{ route('jabatan')}}/tambah" method="POST">
                        @csrf

                    @if(Auth::user()->role == 0)
                        <div class="mb-3">
                        <label for="tenantName" class="form-label">Nama Perusahaan</label>
                            {{-- <input type="text" class="form-control"name="usaha" placeholder="Masukkan nama kantor" required> --}}
                            <select name="usaha" id="tenantName" class="form-select" required>
                                <option selected disabled value="">Pilih Perusahaan</option>
                                @foreach($perusahaan as $usaha)
                                <option value="{{$usaha->id}}">{{$usaha->perusahaan}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Jabatan" required>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
                </div>
        </div>
    </div>
</div>

<!-- Modal Edit Jabatan -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditJabatan" method="POST">
                    @csrf
                    <input type="hidden" id="editId">
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" class="form-control" id="editJabatan" name="jabatan" required>
                    </div>
                     @if(Auth::user()->role == 0)
                        <div class="mb-3">
                            <label for="perusahaan" class="form-label">Nama Perusahaan</label>
                            <select name="perusahaan" id="perusahaan" class="form-select" required>
                                <option selected disabled value="">Pilih Perusahaan</option>
                            @foreach($perusahaan as $usaha)
                                <option value="{{$usaha->id}}">{{$usaha->perusahaan}}</option>
                            @endforeach
                            </select>
                        </div>
                        @endif
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Data -->
<table class="table table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Perusahaan</th>
            <th>Jabatan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jabatan as $key => $item)
        <tr>
            <td>{{ $jabatan->firstItem() + $key }}</td>
            <td>{{ $item->perusa->perusahaan }}</td>
            <td>{{ $item->jabatan }}</td>
            <td>
                <button class="btn btn-sm btn-primary btnEdit" 
                data-id="{{ $item->id }}" 
                data-jabatan="{{ $item->jabatan }}"
                data-perusahaan="{{ $item->perusahaan }}"
                >Edit</button>
                <button class="btn btn-sm btn-danger btnHapus" data-id="{{ $item->id }}">Hapus</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
$(document).ready(function() {
    // Edit Data
    $('.btnEdit').click(function() {
        let id = $(this).data('id');
        let jabatan = $(this).data('jabatan');
        let perusahaan = $(this).data('perusahaan');
        
        $('#editId').val(id);
        $('#editJabatan').val(jabatan);
        $('#editPerusahaan').val(perusahaan);
        $('#modalEdit').modal('show');
    });

    $('#formEditJabatan').submit(function(e) {
        e.preventDefault();
        let id = $('#editId').val();
        let jabatan = $('#editJabatan').val();
        let perusahaan = $('#editPerusahaan').val();
        
        $.ajax({
            url: '/jabatan/edit/' + id,
            method: 'PUT',
            data: { 
                _token: '{{ csrf_token() }}', 
                jabatan: jabatan, 
                perusahaan: perusahaan
            },
            success: function(response) {
                Swal.fire({
                  title: "Berhasil",
                  icon: "success",
                  text: "Berhasil perbarui Jabatan!",
                  timer: 1500
                });
                location.reload();
            }
        });
    });

    // Hapus Data
    $('.btnHapus').click(function() {
        let id = $(this).data('id');
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, hapus!",
        }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Menghapus...",
                            text: "Mohon tunggu",
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        fetch("/jabatan/hapus/" + id, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire("Terhapus!", "Data telah dihapus.", "success");
                                document.getElementById("row-" + id).remove();
                            } else {
                                Swal.fire("Gagal!", "Terjadi kesalahan.", "error");
                            }
                        })
                        .catch(error => {
                            Swal.fire("Error!", "Gagal menghapus data.", "error");
                        });
                    }
                });
    });
});
</script>
@endsection
