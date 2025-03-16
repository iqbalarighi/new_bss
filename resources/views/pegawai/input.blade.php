@extends('layouts.side.side')
@section('content')
<div class="container mt-1">
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
            <button class="float-right btn btn-sm btn-secondary" onclick="history.back()">Kembali</button>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pegawai.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Pegawai</label>
                    <input type="text" class="form-control" name="nama" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">NIP</label>
                    <input type="tel" class="form-control" name="nip" oninput="validateInput(event)" required>
                </div>
    <script>
        function validateInput(event) {
            let input = event.target;
            input.value = input.value.replace(/\D/g, ''); // Hanya izinkan angka
        }
    </script>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
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
                    <input type="tel" class="form-control" name="no_telepon" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">BPJS TK</label>
                    <input type="tel" class="form-control" name="bpjs_tk" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">BPJS Kesehatan</label>
                    <input type="tel" class="form-control" name="bpjs_kesehatan" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kontak Darurat</label>
                    <input type="tel" class="form-control" name="kontak_darurat" required>
                </div>
{{--                 <div class="mb-3">
                    <label class="form-label">Penempatan Kerja</label>
                    <input type="text" class="form-control" name="penempatan_kerja" required>
                </div> --}}
                {{-- {{dd(Auth::user()->role === 0,1)}} --}}
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
                    <select name="penempatan_kerja" id="office" class="form-select" required>

                    </select>
                </div>
                @endif
               {{--  <div class="mb-3">
                    <label class="form-label">Jabatan</label>
                    <input type="text" class="form-control" name="jabatan" required>
                </div> --}}
                {{-- <div class="mb-3">
                    <label class="form-label">Satker</label>
                    <input type="text" class="form-control" name="satker" required>
                </div> --}}
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
                    <label class="form-label">Status Pegawai</label>
                    <select class="form-control" name="status" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Foto</label>
                    <input type="file" class="form-control" name="foto"  accept=".jpg, .jpeg, .png">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-danger">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
