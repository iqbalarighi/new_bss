@extends('layouts.side.side')
@section('content')

<div class="container mw-100">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between fw-bold">{{ __('Daftar Hasil Patroli') }}
                </div>

                <div class="card-body" style="overflow: auto;">
<form method="GET" action="{{ url()->current() }}" class="row g-3 mb-3">
    <div class="col-auto">
        <input type="month" name="bulan" class="form-control" value="{{ request('bulan') }}" required>
    </div>
@php
    use App\Models\KantorModel;

    $user = Auth::user();
    $kantor = [];

    if ($user && in_array($user->role, [0, 1])) {
        $kantor = KantorModel::where('perusahaan', $user->perusahaan)->get();
    }
@endphp
@if(Auth::user()->role == 0 || Auth::user()->role == 1)
<div class="col-auto">
    <select name="kantor" id="kantor" class="form-select" required>
        <option value="">Pilih Kantor</option>
        @foreach($kantor as $office)
            <option value="{{ $office->id }}" {{ request('kantor') == $office->id ? 'selected' : '' }}>
                {{ $office->nama_kantor }}
            </option>
        @endforeach
    </select>
</div>
 @endif
    <div class="col-auto">
        <button type="submit" class="btn btn-success">Filter</button>
        <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

@if(request('bulan'))
    <div class="alert alert-info d-flex justify-content-between align-items-center py-1">
        <div>
            Menampilkan data patroli untuk Bulan: 
            <strong>{{ \Carbon\Carbon::parse(request('bulan'))->isoFormat('MMMM YYYY') }}</strong>
        </div>
        <form method="GET" action="{{ route('export.patrol') }}">
            <input type="hidden" name="bulan" value="{{ request('bulan') }}">
            <input type="hidden" name="kantor" value="{{ request('kantor') }}">
            <button type="submit" class="btn btn-outline-primary btn-sm">
                Export Excel
            </button>
        </form>
    </div>
@endif


                   <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                        @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                            <th>Kantor</th>
                        @endif
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
                        @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                            <td>{{ $log->kant->nama_kantor }}</td>
                        @endif 
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
    
            <div class="d-flex justify-content-center">
                {{ $logs->links('pagination::bootstrap-4') }}
            </div>
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