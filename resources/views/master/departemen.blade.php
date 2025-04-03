@extends('layouts.side.side')

@section('content')
<style type="text/css">
    .modal.fade .modal-dialog {
            transform: scale(0.8);
            transition: transform 0.3s ease-out;
        }
        .modal.show .modal-dialog {
            transform: scale(1);
        }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header">{{ __('Daftar Departemen') }}
                    <button class="btn btn-sm btn-primary float-right" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-building-add"></i></button>
                </div>
{{-- Modal Tambah Departemen --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Departemen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahDepartemen">
                    @if(Auth::user()->role == 0)
                    <div class="mb-3">
                        <label for="perusahaan" class="form-label">Perusahaan</label>
                        <select class="form-select" id="perusahaan" name="perusahaan" required>
                            <option value="">Pilih Perusahaan</option>
                            @foreach($perusahaan as $p)
                                <option value="{{ $p->id }}">{{ $p->perusahaan }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                    <div class="mb-3">
                        <label for="kantor" class="form-label">Kantor</label>
                        <select class="form-select" id="kantor" name="kantor" required>
                            <option value="">Pilih Kantor</option>
                            @foreach($kantor as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kantor }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label for="nama_dept" class="form-label">Nama Departemen</label>
                        <input type="text" class="form-control" id="nama_dept" name="nama_dept" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnSimpan">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Departemen -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Departemen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id">

                    @if(Auth::user()->role == 0)
                    <div class="mb-3">
                        <label for="edit_perusahaan" class="form-label">Nama Perusahaan</label>
                        <select name="perusahaan" id="edit_perusahaan" class="form-select" required>
                            <option selected disabled value="">Pilih Perusahaan</option>
                            @foreach($perusahaan as $usaha)
                            <option value="{{$usaha->id}}">{{$usaha->perusahaan}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                    <div class="mb-3">
                        <label for="edit_kantor" class="form-label">Nama Kantor</label>
                        <select name="kantor" id="edit_kantor" class="form-select" required>
                            <option selected disabled value="">Pilih Kantor</option>
                            @foreach($kantor as $office)
                            <option value="{{$office->id}}">{{$office->nama_kantor}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="edit_nama_dept" class="form-label">Nama Departemen</label>
                        <input type="text" class="form-control" id="edit_nama_dept" name="nama_dept" required>
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
                <div class="card-body" style="overflow-x: auto;">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="text-center table-dark">
                            <tr>
                                <th>No</th>
                                <th>Departemen</th>
                                @if(Auth::user()->role == 0)
                                <th>Perusahaan</th>
                                @endif
                                @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                                <th>Kantor</th>
                                @endif
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dept as $key => $item)
                            <tr id="row-{{$item->id}}">
                                <td>{{ $dept->firstItem() + $key }}</td>
                                <td>{{ $item->nama_dept }}</td>
                                @if(Auth::user()->role == 0)
                                <td>{{ $item->perusa->perusahaan }}</td>
                                @endif
                                @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                                <td>{{ $item->nama_kantor == 0 ? '-' : $item->kantor->nama_kantor}}</td>
                                @endif
                                <td class="align-middle text-center">
                    <button class="btn btn-sm btn-primary btnEdit" 
                    data-id="{{ $item->id }}" 
                    data-dept="{{ $item->nama_dept }}"
                    data-kantor="{{ $item->kantor}}"
                    data-perusahaan="{{ $item->perusahaan }}"
                    >Edit</button>
                    <button class="btn btn-sm btn-danger btnHapus" data-id="{{ $item->id }}">Hapus</button>
                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
{{-- jQuery Ajax --}}
<script>
    $(document).ready(function() {
        $('#btnSimpan').click(function() {
            let data = {
    @if(Auth::user()->role == 0 )
        perusahaan: $('#perusahaan').val(), 
    @endif
    @if(Auth::user()->role == 0 || Auth::user()->role == 1)
        kantor: $('#kantor').val(), 
    @endif
                nama_dept: $('#nama_dept').val(),
                _token: '{{ csrf_token() }}'
            };

             $.ajax({
                url: '{{ route("departemen.store") }}',
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Departemen berhasil ditambahkan',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        console.log(response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal menambahkan departemen'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan, coba lagi nanti.'
                    });
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.btnEdit').click(function() {
            let id = $(this).data('id');
            let perusahaan = $(this).data('perusahaan');
            let kantor = $(this).data('kantor');
            let nama_dept = $(this).data('dept');
            
            $('#edit_id').val(id);
            $('#edit_perusahaan').val(perusahaan);
            $('#edit_kantor').val(kantor.id);
            $('#edit_nama_dept').val(nama_dept);
            
            $('#editModal').modal('show');
        });


        $('#editForm').submit(function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: "Konfirmasi Perubahan",
            text: "Perubahan ini akan mempengaruhi beberapa data terkait di database. Apakah Anda yakin ingin melanjutkan?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Update!",
            cancelButtonText: "Batal",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user menekan "Ya, Update!"
                let id = $('#edit_id').val();
                let data = {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    perusahaan: $('#edit_perusahaan').val(),
                    kantor: $('#edit_kantor').val(),
                    nama_dept: $('#edit_nama_dept').val()
                };

                $.ajax({
                    url: '/departemen/update/' + id,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Departemen berhasil diperbarui',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal memperbarui departemen'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan, coba lagi nanti.'
                        });
                    }
                });
            } 
        });
    });



$('.btnHapus').click(function() {
        let id = $(this).data('id');

        Swal.fire({
            title: "Yakin ingin menghapus?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/departemen/' + id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Departemen berhasil dihapus',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan, coba lagi nanti.'
                        });
                    }
                });
            }
        });
    });


    });
</script>

@endpush
