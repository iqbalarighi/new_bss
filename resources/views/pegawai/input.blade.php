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

        <div class="card-header bg-danger text-white text-center fw-bold">Tambah Pegawai</div>
        <div class="card-body">
            <form method="POST" action="{{ route('pegawai.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Pegawai</label>
                    <input type="text" class="form-control" name="nama" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">NIP</label>
                    <input type="text" class="form-control" name="nip" required>
                </div>
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
                    <input type="text" class="form-control" name="no_telepon" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">BPJS TK</label>
                    <input type="text" class="form-control" name="bpjs_tk" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">BPJS Kesehatan</label>
                    <input type="text" class="form-control" name="bpjs_kesehatan" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kontak Darurat</label>
                    <input type="text" class="form-control" name="kontak_darurat" required>
                </div>
{{--                 <div class="mb-3">
                    <label class="form-label">Penempatan Kerja</label>
                    <input type="text" class="form-control" name="penempatan_kerja" required>
                </div> --}}
                <div class="mb-3">
                    <label for="tenantName" class="form-label">Penempatan Kerja</label>
                    {{-- <input type="text" class="form-control"name="usaha" placeholder="Masukkan nama kantor" required> --}}
                    <select name="penempatan_kerja" id="tenantName" class="form-select" required>
                        <option selected disabled value="">Pilih Kantor</option>
                        @foreach($kantor as $item)
                        <option value="{{$item->id}}">{{$item->nama_kantor}}</option>
                        @endforeach
                    </select>
                </div>
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
                        <option selected disabled value="">Pilih Satuan Kerja</option>
                        @foreach($satker as $item)
                        <option value="{{$item->id}}">{{$item->satuan_kerja}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="jabatan" class="form-label">Jabatan</label>
                    {{-- <input type="text" class="form-control"name="usaha" placeholder="Masukkan nama kantor" required> --}}
                    <select name="jabatan" id="jabatan" class="form-select" required>
                        <option selected disabled value="">Pilih Jabatan</option>
                        @foreach($jabatan as $item)
                        <option value="{{$item->id}}">{{$item->jabatan}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Pegawai</label>
                    <select class="form-control" name="status_pegawai" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Foto</label>
                    <input type="file" class="form-control" name="foto"  accept=".jpg, .jpeg, .png" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-danger">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
