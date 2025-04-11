@extends('layouts.side.side')

@section('content')

<div class="container mw-100">

@if(Session::get('success'))
<script type="text/javascript">
    Swal.fire({
  icon: "success",
  title: "{{Session::get('success')}}",
  showConfirmButton: false,
  timer: 2000
});
</script>
@endif
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between">{{ __('Daftar Pegawai') }}
                </div>
                <div class="card-body d-flex justify-content-center">
                    <div class="col-md-10 d-flex justify-content-center">
                <form action="{{ route('pegawai.absensi.preview') }}" method="POST" target="_blank">
                    @csrf
                    <div class="row g-3 align-items-end">
                        {{-- Bulan & Tahun --}}
                        <div class="col-md-5 justify-content-center">
                            <label for="periode" class="form-label">Bulan & Tahun</label>
                            <input type="month" name="periode" id="periode" class="form-control"
                                value="{{ request('periode') ?? now()->format('Y-m') }}">
                        </div>

                        {{-- Pilih Karyawan --}}
                        <div class="col-md-5 justify-content-center">
                            <label for="pgawai" class="form-label">Pilih Karyawan</label>
                            <select name="pegawai" id="pegawai" class="form-select">
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach ($karyawans as $karyawan)
                                    <option value="{{ $karyawan->id }}">{{ $karyawan->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="col-md-3 d-grid justify-content-center">
                            <button type="submit" name="action" value="cetak" class="btn btn-primary">
                                <i class="bi bi-printer"></i> Cetak
                            </button>
                        </div>
                        <div class="col-md-4 d-grid justify-content-center">
                            <button type="submit" name="action" value="excel" class="btn btn-success">
                                <i class="bi bi-download"></i> Export to Excel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection