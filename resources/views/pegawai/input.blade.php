@extends('layouts.side.side')
@section('content')
<div class="container mt-1 mw-100">
    <div class="card shadow-lg rounded-lg">

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

        <div class="card-header bg-danger text-white text-center fw-bold">Tambah Pegawai
            <button class="float-right btn btn-sm btn-secondary" onclick="window.location.href='{{route('pegawai.index')}}'">Kembali</button>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pegawai.store') }}" enctype="multipart/form-data" id="cekin">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Pegawai</label>
                    <input type="text" class="form-control" name="nama" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">NIP</label>
                    <input type="tel" class="form-control" name="nip" id="nip" oninput="validateInput(event)" required>
                    <div id="notif-nip" class="form-text text-danger d-none">NIP sudah terdaftar!</div>
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
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" name="tgl_lahir" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control" name="alamat" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat Domisili</label>
                    <textarea class="form-control" name="alamat_domisili" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. Telepon</label>
                    <input type="tel" class="form-control" oninput="validateInput(event)" maxlength="14" name="no_telepon" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">BPJS TK</label>
                    <input type="tel" class="form-control" oninput="validateInput(event)" maxlength="16" name="bpjs_tk" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">BPJS Kesehatan</label>
                    <input type="tel" class="form-control" oninput="validateInput(event)" maxlength="16" name="bpjs_kesehatan" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kontak Darurat</label>
                    <input type="tel" class="form-control" oninput="validateInput(event)" maxlength="14" name="kontak_darurat" required>
                </div>

                @if(Auth::user()->role === 0)
                <div class="mb-3">
                    <label for="tenant" class="form-label">Perusahaan</label>
                    {{-- <input type="text" class="form-control"name="usaha" placeholder="Masukkan nama kantor" required> --}}
                    <select name="perusahaan" id="tenant" class="form-select" required>
                        <option selected disabled value="">Pilih Perusahaan</option>
                        @foreach($tenant as $item)
                        <option value="{{$item->id}}">{{$item->perusahaan}}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                @if(Auth::user()->role === 0 or Auth::user()->role === 1)
                <div class="mb-3">
                    <label for="office" class="form-label">Penempatan Kerja</label>
                    {{-- <input type="text" class="form-control"name="usaha" placeholder="Masukkan nama kantor" required> --}}
                    <select name="kantor" id="office" class="form-select" required>
                    </select>
                </div>
                @endif

                <div class="mb-3">
                    <label for="dept" class="form-label">Departemen</label>
                    {{-- <input type="text" class="form-control"name="usaha" placeholder="Masukkan nama kantor" required> --}}
                    <select name="dept" id="dept" class="form-select" required>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="satker" class="form-label">Satuan Kerja</label>
                    {{-- <input type="text" class="form-control"name="usaha" placeholder="Masukkan nama kantor" required> --}}
                    <select name="satker" id="satker" class="form-select" required>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="position" class="form-label">Jabatan</label>
                    {{-- <input type="text" class="form-control"name="usaha" placeholder="Masukkan nama kantor" required> --}}
                    <select name="jabatan" id="position" class="form-select" required>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="shift" class="form-label">Shift</label>
                    <select name="shift" id="shift" class="form-select">
                        <option value="" selected>Optional Shift</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="statpegawai" class="form-label">Status Pegawai</label>
                    <select class="form-control" id="statpegawai" name="statpegawai" required>
                        <option value="Tetap">Tetap</option>
                        <option value="Kontrak">Kontrak</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Foto</label>
                    <input type="file" class="form-control" name="foto" accept=".jpg, .jpeg, .png">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-danger">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
        function validateInput(event) {
            let input = event.target;
            input.value = input.value.replace(/\D/g, ''); // Hanya izinkan angka
        }
    </script>
<script>
    $(document).ready(function () {
        $('#cekin').on('submit', function (e) {
            e.preventDefault(); // Mencegah form submit langsung

            Swal.fire({
                title: 'Konfirmasi Data',
                text: 'Apakah Anda yakin data yang diisi sudah benar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Cek lagi'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); // Submit form jika disetujui
                }
            });
        });
    });
</script>
@if(Auth::user()->role == 0)
<script type="text/javascript">
    $(document).ready(function() {
        $('#tenant').change(function() {
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

            $('#dept').empty();
            $('#dept').append('<option value="">Pilih Departemen</option>');
    
    @if(Auth::user()->role == 3)
            $.each(response.depts, function(key, dept) {
            $('#dept').append('<option value="' + dept.id + '">' + dept.nama_dept + '</option>');
            });
    @endif
            $('#satker').empty();
            $('#satker').append('<option value="">Pilih Satuan Kerja</option>');
            
            // $.each(response.satkers, function(key, satker) {
            //     $('#satker').append('<option value="' + satker.id + '">' + satker.satuan_kerja + '</option>');
            // });

            $('#position').empty();
            $('#position').append('<option value="">Pilih Jabatan</option>');
            
            // $.each(response.positions, function(key, position) {
            //     $('#position').append('<option value="' + position.id + '">' + position.jabatan + '</option>');
            // });

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
        $('#nip').on('input', function() {
            let nip = $(this).val();

            if (nip.length >= 3) { // Minimal 3 karakter sebelum cek
                $.ajax({
                    url: "{{ route('cek.nip') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        nip: nip
                    },
                    success: function(response) {
                        if (response.exists) {
                            $('#notif-nip').removeClass('d-none');
                            $('#nip').addClass('is-invalid');
                        } else {
                            $('#notif-nip').addClass('d-none');
                            $('#nip').removeClass('is-invalid');
                        }
                    }
                });
            } else {
                $('#notif-nip').addClass('d-none');
                $('#nip').removeClass('is-invalid');
            }
        });

        // Mencegah submit jika NIP sudah ada
        $('#form-tambah-pegawai').on('submit', function(e) {
            if ($('#nip').hasClass('is-invalid')) {
                e.preventDefault();
                alert('Periksa kembali NIP!');
            }
        });
    });
</script>
<script type="text/javascript">
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
               
            $('#shift').empty();
            $('#shift').append('<option value="">Optional Shift</option>');
            
            $.each(response.shifts, function(key, shift) {
                $('#shift').append(
                        `<option value="${shift.id}">
                            ${shift.shift} (${shift.jam_masuk.slice(0,5)} - ${shift.jam_keluar.slice(0,5)})
                         </option>`
                    );
            });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }

             if (!satId){
                $('#position').empty().append('<option value="">Pilih Jabatan</option>');
                $('#shift').empty().append('<option value="">Pilih Shift</option>');
            }
        });
</script>
<script type="text/javascript">
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

                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            } 

            if (!departemenId){
                $('#position').empty().append('<option value="">Pilih Jabatan</option>');
                $('#satker').empty().append('<option value="">Pilih Satuan Kerja</option>');
            }
        });
</script>
<script>
    $('#office').change(function() {
            let perusahaanId = $(this).val();
            if (perusahaanId) {
                $.ajax({
                    url: '/get-sat/' + perusahaanId,
                    type: 'GET',
                    success: function(response) {
                        let departemenOptions = '<option value="">Pilih Departemen</option>';

                        response.departemen.forEach(function(dept) {
                            departemenOptions += `<option value="${dept.id}">${dept.nama_dept}</option>`;
                        });

                        $('#dept').html(departemenOptions);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }

            if (!perusahaanId){
                $('#office').empty().append('<option value="">Pilih Kantor</option>');
                $('#dept').empty().append('<option value="">Pilih Departemen</option>');
                $('#satker').empty().append('<option value="">Pilih Satuan Kerja</option>');
                $('#position').empty().append('<option value="">Pilih Jabatan</option>');
            }
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
    $('#cekin').submit(function (e) {
        if (!validPassword()) {
            e.preventDefault(); // Hentikan submit jika password tidak cocok
        }
    });
});

</script>
@endpush
