@extends('layouts.absen.absen')

@section('header')
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" class="ion-icon"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Form Izin</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<div class="container mb-4" style="margin-top: 3.5rem;">
    <div class="col mx-0 px-0" style=" margin-bottom: 5rem;">
        <form method="POST" action="{{ route('absen.storeizin') }}" enctype="multipart/form-data" onsubmit="return validateForm()">
            @csrf
            <div class="mb-2">
                <label for="tanggal" class="form-label mb-1">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" min="{{ Carbon\Carbon::now()->subDays(7)->format('Y-m-d') }}" max="{{ Carbon\Carbon::now()->addDays(7)->format('Y-m-d') }}">
            </div>

            <div class="mb-2">
                <label for="jenisIzin" class="form-label mb-1">Jenis Izin</label>
                <select class="form-select" id="jenisIzin" name="jenisIzin">
                    <option value="" class="text-muted">Pilih Jenis Izin</option>
                    <option value="i">Izin</option>
                    <option value="s">Sakit</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label mb-1">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label for="buktiFoto" class="form-label">Upload Bukti Surat Izin/Sakit/Ket Dokter</label>
                <input type="file" class="form-control" id="buktiFoto" name="buktiFoto" accept="image/*" onchange="previewImage(event)">
                <center>
                    <img id="preview" src="#" alt="Preview Image" class="img-thumbnail mt-3" style="display:none; max-height: 200px;">
                </center>
            </div>

            <button type="submit" class="btn btn-primary w-100">Kirim</button>
        </form>
    </div>
</div>

<div id="loading" style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    display: none;
">
    <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
@endsection

@push('myscript')
<script>
    function previewImage(event) {
	    const input = event.target;
	    const preview = document.getElementById('preview');
	    if (input.files && input.files[0]) {
	        const reader = new FileReader();
	        reader.onload = function(e) {
	            preview.src = e.target.result;
	            preview.style.display = 'block';
	        }
	        reader.readAsDataURL(input.files[0]);
	    } else {
	        preview.style.display = 'none';
	        preview.src = '#';
	    }
	}

    function validateForm() {
	    const tanggal = document.getElementById('tanggal');
	    const jenisIzin = document.getElementById('jenisIzin');
	    const keterangan = document.getElementById('keterangan');
	    const buktiFoto = document.getElementById('buktiFoto');

	    if (tanggal.value === '') {
	        Swal.fire('Error', 'Tanggal wajib diisi.', 'error').then(() => tanggal.focus());
	        return false;
	    }

	    if (jenisIzin.value === '') {
	        Swal.fire('Error', 'Jenis izin wajib dipilih.', 'error').then(() => jenisIzin.focus());
	        return false;
	    }

	    if (keterangan.value.trim() === '') {
	        Swal.fire('Error', 'Keterangan wajib diisi.', 'error').then(() => keterangan.focus());
	        return false;
	    }

	    if (buktiFoto.value === '') {
	        Swal.fire('Error', 'Keterangan wajib diisi.', 'error').then(() => keterangan.focus());
	        return false;
	    }

	    document.getElementById('loading').style.display = 'flex';
	    return true;
	}

</script>
@endpush
