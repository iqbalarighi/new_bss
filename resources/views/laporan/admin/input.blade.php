@extends('layouts.side.side')
@section('content')
<div class="container mw-100">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between fw-bold">{{ __('Laporan Admin') }}
                    <a href="{{ route('lapor.admin')}}" class="btn btn-sm btn-danger">Kembali</a>
                </div>

                <div class="card-body d-flex justify-content-center" style="overflow: auto;">
                    <div class="col-md-8">
                        <div class="mb-1">
                            Nama : {{Auth::user()->name}}
                        </div>
                        <div class="mb-1">
                            Kantor : {{Auth::user()->kant->nama_kantor ?? ""}}
                        </div>
                        <div class="mb-1">
                            Departemen : {{Auth::user()->deptmn->nama_dept ?? ""}}
                        </div>
                        <div class="mb-3">
                            Satuan Kerja : {{Auth::user()->sat->satuan_kerja ?? ""}}
                        </div>
                        <form id="laporanForm" action="{{ route('lapor.admin.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="personil" class="form-label">Personil</label>
                                <textarea class="form-control" id="personil" name="personil" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="kegiatan" class="form-label">Kegiatan</label>
                                <textarea class="form-control" id="kegiatan" name="kegiatan" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            </div>
                            <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Simpan</button>
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

            var formData = new FormData(this); // Menggunakan FormData untuk mengirim file

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: formData,
                processData: false, // Penting untuk FormData
                contentType: false, // Penting untuk FormData
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        text: 'Laporan berhasil disimpan!',
                    }).then(() => {
                        window.location.href = "{{ route('lapor.admin') }}";
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat menyimpan laporan!',
                    });
                }
            });
        });
    });
</script>
@endpush