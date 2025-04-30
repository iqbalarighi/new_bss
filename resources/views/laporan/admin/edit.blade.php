@extends('layouts.side.side')
@section('content')
<div class="container mw-100">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between fw-bold">{{ __('Edit Laporan Admin') }}
                    <a href="{{ route('lapor.admin') }}" class="btn btn-sm btn-danger">Kembali</a>
                </div>

                <div class="card-body d-flex justify-content-center" style="overflow: auto;">
                    <div class="col-md-8 fw-bold">
                        {{-- <div class="mb-1">
                            Supervisor : {{ $edit->usr->name}}
                        </div>
                        <div class="mb-1">
                            Kantor : {{ $edit->kant->nama_kantor ?? "" }}
                        </div>
                        <div class="mb-1">
                            Departemen : {{ $edit->deptmn->nama_dept ?? "" }}
                        </div>
                        <div class="mb-3">
                            Satuan Kerja : {{ $edit->sat->satuan_kerja ?? "" }}
                        </div> --}}

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
                        <form id="laporanForm" action="{{ route('lapor.admin.update', $edit->id) }}" method="POST" enctype="multipart/form-data">
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
                                        <strong>Foto Saat Ini:</strong><br>
                                        <div class="d-flex flex-wrap">
                                            @foreach(explode('|', $edit->foto) as $foto)
                                                <div class="position-relative m-1">
                                                    <img src="{{ asset('storage/laporan/admin/' . $edit->no_lap . '/' . $foto) }}" alt="Foto" width="200" class="img-thumbnail">
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
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
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
                        url: '/laporan/admin/hapus-foto/' + id + '',
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

    });
</script>
@endpush
