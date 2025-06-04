@extends('layouts.absen.absen')

@section('header')
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="{{ route('absen') }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" class="ion-icon"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Scan QRCode</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (Session::get('success'))
<script>
    Swal.fire({
        icon: "success",
        title: "{{ Session::get('success') }}",
        showConfirmButton: true,
    });
</script>
@endif

<!-- QR Code Reader -->
<div style=" margin-top: 5rem;">
    <div id="reader" style="width: auto; margin: auto;"></div>
</div>
<!-- Form setelah scan QR -->
<center>
<div id="form-container" style="display:none; margin-top: 3rem; margin-bottom: 4rem;">
    <h3 id="checkpoint-nama"></h3>
    <form id="log-form">
        @csrf
        <input type="hidden" name="kode_unik" id="kode_unik">
        <textarea name="keterangan" class="form-control" placeholder="Masukkan keterangan..." required></textarea>
        <br>

        <!-- Kamera -->
        <video id="video" autoplay playsinline style="width: 100%; max-width: 300px;"></video>
        <canvas id="canvas" style="display:none;"></canvas>
        <input type="hidden" name="foto" id="foto">

        <!-- Preview -->
        <div id="preview-container" style="display:none;">
            <h5>Preview Foto:</h5>
            <img id="foto-preview" src="" style="width: 100%; max-width: 300px; border:1px solid #ccc;" />
            <br>
            <button type="button" id="ulang-foto">Ulangi Foto</button>
        </div>

        <br>
        <button type="button" id="ambil-foto">Ambil Foto</button>
        <button type="submit">Kirim Log</button>
    </form>
</div>
</center>
@endsection

@push('myscript')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    // QR Scan
    function onScanSuccess(decodedText) {
        $('#kode_unik').val(decodedText);
        fetchCheckpoint(decodedText);
        html5QrCode.stop();
    }

    const html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: 250 },
        onScanSuccess
    );

    // Ambil data checkpoint dari server
    function fetchCheckpoint(kode_unik) {
        $.post("{{ route('checkpoint.info') }}", {
            _token: "{{ csrf_token() }}",
            kode_unik: kode_unik
        }, function(res) {
            $('#checkpoint-nama').text(res.checkpoint.nama);
            $('#form-container').show();
            startCamera();
        }).fail(function() {
            Swal.fire('Error', 'Checkpoint tidak ditemukan', 'error');
        });
    }

    // Kamera
    let videoStream;

    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            videoStream = stream;
            const video = document.getElementById('video');
            video.srcObject = stream;
            video.play();
        }).catch(err => {
            Swal.fire('Gagal Akses Kamera', err.message, 'error');
        });
    }

    // Ambil Foto
    $('#ambil-foto').click(function () {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');

        if (!video.videoWidth || !video.videoHeight) {
            Swal.fire('Error', 'Kamera belum siap. Coba lagi.', 'error');
            return;
        }

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataUrl = canvas.toDataURL('image/jpeg');
        $('#foto').val(dataUrl);
        $('#foto-preview').attr('src', dataUrl);
        $('#preview-container').show();
        $('#video').hide();
        $('#ambil-foto').hide();
    });

    // Ambil Ulang
    $('#ulang-foto').click(function () {
        $('#preview-container').hide();
        $('#video').show();
        $('#ambil-foto').show();
        $('#foto').val('');
    });

    // Kirim Data
    $('#log-form').submit(function (e) {
        e.preventDefault();

        if (!$('#foto').val()) {
            Swal.fire('Error', 'Silakan ambil foto terlebih dahulu.', 'warning');
            return;
        }

        const formData = $(this).serialize();
        $.post("{{ route('scan.qrcode') }}", formData, function (res) {
            Swal.fire('Berhasil', res.message, 'success').then(() => location.reload());
        }).fail(function (xhr) {
            const msg = xhr.responseJSON?.message || 'Terjadi kesalahan';
            Swal.fire('Gagal', msg, 'error');
        });
    });
</script>
@endpush
