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
                <div class="card-header d-flex justify-content-between fw-bold">{{ __('Rekap Lembur Bulanan Pegawai') }}
                </div>
                <div class="card-body d-flex justify-content-center">
                    <div class="col-md-6 d-flex ">
                    <form id="formRekap" action="{{ route('pegawai.lembur.reklem') }}" method="POST" target="_blank">
                    @csrf
                    <div class="row g-3 align-items-end">
                        @php
                       use App\Models\PerusahaanModel;
                           $tenants = PerusahaanModel::all();
                       @endphp
                       @if (Auth::user()->role == 0)
                        {{-- Kantor --}}
                        <div class="">
                            <label for="tenant" class="form-label">Pilih Tenant</label>
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
                            <label for="kantor" class="form-label">Pilih Kantor</label>
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
                            <label for="departemen" class="form-label">Pilih Departemen</label>
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
                            <label for="satker" class="form-label">Pilih Satker</label>
                            <select name="satker" id="satker" class="form-select">
                                <option value="">-- Pilih Satker --</option>
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
<script>
    $('#formRekap').on('submit', function(e) {
        const role = {{ Auth::user()->role }};
        const kantor = $('#kantor').val();
        const departemen = $('#departemen').val();

        // Jika role 1 atau 0, wajib isi kantor dan departemen
        if ((role === 0 || role === 1)) {
            if (!kantor && !departemen) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Kantor dan Departemen kosong',
                    text: 'Silakan pilih kantor dan departemen terlebih dahulu.',
                });
                return false;
            }

            if (!kantor) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Kantor belum dipilih',
                    text: 'Silakan pilih kantor terlebih dahulu.',
                });
                return false;
            }

            if (!departemen) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Departemen belum dipilih',
                    text: 'Silakan pilih departemen terlebih dahulu.',
                });
                return false;
            }
        } else {
            if (!departemen) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Departemen belum dipilih',
                    text: 'Silakan pilih departemen terlebih dahulu.',
                });
                return false;
            }
        }
    });
</script>
@endpush