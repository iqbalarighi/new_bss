@extends('layouts.side.side')

@section('content')
@if(Session::get('success'))
<script type="text/javascript">
    Swal.fire({
  title: "Berhasil",
  text: "{{Session::get('success')}}",
  icon: "success",
  showConfirmButton: false,
  timer: 1500
});
</script>
@endif
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Daftar Tenant') }}
                    <button class="btn btn-sm btn-primary float-right" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-building-add"></i></button>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
<style>
        .modal.fade .modal-dialog {
            transform: scale(0.8);
            transition: transform 0.3s ease-out;
        }
        .modal.show .modal-dialog {
            transform: scale(1);
        }
    </style>
    <!-- Modal Bootstrap -->                    
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered animate__animated animate__zoomIn">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Daftar Tenant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/tenant/tambah" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="tenantName" class="form-label">Nama Tenant</label>
                            <input type="text" class="form-control" id="tenantName" name="tenant" placeholder="Masukkan nama tenant" required>
                        </div>
                        <div class="mb-3">
                            <label for="tenantAddress" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="tenantAddress" name="alamat" placeholder="Masukkan alamat" required>
                        </div>
                        <div class="mb-3">
                            <label for="tenantPhone" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="tenantPhone" name="telp" placeholder="Masukkan nomor telepon" required>
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

            <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Tenant</th>
                    <th>Alamat</th>
                    <th>No. Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($perusahaan as $key => $usaha)
                <tr>
                    <td>{{$perusahaan->firstitem()+$key}}</td>
                    <td>{{$usaha->perusahaan}}</td>
                    <td>{{$usaha->alamat}}</td>
                    <td>{{$usaha->no_tlp}}</td>
                    <td>
                        <button class="btn btn-primary btn-sm">Edit</button>
                        <button class="btn btn-danger btn-sm">Hapus</button>
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