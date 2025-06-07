@extends('layouts.absen.absen')

@section('header')
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="{{ route('absen.patroli') }}" class="headerButton goBack">
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
<style>
    #video {
        width: 100%;
        height: auto;
        max-width: 310px;
        border: 1px solid #ccc;
    }

    #foto-preview {
        width: 100%;
        max-width: 300px;
        border: 1px solid #ccc;
    }
</style>
<!-- QR Code Reader -->
<div style=" margin-top: 3.5rem;">
    <div id="reader" style="width: auto; margin: auto;"></div>
</div>
<!-- Form setelah scan QR -->
<div class="container my-5">
    <div id="form-container" style="display:none;">
        <div class="text-center mb-4">
            <h3 id="checkpoint-nama">Nama Checkpoint</h3>
        </div>

        <form id="log-form">
            @csrf
            <input type="hidden" name="kode_unik" id="kode_unik">

            <div class="mb-3">
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan..." required></textarea>
            </div>

            <!-- Kamera -->
            <div class="text-center mb-3">
                <video id="video" autoplay playsinline></video>
                <canvas id="canvas" style="display:none;"></canvas>
                <input type="hidden" name="foto" id="foto">
                <button type="button" id="ambil-foto" class="btn btn-primary btn-sm mt-2">Ambil Foto</button>
            </div>

            <!-- Preview Foto -->
            <div id="preview-container" class="text-center mb-3" style="display:none;">
                <img id="foto-preview" src="" />
                <div class="row justify-content-center mt-2">
                    <div class="col-6 col-sm-4 text-end">
                        <button type="button" id="ulang-foto" class="btn btn-sm btn-warning w-100">Ulangi Foto</button>
                    </div>
                    <div class="col-6 col-sm-4 text-start">
                        <button type="submit" class="btn btn-sm btn-success w-100">Kirim</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

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
    navigator.mediaDevices.getUserMedia({
        video: {
            facingMode: "environment", // Kamera belakang
            width: { ideal: 310 },
            height: { ideal: 420 }
        }
    })
    .then(stream => {
        videoStream = stream;
        const video = document.getElementById('video');
        video.srcObject = stream;
        video.play();
    })
    .catch(err => {
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

    // Tampilkan loading
    Swal.fire({
        title: 'Mengirim data...',
        text: 'Harap tunggu sebentar',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const formData = $(this).serialize();
    $.post("{{ route('scan.qrcode') }}", formData, function (res) {
        Swal.fire('Berhasil', res.message, 'success').then(() => {
            window.location.href = "{{ route('absen.patroli') }}";
        });
    }).fail(function (xhr) {
        const msg = xhr.responseJSON?.message || 'Terjadi kesalahan';
        Swal.fire('Gagal', msg, 'error');
    });
});

</script>
@endpush
