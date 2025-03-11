@extends('layouts.side.side')
@section('content')
<div class="container mt-1">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-danger">Daftar Pegawai</h2>
        <a href="{{ route('pegawai.input') }}" class="btn btn-danger">Tambah Pegawai</a>
    </div>
    <div class="card shadow-lg rounded-lg">
        <div class="card-body" style="overflow: auto;">
            <table class="table table-striped table-bordered table-hover"> 
                <thead class="table-danger">
                    <tr>
                        <th>No.</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Kantor</th>
                        <th>Perusahaan</th>
                        <th>Jabatan</th>
                        <th>Satker</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pegawais as $num => $pegawai)
                        <tr>
                            <td>{{ $pegawais->firstitem()+$num }}</td>
                            <td>{{ $pegawai->nip }}</td>
                            <td>{{ $pegawai->nama_lengkap }}</td>
                            <td>{{ $pegawai->kantor->nama_kantor }}</td>
                            <td>{{ $pegawai->perusa->perusahaan }}</td>
                            <td>{{ $pegawai->jabat->jabatan }}</td>
                            <td>{{ $pegawai->sat->satuan_kerja }}</td>
                            <td>{{ $pegawai->status }}</td>
                            <td class="align-middle text-center">
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
@endsection