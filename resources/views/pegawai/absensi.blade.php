@extends('layouts.side.side')
@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<div class="container mw-100">
    <!-- CSS Placeholder Palsu -->
<style>
    .form-group {
        position: relative;
        margin-top: 1.5rem;
    }

    .form-control {
        padding-top: 0.5rem;
    }

    .fake-placeholder {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        color: #999;
        font-size: 14px;
        pointer-events: none;
        transition: 0.2s ease;
        background: white;
        padding: 0 4px;
    }

    .form-control:focus + .fake-placeholder,
    .form-control:not(:placeholder-shown) + .fake-placeholder {
        top: 0;
        font-size: 12px;
        color: #495057;
    }
</style>
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between fw-bold">{{ __('Monitoring Absensi Pegawai') }}
                </div>
                <div class="card-body">
                <!-- Bootstrap form-group dengan label -->
                    <div class="form-group position-relative">
                        <input type="" 
                               onfocus="(this.type='date')"
                               id="bultah" 
                               min="2024-01" 
                               max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" 
                               class="form-control" 
                               value=""
                               placeholder=" ">

                        <span class="fake-placeholder">Pilih Tanggal</span>
                    </div>
                    <div style="overflow: auto;">
                    <table class="table table-striped table-bordered table-hover" id="dataTable">
                        <thead class="text-center table-dark px-1">
                            <tr>
                                <th>No</th>
                                <th>Tgl Absen</th>
                                <th>Nip</th>
                                <th>Nama Pegawai</th>
                                <th>Shift</th>
            @if(Auth::user()->role == 0 || Auth::user()->role == 1)<th>Kantor</th> @endif
                                <th>Departemen</th>
                                <th>Satuan Kerja</th>
                                <th>Jam Masuk</th>
                                <th>Foto</th>
                                <th>Jam Pulang</th>
                                <th>Foto</th>
                                <th>Keterangan</th>
                                <th>Lokasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absen as $key => $abs)
                            <tr>
                                <td class="text-center">{{$absen->firstitem() + $key}}</td>
                                <td class="text-center" style="white-space: nowrap;">{{ Carbon\Carbon::parse($abs->tgl_absen)->isoFormat('DD-MM-YYYY')}}</td>
                                <td class="text-center">{{$abs->pegawai->nip}}</td>
                                <td>{{$abs->pegawai->nama_lengkap}}</td>
                                <td class="text-center">{{ $abs->shifts->shift}}</td>
@if(Auth::user()->role == 0 || Auth::user()->role == 1)<td>{{$abs->pegawai->kantor->nama_kantor}}</td> @endif
                                <td>{{$abs->pegawai->deptmn->nama_dept}}</td>
                                <td>{{$abs->pegawai->sat->satuan_kerja}}</td>
                                <td class="text-center {{$abs->jam_in > $abs->shifts->jam_masuk ? 'text-danger' : ''}}">{{$abs->jam_in}}</td>
                                <td class="text-center"> 
                                    <img src="{{ asset('storage/absensi/'.$abs->pegawai->nip.'/'.$abs->foto_in) }}" width="40px">
                                </td>
                                <td class="text-center">{{$abs->jam_out == null ? 'Belum Absen Pulang' : $abs->jam_out}}</td>
                                <td class="text-center">
                                    @if($abs->foto_out == null)
                                    <i class="bi bi-hourglass-split"></i>
                                    @else
                                    <img src="{{ asset('storage/absensi/'.$abs->pegawai->nip.'/'.$abs->foto_out) }}" width="40px">
                                    @endif
                                </td>
                                <td class="text-center {{$abs->jam_in > $abs->shifts->jam_masuk ? 'text-danger' : ''}}">
                                    @php
                                        $jamStandar = Carbon\Carbon::parse($abs->shifts->jam_masuk);

                                    $jamAktual = Carbon\Carbon::parse($abs->jam_in); // misalnya: '08:23'

                                    if ($jamAktual->gt($jamStandar)) {
                                        $selisih = $jamAktual->diff($jamStandar);
    echo "Terlambat " . ($selisih->h == 0 ? '' : $selisih->h . ' jam ') . ($selisih->i == 0 ? '' : $selisih->i . ' menit ') . $selisih->s . ' detik';
                                    } else {
                                        echo "Tepat waktu";
                                    }
                                    @endphp
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary" 
                                    data-id="{{$abs->id}}" 
                                    data-lokasi="{{$abs->lokasi_in}}" 
                                    data-nama="{{$abs->pegawai->nama_lengkap}}" 
                                    data-kantor="{{$abs->pegawai->kantor->lokasi}}" 
                                    data-radius="{{$abs->pegawai->kantor->radius}}" 
                                    id="btnMap"><i class="bi bi-map"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    <div>
                        {{$absen->links('pagination::bootstrap-5')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    $(document).ready(function() {
        function loadabs() {
            const selectedDate = $('#bultah').val(); // Format: YYYY-MM

            $.ajax({
                url: '/get-abs', // Sesuaikan dengan route kamu
                method: 'GET',
                data: { bultah: selectedDate },
                success: function(response) {
                    const tbody = $('#dataTable tbody');
                    tbody.empty(); // Kosongkan tabel

                    if (response.length === 0) {
                        tbody.append('<tr><td colspan="14" class="text-center">Data tidak ditemukan</td></tr>');
                        return;
                    }

                    tbody.html(response);
                    
                },
                error: function(xhr) {
                    console.error(xhr);
                    alert('Terjadi kesalahan saat mengambil data.');
                }
            });
        }
        $("#bultah").change(function() {
            loadabs();
        });
// loadabs();
    });
</script>
<script>
$(document).ready(function () {
    // Event delegation agar tombol btnMap tetap bisa berfungsi setelah AJAX
    $('#dataTable').on('click', '[id^="btnMap"]', function () {
        let id = $(this).data('id');
        let lokasi = $(this).data('lokasi'); // "lat,lng"
        let nama = $(this).data('nama');
        let kantor = $(this).data('kantor'); // "lat,lng"
        let radius = parseFloat($(this).data('radius'));

        let [lat, lng] = lokasi.split(',').map(Number);
        let [latKantor, lngKantor] = kantor.split(',').map(Number);

        Swal.fire({
            title: 'Lokasi Absen: ' + nama,
            html: `<div id="map${id}" style="height: 400px;"></div>`,
            width: 700,
            didOpen: () => {
                let map = L.map(`map${id}`).setView([lat, lng], 17);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                L.marker([lat, lng]).addTo(map).bindPopup(nama).openPopup();

                L.circle([latKantor, lngKantor], {
                    color: 'blue',
                    fillColor: '#0000FF',
                    fillOpacity: 0.2,
                    radius: radius
                }).addTo(map);
            }
        });
    });
});
</script>
@endpush

{{-- {
    title: 'Lokasi Absen',
    icon: L.icon({
        iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
        iconSize: [30, 30]
    })
}) --}}