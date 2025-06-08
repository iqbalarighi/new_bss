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
            display: block;
            width: 100%;
            max-width: 310px;
            aspect-ratio: 3/4;
            border: 1px solid #ccc;
            object-fit: cover;
            margin: auto;
        }

        #foto-preview {
            display: block;
            width: 100%;
            max-width: 300px;
            aspect-ratio: 3 / 4;
            object-fit: cover;
            border: 1px solid #ccc;
            margin: auto;
        }
    </style>

<!-- QR Code Reader -->
<div style=" margin-top: 3.5rem;">
    <div id="reader" style="width: auto; margin: auto;"></div>
</div>
<!-- Form setelah scan QR -->
<div class="container my-5" style=" margin-bottom: 3.5rem;">
    <div id="form-container" style="display:none;">
        <div class="text-center mb-1">
            <h3 id="checkpoint-nama">Nama Checkpoint</h3>
        </div>

        <form id="log-form">
            @csrf
            <input type="hidden" name="kode_unik" id="kode_unik">

            <div class="mb-3">
                <textarea name="keterangan" class="form-control" rows="2" placeholder="Masukkan keterangan..." required></textarea>
            </div>

            <!-- Kamera -->
            <div class="text-center mb-3" style="max-width: 100%; margin: auto;">
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
    let html5QrCode;
    let isScanning = false;

    function startScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                initScanner();
            }).catch(() => {
                initScanner();
            });
        } else {
            initScanner();
        }
    }

    function initScanner() {
        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            onScanSuccess,
            onScanFailure
        ).then(() => {
            isScanning = true;
        }).catch(err => {
            Swal.fire('Gagal Memulai Scanner', err.message || err, 'error');
        });
    }

    function onScanSuccess(decodedText) {
        if (!isScanning) return;

        $('#kode_unik').val(decodedText);
        fetchCheckpoint(decodedText);
        html5QrCode.stop().then(() => {
            html5QrCode.clear();
            isScanning = false;
        });
    }

    function onScanFailure(error) {
        console.warn(`Scan gagal: ${error}`);
    }

    window.addEventListener('load', function () {
        startScanner();

        // Coba kirim data yang tersimpan di localStorage (jika ada)
        const offlineLogs = JSON.parse(localStorage.getItem('offline_patrol_logs') || '[]');
        if (offlineLogs.length > 0 && navigator.onLine) {
            offlineLogs.forEach(log => {
                $.post("{{ route('scan.qrcode') }}", log, function (res) {
                    console.log('Log offline berhasil dikirim');
                });
            });
            localStorage.removeItem('offline_patrol_logs');
        }
    });

function fetchCheckpoint(kode_unik) {
    if (!navigator.onLine) {
        // Offline: ambil dari localStorage
        const checkpoints = JSON.parse(localStorage.getItem('checkpoints') || '[]');
        const checkpoint = checkpoints.find(c => c.kode_unik === kode_unik);
        if (checkpoint) {
            $('#checkpoint-nama').text(checkpoint.nama);
            $('#form-container').show();
            startCamera();
        } else {
            Swal.fire('Error', 'Checkpoint tidak ditemukan (offline)', 'error');
        }
    } else {
        // Online: ambil dari server
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
}

    let videoStream;

    function startCamera() {
        navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: "environment",
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

    $('#ambil-foto').on('click', function () {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        const width = video.videoWidth;
        const height = video.videoHeight;

        canvas.width = width;
        canvas.height = height;

        ctx.drawImage(video, 0, 0, width, height);

        let stream = video.srcObject;
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            video.srcObject = null;
        }

        const imageData = canvas.toDataURL('image/jpeg');
        $('#foto').val(imageData);
        $('#foto-preview').attr('src', imageData);
        $('#video').hide();
        $('#preview-container').show();
        $(this).hide();
    });

    $('#ulang-foto').click(function () {
        $('#preview-container').hide();
        $('#video').show();
        $('#ambil-foto').show();
        $('#foto').val('');

        startCamera();
    });

$('#log-form').submit(function (e) {
    e.preventDefault();
    if (!$('#foto').val()) {
        Swal.fire('Error', 'Silakan ambil foto terlebih dahulu.', 'warning');
        return;
    }

    const formData = $(this).serializeArray();
    let dataObj = {};
    formData.forEach(item => {
        dataObj[item.name] = item.value;
    });

    if (!navigator.onLine) {
        // Simpan data offline
        let offlineLogs = JSON.parse(localStorage.getItem('offlineLogs') || '[]');
        offlineLogs.push(dataObj);
        localStorage.setItem('offlineLogs', JSON.stringify(offlineLogs));

        Swal.fire('Gagal kirim', 'Data disimpan offline sementara.', 'warning').then(() => {
            Swal.fire({
                icon: 'info',
                title: 'Sukses',
                text: 'Data berhasil tersimpan offline dan akan dikirim saat koneksi tersedia.',
                timer: 3000,
                showConfirmButton: false
            });
            // tetap di halaman tanpa reload
        });
        return;
    }

    // Kirim data langsung jika online
    $.post("{{ route('scan.qrcode') }}", dataObj)
    .done(res => {
        Swal.fire('Berhasil', res.message, 'success').then(() => {
            window.location.href = "{{ route('absen.patroli') }}";
        });
    })
    .fail(xhr => {
        Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
    });
});
</script>
@endpush


