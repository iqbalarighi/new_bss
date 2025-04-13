@extends('layouts.side.side')
@section('content')
<div class="container">
    <h4 class="mb-4">Daftar Izin Absen</h4>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Perusahaan</th>
                <th>Kantor</th>
                <th>NIP</th>
                <th>Nama Pegawai</th>
                <th>Tanggal</th>
                <th>Jenis Izin</th>
                <th>Keterangan</th>
                <th>Foto</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($izinList as $izin)
            <tr>
                <td>{{ $izin->perusa->perusahaan ?? '-' }}</td>
                <td>{{ $izin->kantor->nama_kantor ?? '-' }}</td>
                <td>{{ $izin->pegawai->nip }}</td>
                <td>{{ $izin->pegawai->nama_lengkap ?? '-' }}</td>
                <td>{{ $izin->tanggal }}</td>
                <td>{{ $izin->jenis_izin }}</td>
                <td>{{ $izin->keterangan }}</td>
                <td>
                    @if($izin->foto)
                        <a href="{{ asset('storage/izin/'.$izin->nip.'/'.$izin->foto) }}" target="_blank">Lihat</a>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($izin->status_approve === '0')
                        <span class="badge bg-warning">Menunggu</span>
                    @elseif($izin->status_approve === '1')
                        <span class="badge bg-success">Disetujui</span>
                    @else
                        <span class="badge bg-danger">Ditolak</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection