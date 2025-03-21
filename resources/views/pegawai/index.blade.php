@extends('layouts.side.side')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between">{{ __('Daftar Pegawai') }}

        <a href="{{ route('pegawai.input') }}" class="btn btn-danger bi bi-person-add"></a>
                </div>

                <div class="card-body">
    <div class="card shadow-lg rounded-lg">
        <div class="card-body" style="overflow: auto;">
            <table class="table table-striped table-bordered table-hover"> 
                <thead class="table-danger text-center">
                    <tr>
                        <th>No.</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        @if(Auth::user()->role == 0)
                            <th>Perusahaan</th>
                        @endif
                        @if(Auth::user()->role == 1 || Auth::user()->role == 0)
                            <th>Kantor</th>
                        @endif
                        <th>Jabatan</th>
                        <th>Satker</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pegawais as $num => $pegawai)
                        <tr>
                            <td onclick="window.location='{{route('pegawai.detail', $pegawai->id)}}'" style="cursor: pointer;">{{ $pegawais->firstitem()+$num }}</td>
                            <td onclick="window.location='{{route('pegawai.detail', $pegawai->id)}}'" style="cursor: pointer;">{{ $pegawai->nip }}</td>
                            <td onclick="window.location='{{route('pegawai.detail', $pegawai->id)}}'" style="cursor: pointer;">{{ $pegawai->nama_lengkap }}</td>
                        @if(Auth::user()->role == 0)
                            <td onclick="window.location='{{route('pegawai.detail', $pegawai->id)}}'" style="cursor: pointer;">{{ $pegawai->perusa->perusahaan }}</td>
                        @endif
                        @if(Auth::user()->role == 1 || Auth::user()->role == 0)
                            <td onclick="window.location='{{route('pegawai.detail', $pegawai->id)}}'" style="cursor: pointer;">{{ $pegawai->kantor->nama_kantor }}</td>
                        @endif
                            <td onclick="window.location='{{route('pegawai.detail', $pegawai->id)}}'" style="cursor: pointer;white-space: wrap;">{{ $pegawai->jabat->jabatan }}</td>
                            <td onclick="window.location='{{route('pegawai.detail', $pegawai->id)}}'" style="cursor: pointer;">{{ $pegawai->sat->satuan_kerja }}</td>
                            <td onclick="window.location='{{route('pegawai.detail', $pegawai->id)}}'" style="cursor: pointer;">{{ $pegawai->status}}</td>
                            <td class="align-middle text-center">
                                <button class="btn btn-primary btn-sm px-1">Edit</button>
                                <button class="btn btn-danger btn-sm px-1">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $pegawais->links('pagination::bootstrap-5') }}
            </div>
        </div>
</div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection