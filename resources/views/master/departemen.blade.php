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
                    <div class="mb-3">
                        <label for="kantor" class="form-label">Kantor</label>
                        <select class="form-select" id="kantor" name="kantor" required>
                            <option value="">Pilih Kantor</option>
                            @foreach($kantor as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kantor }}</option>
                            @endforeach
                        </select>
                    </div>
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
                <div class="card-body">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="text-center table-dark">
                            <tr>
                                <th>No</th>
                                @if(Auth::user()->role == 0)
                                <th>Perusahaan</th>
                                @endif
                                <th>Kantor</th>
                                <th>Departemen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dept as $key => $item)
                            <tr id="row-{{$item->id}}">
                                <td>{{ $dept->firstItem() + $key }}</td>
                                @if(Auth::user()->role == 0)
                                <td>{{ $item->perusa->perusahaan }}</td>
                                @endif
                                <td>{{ $item->kantor->nama_kantor }}</td>
                                <td>{{ $item->nama_dept }}</td>
                                <td class="align-middle text-center">
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
                perusahaan: $('#perusahaan').val(),
                kantor: $('#kantor').val(),
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

@endpush