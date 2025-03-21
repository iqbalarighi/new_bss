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
                        @if(Auth::user()->role == 0)
                            <th>Perusahaan</th>
                        @endif
                        @if(Auth::user()->role == 1 || Auth::user()->role == 0)
                            <th>Kantor</th>
                        @endif
                            <th>Jabatan</th>
                            <th>Satker</th>
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
                            @if ($item->satker == 0)
                            -
                            @else
                               {{$item->sat->satuan_kerja}}
                            @endif
                        </td>
                        <td>
                            @if($item->role == 0)
                                Superadmin
                            @elseif($item->role == 1)
                                Admin Pusat
                            @elseif($item->role == 3)
                                Admin Kantor
                            @else
                                User
                            @endif
                        </td>
                        <td class="align-middle text-center">
                            <button class="btn btn-primary btn-sm cen">Edit</button>
                            <button class="btn btn-danger btn-sm cen">Hapus</button>
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
                    <form method="POST" action="{{ route('adduser') }}">
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
    <!-- jQuery untuk menonaktifkan dan menyembunyikan Satker & Jabatan jika memilih Admin -->
    <script>
        $(document).ready(function() {
            function toggleFields() {
                if ($('#role').val() == "3") { // Jika memilih Admin
                    $('#satker-container, #position-container').hide();
                    $('#satker, #position').prop("disabled", true);
                    $('#office-container').show();
                    $('#office').prop("disabled", false);
                    $('#office').prop("required", true);
                } else if ($('#role').val() == "1") { // Jika memilih Admin
                    $('#satker-container, #position-container, #office-container').hide();
                    $('#satker, #position, #office').prop("disabled", true);
                    $('#office').prop("required", false);
                    $('#satker').prop("required", false);
                    $('#position').prop("required", false);
                } else { // Jika memilih User
                    $('#satker-container, #position-container, #office-container').show();
                    $('#satker, #position, #office').prop("disabled", false);
                    $('#office').prop("required", true);
                    $('#satker').prop("required", true);
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
            </div>
        </div>
    </div>

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

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#office').empty().append('<option value="">Pilih Kantor</option>');
                $('#satker').empty().append('<option value="">Pilih Satuan Kerja</option>');
                $('#position').empty().append('<option value="">Pilih Jabatan</option>');
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

        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});

</script>

@endif
@endsection
