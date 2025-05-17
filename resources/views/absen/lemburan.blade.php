@extends('layouts.absen.absen')

@section('header')
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" class="ion-icon"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Lemburan Pegawai</div>
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
<style type="text/css">
    .bg-apprv {
        background-color: #e6f4ea;
    }
    .bg-decline {
        background-color: #ffc7c7;
    }
    .bg-null {
        background-color: #e0e0e0;
    }
</style>

<div class="row" style="margin-top: 4rem;">
    <div class="col">
        @forelse ($lembur as $d)
@php
    $durasiFormatted = null;

    if ($d->jam_in && $d->jam_out) {
        $jamMasuk = \Carbon\Carbon::parse($d->tgl_absen . ' ' . $d->jam_in);
        $updated_at = \Carbon\Carbon::parse($d->updated_at)->format('Y-m-d');
        $jamKeluar = \Carbon\Carbon::parse($updated_at . ' ' . $d->jam_out);

        if ($jamKeluar->lt($jamMasuk)) {
            $jamKeluar->addDay();
        }

        $totalSeconds = $jamMasuk->diffInSeconds($jamKeluar);
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;
        
        $parts = [];
        if ($hours > 0) $parts[] = "{$hours} jam";
        if ($minutes > 0) $parts[] = "{$minutes} menit";
        if ($seconds > 0 || empty($parts)) $parts[] = "{$seconds} detik";

        $durasiFormatted = implode(' ', $parts);
    }
@endphp
        <ul class="listview image-listview">
            <li>
                <div class="item {{$d->aprv_by_adm === null ? 'bg-null' : ($d->aprv_by_adm === 0 ? 'bg-decline' : 'bg-apprv')}}">
                    <div class="in">
                        <div style="width: 250px;">
                            <b>{{$d->pegawai->nama_lengkap}}</b><br>
                            Total Waktu :<small class="text-muted"> {{ $durasiFormatted }}</small> <br>
                            Area Kerja :<small class="text-muted"> {{ $d->area_kerja }}</small> <br>
                            Alasan Lembur :<small class="text-muted"> {{ $d->uraian }}</small>
                        </div>
                        @if($d->aprv_by_spv === Auth::guard('pegawai')->user()->id)
                        <span class="badge bg-success {{$d->aprv_by_adm == null ? 'approve-popup' : ''}}" data-id="{{ $d->id }}"  style="padding-left: 10px; padding-right: 10px;">Disetujui</span>
                        @elseif($d->aprv_by_spv === 0)
                           <span class="badge bg-danger {{$d->aprv_by_adm == null ? 'approve-popup' : ''}}" data-id="{{ $d->id }}"  style="padding-left: 10px; padding-right: 10px;">Ditolak</span>
                        @else
                            <span class="badge bg-warning text-dark approve-popup" data-id="{{ $d->id }}"  style="padding-left: 10px; padding-right: 10px;">Validasi</span>
                        @endif
                    </div>
                </div>
            </li>
        </ul>
        @empty
            <div class="alert text-center">
                Belum ada data lembur
            </div>
        @endforelse

    </div>
</div>
@endsection

@push('myscript')
    <script type="text/javascript">
        $('.approve-popup').on('click', function(e) {
        e.preventDefault();
        const id = $(this).data('id');

        Swal.fire({
            title: 'Validasi Lemburan',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: 'Ajukan',
            confirmButtonColor: 'green',
            denyButtonText: 'Tolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            let status = null;
            if (result.isConfirmed) {
                status = {{Auth::guard('pegawai')->user()->id}};
            } else if (result.isDenied) {
                status = 0;
            }

            if (status !== null) {
                $.ajax({
                    url: `/absen/lemburan/${id}/status`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        aprv_by_spv : status
                    },
                    success: function(response) {
                        Swal.fire('Berhasil!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat memproses.', 'error');
                    }
                });
            }
        });
    });
    </script>
@endpush 