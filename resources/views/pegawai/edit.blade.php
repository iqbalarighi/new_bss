@extends('layouts.side.side')
@section('content')
<div class="container mt-1 mw-100">
    <div class="card shadow-lg rounded-lg">

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

        <div class="card-header bg-danger text-white text-center fw-bold">Edit Pegawai
            <button class="float-right btn btn-sm btn-secondary" onclick="window.location.href='{{route('pegawai.index')}}'">Kembali</button>
        </div>
        <div class="card-body">
<form method="POST" action="{{ route('pegawai.update', $pegawai->id) }}" enctype="multipart/form-data" id="formEditPegawai">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Nama Pegawai</label>
        <input type="text" class="form-control" name="nama" value="{{ $pegawai->nama_lengkap }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">NIP</label>
        <input type="text" class="form-control" name="nip" value="{{ $pegawai->nip }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Tanggal Lahir</label>
        <input type="date" class="form-control" name="tgl_lahir" value="{{ $pegawai->tgl_lahir }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Alamat</label>
        <textarea class="form-control" name="alamat" required>{{ $pegawai->alamat }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Alamat Domisili</label>
        <textarea class="form-control" name="alamat_domisili" required>{{ $pegawai->domisili }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">No. Telepon</label>
        <input type="text" class="form-control" name="no_telepon" value="{{ $pegawai->no_hp }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">BPJS TK</label>
        <input type="text" class="form-control" name="bpjs_tk" value="{{ $pegawai->bpjs_tk }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">BPJS Kesehatan</label>
        <input type="text" class="form-control" name="bpjs_kesehatan" value="{{ $pegawai->bpjs_sehat }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Kontak Darurat</label>
        <input type="text" class="form-control" name="kontak_darurat" value="{{ $pegawai->ko_drat }}" required>
    </div>

    @if(Auth::user()->role === 0)
    <div class="mb-3">
        <label class="form-label">Perusahaan</label>
        <select name="perusahaan" id="select-perusahaan" class="form-select" required>
            <option selected disabled value="">Pilih Perusahaan</option>
            @foreach($tenant as $item)
            <option value="{{$item->id}}" {{ $pegawai->perusahaan == $item->id ? 'selected' : '' }}>{{$item->perusahaan}}</option>
            @endforeach
        </select>
    </div>
    @endif

    @if(Auth::user()->role === 0 || Auth::user()->role === 1)
    <div class="mb-3">
        <label class="form-label">Penempatan Kerja</label>
        <select name="kantor" id="select-kantor" class="form-select" required>
            @foreach($kantorList as $item)
            <option value="{{ $item->id }}" {{ "$pegawai->kantor" == $item->id ? 'selected' : '' }}>{{ $item->nama_kantor }}</option>
            @endforeach
        </select>
    </div>
    @endif

    <div class="mb-3">
        <label class="form-label">Departemen</label>
        <select name="dept" id="select-dept" class="form-select" required>
            @foreach($departemenList as $item)
            <option value="{{ $item->id }}" {{ $pegawai->dept == $item->id ? 'selected' : '' }}>{{ $item->nama_dept }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Satuan Kerja</label>
        <select name="satker" id="select-satker" class="form-select" required>
            @foreach($satkerList as $item)
            <option value="{{ $item->id }}" {{ $pegawai->satker == $item->id ? 'selected' : '' }}>{{ $item->satuan_kerja }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Jabatan</label>
        <select name="jabatan" id="select-jabat" class="form-select" required>
            @foreach($jabatanList as $item)
            <option value="{{ $item->id }}" {{ $pegawai->jabatan == $item->id ? 'selected' : '' }}>{{ $item->jabatan }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Shift</label>
        <select name="shift" class="form-select" required>
            @foreach($shift as $item)
            <option value="{{ $item->id }}" {{ $pegawai->shift == $item->id ? 'selected' : '' }}>
                {{ $item->shift }} {{ Carbon\Carbon::parse($item->jam_masuk)->format('H:i') }}-{{ Carbon\Carbon::parse($item->jam_keluar)->format('H:i') }} WIB
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Status Pegawai</label>
        <select name="status" class="form-select" required>
            <option value="Aktif" {{ $pegawai->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="Tidak Aktif" {{ $pegawai->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Foto</label>
        <input type="file" class="form-control" name="foto" accept=".jpg,.jpeg,.png">
        @if($pegawai->foto)
        <small class="form-text text-muted">
            Foto saat ini: 
            <a href="#" class="lihat-foto" data-img="{{ asset('storage/foto_pegawai/'.$pegawai->nip.'/'.$pegawai->foto) }}">Lihat</a>
        </small>
        @endif
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary">Perbarui</button>
    </div>
</form>

        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    document.getElementById('formEditPegawai').addEventListener('submit', function(e) {
        e.preventDefault(); // Stop form dari langsung submit

        Swal.fire({
            title: 'Perbarui Data?',
            text: "Perubahan ini akan memengaruhi data terkait. Lanjutkan?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, perbarui!',
            cancelButtonText: 'Batal',
            allowOutsideClick: false,
            reverseButtons: true,
            allowEscapeKey: false,
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading dalam SweetAlert
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit form setelah loading muncul
                e.target.submit();
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lihatFotoBtn = document.querySelector('.lihat-foto');

        if (lihatFotoBtn) {
            lihatFotoBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const imgSrc = this.getAttribute('data-img');

                Swal.fire({
                    imageUrl: imgSrc,
                    imageAlt: 'Foto Pegawai',
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: 400
                });
            });
        }
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const perusahaanSelect = document.getElementById('select-perusahaan');
    const kantorSelect = document.getElementById('select-kantor');
    const deptSelect = document.getElementById('select-dept');
    const satkerSelect = document.getElementById('select-satker');
    const jabatSelect = document.getElementById('select-jabat');

    perusahaanSelect?.addEventListener('change', function () {
        const perusahaanId = this.value;
        fetch(`/get-konten/${perusahaanId}`)
            .then(response => response.json())
            .then(data => {
                kantorSelect.innerHTML = '<option value="">Pilih Kantor</option>';
                data.offices.forEach(item => {
                    kantorSelect.innerHTML += `<option value="${item.id}">${item.nama_kantor}</option>`;
                });
                kantorSelect.dispatchEvent(new Event('change'));
            });
    });

    kantorSelect?.addEventListener('change', function () {
        const kantorId = this.value;
        fetch(`/get-sat/${kantorId}`)
            .then(response => response.json())
            .then(data => {
                deptSelect.innerHTML = '<option value="">Pilih Departemen</option>';
                data.departemen.forEach(item => {
                    deptSelect.innerHTML += `<option value="${item.id}">${item.nama_dept}</option>`;
                });
                deptSelect.dispatchEvent(new Event('change'));
            });
    });

    deptSelect?.addEventListener('change', function () {
        const deptId = this.value;
        fetch(`/get-satker-by-departemen/${deptId}`)
            .then(response => response.json())
            .then(data => {
                satkerSelect.innerHTML = '<option value="">Pilih Satuan Kerja</option>';
                data.satker.forEach(item => {
                    satkerSelect.innerHTML += `<option value="${item.id}">${item.satuan_kerja}</option>`;
                });
            });
    });

    satkerSelect?.addEventListener('change', function () {
        const satId = this.value;
        fetch(`/get-position-by-satker/${satId}`)
            .then(response => response.json())
            .then(data => {
                jabatSelect.innerHTML = '<option value="">Pilih Jabatan</option>';
                data.positions.forEach(item => {
                    jabatSelect.innerHTML += `<option value="${item.id}">${item.jabatan}</option>`;
                });
            });
    });
});
</script>
@endpush