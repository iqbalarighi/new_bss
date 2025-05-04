@extends('layouts.absen.absen')

@section('header')
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" class="ion-icon"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Buat Laporan</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (Session::get('error'))
<script type="text/javascript">
    Swal.fire({
  icon: "warning",
  title: "{{Session::get('error')}}",
  showConfirmButton: true,
});
</script>
@endif
<div class="container mb-4" style="margin-top: 3.5rem;">
    <div class="col mx-0 px-0" style=" margin-bottom: 5rem;">
        <form method="POST" action="{{ route('absen.storelap') }}" enctype="multipart/form-data" onsubmit="return validateForm()">
            @csrf
            <div class="mb-3">
                <label for="personil" class="form-label mb-1">Personil</label>
                <textarea class="form-control" id="personil" name="personil" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label for="kegiatan" class="form-label mb-1">Uraian Kegiatan</label>
                <textarea class="form-control" id="kegiatan" name="kegiatan" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label mb-1">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">Upload Bukti Surat Izin/Sakit/Ket Dokter</label>
                <input type="file" class="form-control" id="foto" name="foto[]" accept="image/*" onchange="previewImage(event)" multiple>
                <center>
                    <div id="previewContainer" class="mt-3 d-flex flex-wrap justify-content-center gap-2"></div>
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
        const previewContainer = document.getElementById('previewContainer');
        previewContainer.innerHTML = ''; // Clear previous previews

        if (input.files && input.files.length > 0) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail');
                    img.style.maxHeight = '150px';
                    img.style.margin = '5px';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    }
</script>
<script>

    function validateForm() {
	    const personil = document.getElementById('personil');
	    const kegiatan = document.getElementById('kegiatan');
	    const keterangan = document.getElementById('keterangan');
	    const foto = document.getElementById('foto');

	    if (personil.value === '') {
	        Swal.fire('Error', 'Personil wajib diisi.', 'error').then(() => personil.focus());
	        return false;
	    }

	    if (kegiatan.value === '') {
	        Swal.fire('Error', 'Kegiatan mohon di isi.', 'error').then(() => kegiatan.focus());
	        return false;
	    }

	    if (keterangan.value.trim() === '') {
	        Swal.fire('Error', 'Keterangan wajib diisi.', 'error').then(() => keterangan.focus());
	        return false;
	    }

	    if (foto.value === '') {
	        Swal.fire('Error', 'Foto wajib diisi.', 'error').then(() => foto.focus());
	        return false;
	    }

	    document.getElementById('loading').style.display = 'flex';
	    return true;
	}

</script>
@endpush
