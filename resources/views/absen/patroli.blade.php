@extends('layouts.absen.absen')

@section('header')
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" class="ion-icon"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Hasil Patroli</div>
    <div class="right"></div>
</div>
@endsection

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
@if ($checkpoints)
    // Kirim semua checkpoint ke localStorage
    const checkpoints = @json($checkpoints);
    localStorage.setItem('checkpoints', JSON.stringify(checkpoints));
@endif
</script>
@if (Session::get('success'))
<script type="text/javascript">
    Swal.fire({
  icon: "success",
  title: "{{Session::get('success')}}",
  showConfirmButton: true,
});
</script>
@endif
@if ($belumAbsen)
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'warning',
        title: 'Belum Absen!',
        text: 'Anda belum melakukan absensi hari ini.',
        allowOutsideClick : false,
        confirmButtonText: 'Absen Sekarang'
    }).then((result) => {
        if (result.isConfirmed) {
            // Ganti '/absen' dengan route tujuanmu
            window.location.href = "{{ route('absen.create') }}";
        }
    });
</script>
@endif
@if ($absen && $absen->jam_out)
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'warning',
        title: 'Perhatian!',
        text: 'Anda sudah selesai bertugas hari ini!',
        allowOutsideClick : false,
        confirmButtonText: 'OK'
    });
</script>
@endif

@if ($absen && $absen->jam_out == null)
    <div class="fab-button bottom-right" style="margin-bottom: 70px;">
        <a href="{{route('absen.patrolicheck')}}" class="fab"><ion-icon name="add-outline"></ion-icon></a>
    </div>
@endif

<div class="row" style="margin-top: 4rem;">

<div id="status" style="margin-top:10px; font-weight:bold;"></div>

    <div class="col">
        {{-- buat nampilin data patroli disini --}}
@foreach ($show as $d)
    <ul class="listview image-listview px-1 bg-light">
        <li>
            <div class="item d-flex rounded shadow-sm p-2">
                <div class="me-3">
                    <img src="{{ asset('storage/foto_patrol/' . $d->foto) }}" width="100" class="rounded shadow">
                </div>

                <div>
                    <b>{{ $d->karyawan->nama_lengkap }}</b><br>
                    {{ $d->shift }}<br>
                    {{ \Carbon\Carbon::parse($d->waktu_scan)->isoFormat('dddd, D MMMM Y') }}<br>
                    {{ \Carbon\Carbon::parse($d->waktu_scan)->format('H:i:s') }} WIB<br>
                    <b>{{ $d->checkpoint->nama }}</b><br>
                    {{ $d->checkpoint->deskripsi }}<br>
                    {{ $d->keterangan }}
                </div>
            </div>
        </li>
    </ul>
@endforeach

    </div>
</div>

@endsection
@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    $(document).ready(function() {
        console.log('Document ready, starting offline sync...');

        let offlineLogs = JSON.parse(localStorage.getItem('offlineLogs') || '[]');
        console.log('Offline logs to send:', offlineLogs);

        if (offlineLogs.length === 0) {
            $('#status').text('Tidak ada data offline untuk disinkronkan.');
            return;
        }

        Swal.fire({
            title: 'Mengirim data offline',
            text: 'Harap tunggu, sedang mengupload data...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        let total = offlineLogs.length;
        let sentCount = 0;

        function sendNext() {
            if (offlineLogs.length === 0) {
                Swal.close();
                $('#status').text('Semua data offline berhasil dikirim!');
                localStorage.removeItem('offlineLogs');
                // Reload halaman setelah sukses semua
                location.reload();
                return;
            }

            let log = offlineLogs.shift();
            console.log('Mengirim log:', log);

            $.post("{{ route('scan.qrcode') }}", log)
                .done(() => {
                    console.log('Sukses kirim data');
                    sentCount++;
                    $('#status').text(`Berhasil mengirim data ke-${sentCount} dari ${total}`);
                    sendNext();
                })
                .fail(() => {
                    console.log('Gagal kirim data');
                    Swal.close();
                    $('#status').text('Gagal mengirim data offline. Silakan coba lagi nanti.');
                    offlineLogs.unshift(log);
                    localStorage.setItem('offlineLogs', JSON.stringify(offlineLogs));
                });
        }

        sendNext();
    });
</script>
@endpush