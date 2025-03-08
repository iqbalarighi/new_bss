@extends('layouts.side.side')
@section('content')
<div class="container mt-1">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-danger">Daftar Pegawai</h2>
        <a href="{{ route('pegawai.input') }}" class="btn btn-danger">Tambah Pegawai</a>
    </div>
    <div class="card shadow-lg rounded-lg">
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead class="table-danger">
                    <tr>
                        <th>Perusahaan</th>
                        <th>Kantor</th>
                        <th>Jabatan</th>
                        <th>Satker</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pegawais as $pegawai)
                        <tr>
                            <td>{{ $pegawai->perusahaan }}</td>
                            <td>{{ $pegawai->kantor }}</td>
                            <td>{{ $pegawai->jabatan }}</td>
                            <td>{{ $pegawai->satker }}</td>
                            <td>{{ $pegawai->nama }}</td>
                            <td>{{ $pegawai->nip }}</td>
                            <td>{{ $pegawai->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection