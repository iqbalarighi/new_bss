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
                <div class="card-header d-flex justify-content-between fw-bold">{{ __('Rekap Absensi Bulanan Pegawai') }}
                </div>
                <div class="card-body d-flex ">
                    <div class="col-md-10 d-flex justify-content-center">
                <form action="{{ route('pegawai.absensi.rekapview') }}" method="POST" target="_blank">
    @csrf
    <div class="row g-3 align-items-end">
        <div class="">
            <label for="kantor" class="form-label">Pilih Kantor</label>
            <select name="kantor" id="kantor" class="form-select">
                <option value="">-- Pilih Kantor --</option>
                @foreach ($kantors as $kantor)
                    <option value="{{ $kantor->id }}">{{ $kantor->nama_kantor }}</option>
                @endforeach
            </select>
        </div>

        {{-- Departemen --}}
        <div class="">
            <label for="departemen" class="form-label">Pilih Departemen</label>
            <select name="departemen" id="departemen" class="form-select">
                <option value="">-- Pilih Departemen --</option>
            </select>
        </div>
        {{-- Satker --}}
        <div class="">
            <label for="satker" class="form-label">Pilih Satker</label>
            <select name="satker" id="satker" class="form-select">
                <option value="">-- Pilih Satker --</option>
            </select>
        </div>
        {{-- Bulan & Tahun --}}
        <div class="">
            <label for="periode" class="form-label">Bulan & Tahun</label>
            <input type="month" name="periode" id="periode" class="form-control"
                value="{{ request('periode') ?? now()->format('Y-m') }}">
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex justify-content-around">
            <button type="submit" name="action" value="cetak" class="btn btn-primary">
                <i class="bi bi-printer"></i> Cetak
            </button>

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

@push('script')
<script>
    // Load Departemen saat Kantor dipilih
    $('#kantor').on('change', function () {
        let kantorId = $(this).val();
        $('#departemen').empty().append('<option value="">-- Pilih Departemen --</option>');
        $('#pegawais').empty().append('<option value="">-- Pilih Karyawan --</option>');

        if (kantorId) {
            $.get('/get-sat/' + kantorId, function (data) {
                data.departemen.forEach(function (dept) {
                    $('#departemen').append('<option value="' + dept.id + '">' + dept.nama_dept + '</option>');
                });
            });
        }
    });

    // Load Pegawai saat Departemen dipilih
    $('#departemen').on('change', function () {
        let deptId = $(this).val();
        $('#satker').empty().append('<option value="">-- Pilih Satker --</option>');

        if (deptId) {
            $.get('/get-satker-by-departemen/' + deptId, function (data) {
                data.satker.forEach(function (sat) {
                    $('#satker').append('<option value="' + sat.id + '">' + sat.satuan_kerja + '</option>');
                });
            });
        }
    });
</script>

@endpush