@extends('layouts.side.side')

@push('styles')
<!-- SELECT2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container {
    z-index: 9999 !important;
}

.select2-container--open {
    z-index: 999999 !important;
}

.dragging-active {
    cursor: grabbing;
}

.drag-handle {
    user-select: none;
}

.sortable-chosen {
    background: #fff3cd !important;
}

.sortable-ghost {
    opacity: 0.4;
}

.is-danru {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
<style>
.highlight {
    background-color: #ffe066;
    padding: 2px 4px;
    border-radius: 4px;
}
</style>
@endpush

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Regu Pengamanan</h5>

        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalRegu">
            + Regu
        </button>
    </div>
<div class="mb-3">
<form method="GET" action="{{ url('/regu') }}" class="mb-3">
    <div class="input-group">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               class="form-control"
               placeholder="🔍 Cari regu / spv / danru / anggota / NIP...">

        <button class="btn btn-primary">
            Cari
        </button>

        @if(request('search'))
        <a href="{{ url('/regu') }}" class="btn btn-secondary">
            Reset
        </a>
        @endif
    </div>
</form>
</div>
    <div class="accordion" id="accordionRegu">

        @forelse($regu as $r)
        <div class="accordion-item mb-2 shadow-sm regu-item"
         data-nama="{{ strtolower($r->nama_regu) }}"
         data-danru="{{ strtolower($r->danru->nama_lengkap ?? '') }}">

            {{-- HEADER ACCORDION --}}
            <h2 class="accordion-header" id="heading{{ $r->id }}">
                <button class="accordion-button collapsed p-1" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapse{{ $r->id }}">

                    <div class="d-flex justify-content-between w-100 align-items-center">

                        <div>
                            <b>{{ $r->nama_regu }}</b>

                            {{-- badge ringkasan --}}
                            <span class="badge bg-secondary ms-2">
                                {{ count($r->anggota) }} anggota
                            </span>

                            @if($r->supervisor)
                                <span class="badge bg-primary ms-1">
                                    Supervisor: {{ $r->supervisor->nama_lengkap }}
                                </span>
                            @endif
                            @if($r->danru)
                                <span class="badge bg-danger ms-1">
                                    Danru: {{ $r->danru->nama_lengkap }}
                                </span>
                            @endif
                        </div>

                    </div>

                </button>
            </h2>

            {{-- BODY ACCORDION --}}
            <div id="collapse{{ $r->id }}" class="accordion-collapse collapse"
                data-bs-parent="#accordionRegu">

                <div class="accordion-body p-1">

                    {{-- ACTION BUTTON --}}
                    <div class="mb-2 d-flex gap-1 flex-wrap float-end">

                        <button class="btn btn-info btn-sm btnAssignSupervisor"
                            data-id="{{ $r->id }}"
                            data-nama="{{ $r->nama_regu }}"
                            data-supervisor="{{ $r->supervisor_id }}">
                            Supervisor
                        </button>

                        <button class="btn btn-success btn-sm btnDanru"
                            data-id="{{ $r->id }}"
                            data-nama="{{ $r->nama_regu }}"
                            data-danru="{{ $r->danru->nama_lengkap ?? '' }}"
                            data-danru-id="{{ $r->danru_id ?? '' }}">
                            Danru
                        </button>

                        <button class="btn btn-warning btn-sm btnTambahAnggota"
                            data-id="{{ $r->id }}"
                            data-nama="{{ $r->nama_regu }}"
                            data-anggota='@json($r->anggota->pluck("pegawai_id"))'>
                            + Anggota
                        </button>

                        <form action="{{ route('regu.destroy', $r->id) }}" method="POST"
                              onsubmit="confirmDelete(this, 'Regu dan semua anggota akan ikut terhapus!')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm">
                                Hapus Regu
                            </button>
                        </form>

                    </div>

                    {{-- TABLE --}}
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle mb-0">

                            <thead class="table-light text-center">
                                <tr>
                                    <th style="width:50px">#</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Jabatan</th>
                                    <th>Kantor</th>
                                    <th style="width:80px">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="anggota-list" data-regu-id="{{ $r->id }}">

                                @foreach($r->anggota as $i => $a)

                                @php
                                    $isDanru = ($r->danru_id == $a->pegawai_id);
                                @endphp

                                <!-- <tr class="{{ $isDanru ? 'table-danger' : '' }}" data-id="{{ $a->pegawai_id }}"> -->
                                <tr class="{{ $isDanru ? 'table-danger danru-lock' : '' }}"
                                    data-id="{{ $a->pegawai_id }}"
                                    data-danru="{{ $isDanru ? '1' : '0' }}">

                                    <td class="text-center">{{ $i+1 }}</td>

                                    <td>
                                        <span class="drag-handle {{ $isDanru ? 'text-muted is-danru' : '' }}"
                                              style="cursor: {{ $isDanru ? 'not-allowed' : 'grab' }};"
                                              data-danru="{{ $isDanru ? '1' : '0' }}">
                                            ☰
                                        </span>
                                        {{ $a->pegawai->nama_lengkap ?? '-' }}
                                    </td>

                                    <td class="text-center">{{ $a->pegawai->nip ?? '-' }}</td>

                                    <td class="text-center">
                                        <span class="badge {{ $isDanru ? 'bg-danger' : 'bg-info' }}">
                                            {{ $a->pegawai->jabat->jabatan ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        {{ $a->pegawai->kantor->nama_kantor ?? '-' }}
                                    </td>

                                    <td class="text-center">
                                        <form action="{{ route('regu.anggota.delete', $a->id) }}" method="POST"
                                              onsubmit="confirmDelete(this, 'Anggota akan dihapus dari regu ini!')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                ✕
                                            </button>
                                        </form>
                                    </td>

                                </tr>

                                @endforeach

                            </tbody>

                        </table>
                    </div>

                </div>

            </div>
        </div>
        @empty

        <div class="alert alert-warning text-center">
            Belum ada data regu
        </div>

        @endforelse

    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $regu->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- ================= MODAL TAMBAH ================= -->
<div class="modal fade" id="modalRegu">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" action="{{ route('regu.store') }}">
                @csrf

                <div class="modal-header">
                    <h5>Tambah Regu</h5>
                </div>

                <div class="modal-body">
                    <input type="text" name="nama_regu" class="form-control" placeholder="Nama Regu" required>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ================= MODAL Tambah Anggota ================= -->
<div class="modal fade" id="modalAnggota">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="POST" id="formTambahAnggota">
                @csrf

                <div class="modal-header">
                    <h5 id="judulModal">Tambah Anggota</h5>
                </div>

                <div class="modal-body">

                    <!-- FILTER -->
                    <select id="filterKantor" class="form-control mb-3">
                        <option value="">-- Semua Kantor --</option>
                        @foreach($listKantor as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kantor }}</option>
                        @endforeach
                    </select>

                    <!-- ANGGOTA -->
                    <select name="pegawai_id[]" id="pegawaiSelect" class="form-control select2" multiple>
                        @foreach($pegawai as $p)
                            <option value="{{ $p->id }}" data-kantor="{{ $p->kantor->id ?? '' }}">
                                {{ $p->nama_lengkap }} - {{ $p->nip }}
                            </option>
                        @endforeach
                    </select>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ================= MODAL Set Supervisor ================= -->
<div class="modal fade" id="modalSupervisor">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" id="formSupervisor">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 id="judulSupervisor">Assign Supervisor</h5>
                </div>

                <div class="modal-body">

                    <label>Pilih Supervisor</label>
                    <select name="supervisor_id" id="selectSupervisor" class="form-control select2" required>
                        <option value="">-- Pilih Supervisor --</option>
                        @foreach($pegawai as $p)
                            @if(Str::contains(strtolower($p->jabat->jabatan ?? ''), ['supervisor', 'spv', 'koordinator']))
                                <option value="{{ $p->id }}">
                                    {{ $p->nama_lengkap }} - {{ $p->kantor->nama_kantor ?? '-' }}
                                </option>
                            @endif
                        @endforeach
                    </select>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ================= MODAL set DANRU ================= -->
<div class="modal fade" id="modalDanru">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" id="formDanru">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 id="judulDanru">Assign Danru</h5>
                </div>

                <div class="modal-body">

                    @php
                        $danruTerpakai = \App\Models\ReguModel::pluck('danru_id')->toArray();
                    @endphp

                    <!-- DANRU SAAT INI -->
                    <label>Danru Saat Ini</label>
                    <div id="danruSekarang" class="mb-3">
                        <span class="text-muted">Belum ada</span>
                    </div>

                    <!-- PILIH DANRU -->
                    <label>Pilih Danru</label>
                    <select name="danru_id" id="selectDanru" class="form-control select2" required>
                        <option value="">-- Pilih Danru --</option>

                        @foreach($pegawai as $p)
                            @if(Str::contains(strtolower($p->jabat->jabatan ?? ''), ['danru', 'komandan']))

                                <option value="{{ $p->id }}"
                                    data-used="{{ in_array($p->id, $danruTerpakai) ? '1' : '0' }}">

                                    {{ $p->nama_lengkap }} - {{ $p->kantor->nama_kantor ?? '-' }}

                                </option>

                            @endif
                        @endforeach

                    </select>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection

@push('script')

<!-- SELECT2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
// ================= SWEET ALERT SESSION =================
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: "{{ session('success') }}",
    timer: 1500,
    showConfirmButton: false
});
@endif

@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Gagal',
    text: "{{ session('error') }}"
});
@endif
</script>

<script>
// ================= BLOCK DRAG DANRU =================
document.addEventListener('pointerdown', function (e) {

    const handle = e.target.closest('.drag-handle');
    if (!handle) return;

    if (handle.classList.contains('is-danru') || handle.dataset.danru === '1') {

        e.preventDefault();
        e.stopPropagation();

        Swal.fire({
            icon: 'warning',
            title: 'Tidak bisa dipindahkan',
            text: 'Hapus Danru untuk dapat dipindahkan ke regu lain',
            confirmButtonColor: '#d33'
        });

        return false;
    }

}, true);
</script>

<script>
// ================= HIGHLIGHT SEARCH =================
function highlightText(keyword) {

    if (!keyword) return;

    keyword = keyword.toLowerCase();

    document.querySelectorAll('.accordion-item').forEach(item => {

        let elements = item.querySelectorAll('b, td, span, small');

        let found = false;

        elements.forEach(el => {

            let text = el.textContent;
            let lower = text.toLowerCase();

            if (lower.includes(keyword)) {

                let regex = new RegExp(`(${keyword})`, 'gi');

                el.innerHTML = text.replace(regex, `<span class="highlight">$1</span>`);

                found = true;
            }
        });

        // 🔥 AUTO OPEN ACCORDION JIKA ADA MATCH
        if (found) {
            let collapse = item.querySelector('.accordion-collapse');
            if (collapse) {
                bootstrap.Collapse.getOrCreateInstance(collapse).show();
            }
        }

    });

    // 🔥 AUTO SCROLL KE HASIL PERTAMA
    setTimeout(() => {
        let first = document.querySelector('.highlight');
        if (first) {
            first.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    }, 300);
}

// RUN SAAT LOAD
document.addEventListener('DOMContentLoaded', function () {

    let keyword = "{{ request('search') }}";

    if (keyword) {
        highlightText(keyword);
    }

});
</script>

<script>
// ================= SORTABLE =================
function openAllAccordion() {
    document.querySelectorAll('.accordion-collapse').forEach(el => {
        bootstrap.Collapse.getOrCreateInstance(el, { toggle: false }).show();
    });
}

document.querySelectorAll('.anggota-list').forEach(function (el) {

    new Sortable(el, {
        group: { name: 'anggota', pull: true, put: true },
        animation: 150,
        handle: '.drag-handle',
        draggable: 'tr',
        sort: false,

        fallbackOnBody: true,
        swapThreshold: 0.65,

        // AUTO SCROLL
        scroll: true,
        scrollSensitivity: 80,
        scrollSpeed: 15,
        bubbleScroll: true,

        onStart: function () {
            document.body.classList.add('dragging-active');
            openAllAccordion();
        },

        onEnd: function () {
            document.querySelectorAll('.accordion-collapse.show').forEach(el => {
                bootstrap.Collapse.getOrCreateInstance(el).hide();
            });
        },

        onAdd: function (evt) {

            let pegawaiId = evt.item.dataset.id;
            let newReguId = evt.to.dataset.reguId;

            if (!newReguId) return;

            Swal.fire({
                title: 'Memindahkan...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('/regu/move-anggota', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    pegawai_id: pegawaiId,
                    regu_id: newReguId
                })
            })
            .then(res => res.json())
            .then(res => {

                Swal.close();

                if (!res.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: res.message || 'Gagal memindahkan anggota'
                    });
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.message,
                    timer: 1200,
                    showConfirmButton: false
                });

                location.reload();
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan server'
                });
                location.reload();
            });
        }
    });

});
</script>

<script>
// ================= SELECT2 DAN MODAL =================
let pegawaiGlobalUsed = @json($pegawaiSudahMasukRegu);
let anggotaExisting = [];

$(document).ready(function () {

    $('#selectDanru').select2({
        dropdownParent: $('#modalDanru'),
        width: '100%'
    });

    $('#selectSupervisor').select2({
        dropdownParent: $('#modalSupervisor'),
        width: '100%'
    });

    $('#pegawaiSelect').select2({
        dropdownParent: $('#modalAnggota'),
        placeholder: "Pilih anggota...",
        width: '100%'
    });

});
</script>

<script>
// ================= CONFIRM DELETE =================
function confirmDelete(form, text = 'Data akan dihapus permanen!') {
    event.preventDefault();

    Swal.fire({
        title: 'Yakin hapus?',
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>

@endpush