@extends('layouts.side.side')

@section('content')
<style>
    .card {
        border-radius: 15px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        width: 100%;
/*        max-width: 600px;*/
    }
    .btn-custom {
        background-color: #cc0000;
        color: white;
    }
    .btn-custom:hover {
        background-color: #990000;
    }
</style>
<style>
        .modal.fade .modal-dialog {
            transform: scale(0.8);
            transition: transform 0.3s ease-out;
        }
        .modal.show .modal-dialog {
            transform: scale(1);
        }
         thead {
            text-align: center;
            vertical-align: middle !important;
        }
    </style>
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




<div class="container">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header">{{ __('User Management') }}
                    <button class="btn btn-custom mb-0 btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-person-plus"></i>
            </button>
                </div>

                <div class="card-body" style="overflow-x: auto;">
<table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Perusahaan</th>
                        <th>Kantor</th>
                        <th>Jabatan</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Contoh data, nanti bisa diganti dengan loop dari backend -->
                    <tr>
                        <td>1</td>
                        <td>John Doe</td>
                        <td>john@example.com</td>
                        <td>Perusahaan A</td>
                        <td>Kantor Pusat</td>
                        <td>Manager</td>
                        <td>Admin</td>
                        <td class="align-middle text-center">
                            <button class="btn btn-primary btn-sm cen">Edit</button>
                            <button class="btn btn-danger btn-sm cen">Hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Jane Doe</td>
                        <td>jane@example.com</td>
                        <td>Perusahaan B</td>
                        <td>Cabang 1</td>
                        <td>Staff</td>
                        <td>User</td>
                        <td class="align-middle text-center">
                            <button class="btn btn-primary btn-sm cen">Edit</button>
                            <button class="btn btn-danger btn-sm cen">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>

                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Modal Tambah User -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="addUserModalLabel">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Daftar sebagai</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="2">User</option>
                                @if(Auth::user()->role == 0)<option value="1">Admin</option>@endif
                            </select>
                        </div>
                        @if(Auth::user()->role == 0)
                        <div class="mb-3">
                            <label for="company" class="form-label">Perusahaan</label>
                            <select id="company" name="company" class="form-select" required>
                                <option value="">Pilih Perusahaan</option>
                                <option value="company1">Perusahaan A</option>
                                <option value="company2">Perusahaan B</option>
                            </select>
                        </div>
                        @endif
                        <div class="mb-3">
                            <label for="office" class="form-label">Kantor</label>
                            <select id="office" name="office" class="form-select" required>
                                <option value="">Pilih Kantor</option>
                                <option value="office1">Kantor Pusat</option>
                                <option value="office2">Cabang 1</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="satker" class="form-label">Satuan Kerja</label>
                            <select id="satker" name="satker" class="form-select" required>
                                <option value="">Pilih Satuan Kerja</option>
                                <option value="manager">Manager</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">Jabatan</label>
                            <select id="position" name="position" class="form-select" required>
                                <option value="">Pilih Jabatan</option>
                                <option value="manager">Manager</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-custom w-100">Daftar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
