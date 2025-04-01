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
                        @if(Auth::user()->role == 0)
                            <th>Perusahaan</th>
                        @endif
                        @if(Auth::user()->role == 1 || Auth::user()->role == 0)
                            <th>Kantor</th>
                        @endif
                            <th>Departemen</th>
                            <th>Satker</th>
                            <th>Jabatan</th>
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
                            data-company="{{$item->perusahaan}}"
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
                            </select>
                        </div>
                    @endif
                    <div class="mb-3" id="edit_dept-container">
                        <label for="edit_dept" class="form-label">Departemen</label>
                        <select id="edit_dept" name="dept" class="form-select">
                        </select>
                    </div>
                    <div class="mb-3" id="edit_satker-container">
                        <label for="edit_satker" class="form-label">Satuan Kerja</label>
                        <select id="edit_satker" name="satker" class="form-select">
                        </select>
                    </div>
                    <div class="mb-3" id="edit_position-container">
                        <label for="edit_position" class="form-label">Jabatan</label>
                        <select id="edit_position" name="position" class="form-select">
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
    $(document).ready(function() {
        function edittoggleFields() {
            var selectedRole = $('#edit_role').val();
            
            if (selectedRole == "3") { // Admin Cabang
                $('#edit_satker-container, #edit_position-container, #edit_dept-container').hide();
                $('#edit_satker, #edit_position, #edit_dept').prop("disabled", true);
                $('#edit_office-container').show();
                $('#edit_office').prop("disabled", false).prop("required", true);
            } 
            else if (selectedRole == "1") { // Admin Pusat
                $('#edit_satker-container, #edit_position-container, #edit_office-container, #edit_dept-container').hide();
                $('#edit_satker, #edit_position, #edit_office, #edit_dept').prop("disabled", true);
                $('#edit_office, #edit_satker, #edit_dept, #edit_position').prop("required", false);
            } 
            else { // User
                $('#edit_satker-container, #edit_position-container, #edit_office-container, #edit_dept-container').show();
                $('#edit_satker, #edit_position, #edit_office, #edit_dept').prop("disabled", false);
                $('#edit_office, #edit_satker, #edit_dept, #edit_position').prop("required", true);
            }
        }

        // Jalankan toggle saat role berubah
        $('#edit_role').change(function() {
            edittoggleFields();
        });

        // Ketika tombol edit ditekan
        $('.editUserBtn').click(function() {
            var userId = $(this).data('id');
            var userName = $(this).data('name');
            var userEmail = $(this).data('email');
            var userRole = $(this).data('role');
            var userCompany = $(this).data('company');
            var userOffice = $(this).data('office');
            var userDept = $(this).data('dept');
            var userSatker = $(this).data('satker');
            var userPosition = $(this).data('position');

            // Isi modal dengan data dari tabel
            $('#edit_user_id').val(userId);
            $('#edit_name').val(userName);
            $('#edit_email').val(userEmail);
            $('#edit_role').val(userRole);
            $('#edit_company').val(userCompany);
            $('#edit_office').val(userOffice);
            $('#edit_dept').val(userDept);
            $('#edit_satker').val(userSatker);
            $('#edit_position').val(userPosition);

            // Panggil fungsi toggle fields agar menyesuaikan role yang dipilih
            edittoggleFields();
        });

        // Panggil fungsi saat halaman dimuat pertama kali
        edittoggleFields();
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
    function loadCompanyData() {
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
    }

    // Panggil saat perusahaan berubah
    $('#edit_company').change(function() {
        loadCompanyData();
    });

    // Panggil saat modal edit dibuka
    $('#editUserModal').on('shown.bs.modal', function () {
        loadCompanyData();
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
            $('#edit_office').empty();
            $('#edit_office').append('<option value="">Pilih Kantor</option>');
            
            $.each(response.offices, function(key, office) {
                $('#edit_office').append('<option value="' + office.id + '">' + office.nama_kantor + '</option>');
            });

            $('#edit_satker').empty();
            $('#edit_satker').append('<option value="">Pilih Satuan Kerja</option>');
            
            $.each(response.satkers, function(key, satker) {
                $('#edit_satker').append('<option value="' + satker.id + '">' + satker.satuan_kerja + '</option>');
            });

            $('#edit_position').empty();
            $('#edit_position').append('<option value="">Pilih Jabatan</option>');
            
            $.each(response.positions, function(key, position) {
                $('#edit_position').append('<option value="' + position.id + '">' + position.jabatan + '</option>');
            });

            $('#edit_dept').empty();
            $('#edit_dept').append('<option value="">Pilih Departemen</option>');
            
            $.each(response.depts, function(key, dept) {
                $('#edit_dept').append('<option value="' + dept.id + '">' + dept.nama_dept + '</option>');
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
    function validatePassword() {
        var password = $('#edit_password').val();
        var confirmPassword = $('#edit_confirm_password').val();

        var isValid = true;

        // Cek panjang password minimal 6 karakter
        if (password.length < 6) {
            $('#passwordLengthError').show();
            $('#edit_password').addClass('is-invalid');
            isValid = false;
        } else {
            $('#passwordLengthError').hide();
            $('#edit_password').removeClass('is-invalid');
        }

        // Cek apakah password dan konfirmasi password cocok
        if (password !== confirmPassword) {
            $('#passwordError').show();
            $('#edit_confirm_password').addClass('is-invalid');
            isValid = false;
        } else {
            $('#passwordError').hide();
            $('#edit_confirm_password').removeClass('is-invalid');
        }

        return isValid;
    }

    // Cek saat user mengetik di input password atau confirm password
    $('#edit_password, #edit_confirm_password').keyup(function () {
        validatePassword();
    });

    // Cek sebelum submit form
    $('#editUserForm').submit(function (e) {
        if (!validatePassword()) {
            e.preventDefault(); // Hentikan submit jika password tidak cocok
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
    $(document).ready(function () {
    $('#editUserForm').submit(function (e) {
        e.preventDefault(); // Mencegah form submit secara default

        var formData = $(this).serialize() + '&_method=PUT'; // Tambahkan method PUT

        $.ajax({
            url: '/users/update', // Sesuaikan dengan route di Laravel
            type: 'POST', // Gunakan POST karena Laravel mengenali _method=PUT
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

                    $('#editUserModal').modal('hide'); // Tutup modal
                    location.reload(); // Reload halaman untuk update tabel
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message
                    });
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: 'Cek kembali data yang diinput.'
                });
            }
        });
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
@endpush
