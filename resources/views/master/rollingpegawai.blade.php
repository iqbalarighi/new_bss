@extends('layouts.side.side')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('content')
<div class="container mt-1 mw-100">

@if(session('success'))
<script>
Swal.fire('Berhasil!', '{{ session('success') }}', 'success');
</script>
@endif

<div class="card shadow-lg">

<div class="card-header bg-danger text-white fw-bold d-flex justify-content-between">
    Rolling Pegawai
    <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#modalRolling">
        + Rolling
    </button>
</div>

<div class="card-body">
<div class="table-responsive">
<table class="table table-bordered table-striped">
<thead>
<tr>
<th>No</th>
<th>Nama</th>
<th>Kantor</th>
<th>Dept</th>
<th>Satker</th>
<th>Jabatan</th>
<th>Tanggal</th>
<th>Status</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>
@foreach($data as $item)
<tr>
<td>{{ $data->firstItem() + $loop->index }}</td>
<td>{{ $item->pegawai->nama_lengkap }}</td>
<td>{{ $item->kant->nama_kantor ?? '-' }}</td>
<td>{{ $item->deptmn->nama_dept ?? '-' }}</td>
<td>{{ $item->sat->satuan_kerja ?? '-' }}</td>
<td>{{ $item->jabat->jabatan ?? '-' }}</td>
<td>
    {{ $item->tanggal_efektif 
        ? \Carbon\Carbon::parse($item->tanggal_efektif)->translatedFormat('d F Y') 
        : '-' 
    }}
</td>
<td>
    @if($item->is_executed)
        <span class="badge badge-success">Executed</span>
    @else
        <span class="badge badge-warning">Pending</span>
    @endif
</td>

<td>
<button class="btn btn-warning btn-sm btn-edit"
    data-id="{{ $item->id }}"
    data-pegawai="{{ $item->pegawai_id }}"
    data-nama="{{ $item->pegawai->nama_lengkap }}"
    data-kantor="{{ $item->kantor }}"
    data-dept="{{ $item->dept }}"
    data-satker="{{ $item->satker }}"
    data-jabatan="{{ $item->jabatan }}"
    data-tanggal="{{ $item->tanggal_efektif }}">
    Edit
</button>

<button class="btn btn-danger btn-sm btn-delete"
    data-id="{{ $item->id }}">
    Hapus
</button>
</td>

</tr>
@endforeach
</tbody>
</table>
</div>
<div class="d-flex justify-content-between align-items-center mt-3">

    <div>
        <small>
        @if($data->count())
            Menampilkan {{ $data->firstItem() }} - {{ $data->lastItem() }} 
            dari {{ $data->total() }} data
        @else
            Tidak ada data
        @endif
        </small>
    </div>

    <div>
        {{ $data->links('pagination::bootstrap-4') }}
    </div>

</div>
</div>
</div>
</div>

{{-- ================= MODAL CREATE ================= --}}
<div class="modal fade" id="modalRolling">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<form action="{{ route('rollingpegawai.store') }}" method="POST">
@csrf

<div class="modal-header bg-danger text-white">
<h5>Tambah Rolling</h5>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">


<div class="mb-3">
<label>Pegawai</label>
<select id="pegawai_id" name="pegawai_id" class="form-control" style="width:100%"></select>
</div>

<div class="mb-3">
<label>Kantor</label>
<select id="select-kantor" name="kantor" class="form-control"></select>
</div>

<div class="mb-3">
<label>Departemen</label>
<select id="select-dept" name="dept" class="form-control"></select>
</div>

<div class="mb-3">
<label>Satker</label>
<select id="select-satker" name="satker" class="form-control"></select>
</div>

<div class="mb-3">
<label>Jabatan</label>
<select id="select-jabat" name="jabatan" class="form-control"></select>
</div>

<div class="mb-3">
<label>Tanggal</label>
<input type="text" name="tanggal_efektif" id="tanggal_create" class="form-control" placeholder="Pilih tanggal">
</div>

</div>

<div class="modal-footer">
<button class="btn btn-danger">Simpan</button>
</div>

</form>
</div>
</div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div class="modal fade" id="modalEdit">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<form id="formEdit" method="POST">
@csrf
@method('PUT')

<div class="modal-header bg-warning text-white">
<h5>Edit Rolling</h5>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">

<input type="hidden" name="pegawai_id" id="pegawai_id_edit">

<div class="mb-3">
<label>Pegawai</label>
<input type="text" id="pegawai_nama_edit" class="form-control" readonly>
</div>

<div class="mb-3">
<label>Kantor</label>
<select id="kantor_edit" name="kantor" class="form-control"></select>
</div>

<div class="mb-3">
<label>Dept</label>
<select id="dept_edit" name="dept" class="form-control"></select>
</div>

<div class="mb-3">
<label>Satker</label>
<select id="satker_edit" name="satker" class="form-control"></select>
</div>

<div class="mb-3">
<label>Jabatan</label>
<select id="jabat_edit" name="jabatan" class="form-control"></select>
</div>

<div class="mb-3">
<label>Tanggal</label>
<input type="text" name="tanggal_efektif" id="tanggal_edit" class="form-control">
</div>

</div>

<div class="modal-footer">
<button class="btn btn-warning">Update</button>
</div>

</form>
</div>
</div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
$(document).ready(function(){

// ================= SELECT2 =================
$('#pegawai_id').select2({
    dropdownParent: $('#modalRolling'),
    width: '100%',
    placeholder: 'Cari Pegawai...',
    minimumInputLength: 1,

    ajax:{
        url:'/api/karyawan',
        dataType:'json',
        delay: 250,
        data: function (params) {
            return { q: params.term };
        },
        processResults: function(data){
            return {
                results: data
            };
        },
        cache: true
    }
});

// ================= HELPER =================
function fillSelect(el, data, value, text){
    $(el).html('<option value="">Pilih</option>');
    data.forEach(x=>{
        $(el).append(`<option value="${x[value]}">${x[text]}</option>`);
    });
}

// ================= LOAD MASTER =================
function loadMasterCreate(){
    return $.get('/get-konten/{{ Auth::user()->perusahaan }}');
}

function loadDeptSatker(kantor){
    return $.get('/get-sat/' + kantor);
}

function loadSatkerByDept(dept){
    return $.get('/get-satker-by-departemen/' + dept);
}

function loadJabatan(satker){
    return $.get('/get-position-by-satker/' + satker);
}

// ================= AUTO FILL PEGAWAI =================
$('#pegawai_id').on('select2:select', function(e){

    let id = e.params.data.id;

    $.get('/rollingpegawai/detail/' + id).done(async function(res){

        let master = await loadMasterCreate();

        // KANTOR
        fillSelect('#select-kantor', master.offices, 'id', 'nama_kantor');
        $('#select-kantor').val(res.nama_kantor);

        // DEPT + SATKER
        let sat = await loadDeptSatker(res.nama_kantor);

        fillSelect('#select-dept', sat.departemen, 'id', 'nama_dept');
        $('#select-dept').val(res.dept);

        fillSelect('#select-satker', sat.satker, 'id', 'satuan_kerja');
        $('#select-satker').val(res.satker);

        // JABATAN
        let jab = await loadJabatan(res.satker);

        fillSelect('#select-jabat', jab.positions, 'id', 'jabatan');
        $('#select-jabat').val(res.jabatan);

    });

});

// ================= CHAIN CREATE =================

// kantor → dept + satker
$('#select-kantor').on('change', async function(){

    let kantor = $(this).val();
    if(!kantor) return;

    let res = await loadDeptSatker(kantor);

    fillSelect('#select-dept', res.departemen, 'id', 'nama_dept');
    fillSelect('#select-satker', res.satker, 'id', 'satuan_kerja');
    $('#select-jabat').html('<option value="">Pilih</option>');
});

// dept → satker
$('#select-dept').on('change', async function(){

    let dept = $(this).val();
    if(!dept) return;

    let res = await loadSatkerByDept(dept);

    fillSelect('#select-satker', res.satker, 'id', 'satuan_kerja');
    $('#select-jabat').html('<option value="">Pilih</option>');
});

// satker → jabatan
$('#select-satker').on('change', async function(){

    let satker = $(this).val();
    if(!satker) return;

    let res = await loadJabatan(satker);

    fillSelect('#select-jabat', res.positions, 'id', 'jabatan');
});

// ================= EDIT =================
$('.btn-edit').click(async function(){

    let d = $(this).data();

    $('#formEdit').attr('action','{{ url("rollingpegawai/update") }}/'+d.id);

    $('#pegawai_nama_edit').val(d.nama);
    $('#pegawai_id_edit').val(d.pegawai);
    $('#tanggal_edit').val(d.tanggal);

    let master = await loadMasterCreate();

    fillSelect('#kantor_edit', master.offices, 'id', 'nama_kantor');
    $('#kantor_edit').val(d.kantor);

    let sat = await loadDeptSatker(d.kantor);

    fillSelect('#dept_edit', sat.departemen, 'id', 'nama_dept');
    $('#dept_edit').val(d.dept);

    let satker = await loadSatkerByDept(d.dept);

    fillSelect('#satker_edit', satker.satker, 'id', 'satuan_kerja');
    $('#satker_edit').val(d.satker);

    let jab = await loadJabatan(d.satker);

    fillSelect('#jabat_edit', jab.positions, 'id', 'jabatan');
    $('#jabat_edit').val(d.jabatan);

    $('#modalEdit').modal('show');
});

// ================= DELETE =================
$('.btn-delete').click(function(){

    let id = $(this).data('id');

    Swal.fire({
        title:'Yakin?',
        icon:'warning',
        showCancelButton:true
    }).then(r=>{
        if(r.isConfirmed){
            $.ajax({
                url:'/rollingpegawai/delete/'+id,
                type:'DELETE',
                data:{_token:'{{ csrf_token() }}'},
                success:()=>location.reload()
            });
        }
    });

});

// ================= UPDATE =================
$('#formEdit').submit(function(e){
    e.preventDefault();

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        success: function(){
            $('#modalEdit').modal('hide');
            Swal.fire('Berhasil!', 'Data berhasil diupdate', 'success')
            .then(()=> location.reload());
        },
        error: function(xhr){
            Swal.fire('Error!', xhr.responseText, 'error');
        }
    });
});

// ================= FIX MODAL CLOSE =================
$(document).on('click', '[data-dismiss="modal"]', function () {
    $('.modal').modal('hide');
});

// ================= FLATPICKR =================
flatpickr("input[name='tanggal_efektif']", {
    dateFormat: "Y-m-d",
    allowInput: true
});

flatpickr("#tanggal_edit", {
    dateFormat: "Y-m-d",
    allowInput: true
});

// ================= AUTO FOCUS SELECT2 =================
$('#modalRolling').on('shown.bs.modal', function () {
    $('#pegawai_id').select2('open');
});

// OPTIONAL: kalau mau fokus lagi tiap buka dropdown
$(document).on('select2:open', () => {
    document.querySelector('.select2-container--open .select2-search__field')?.focus();
});


});
</script>
@endpush