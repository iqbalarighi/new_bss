@extends('layouts.side.side')
@section('content')

<div class="container mw-100">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between fw-bold">{{ __('Daftar Hasil Patroli') }}
                </div>

                <div class="card-body" style="overflow: auto;">
                   <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kantor</th>
                            <th>Petugas</th>
                            <th>Shift</th>
                            <th>Lokasi</th>
                            <th>Area</th>
                            <th>Waktu</th>
                            <th>Keterangan</th>
                            <th>Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $n => $log)
                        <tr>
                            <td align="center">{{ $logs->firstitem()+$n }}</td>
                            <td>{{ $log->kant->nama_kantor }}</td>
                            <td>{{ $log->karyawan->nama_lengkap }}</td>
                            <td>{{ $log->shift }}</td>
                            <td>{{ $log->checkpoint->nama }}</td>
                            <td>{{ $log->checkpoint->lokasi }}</td>
                            <td>{{ $log->waktu_scan }}</td>
                            <td>{{ $log->keterangan }}</td>
                            <td>
                                @if($log->foto)
                                    <button class="btn btn-sm btn-primary" onclick="previewFoto('{{ asset('storage/foto_patrol/'.$log->foto) }}')">
                                        Preview
                                    </button>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    function previewFoto(fotoUrl) {
        Swal.fire({
            title: 'Preview Foto',
            imageUrl: fotoUrl,
            imageAlt: 'Foto Patroli',
            showCloseButton: true,
            showConfirmButton: false,
            width: 600,
        });
    }
</script>

@endpush