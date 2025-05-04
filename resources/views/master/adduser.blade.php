@extends('layouts.side.side')

@section('content')
<style>
    
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




<div class="container mw-100">
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
                        @if(Auth::user()->role == 0)
                            <th>Perusahaan</th>
                        @endif
                        @if(Auth::user()->role == 0 || Auth::user()->role == 1 )
                            <th class="text-start position-relative">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Kantor</span>
                                    <div class="dropdown">
                                        <i class="fas fa-filter ms-2 text-white" role="button" data-bs-toggle="dropdown"></i>
                                        <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width: 150px;">
                                            <li>
                                                <select id="filterKantor" class="form-select form-select-sm" onchange="filterTable('kantor', this.value)">
                                                    <option value="">Semua</option>
                                                    @foreach($kantor as $k)
                                                        <option value="{{ $k->nama_kantor }}">{{ $k->nama_kantor }}</option>
                                                    @endforeach
                                                </select>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </th>
                            @endif
                            <th class="text-start position-relative">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Departemen</span>
                                    <div class="dropdown">
                                        <i class="fas fa-filter ms-2 text-white" role="button" data-bs-toggle="dropdown"></i>
                                        <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width: 150px;">
                                            <li>
                                                <select id="filterDepartemen" class="form-select form-select-sm" onchange="filterTable('departemen', this.value)">
                                                    <option value="">Semua</option>
                                                    @foreach($dept->unique('nama_dept') as $d)
                                                        <option value="{{ $d->nama_dept }}">{{ $d->nama_dept }}</option>
                                                    @endforeach
                                                </select>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </th>
                            <th class="text-start position-relative">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Satuan Kerja</span>
                                    <div class="dropdown">
                                        <i class="fas fa-filter ms-2 text-white" role="button" data-bs-toggle="dropdown"></i>
                                        <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width: 150px;">
                                            <li>
                                                <select id="filterSatker" class="form-select form-select-sm" onchange="filterTable('satker', this.value)">
                                                    <option value="">Semua</option>
                                                    @foreach($satker->unique('satuan_kerja') as $s)
                                                        <option value="{{ $s->satuan_kerja }}">{{ $s->satuan_kerja }}</option>
                                                    @endforeach
                                                </select>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </th>
                            <th class="text-start position-relative">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Jabatan</span>
                                    <div class="dropdown">
                                        <i class="fas fa-filter ms-2 text-white" role="button" data-bs-toggle="dropdown"></i>
                                        <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width: 150px;">
                                            <li>
                                                <select id="filterJabatan" class="form-select form-select-sm" onchange="filterTable('jabatan', this.value)">
                                                    <option value="">Semua</option>
                                                    @foreach($jabat->unique('jabatan') as $d)
                                                        <option value="{{ $d->jabatan }}">{{ $d->jabatan }}</option>
                                                    @endforeach
                                                </select>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                <tbody>
                    <!-- Contoh data, nanti bisa diganti dengan loop dari backend -->
                    @foreach($users as $key => $item)
                    <tr>
                        <td>{{$users->firstItem() + $key}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->email}}</td>
                    @if(Auth::user()->role == 0)
                        <td>
                            @if ($item->perusahaan == 0)
                            -
                            @else
                               {{$item->perusa->perusahaan}}
                            @endif
                        </td>
                    @endif
                        
                    @if(Auth::user()->role == 1 || Auth::user()->role == 0)
                        <td>
                            @if ($item->kantor == 0)
                            -
                            @else
                               {{$item->kant->nama_kantor}}
                            @endif
                        </td>
                    @endif
                        <td>
                            @if ($item->dept == 0)
                            -
                            @else
                               {{$item->deptmn->nama_dept}}
                            @endif
                        </td>
                        <td>
                            @if ($item->satker == 0)
                            -
                            @else
                               {{$item->sat->satuan_kerja}}
                            @endif
                        </td>
                        <td>
                            @if ($item->jabatan == 0)
                            -
                            @else
                               {{$item->jabat->jabatan}}
                            @endif
                        </td>
                        <td>
                            @if($item->role == 0)
                                Superadmin
                            @elseif($item->role == 1)
                                Admin Pusat
                            @elseif($item->role == 3)
                                Admin Cabang
                            @else
                                User
                            @endif
                        </td>
                        <td class="align-middle text-center">
                            <button class="btn btn-primary btn-sm editUserBtn"
                            data-id="{{$item->id}}"
                            data-name="{{$item->name}}"
                            data-email="{{$item->email}}"
                            data-role="{{$item->role}}"
                            data-company="{{Auth::user()->role != 0 ? Auth::user()->perusahaan : $item->perusahaan}}"
                            data-office="{{$item->kantor}}"
                            data-dept="{{$item->dept}}"
                            data-satker="{{$item->satker}}"
                            data-position="{{$item->jabatan}}"
                            data-bs-toggle="modal"
                            data-bs-target="#editUserModal">Edit</button>
                            <button class="btn btn-danger btn-sm del-btn" data-id="{{$item->id}}">Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
 <div class="d-flex justify-content-center">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
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
                    <form method="POST" action="{{ route('adduser') }}" id="addUser">
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
                        <div id="passLengthError" class="text-danger mt-1" style="display: none; font-size: 0.875rem;">
                            ⚠ Kata sandi minimal 6 karakter!
                        </div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Kata Sandi</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                            <div id="passError" class="text-danger mt-1" style="display: none; font-size: 0.875rem;">
                                ⚠ Kata sandi tidak cocok!
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Daftar sebagai</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="2">User</option>
                                @if(Auth::user()->role == 0)<option value="1">Admin Pusat</option>@endif
                                @if(Auth::user()->role == 0 || Auth::user()->role == 1)<option value="3">Admin Cabang</option>@endif
                            </select>
                        </div>
                    @if(Auth::user()->role == 0)
                        <div class="mb-3">
                            <label for="company" class="form-label">Perusahaan</label>
                            <select id="company" name="company" class="form-select" required>
                                <option value="">Pilih Perusahaan</option>
                                @foreach($perusa as $usa)
                                    <option value="{{$usa->id}}">{{$usa->perusahaan}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                        <div class="mb-3" id="office-container">
                            <label for="office" class="form-label">Kantor</label>
                            <select id="office" name="office" class="form-select">
                            </select>
                        </div>
                    @endif
                        <div class="mb-3" id="dept-container">
                            <label for="dept" class="form-label">Departemen</label>
                            <select id="dept" name="dept" class="form-select">
                            </select>
                        </div>
                        <div class="mb-3" id="satker-container">
                            <label for="satker" class="form-label">Satuan Kerja</label>
                            <select id="satker" name="satker" class="form-select">
                            </select>
                        </div>
                        <div class="mb-3" id="position-container">
                            <label for="position" class="form-label">Jabatan</label>
                            <select id="position" name="position" class="form-select">
                            </select>
                        </div>
                        <button type="submit" class="btn btn-custom w-100">Daftar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Edit -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    @csrf
                    <input type="hidden" id="edit_user_id" name="user_id">
                    
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Lengkap</label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" id="edit_email" name="email" class="form-control" required>
                        
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Kata Sandi</label>
                        <input type="password" id="edit_password" name="password" class="form-control" autocomplete="off">
                        <div id="passwordLengthError" class="text-danger mt-1" style="display: none; font-size: 0.875rem;">
                            ⚠ Kata sandi minimal 6 karakter!
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_confirm_password" class="form-label">Konfirmasi Kata Sandi</label>
                        <input type="password" id="edit_confirm_password" name="confirm_password" class="form-control">
                        <div id="passwordError" class="text-danger mt-1" style="display: none; font-size: 0.875rem;">
                            ⚠ Kata sandi tidak cocok!
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Daftar sebagai</label>
                        <select id="edit_role" name="role" class="form-select" required>
                            <option value="2">User</option>
                            @if(Auth::user()->role == 0 || Auth::user()->role == 1)<option value="1">Admin Pusat</option>@endif
                            @if(Auth::user()->role == 0 || Auth::user()->role == 1 || Auth::user()->role == 3)
                                <option value="3">Admin Cabang</option>
                                @endif
                        </select>
                    </div>
                    @if(Auth::user()->role == 0)
                        <div class="mb-3">
                            <label for="edit_company" class="form-label">Perusahaan</label>
                            <select id="edit_company" name="company" class="form-select" required>
                                <option value="">Pilih Perusahaan</option>
                                @foreach($perusa as $usa)
                                    <option value="{{$usa->id}}">{{$usa->perusahaan}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                        <div class="mb-3" id="edit_office-container">
                            <label for="edit_office" class="form-label">Kantor</label>
                            <select id="edit_office" name="office" class="form-select">
                                @foreach($kantor as $kan)
                                    <option value="{{$kan->id}}">{{$kan->nama_kantor}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="mb-3" id="edit_dept-container">
                        <label for="edit_dept" class="form-label">Departemen</label>
                        <select id="edit_dept" name="dept" class="form-select">
                            @foreach($dept as $dep)
                                    <option value="{{$dep->id}}">{{$dep->nama_dept}}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="mb-3" id="edit_satker-container">
                        <label for="edit_satker" class="form-label">Satuan Kerja</label>
                        <select id="edit_satker" name="satker" class="form-select">
                            @foreach($satker as $sat)
                                    <option value="{{$sat->id}}">{{$sat->satuan_kerja}}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="mb-3" id="edit_position-container">
                        <label for="edit_position" class="form-label">Jabatan</label>
                        <select id="edit_position" name="position" class="form-select">
                            @foreach($jabat as $jab)
                                    <option value="{{$jab->id}}">{{$jab->jabatan}}</option>
                                @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-custom w-100">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@push('script')
 <!-- jQuery untuk menonaktifkan dan menyembunyikan Satker & Jabatan jika memilih Admin -->
    <script>
        $(document).ready(function() {
            function toggleFields() {
                if ($('#role').val() == "3") { // Jika memilih Admin
                    $('#satker-container, #position-container, #dept-container').hide();
                    $('#satker, #position, #dept').prop("disabled", true);
                    $('#office-container').show();
                    $('#office').prop("disabled", false);
                    $('#office').prop("required", true);
                } else if ($('#role').val() == "1") { // Jika memilih Admin
                    $('#satker-container, #position-container, #office-container, #dept-container').hide();
                    $('#satker, #position, #office, #dept').prop("disabled", true);
                    $('#office').prop("required", false);
                    $('#satker').prop("required", false);
                    $('#dept').prop("required", false);
                    $('#position').prop("required", false);
                } else { // Jika memilih User
                    $('#satker-container, #position-container, #office-container, #dept-container').show();
                    $('#satker, #position, #office, #dept').prop("disabled", false);
                    $('#office').prop("required", true);
                    $('#satker').prop("required", true);
                    $('#dept').prop("required", true);
                    $('#position').prop("required", true);
                }
            }
            $('#role').change(function() {
                toggleFields();
            });
            
            // Panggil fungsi saat halaman dimuat pertama kali
            toggleFields();
        });
    </script>

<script>
$(document).ready(function () {
    function edittoggleFields() {
        var selectedRole = $('#edit_role').val();

        if (selectedRole == "3") {
            $('#edit_satker-container, #edit_position-container, #edit_dept-container').hide();
            $('#edit_satker, #edit_position, #edit_dept').prop("disabled", true);
            $('#edit_office-container').show();
            $('#edit_office').prop("disabled", false).prop("required", true);
        } else if (selectedRole == "1") {
            $('#edit_satker-container, #edit_position-container, #edit_office-container, #edit_dept-container').hide();
            $('#edit_satker, #edit_position, #edit_office, #edit_dept').prop("disabled", true).prop("required", false);
        } else {
            $('#edit_satker-container, #edit_position-container, #edit_office-container, #edit_dept-container').show();
            $('#edit_satker, #edit_position, #edit_office, #edit_dept').prop("disabled", false).prop("required", true);
        }
    }

    function validatePassword() {
        var password = $('#edit_password').val();
        var confirmPassword = $('#edit_confirm_password').val();
        var isValid = true;

        if (password && password.length < 6) {
            $('#passwordLengthError').show();
            $('#edit_password').addClass('is-invalid');
            isValid = false;
        } else {
            $('#passwordLengthError').hide();
            $('#edit_password').removeClass('is-invalid');
        }

        if (password && password !== confirmPassword) {
            $('#passwordError').show();
            $('#edit_confirm_password').addClass('is-invalid');
            isValid = false;
        } else {
            $('#passwordError').hide();
            $('#edit_confirm_password').removeClass('is-invalid');
        }

        return isValid;
    }

    $('#edit_role').change(edittoggleFields);
    edittoggleFields(); // saat load awal

    $('#edit_password, #edit_confirm_password').keyup(validatePassword);

    $(document).on("click", ".editUserBtn", function () {
        let btn = $(this);
        let id = btn.data("id");
        let name = btn.data("name");
        let email = btn.data("email");
        let perusahaan = btn.data("company");
        let kantor = btn.data("office");
        let dept = btn.data("dept");
        let satker = btn.data("satker");
        let posisi = btn.data("position");
        let role = btn.data("role");

        $("#edit_user_id").val(id);
        $("#edit_name").val(name);
        $("#edit_email").val(email);
        $("#edit_role").val(role);
        edittoggleFields(); // gunakan fungsi saja

        $("#edit_password, #edit_confirm_password").val("");
        $("#passwordLengthError, #passwordError").hide();

        @if(Auth::user()->role == 0)
            $("#edit_company").val(perusahaan);
        @endif
        @if(Auth::user()->role == 0 || Auth::user()->role == 1)
            $("#edit_office").val(kantor);
        @endif

        $("#edit_dept").html('<option value="">Loading...</option>');
        $("#edit_satker").html('<option value="">Loading...</option>');
        $("#edit_position").html('<option value="">Loading...</option>');
        $("#edit_office").html('<option value="">Loading...</option>');

        if (perusahaan) {
            $.getJSON('/get-konten/' + perusahaan, function (response) {
                let deptOptions = '<option value="">Pilih Departemen</option>';
                let satkerOptions = '<option value="">Pilih Satuan Kerja</option>';
                let positionOptions = '<option value="">Pilih Posisi</option>';
                let officeOptions = '<option value="">Pilih Kantor</option>';

                $.each(response.offices, function (i, o) {
                    console.log(o.id == kantor);
                    officeOptions += `<option value="${o.id}" ${o.id == kantor ? 'selected' : ''}>${o.nama_kantor}</option>`;
                });

                $.each(response.depts, function (i, d) {
                    deptOptions += `<option value="${d.id}" ${d.id == dept ? 'selected' : ''}>${d.nama_dept}</option>`;
                });

                $.each(response.satkers, function (i, s) {
                    satkerOptions += `<option value="${s.id}" ${s.id == satker ? 'selected' : ''}>${s.satuan_kerja}</option>`;
                });

                $.each(response.positions, function (i, p) {
                    positionOptions += `<option value="${p.id}" ${p.id == posisi ? 'selected' : ''}>${p.jabatan}</option>`;
                });

                $("#edit_dept").html(deptOptions);
                $("#edit_satker").html(satkerOptions);
                $("#edit_position").html(positionOptions);
                $("#edit_office").html(officeOptions);
            });
        }

        $("#editUserModal").modal("show");
    });

    $('#editUserForm').submit(function (e) {
        e.preventDefault();

        if (!validatePassword()) return;

        Swal.fire({
            title: "Konfirmasi Perubahan",
            text: "Apakah Anda yakin ingin menyimpan perubahan ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, simpan!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                var formData = $(this).serialize() + '&_method=PUT';
                $.ajax({
                    url: '/users/update',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data user berhasil diperbarui.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $('#editUserModal').modal('hide');
                            location.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan!',
                            text: 'Cek kembali data yang diinput.'
                        });
                    }
                });
            }
        });
    });
});
</script>

@if(Auth::user()->role == 0)
<script type="text/javascript">
    $(document).ready(function() {
        $('#company').change(function() {
            var companyId = $(this).val();
            
            if (companyId) {
                $.ajax({
                    url: '/get-konten/' + companyId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $('#office').empty();
                        $('#office').append('<option value="">Pilih Kantor</option>');
                        
                        $.each(response.offices, function(key, office) {
                            $('#office').append('<option value="' + office.id + '">' + office.nama_kantor + '</option>');
                        });

                        $('#satker').empty();
                        $('#satker').append('<option value="">Pilih Satuan Kerja</option>');
                        
                        $.each(response.satkers, function(key, satker) {
                            $('#satker').append('<option value="' + satker.id + '">' + satker.satuan_kerja + '</option>');
                        });

                        $('#position').empty();
                        $('#position').append('<option value="">Pilih Jabatan</option>');
                        
                        $.each(response.positions, function(key, position) {
                            $('#position').append('<option value="' + position.id + '">' + position.jabatan + '</option>');
                        });

                        $('#dept').empty();
                        $('#dept').append('<option value="">Pilih Departemen</option>');
                        
                        $.each(response.depts, function(key, dept) {
                            $('#dept').append('<option value="' + dept.id + '">' + dept.nama_dept + '</option>');
                        });

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#office').empty().append('<option value="">Pilih Kantor</option>');
                $('#satker').empty().append('<option value="">Pilih Satuan Kerja</option>');
                $('#position').empty().append('<option value="">Pilih Jabatan</option>');
                $('#dept').empty().append('<option value="">Pilih Departemen</option>');
            }
        });
    });
</script>
@else
<script type="text/javascript">
    $(document).ready(function() {
    $.ajax({
        url: '/get-konten/' + {{Auth::user()->perusahaan}},
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#office').empty();
            $('#office').append('<option value="">Pilih Kantor</option>');
            
            $.each(response.offices, function(key, office) {
                $('#office').append('<option value="' + office.id + '">' + office.nama_kantor + '</option>');
            });

            $('#satker').empty();
            $('#satker').append('<option value="">Pilih Satuan Kerja</option>');
            
            $.each(response.satkers, function(key, satker) {
                $('#satker').append('<option value="' + satker.id + '">' + satker.satuan_kerja + '</option>');
            });

            $('#position').empty();
            $('#position').append('<option value="">Pilih Jabatan</option>');
            
            $.each(response.positions, function(key, position) {
                $('#position').append('<option value="' + position.id + '">' + position.jabatan + '</option>');
            });

            $('#dept').empty();
            $('#dept').append('<option value="">Pilih Departemen</option>');
            
            $.each(response.depts, function(key, dept) {
                $('#dept').append('<option value="' + dept.id + '">' + dept.nama_dept + '</option>');
            });

        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});

</script>
@endif
<script>
    $(document).ready(function() {
        $('#office').change(function() {
            let perusahaanId = $(this).val();
            if (perusahaanId) {
                $.ajax({
                    url: '/get-sat/' + perusahaanId,
                    type: 'GET',
                    success: function(response) {
                        let departemenOptions = '<option value="">Pilih Departemen</option>';
                        let satkerOptions = '<option value="">Pilih Satuan Kerja</option>';

                        response.departemen.forEach(function(dept) {
                            departemenOptions += `<option value="${dept.id}">${dept.nama_dept}</option>`;
                        });
                        response.satker.forEach(function(satker) {
                            satkerOptions += `<option value="${satker.id}">${satker.nama_satker}</option>`;
                        });

                        $('#dept').html(departemenOptions);
                        $('#satker').html(satkerOptions);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            } else {
                $('#dept').empty().append('<option value="">Pilih Departemen</option>');
                $('#satker').empty().append('<option value="">Pilih Satuan Kerja</option>');
                $('#position').empty().append('<option value="">Pilih Jabatan</option>');
            }
        });

        $('#dept').change(function() {
            let departemenId = $(this).val();
            if (departemenId) {
                $.ajax({
                    url: '/get-satker-by-departemen/' + departemenId,
                    type: 'GET',
                    success: function(response) {
                        let satkerOptions = '<option value="">Pilih Satuan Kerja</option>';
                        response.satker.forEach(function(satker) {
                            satkerOptions += `<option value="${satker.id}">${satker.satuan_kerja}</option>`;
                        });
                        $('#satker').html(satkerOptions);

                        $('#position').empty();
                    $('#position').append('<option value="">Pilih Jabatan</option>');
                    
                    $.each(response.positions, function(key, position) {
                        $('#position').append('<option value="' + position.id + '">' + position.jabatan + '</option>');
                    });

                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            } else {
                $('#satker').empty().append('<option value="">Pilih Satuan Kerja</option>');
                $('#position').empty().append('<option value="">Pilih Jabatan</option>');
            }
        });

        $('#satker').change(function() {
            let satId = $(this).val();
            if (satId) {
                $.ajax({
                    url: '/get-position-by-satker/' + satId,
                    type: 'GET',
                    success: function(response) {

            $('#position').empty();
            $('#position').append('<option value="">Pilih Jabatan</option>');
            
            $.each(response.positions, function(key, position) {
                $('#position').append('<option value="' + position.id + '">' + position.jabatan + '</option>');
            });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            } else {
                $('#position').empty().append('<option value="">Pilih Jabatan</option>');
            }
        });
    });
</script>
@if(Auth::user()->role == 0)
<script type="text/javascript">

        var companyId = $('#edit_company').val();
        
        if (companyId) {
            $.ajax({
                url: '/get-konten/' + companyId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#edit_office').empty().append('<option value="">Pilih Kantor</option>');
                    $.each(response.offices, function(key, office) {
                        $('#edit_office').append('<option value="' + office.id + '">' + office.nama_kantor + '</option>');
                    });

                    $('#edit_satker').empty().append('<option value="">Pilih Satuan Kerja</option>');
                    $.each(response.satkers, function(key, satker) {
                        $('#edit_satker').append('<option value="' + satker.id + '">' + satker.satuan_kerja + '</option>');
                    });

                    $('#edit_position').empty().append('<option value="">Pilih Jabatan</option>');
                    $.each(response.positions, function(key, position) {
                        $('#edit_position').append('<option value="' + position.id + '">' + position.jabatan + '</option>');
                    });

                    $('#edit_dept').empty().append('<option value="">Pilih Departemen</option>');
                    $.each(response.depts, function(key, dept) {
                        $('#edit_dept').append('<option value="' + dept.id + '">' + dept.nama_dept + '</option>');
                    });

                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            $('#edit_office').empty().append('<option value="">Pilih Kantor</option>');
            $('#edit_satker').empty().append('<option value="">Pilih Satuan Kerja</option>');
            $('#edit_position').empty().append('<option value="">Pilih Jabatan</option>');
            $('#edit_dept').empty().append('<option value="">Pilih Departemen</option>');
        }
    

    // Panggil saat perusahaan berubah
    // $('#edit_company').change(function() {
    //     loadCompanyData();
    // });

    // Panggil saat modal edit dibuka
    // $('#editUserModal').on('shown.bs.modal', function () {
    //     loadCompanyData();
    // });
</script>
@endif

<script>
    $(document).ready(function() {
        $('#edit_office').change(function() {
            let perusahaanId = $(this).val();
            if (perusahaanId) {
                $.ajax({
                    url: '/get-sat/' + perusahaanId,
                    type: 'GET',
                    success: function(response) {
                        let departemenOptions = '<option value="">Pilih Departemen</option>';
                        let satkerOptions = '<option value="">Pilih Satuan Kerja</option>';

                        response.departemen.forEach(function(dept) {
                            departemenOptions += `<option value="${dept.id}">${dept.nama_dept}</option>`;
                        });
                        response.satker.forEach(function(satker) {
                            satkerOptions += `<option value="${satker.id}">${satker.nama_satker}</option>`;
                        });

                        $('#edit_dept').html(departemenOptions);
                        $('#edit_satker').html(satkerOptions);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            } else {
                $('#edit_satker').empty().append('<option value="">Pilih Satuan Kerja</option>');
                $('#edit_position').empty().append('<option value="">Pilih Jabatan</option>');
            }
        });

        $('#edit_dept').change(function() {
            let departemenId = $(this).val();
            if (departemenId) {
                $.ajax({
                    url: '/get-satker-by-departemen/' + departemenId,
                    type: 'GET',
                    success: function(response) {
                        let satkerOptions = '<option value="">Pilih Satuan Kerja</option>';
                        response.satker.forEach(function(satker) {
                            satkerOptions += `<option value="${satker.id}">${satker.satuan_kerja}</option>`;
                        });
                        $('#edit_satker').html(satkerOptions);

                        $('#edit_position').empty();
                        $('#edit_position').append('<option value="">Pilih Jabatan</option>');
                        
                        $.each(response.positions, function(key, position) {
                            $('#edit_position').append('<option value="' + position.id + '">' + position.jabatan + '</option>');
                        });

                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            } else {
                $('#edit_satker').empty().append('<option value="">Pilih Satuan Kerja</option>');
                 $('#edit_position').empty().append('<option value="">Pilih Jabatan</option>');
            }
        });

        $('#edit_satker').change(function() {
            let satId = $(this).val();
            if (satId) {
                $.ajax({
                    url: '/get-position-by-satker/' + satId,
                    type: 'GET',
                    success: function(response) {

            $('#edit_position').empty();
            $('#edit_position').append('<option value="">Pilih Jabatan</option>');
            
            $.each(response.positions, function(key, position) {
                $('#edit_position').append('<option value="' + position.id + '">' + position.jabatan + '</option>');
            });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            } else {
                $('#edit_position').empty().append('<option value="">Pilih Jabatan</option>');
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
    function validPassword() {
        var pass = $('#password').val();
        var confirmPass = $('#confirm_password').val();

        var isValid = true;

        // Cek panjang password minimal 6 karakter
        if (pass.length < 6) {
            $('#passLengthError').show();
            $('#edit_password').addClass('is-invalid');
            isValid = false;
        } else {
            $('#passLengthError').hide();
            $('#password').removeClass('is-invalid');
        }

        // Cek apakah password dan konfirmasi password cocok
        if (pass !== confirmPass) {
            $('#passError').show();
            $('#confirm_password').addClass('is-invalid');
            isValid = false;
        } else {
            $('#passError').hide();
            $('#confirm_password').removeClass('is-invalid');
        }

        return isValid;
    }

    // Cek saat user mengetik di input password atau confirm password
    $('#password, #confirm_password').keyup(function () {
        validPassword();
    });

    // Cek sebelum submit form
    $('#addUser').submit(function (e) {
        if (!validPassword()) {
            e.preventDefault(); // Hentikan submit jika password tidak cocok
        }
    });
});

</script>
<script type="text/javascript">
    // Hapus Data
    $('.del-btn').click(function () {
        var userId = $(this).data('id'); // Ambil ID user dari atribut data-id

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/users/delete/' + userId, // Sesuaikan dengan route di Laravel
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Data user telah dihapus.',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            location.reload(); // Reload halaman setelah berhasil dihapus
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan!',
                            text: 'Gagal menghapus data.'
                        });
                    }
                });
            }
        });
    });
</script>
<script>
    function filterTable(type, value) {
        const selectedValue = value.toLowerCase();
        const rows = document.querySelectorAll("table tbody tr");

        // Tentukan index kolom berdasarkan role user
        let kantorIndex = {{ Auth::user()->role == 0 || Auth::user()->role == 1 ? 3 : 2 }};
        let departemenIndex = kantorIndex + 1;
        let satkerIndex = departemenIndex + 1;
        let jabatanIndex = satkerIndex + 1;

        rows.forEach(row => {
            const cells = row.querySelectorAll("td");
            if (!cells.length) return;

            let isMatch = true;

            if (type === 'kantor' && kantorIndex >= 0) {
                const cellValue = cells[kantorIndex].innerText.toLowerCase();
                if (selectedValue && cellValue !== selectedValue) isMatch = false;
            }

            if (type === 'departemen') {
                const cellValue = cells[departemenIndex].innerText.toLowerCase();
                if (selectedValue && cellValue !== selectedValue) isMatch = false;
            }

            if (type === 'satker') {
                const cellValue = cells[satkerIndex].innerText.toLowerCase();
                if (selectedValue && cellValue !== selectedValue) isMatch = false;
            }

            if (type === 'jabatan') {
                const cellValue = cells[jabatanIndex].innerText.toLowerCase();
                if (selectedValue && cellValue !== selectedValue) isMatch = false;
            }

            row.style.display = isMatch ? '' : 'none';
        });
    }
</script>

@endpush
