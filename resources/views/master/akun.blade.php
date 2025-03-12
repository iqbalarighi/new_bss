@extends('layouts.side.side')

@section('content')
<style>
    body {
        background-color: #ff4d4d;
    }
    .card {
        border-radius: 15px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        width: 100%;
        max-width: 600px;
    }
    .btn-custom {
        background-color: #cc0000;
        color: white;
    }
    .btn-custom:hover {
        background-color: #990000;
    }
</style>

<div class="container d-flex justify-content-center align-items-center">
    <div class="card p-4">
        <h3 class="text-center mb-3 text-danger">Tambah user</h3>
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
                    <option value="1">Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="company" class="form-label">Perusahaan</label>
                <select id="company" name="company" class="form-select" required>
                    <option value="">Pilih Perusahaan</option>
                    <option value="company1">Perusahaan A</option>
                    <option value="company2">Perusahaan B</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="office" class="form-label">Kantor</label>
                <select id="office" name="office" class="form-select" required>
                    <option value="">Pilih Kantor</option>
                    <option value="office1">Kantor Pusat</option>
                    <option value="office2">Cabang 1</option>
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
    @endsection