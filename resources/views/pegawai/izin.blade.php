@extends('layouts.side.side')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between fw-bold">{{ __('Daftar Izin Pegawai') }}
                </div>

                <div class="card-body">
                   <table class="table table-bordered table-striped">
        <thead class="table-dark text-center">
            <tr>
                <th>No</th>
                @if (Auth::user()->role == 0)
                <th>Perusahaan</th>
                @endif
                @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                <th>Kantor</th>
                @endif
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
            @foreach ($izinList as $num => $izin)
            <tr>
                <td class="text-center">{{ $izinList->firstitem() + $num}}</td>
                @if (Auth::user()->role == 0)
                <td>{{ $izin->perusa->perusahaan ?? '-' }}</td>
                @endif
                @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                <td>{{ $izin->kantor->nama_kantor ?? '-' }}</td>
                @endif
                <td>{{ $izin->pegawai->nip }}</td>
                <td>{{ $izin->pegawai->nama_lengkap ?? '-' }}</td>
                <td>{{ $izin->tanggal }}</td>
                <td>
                    @switch($izin->jenis_izin)
                        @case('s') Sakit @break
                        @case('i') Izin @break
                        @case('c') Cuti @break
                        @default -
                    @endswitch
                </td>
                <td>{{ $izin->keterangan }}</td>
                <td>
                    @if($izin->foto)
                        <a href="#" class="lihat-foto" data-img="{{ asset('storage/bukti_izin/'.$izin->pegawai->nip.'/'.$izin->foto) }}">
                            <img src="{{ asset('storage/bukti_izin/'.$izin->pegawai->nip.'/'.$izin->foto) }}" alt="Thumbnail" width="40" height="40" class="rounded">
                        </a>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($izin->status_approve == 1)
                        <a href="#" class="badge bg-success approve-popup" data-id="{{ $izin->id }}">Disetujui</a>
                    @elseif($izin->status_approve == 2)
                       <a href="#" class="badge bg-danger approve-popup" data-id="{{ $izin->id }}">Ditolak</a>
                    @else
                        <a href="#" class="badge bg-warning text-dark approve-popup" data-id="{{ $izin->id }}">Menunggu</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
$(document).ready(function() {
    $('.lihat-foto').on('click', function(e) {
        e.preventDefault();
        const imgUrl = $(this).data('img');
        Swal.fire({
            imageUrl: imgUrl,
            imageWidth: 500,
            imageAlt: 'Foto Izin',
            confirmButtonText: 'Tutup',
        });
    });
});


$('.approve-popup').on('click', function(e) {
        e.preventDefault();
        const id = $(this).data('id');

        Swal.fire({
            title: 'Status Izin',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: 'Diterima',
            confirmButtonColor: 'green',
            denyButtonText: 'Ditolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            let status = null;
            if (result.isConfirmed) {
                status = 1;
            } else if (result.isDenied) {
                status = 2;
            }

            if (status !== null) {
                $.ajax({
                    url: `/pegawai/absensi/izin/${id}/status`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status_approve: status
                    },
                    success: function(response) {
                        Swal.fire('Berhasil!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat memproses.', 'error');
                    }
                });
            }
        });
    });
</script>
@endpush