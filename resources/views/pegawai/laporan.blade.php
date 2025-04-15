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
                <div class="card-header d-flex justify-content-between fw-bold">{{ __('Rekap Absensi Pegawai') }}
                </div>
                <div class="card-body d-flex justify-content-center">
                    <div class="col-md-6 d-flex justify-content-center">
                <form id="formAbsensi" action="{{ route('pegawai.absensi.preview') }}" method="POST" target="_blank">
                    @csrf
                    <div class="row g-3 align-items-end">
                       @php
                       use App\Models\PerusahaanModel;
                           $tenants = PerusahaanModel::all();
                       @endphp
                       @if (Auth::user()->role == 0)
                        {{-- Kantor --}}
                        <div class="">
                            <label for="tenant" class="form-label mb-0">Pilih Tenant</label>
                            <select name="tenant" id="tenant" class="form-select">
                                <option value="">-- Pilih Tenant --</option>
                                @foreach ($tenants as $tenan)
                                    <option value="{{ $tenan->id }}">{{ $tenan->perusahaan }}</option>
                                @endforeach
                            </select>
                        </div>
                       @endif

                       @if (Auth::user()->role == 1 || Auth::user()->role == 0)
                        {{-- Kantor --}}
                        <div class="">
                            <label for="kantor" class="form-label mb-0">Pilih Kantor</label>
                            <select name="kantor" id="kantor" class="form-select">
                                <option value="">-- Pilih Kantor --</option>
                                @if (Auth::user()->role != 0)
                                    @foreach ($kantors as $kantor)
                                        <option value="{{ $kantor->id }}">{{ $kantor->nama_kantor }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                       @endif
                       
                        {{-- Departemen --}}
                        <div class="">
                            <label for="departemen" class="form-label mb-0">Pilih Departemen</label>
                            <select name="departemen" id="departemen" class="form-select">
                                <option value="">-- Pilih Departemen --</option>
                                @if (Auth::user()->role == 3)
                                    @foreach ($depts as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->nama_dept }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        {{-- Satker --}}
                        <div class="">
                            <label for="satker" class="form-label mb-0">Pilih Satker</label>
                            <select name="satker" id="satker" class="form-select">
                                <option value="">-- Pilih Satker --</option>
                            </select>
                        </div>

                        {{-- Pilih Karyawan --}}
                        <div class="">
                            <label for="pegawais" class="form-label mb-0">Pilih Karyawan</label>
                            <select name="pegawais" id="pegawais" class="form-select">
                                <option value="">-- Pilih Karyawan --</option>
                            </select>
                        </div>

                         {{-- Bulan & Tahun --}}
                        <div class="">
                            <label for="periode" class="form-label mb-0">Bulan & Tahun</label>
                            <input 
                            type="month" 
                            name="periode" 
                            id="periode" 
                            class="form-control" 
                            min="{{ Carbon\Carbon::parse($tabul->created_at ?? '')->format('Y-m')}}"
                            max="{{ now()->format('Y-m') }}" 
                            value="{{ now()->format('Y-m') }}">
                        </div>

                        

                        {{-- Tombol Aksi --}}
                        <div class="col d-flex justify-content-around">
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
    @if (Auth::user()->role == 0)
        // Load Departemen saat Kantor dipilih
    $('#tenant').on('change', function () {
        let tenanrId = $(this).val();
        $('#kantor').empty().append('<option value="">-- Pilih Kantor --</option>');
        $('#departemen').empty().append('<option value="">-- Pilih Departemen --</option>');
        $('#satker').empty().append('<option value="">-- Pilih Satker --</option>');
        $('#pegawais').empty().append('<option value="">-- Pilih Karyawan --</option>');

        if (tenanrId) {
            $.get('/get-konten/' + tenanrId, function (data) {
                data.offices.forEach(function (kant) {
                    $('#kantor').append('<option value="' + kant.id + '">' + kant.nama_kantor + '</option>');
                });
            });
        }
    });    
    @endif
    
    @if (Auth::user()->role == 1 || Auth::user()->role == 0)
        // Load Departemen saat Kantor dipilih
        $('#kantor').on('change', function () {
            let kantorId = $(this).val();
            $('#departemen').empty().append('<option value="">-- Pilih Departemen --</option>');
            $('#satker').empty().append('<option value="">-- Pilih Satker --</option>');
            $('#pegawais').empty().append('<option value="">-- Pilih Karyawan --</option>');

            if (kantorId) {
                $.get('/get-sat/' + kantorId, function (data) {
                    data.departemen.forEach(function (dept) {
                        $('#departemen').append('<option value="' + dept.id + '">' + dept.nama_dept + '</option>');
                    });
                });
            }
        });

     @endif

$('#departemen').on('change', function () {
        let deptId = $(this).val();
        $('#satker').empty().append('<option value="">-- Pilih Satker --</option>');
        $('#pegawais').empty().append('<option value="">-- Pilih Karyawan --</option>');

        if (deptId) {
            $.get('/get-satker-by-departemen/' + deptId, function (data) {
                data.satker.forEach(function (sat) {
                    $('#satker').append('<option value="' + sat.id + '">' + sat.satuan_kerja + '</option>');
                });
            });
        }
    });
    // Load Pegawai saat Departemen dipilih
    $('#satker').on('change', function () {
        let satId = $(this).val();
        $('#pegawais').empty().append('<option value="">-- Pilih Karyawan --</option>');

        if (satId) {
            $.get('/get-pegawai/' + satId, function (data) {
                data.forEach(function (pgw) {
                    $('#pegawais').append('<option value="' + pgw.id + '">' + pgw.nama_lengkap + '</option>');
                });
            });
        }
    });
</script>
<script>
    $('#formAbsensi').on('submit', function(e) { 
         @if (Auth::user()->role == 1 || Auth::user()->role == 0)
        const kantor = $('#kantor').val();
        @endif
        const departemen = $('#departemen').val();
        const satker = $('#satker').val();
        const pegawai = $('#pegawais').val();
        const periode = $('#periode').val();

        if ({{Auth::user()->role == 0 || Auth::user()->role == 1 ? '!kantor || ' : ''}}!departemen || !satker || !pegawai || !periode) {
            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Form belum lengkap',
                text: 'Mohon lengkapi semua isian sebelum mengirim.',
                confirmButtonText: 'Oke'
            });

            return false;
        }
    });
</script>


@endpush
{{-- tambahin field kantor sama depatremen buat filter rekap pegawai --}}