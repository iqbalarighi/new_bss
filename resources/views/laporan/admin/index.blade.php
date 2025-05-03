@extends('layouts.side.side')
@section('content')
<div class="container mw-100">
    <style type="text/css">
        table, tr, td, th{
            padding: 2px !important;
        }
    </style>
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between fw-bold">{{ __('Laporan Kegiatan '.$lapor[0]->sat->satuan_kerja) }}
                    {{-- <a href="{{route('lapor.admin.input')}}" class="btn btn-sm btn-danger">Buat Laporan</a> --}}
                </div>
                <div class="card-body">
                    <form method="GET" action="{{  route('laporan.satker', $id) }}" class="row gy-2 align-items-center mb-3">
                        <div class="col-12 col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Cari Laporan" value="{{ request('search') }}">
                        </div>
                        <div class="col-12 col-md-3">
                            <input type="text" name="tanggal" id="tanggal" class="form-control" placeholder="Tanggal (dd-mm-yyyy)" value="{{ request('tanggal') }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary w-100 w-md-auto">Cari</button>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('laporan.satker', $id) }}" class="btn btn-secondary w-100 w-md-auto">Reset</a>
                        </div>
                    </form>

                <div class="d-flex justify-content-center" style="overflow: auto;">
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>No. Laporan</th>
                            @if(Auth::user()->role == 0 || Auth::user()->role == 1 || Auth::user()->role == 3)
                                <th>Nama</th>
                            @endif
                            @if(Auth::user()->role == 0 )
                                <th>Kantor</th>
                            @endif
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($lapor as $num => $lap)
                            <tr class="text-center">
                                <td onclick="window.location='/laporan/{{$id}}/detail/{{$lap->id}}'" style="cursor:pointer;">{{$lapor->firstItem() + $num}}</td>
                                <td onclick="window.location='/laporan/{{$id}}/detail/{{$lap->id}}'" style="cursor:pointer;">{{$lap->no_lap}}</td>
                                <td onclick="window.location='/laporan/{{$id}}/detail/{{$lap->id}}'" style="cursor:pointer;">{{$lap->usr->nama_lengkap ?? ''}}</td>
                            @if(Auth::user()->role == 0 )
                                <td onclick="window.location='/laporan/{{$id}}/detail/{{$lap->id}}'" style="cursor:pointer;">{{$lap->usr->kantor->nama_kantor ?? ''}}</td>
                            @endif
                                <td onclick="window.location='/laporan/{{$id}}/detail/{{$lap->id}}'" style="cursor:pointer;">{{Carbon\Carbon::parse($lap->created_at)->format('d-m-Y')}}</td>
                                <td onclick="window.location='/laporan/{{$id}}/detail/{{$lap->id}}'" style="cursor:pointer;">{{Carbon\Carbon::parse($lap->created_at)->format('H:i')}}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-warning btn-edit" data-id="{{ $lap->id }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                @if(Auth::user()->role == 0 || Auth::user()->role == 1 || Auth::user()->role == 3)
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $lap->id }}">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $lapor->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('script')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#tanggal", {
        dateFormat: "d-m-Y",
        allowInput: true
    });
</script>

<script>
    $(document).ready(function() {
        // Form simpan
        $('#laporanForm').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this); 

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        text: 'Laporan berhasil disimpan!',
                    }).then(() => {
                        window.location.href = "{{ route('laporan.satker', $id) }}";
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

        // Tombol hapus
        $('.btn-delete').click(function() {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Yakin hapus data ini?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/laporan/admin/hapus/' + id,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Terhapus!',
                                'Data berhasil dihapus.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menghapus data.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        // Tombol edit
    $('.btn-edit').click(function() {
        var id = $(this).data('id');

        Swal.fire({
            title: 'Ingin mengedit data ini?',
            text: "Pastikan data yang diedit benar ya!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Edit',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/laporan/{{$id}}/edit/' + id ;
            }
        });
    });

    });
</script>
@endpush