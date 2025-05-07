 <style type="text/css">
    /* Default Style Tombol Kamera */
    .appBottomMenu .action-button.large {
        background-color: #007bff; /* Biru Default */
        border-radius: 50%;
        padding: 15px;
        transition: background-color 0.3s ease;
    }

    /* Warna Abu-Abu Saat Disabled */
    .appBottomMenu .disabled-link .action-button.large {
        background-color: #b0b0b0; /* Warna Abu-Abu */
        pointer-events: none;       /* Nonaktifkan Interaksi */
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Tambahan untuk Tekan Tombol */
    .appBottomMenu .item {
        text-decoration: none;
        color: inherit;
    }
 </style>

 <!-- App Bottom Menu -->
    <div class="appBottomMenu">
        <a href="{{route('absen')}}" class="item {{Request::is('absen') ? 'active' : ''}}">
            <div class="col">
                <ion-icon name="home-outline" role="img" class="md hydrated"
                    aria-label="file tray full outline"></ion-icon>
                <strong>Home</strong>
            </div>
        </a>
        <a href="{{route('absen.histori')}}" class="item {{Request::is('absen/histori') ? 'active' : ''}}">
            <div class="col">
                <ion-icon name="calendar-outline" role="img" class="md hydrated"
                    aria-label="calendar text outline"></ion-icon>
                <strong>History</strong>
            </div>
        </a>
        @if(Request::is('absen/create'))
                @if($cek == 1)
                    @if($cek2->jam_out == null)
                    <a class="item">
                        <div class="col">
                            <button class="action-button large bg-danger" id="capture" data-absen="belum" data-stat="pulang">
                                <ion-icon name="camera-outline"></ion-icon>
                            </button>
                        </div>
                    </a>
                    @else
                        <a class="item disabled-link" onclick="showAbsenAlert()" data-absen="sudah">
                            <div class="col">
                                <button class="action-button large">
                                    <ion-icon name="camera-outline"></ion-icon>
                                </button>
                            </div>
                        </a>
                    @endif
                @elseif($absenTerakhir && $absenTerakhir->jam_out == null)
                    <a class="item">
                        <div class="col">
                            <button class="action-button large bg-danger" id="capture" data-absen="belum" data-stat="pulang">
                                <ion-icon name="camera-outline"></ion-icon>
                            </button>
                        </div>
                    </a>
                @else
                    <a class="item">
                        <div class="col">
                            <button class="action-button large" id="capture" data-absen="belum" data-stat="masuk"> 
                                <ion-icon name="camera-outline"></ion-icon>
                            </button>
                        </div>
                    </a>
                @endif
        @elseif(Request::is('absen/lembur'))
            @if(!$existing)
                <a class="item">
                    <div class="col">
                        <button class="action-button large bg-info" data-stat="lemburmasuk" onclick="mulaiLembur()"> 
                            <ion-icon name="camera-outline"></ion-icon>
                        </button>
                    </div>
                </a>
            @elseif(!$existing->jam_out)
                 <a class="item">
                    <div class="col">
                        <button class="action-button large" style="background-color: orange;" data-stat="lemburselesai" onclick="selesaiLembur()"> 
                            <ion-icon name="camera-outline"></ion-icon>
                        </button>
                    </div>
                </a>
            @else
                <a class="item disabled-link" onclick="showLemburAlert()" data-absen="sudah">
                    <div class="col">
                        <button class="action-button large">
                            <ion-icon name="camera-outline"></ion-icon>
                        </button>
                    </div>
                </a>
            @endif
        @else

            {{-- buat logic untuk menangani shift malam yang mau lembur. --}}

            @if($cek == 1 && $cek2->jam_out != null)
                @if($existing && $existing->jam_out != null)
                    <a class="item disabled-link" onclick="showLemburAlert()"> <!-- ini kalo udah absen masuk dan lembur -->
                        <div class="col">
                            <button class="action-button large">
                                <ion-icon name="camera-outline"></ion-icon>
                            </button>
                        </div>
                    </a>
                @elseif($existing && $existing->jam_out == null)
                    <a class="item" onclick="absenLemburSelesai()">
                        <div class="col">
                            <button class="action-button large" style="background-color: orange;">
                                <ion-icon name="camera-outline"></ion-icon>
                            </button>
                        </div>
                    </a>
                @else
                    <a class="item" onclick="showAbsenAlert()">
                        <div class="col">
                            <div class="action-button large bg-info">
                                <ion-icon name="camera-outline"></ion-icon>
                            </div>
                        </div>
                    </a>
                @endif
            @else
                @if($cek == 1 && $cek2->jam_out == null)
                    <a href="{{route('absen.create')}}" class="item">
                        <div class="col">
                            <div class="action-button large bg-danger">
                                <ion-icon name="camera-outline"></ion-icon>
                            </div>
                        </div>
                    </a>
                @else
                    <a href="{{route('absen.create')}}" class="item">
                        <div class="col">
                            <div class="action-button large">
                                <ion-icon name="camera-outline"></ion-icon>
                            </div>
                        </div>
                    </a>
                @endif
            @endif
        @endif
        <a href="{{route('absen.izin')}}" class="item {{Request::is('absen/izin') ? 'active' : ''}}">
            <div class="col">
                <ion-icon name="document-text-outline" role="img" class="md hydrated"
                    aria-label="document text outline"></ion-icon>
                <strong>Izin</strong>
            </div>
        </a>
        <a href="/absen/profile" class="item {{Request::is('absen/profile') ? 'active' : ''}}">
            <div class="col">
                <ion-icon name="people-outline" role="img" class="md hydrated" aria-label="people outline"></ion-icon>
                <strong>Profile</strong>
            </div>
        </a>
    </div>

    @push('myscript')
<script type="text/javascript">
    function showAbsenAlert() {
        Swal.fire({
            icon: 'info',
            title: 'Oops!',
            text: 'Anda sudah absen hari ini! Lanjut absen lembur?',
            showCancelButton: true,
                    confirmButtonText: 'Ya, lanjut',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('absen.lembur') }}'; // lanjut absen walau di luar radius
                    }
                });
    }
</script>
<script type="text/javascript">
    function absenLemburSelesai() {
        Swal.fire({
            icon: 'info',
            title: 'Absen Lembur',
            text: 'Ingin selesaikan absen lembur?',
            showCancelButton: true,
                    confirmButtonText: 'Ya, lanjut',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('absen.lembur') }}'; // lanjut absen walau di luar radius
                    }
                });
    }
</script>
<script type="text/javascript">
        function showLemburAlert() {
        Swal.fire({
            icon: 'info',
            title: 'Oops!',
            text: 'Anda sudah absen dan lembur hari ini!',
            confirmButtonText: 'OK'
                });
    }
</script>
@endpush
    <!-- * App Bottom Menu -->

    {{-- buat pop up di history menampilkan tombol histry absensi dan lembur --}}
    {{-- dubawah kehadiran terakhir jika yang lembur maka akan muncul data seperti absensi --}}
    {{-- buat monitoring lembur di halaman view admin --}}
    {{-- buat rekap laporan lembur bulanan per user dan all user--}}