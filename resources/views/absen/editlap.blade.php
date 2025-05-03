@extends('layouts.absen.absen')

@section('header')
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="{{route('absen.lapor.detail', $edit->id)}}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" class="ion-icon"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Edit Laporan</div>
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
        <tr>
                        <td>
                            <b><center>Laporan Kegiatan Admin</center></b>
                            <b><center>{{$edit->kant->nama_kantor ?? ''}}</center></b>
                            <b><center>{{Carbon\Carbon::parse($edit->tanggal)->isoFormat('dddd, D MMMM Y')}}</center></b>
                            <b><center>Pukul {{Carbon\Carbon::parse($edit->updated_at)->isoFormat('HH:mm:ss')}} WIB</center></b>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <div class="mb-1 mt-3">
                        No. Laporan  : {{$edit->no_lap}}
                    </div>
                        <form id="laporanForm" action="{{route('absen.updatelap', $edit->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                            <div class="mb-3">
                                <label for="personil" class="form-label mb-0">Personil</label>
                                <textarea class="form-control" id="personil" name="personil" rows="3" required>{{ $edit->personil }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="kegiatan" class="form-label mb-0">Kegiatan</label>
                                <textarea class="form-control" id="kegiatan" name="kegiatan" rows="3" required>{{ $edit->kegiatan }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label mb-0">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ $edit->keterangan }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="foto" class="form-label mb-0">Foto</label>
                                <input type="file" class="form-control" id="foto" name="foto[]" accept="image/*" multiple>
                                
                                @if ($edit->foto)
                                    <div class="mt-2">
                                        <strong>Foto Dokumentasi:</strong><br>
                                        <div class="d-flex flex-wrap justify-content-center">
                                            @foreach(explode('|', $edit->foto) as $foto)
                                                <div class="position-relative m-1">
                                                    <img src="{{ asset('storage/laporan/' . $edit->no_lap . '/' . $foto) }}" alt="Foto" width="200" class="img-thumbnail">
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete-foto position-absolute top-0 end-0 m-1" 
                                                        data-foto="{{ $foto }}" data-id="{{ $edit->id }}">
                                                        &times;
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
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
    $('#laporanForm').on('submit', function(e) {
        e.preventDefault();

        var form = $(this);
        var submitButton = form.find('button[type="submit"]');

        var formData = new FormData(this);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                // Disable tombol submit dan ubah tulisan jadi loading
                submitButton.prop('disabled', true);
                submitButton.html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: 'Laporan berhasil diupdate!',
                }).then(() => {
                    window.location.reload(true);
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat mengupdate laporan!',
                });
            },
            complete: function() {
                // Enable tombol submit lagi dan kembalikan tulisan aslinya
                submitButton.prop('disabled', false);
                submitButton.html('Simpan');
            }
        });
    });

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

        // Tombol hapus foto
        $(document).on('click', '.btn-delete-foto', function() {
            var foto = $(this).data('foto');
            var id = $(this).data('id');

            Swal.fire({
                title: 'Yakin hapus foto ini?',
                text: "Foto akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/laporan/hapus-foto/' + id + '',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            foto: foto
                        },
                        success: function(response) {
                            Swal.fire(
                                'Terhapus!',
                                'Foto berhasil dihapus.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menghapus foto.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
</script>

@endpush
