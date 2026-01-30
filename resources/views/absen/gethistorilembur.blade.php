@if($get->isEmpty())
<div class="alert alert-outline-warning text-center">Belum Ada Data Rekap Lembur</div>
@else
@foreach($get as $key => $item )
@php
    $durasiFormatted = null;

    if ($item->jam_in && $item->jam_out) {
        $jamMasuk = \Carbon\Carbon::parse($item->jam_in);
        $jamKeluar = \Carbon\Carbon::parse($item->jam_out);

        if ($jamKeluar->lt($jamMasuk)) {
            $jamKeluar->addDay();
        }

        $totalSeconds = $jamMasuk->diffInSeconds($jamKeluar);
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;
        $durasiFormatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
@endphp
<div class="card p-1 mb-2">
<h5>
    {{ \Carbon\Carbon::parse($item->tgl_absen)->locale('id')->translatedFormat('l, d M Y') }}
    @if($durasiFormatted)
        <span class="text-success float-right pe-3">{{ $durasiFormatted }}</span>
    @else
        <span class="text-secondary float-right pe-3">Berlangsung</span>
    @endif
</h5>
    
    <div class="d-flex justify-content-around align-items-center">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('storage/lembur/'.$item->pegawai->nip.'/'.$item->foto_in) }}" alt="Foto Masuk" class="rounded" width="50">
            <div class="text-center pl-1">
                <span class="d-block">Mulai</span>
                <strong>{{ Carbon\Carbon::parse($item->jam_in)->format('H:i:s') }}</strong>
            </div>
        </div>

        @if($item->foto_out == null)
            <div class="d-flex align-items-center gap-2">
                <div class="text-center pl-1">
                    <span class="d-block">Pulang</span>
                    <strong>--:--</strong>
                </div>
            </div>
        @else
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('storage/lembur/'.$item->pegawai->nip.'/'.$item->foto_out) }}" alt="Foto Pulang" class="rounded" width="50">
                <div class="text-center pl-1">
                    <span class="d-block">Selesai</span>
                    <strong>{{ Carbon\Carbon::parse($item->jam_out)->format('H:i:s') }}</strong>
                </div>
            </div>
        @endif
    </div>
</div>

@endforeach
@endif