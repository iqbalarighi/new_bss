@extends('layouts.side.side')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
.select2-container {
    width: 100% !important;
}
</style>
@endpush

@section('content')
<div class="container mt-4">

    {{-- SWEET ALERT --}}
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                html: `{!! implode('<br>', $errors->all()) !!}`,
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}"
            });
        @endif
    </script>

    <div class="card shadow">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
    
    <h5 class="mb-0">Pengecualian Absen (Luar Radius)</h5>
    
    <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#modalTambah">
        + Tambah Pengecualian
    </button>

</div>

        <div class="card-body">

<form method="GET" action="">
    <div class="row mb-3">

        <div class="col-md-3">
            <label>Kantor</label>
            <select name="kantor" class="form-control">
                <option value="">-- Semua Kantor --</option>
                @foreach($kantor as $k)
                    <option value="{{ $k->id }}"
                        {{ request('kantor') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kantor }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label>Cari Nama / NIP</label>
            <input type="text" name="search" class="form-control"
                value="{{ request('search') }}"
                placeholder="Masukkan nama atau NIP">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-danger w-100">Filter</button>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <a href="{{ url()->current() }}" class="btn btn-secondary w-100">Reset</a>
        </div>

    </div>
</form>
            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Lokasi</th>
                            <th>Keterangan</th>
                            <th>Periode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $key => $d)
                        <tr>
                            <td>{{ $data->firstitem() + $key }}</td>
                            <td>{{ $d->karyawan->nama_lengkap ?? '-' }}</td>
                            <td>{{ $d->karyawan->nip ?? '-' }}</td>
                            <td>{{ $d->karyawan->kantor->nama_kantor?? '-' }}</td>
                            <td>{{ $d->keterangan ?? '-' }}</td>
<td>
    @php
        $list = $d->tanggal_list ?? [];
        $jumlah = count($list);
    @endphp

    @if($jumlah == 1)
        <span class="badge bg-info">
            {{ \Carbon\Carbon::parse($list[0])->format('d-m-Y') }}
        </span>

    @elseif($jumlah > 1)
        <button class="btn btn-sm btn-outline-primary btn-detail-tanggal"
            data-tanggal='@json($list)'>
            {{ $jumlah }} Tanggal
        </button>

    @else
        <span class="badge bg-success">Permanent</span>
    @endif
</td>
                            <td>

                                {{-- EDIT --}}
                                <button class="btn btn-warning btn-sm"
                                    onclick='openEditModal({
                                        id: {{ $d->id }},
                                        karyawan_id: {{ $d->karyawan_id }},
                                        nama_pegawai: @json($d->karyawan->nama_lengkap),
                                        nip: @json($d->karyawan->nip),
                                        keterangan: @json($d->keterangan),
                                        tanggal: @json($d->tanggal_list)
                                    })'>
                                    Edit
                                </button>

                                {{-- DELETE --}}
                                <form id="delete-form-{{ $d->id }}" action="{{ url('/pengecualian-absen/'.$d->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $d->id }})">
                                        Hapus
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
<div class="d-flex justify-content-center">
                        {{$data->links('pagination::bootstrap-4')}}
                    </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="modalEdit">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Edit Pengecualian</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="edit_karyawan_id" name="karyawan_id">

                    <div class="form-group">
                        <label>Pegawai</label>
                        <input type="text" id="edit_nama_pegawai" class="form-control bg-light" readonly>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" id="edit_keterangan" name="keterangan" class="form-control">
                    </div>

                    <!--<div class="form-row">-->
                    <!--    <div class="col">-->
                    <!--        <label>Mulai</label>-->
                    <!--        <input type="date" id="edit_mulai" name="tanggal_mulai" class="form-control">-->
                    <!--    </div>-->
                    <!--    <div class="col">-->
                    <!--        <label>Selesai</label>-->
                    <!--        <input type="date" id="edit_selesai" name="tanggal_selesai" class="form-control">-->
                    <!--    </div>-->
                    <!--</div>-->
                    
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="text" id="edit_tanggal" name="tanggal[]" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>

            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form action="{{ url('/pengecualian-absen/store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pengecualian Absen</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Pegawai</label>
                        <select name="karyawan_id" id="karyawan_id" class="form-control" required></select>
                    </div>
                
                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control">
                    </div>
                
                    <!--<div class="form-row">-->
                    <!--    <div class="col">-->
                    <!--        <label>Mulai</label>-->
                    <!--        <input type="date" name="tanggal_mulai" class="form-control">-->
                    <!--    </div>-->
                    <!--    <div class="col">-->
                    <!--        <label>Selesai</label>-->
                    <!--        <input type="date" name="tanggal_selesai" class="form-control">-->
                    <!--    </div>-->
                    <!--</div>-->
                    
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="text" name="tanggal[]" id="tanggal_multi" class="form-control">
                    </div>
                
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Batal
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    const openModalTambah = @json(session('open_modal_tambah'));
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script>
$(document).ready(function () {

    // ================= AUTO OPEN AFTER SUBMIT =================
    if (typeof openModalTambah !== 'undefined' && openModalTambah) {
        $('#modalTambah').modal('show');
    }

    // ================= SELECT2 =================
    $('#karyawan_id').select2({
        dropdownParent: $('#modalTambah'),
        placeholder: "Cari Pegawai...",
        width: '100%',
        ajax: {
            url: '/api/karyawan',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    kantor: $('select[name=kantor]').val()
                };
            },
            processResults: function (data) {
                return { results: data };
            }
        }
    });

    // ================= AUTO OPEN SELECT2 =================
    $('#modalTambah').on('shown.bs.modal', function () {

        // reset form saat modal dibuka manual
        $(this).find('form')[0].reset();
        $('#tanggal_multi').val('');

        setTimeout(function () {
            $('#karyawan_id').select2('open');
            const searchField = document.querySelector('.select2-search__field');
            if (searchField) searchField.focus();
        }, 200);

    });

    // ================= RESET SAAT MODAL TERTUTUP =================
    $('#modalTambah').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('#tanggal_multi').val('');
        $('#karyawan_id').val(null).trigger('change');
    });

});

// DELETE
function confirmDelete(id) {
    Swal.fire({
        title: 'Yakin?',
        text: "Data tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#delete-form-' + id).submit();
        }
    });
}

// EDIT
function openEditModal(data) {

    // set action form
    $('#formEdit').attr('action', '/pengecualian-absen/' + data.id);

    // isi data
    $('#edit_karyawan_id').val(data.karyawan_id);
    $('#edit_nama_pegawai').val(data.nama_pegawai + ' (' + data.nip + ')');
    $('#edit_keterangan').val(data.keterangan);

    // 🔥 isi flatpickr
    if (data.tanggal && data.tanggal.length) {
        fpEdit.setDate(data.tanggal, true);
    } else {
        fpEdit.clear();
    }

    // tampilkan modal
    $('#modalEdit').modal('show');
}


$('#modalTambah').on('hidden.bs.modal', function () {
    $(this).find('form')[0].reset();
});

flatpickr("#tanggal_multi", {
    mode: "multiple",
    dateFormat: "Y-m-d"
});

$(document).on('click', '.btn-detail-tanggal', function () {
    let tanggalList = $(this).data('tanggal');

    let html = '';

    tanggalList.forEach(function (tgl) {
        let format = moment(tgl).format('DD-MM-YYYY');
        html += `<span class="badge bg-info m-1">${format}</span>`;
    });

    Swal.fire({
        title: 'Detail Tanggal Pengecualian',
        html: html,
        width: 400,
        confirmButtonText: 'Tutup'
    });
});

// TAMBAH
flatpickr("#tanggal_multi", {
    mode: "multiple",
    dateFormat: "Y-m-d"
});

// EDIT
let fpEdit = flatpickr("#edit_tanggal", {
    mode: "multiple",
    dateFormat: "Y-m-d"
});


</script>
@endpush