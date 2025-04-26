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
                <div class="card-header d-flex justify-content-between fw-bold">{{ __('Laporan Admin') }}
                    <a href="{{route('lapor.admin.input')}}" class="btn btn-sm btn-danger">Buat Laporan</a>
                </div>

                <div class="card-body d-flex justify-content-center" style="overflow: auto;">
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>No. Laporan</th>
                                <th>Nama</th>
                            @if(Auth::user()->role == 0 || Auth::user()->role == 1 || Auth::user()->role == 3)
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
                                <td onclick="window.location='/laporan/admin/detail/{{$lap->id}}'" style="cursor:pointer;">{{$lapor->firstItem() + $num}}</td>
                                <td onclick="window.location='/laporan/admin/detail/{{$lap->id}}'" style="cursor:pointer;">{{$lap->no_lap}}</td>
                                <td onclick="window.location='/laporan/admin/detail/{{$lap->id}}'" style="cursor:pointer;">{{$lap->usr->name}}</td>
                            @if(Auth::user()->role == 0 || Auth::user()->role == 1 || Auth::user()->role == 3)
                                <td onclick="window.location='/laporan/admin/detail/{{$lap->id}}'" style="cursor:pointer;">{{$lap->usr->kant->nama_kantor ?? ''}}</td>
                            @endif
                                <td onclick="window.location='/laporan/admin/detail/{{$lap->id}}'" style="cursor:pointer;">{{Carbon\Carbon::parse($lap->created_at)->format('d-m-Y')}}</td>
                                <td onclick="window.location='/laporan/admin/detail/{{$lap->id}}'" style="cursor:pointer;">{{Carbon\Carbon::parse($lap->created_at)->format('H:i')}}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-warning btn-edit" data-id="{{ $lap->id }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>

                                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $lap->id }}">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
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
                window.location.href = '/laporan/admin/edit/' + id ;
            }
        });
    });

    });
</script>
@endpush