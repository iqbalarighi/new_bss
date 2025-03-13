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
          icon: "success",
          showConfirmButton: false,
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

        th {
            text-align: center;
            vertical-align: middle;
        }
    </style>
    <!-- Modal Bootstrap -->                    
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered animate__animated animate__zoomIn">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Jabatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/jabatan/tambah" method="POST">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submmit" class="btn btn-primary">Simpan</button>
                </div> 
            </form>
            </div>
        </div>
    </div>
    <!-- Modal Bootstrap -->
    <div>
            <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                @if(Auth::user()->role == 0)
                    <th>Tenant</th>
                @endif
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jabatan as $key => $item)
                <tr>
                    <td class="align-middle text-center">{{$jabatan->firstitem()+$key}}</td>
                @if(Auth::user()->role == 0)
                    <td>{{$item->perusa->perusahaan}}</td>
                @endif
                    <td>{{$item->jabatan}}</td>
                    <td class="align-middle text-center">
                        <button class="btn btn-primary btn-sm cen">Edit</button>
                        <button class="btn btn-danger btn-sm cen">Hapus</button>
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
</div>
@endsection