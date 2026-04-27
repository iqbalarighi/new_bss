@extends('layouts.absen.absen')

@section('header')
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Izin</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (Session::get('success'))
<script type="text/javascript">
    Swal.fire({
  icon: "success",
  title: "{{Session::get('success')}}",
  showConfirmButton: true,
});
</script>
@endif

<div class="fab-button bottom-right" style="margin-bottom: 70px;">
    <a href="{{route('absen.formizin')}}" class="fab"><ion-icon name="add-outline"></ion-icon></a>
</div>
@php
    $jabatanUser = strtolower(optional(Auth::guard('pegawai')->user()->jabat)->jabatan ?? '');
    $isApprover = str_contains($jabatanUser, 'koordinator') 
        || str_contains($jabatanUser, 'supervisor') 
        || str_contains($jabatanUser, 'komandan')
        || str_contains($jabatanUser, 'admin')
        || str_contains($jabatanUser, 'spv');
@endphp

<div class="row" style="margin-top: 4rem; margin-bottom: 4rem;">
    <form method="GET">
    <div class="input-group mb-3">
        <input 
            type="month" 
            name="bulan" 
            class="form-control"
            value="{{ $bulanInput ?? date('Y-m') }}"
            onchange="this.form.submit()"
        >
    </div>
</form>
    <div class="col">

        @foreach ($izin as $tanggal => $items)

    <div class="">
        <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</strong>

    </div>
            @foreach ($items as $d)

                    @if(!$d || !is_object($d)) @continue @endif
            <ul class="listview image-listview mb-2">
                <li>
                    <div class="item show-detail"
                        data-id="{{ $d->id ?? 0 }}"
                        data-nama="{{ optional($d->pegawai)->nama_lengkap ?? '-' }}"
                        data-tanggal="{{ $d->tanggal ? date('d-m-Y', strtotime($d->tanggal)) : '-' }}"
                        data-jenis="{{ $d->jenis_izin == 's' ? 'Sakit' : 'Izin' }}"
                        data-keterangan="{{ e($d->keterangan ?? '-') }}"
                        data-foto="{{ $d->foto ? asset('storage/bukti_izin/'.optional($d->pegawai)->nip.'/'.$d->foto) : '' }}"
                        data-status="{{ $d->status_approve ?? 0 }}"
                    >
                        <div class="in">
                            <div>
                                <b>{{ optional($d->pegawai)->nama_lengkap ?? '-' }}</b><br>

                                <b>
                                    {{ $d->tanggal ? date('d-m-Y', strtotime($d->tanggal)) : '-' }}
                                    ({{ $d->jenis_izin == 's' ? 'Sakit' : 'Izin' }})
                                </b><br>

                                <small class="text-muted">
                                    {{ $d->keterangan ?? '-' }}
                                </small>
                            </div>

                            <div class="text-right">

                                @if (($d->status_approve ?? 0) == 0)
                                    <span class="badge bg-warning">Waiting</span>
                                @elseif($d->status_approve == 1)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Decline</span>
                                @endif

                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            @endforeach

        @endforeach

    </div>
</div>

<div class="modal fade" id="modalDetailIzin" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detail Izin</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <p><b>Nama:</b> <span id="d_nama"></span></p>
                <p><b>Tanggal:</b> <span id="d_tanggal"></span></p>
                <p><b>Jenis:</b> <span id="d_jenis"></span></p>
                <p><b>Keterangan:</b> <span id="d_keterangan"></span></p>

                <div class="text-center">
                    <img id="d_foto" src="" class="img-fluid rounded mb-3" style="max-height:300px;">
                </div>

                <input type="hidden" id="d_id">
                <input type="hidden" id="d_status">

                {{-- ✅ tombol hanya untuk approver --}}
                @if($isApprover)
                <div id="actionButtons" class="d-flex">
                    <button class="btn btn-danger w-50 mr-1" onclick="aksiIzin(2)">
                        Tolak
                    </button>
                    <button class="btn btn-success w-50 ml-1" onclick="aksiIzin(1)">
                        Setujui
                    </button>
                </div>
                @endif

            </div>

        </div>
    </div>
</div>
@endsection

@push('myscript')

<script>
$(document).on('click', '.show-detail', function () {

    let el = $(this);

    let id          = el.data('id');
    let nama        = el.data('nama');
    let tanggal     = el.data('tanggal');
    let jenis       = el.data('jenis');
    let keterangan  = el.data('keterangan');
    let foto        = el.data('foto');
    let status      = el.data('status');

    showDetailIzin(id, nama, tanggal, jenis, keterangan, foto, status);
});

// ================= SHOW MODAL =================
function showDetailIzin(id, nama, tanggal, jenis, keterangan, foto) {

    $('#d_id').val(id);
    $('#d_nama').text(nama);
    $('#d_tanggal').text(tanggal);
    $('#d_jenis').text(jenis);
    $('#d_keterangan').text(keterangan);

    if (foto) {
        $('#d_foto').attr('src', foto).show();
    } else {
        $('#d_foto').hide();
    }

    $('#modalDetailIzin').modal('show');
}


// ================= APPROVE / TOLAK =================
function aksiIzin(status) {

    let id = $('#d_id').val();

    let text = status == 1 ? "Setujui izin ini?" : "Tolak izin ini?";
    let icon = status == 1 ? "question" : "warning";
    let confirmText = status == 1 ? "Ya, Setujui!" : "Ya, Tolak!";
    let btnColor = status == 1 ? "#28a745" : "#dc3545";

    Swal.fire({
        title: text,
        text: "Aksi ini tidak bisa dibatalkan!",
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: btnColor,
        cancelButtonColor: "#6c757d",
        confirmButtonText: confirmText,
        cancelButtonText: "Batal"
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: '/pegawai/absensi/izin/' + id + '/status',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status_approve: status // ✅ disesuaikan dengan controller
                },
                success: function(res) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    $('#modalDetailIzin').modal('hide');

                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                },
                error: function(xhr) {

                    let msg = 'Terjadi kesalahan';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: msg
                    });
                }
            });

        }

    });
}

</script>

@endpush