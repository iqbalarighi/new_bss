@extends('layouts.side.side')
@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<div class="container mw-100">
    <!-- CSS Placeholder Palsu -->
           <style>
    .form-group {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .form-control {
        height: 45px;
        border-radius: 6px;
    }

    .fake-placeholder {
        position: absolute;
        top: 50%;
        left: 12px;
        transform: translateY(-50%);
        color: #999;
        font-size: 14px;
        pointer-events: none;
        transition: 0.2s ease;
        background: #fff;
        padding: 0 6px;
    }

    .form-control:focus + .fake-placeholder,
    .form-control:not(:placeholder-shown) + .fake-placeholder {
        top: 0;
        font-size: 12px;
        color: #495057;
    }

    .btn-group-filter {
        display: flex;
        gap: 10px;
        height: 45px;
        align-items: center;
    }
</style>

<div class="row justify-content-center">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between fw-bold">
                {{ __('Monitoring Absensi Pegawai') }}
            </div>

<div class="card-body">
    <form method="GET" id="formFilter">

        <div class="row">

            <!-- BULAN -->
            <div class="col-md-3 form-group">
                <input type="month"
                       name="tanggal"
                       value="{{ request('tanggal') }}"
                       class="form-control"
                       placeholder=" ">
                <span class="fake-placeholder">Pilih Bulan</span>
            </div>

            <!-- KANTOR -->
            @if(Auth::user()->role == 0 || Auth::user()->role == 1)
            <div class="col-md-3 form-group">
                <select name="kantor" class="form-control">
                    <option value="">-- Semua Kantor --</option>
                    @foreach($listKantor as $k)
                        <option value="{{ $k->id }}"
                            {{ request('kantor') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kantor }}
                        </option>
                    @endforeach
                </select>
                <span class="fake-placeholder">Pilih Kantor</span>
            </div>
            @endif

            <!-- SEARCH -->
            <div class="col-md-3 form-group">
                <input type="text"
                       name="keyword"
                       value="{{ request('keyword') }}"
                       class="form-control"
                       placeholder=" ">
                <span class="fake-placeholder">Cari Nama / NIP</span>
            </div>

            <!-- BUTTON -->
            <div class="col-md-3 form-group">
                <div class="btn-group-filter">
                    <button type="submit" class="btn btn-primary w-50">
                        Filter
                    </button>

                    <button type="button" class="btn btn-secondary w-50" onclick="resetFilter()">
                        Reset
                    </button>
                </div>
            </div>

        </div>

    </form>
</div>
            
        </div>
    </div>
</div>

<script>
function resetFilter() {
    window.location.href = "{{ url()->current() }}";
}
</script>
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
                                        data-lokasi-in="{{$abs->lokasi_in}}" 
                                        data-lokasi-out="{{$abs->lokasi_out}}" 
                                        data-nama="{{$abs->pegawai->nama_lengkap}}" 
                                        data-kantor="{{$abs->pegawai->kantor->lokasi}}" 
                                        data-radius="{{$abs->pegawai->kantor->radius}}" 
                                        id="btnMap">
                                        <i class="bi bi-map"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{$absen->links('pagination::bootstrap-4')}}
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
    document.addEventListener('DOMContentLoaded', function () {
        const inputTanggal = document.getElementById('bultah');
        inputTanggal?.addEventListener('change', function () {
            document.getElementById('formTanggal')?.submit();
        });
    });

    function resetTanggal() {
        document.getElementById('bultah').value = '';
        document.getElementById('formTanggal').submit(); // reload tanpa filter tanggal
    }
</script>
<script>
$(document).ready(function () {
    // Event delegation agar tombol btnMap tetap bisa berfungsi setelah AJAX
   $('#dataTable').on('click', '[id^="btnMap"]', function () {

    let id = $(this).data('id');
    let lokasiIn = $(this).data('lokasi-in');
    let lokasiOut = $(this).data('lokasi-out');
    let nama = $(this).data('nama');
    let kantor = $(this).data('kantor');
    let radius = parseFloat($(this).data('radius'));

    // ================= VALIDASI MASUK =================
    if (!lokasiIn || !lokasiIn.includes(',')) {
        alert("Lokasi masuk tidak tersedia");
        return;
    }

    let [latIn, lngIn] = lokasiIn.split(',').map(Number);

    // ================= VALIDASI KANTOR =================
    let latKantor = null;
    let lngKantor = null;

    if (kantor && kantor.includes(',')) {
        [latKantor, lngKantor] = kantor.split(',').map(Number);
    }

    // ================= ICON PULANG =================
    let redIcon = L.icon({
        iconUrl: 'https://cdn.jsdelivr.net/gh/pointhi/leaflet-color-markers/img/marker-icon-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    Swal.fire({
        title: 'Lokasi Absen: ' + nama,
        html: `<div id="map${id}" style="height: 400px;"></div>`,
        width: 700,

        didOpen: () => {

            let map = L.map(`map${id}`);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // ================= GROUP BOUNDS =================
            let group = [];

            // ================= MASUK =================
            let markerIn = L.marker([latIn, lngIn])
                .addTo(map)
                .bindPopup("Masuk: " + nama)
                .openPopup();

            group.push([latIn, lngIn]);

            // ================= PULANG =================
            if (lokasiOut && lokasiOut.includes(',')) {

                let [latOut, lngOut] = lokasiOut.split(',').map(Number);

                if (!isNaN(latOut) && !isNaN(lngOut)) {

                    let markerOut = L.marker([latOut, lngOut], { icon: redIcon })
                        .addTo(map)
                        .bindPopup("Pulang: " + nama);

                    group.push([latOut, lngOut]);
                }
            }

            // ================= RADIUS KANTOR =================
            if (!isNaN(radius) && latKantor && lngKantor) {

                L.circle([latKantor, lngKantor], {
                    color: 'blue',
                    fillColor: '#0000FF',
                    fillOpacity: 0.2,
                    radius: radius
                }).addTo(map);

                group.push([latKantor, lngKantor]);
            }

            // ================= AUTO FIT ALL MARKER =================
            if (group.length > 0) {
                let bounds = L.latLngBounds(group);
                map.fitBounds(bounds, { padding: [50, 50] });
            } else {
                map.setView([latIn, lngIn], 17);
            }
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