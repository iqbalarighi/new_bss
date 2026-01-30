@extends('layouts.side.side')
@section('content')

<div class="container mw-100">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between fw-bold">{{ __('Daftar Lembur Pegawai') }}
                </div>

                <div class="card-body" style="overflow: auto;">
                   <table class="table table-bordered table-striped">
        <thead class="table-dark text-center">
            <tr>
                <th>No</th>
                @if (Auth::user()->role == 0)
                <th>Perusahaan</th>
                @endif
                @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                <th>Kantor</th>
                @endif
                <th>NIP</th>
                <th>Nama Pegawai</th>
                <th>Tanggal</th>
                <th>Total Jam Lembur</th>
                <th>Area Kerja</th>
                <th>Keperluan Lembur</th>
                <th>Diajukan Oleh</th>
                {{-- <th>Foto</th> --}}
                <th>Disetujui oleh</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lembur as $num => $l)
            @php
                $durasiFormatted = null;

                if ($l->jam_in && $l->jam_out) {
                    $jamMasuk = \Carbon\Carbon::parse($l->jam_in);
                    $jamKeluar = \Carbon\Carbon::parse($l->jam_out);

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

            <tr>
                <td class="text-center">{{ $lembur->firstitem() + $num}}</td>
                @if (Auth::user()->role == 0)
                <td>{{ $l->perusa->perusahaan ?? '-' }}</td>
                @endif
                @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                <td>{{ $l->kant->nama_kantor ?? '-' }}</td>
                @endif
                <td>{{ $l->pegawai->nip }}</td>
                <td>{{ $l->pegawai->nama_lengkap?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($l->tgl_absen)->locale('id')->translatedFormat('d M Y') }}</td>
                <td>@if($durasiFormatted){{ $durasiFormatted }}@else Berlangsung @endif</td>
                <td>{{ $l->area_kerja }}</td>
                <td>{{ $l->uraian }}</td>
                <td>
                    @if(is_null($l->aprv_by_spv))
                        <span class="badge bg-warning" style="cursor: not-allowed;">Menunggu</span> 
                    @elseif($l->aprv_by_spv === 0 )
                        <span class="badge bg-danger">Ditolak</span> 
                    @else
                     <span class="text-success">{{ $l->spv->nama_lengkap ?? ''}}</span> 
                    @endif
                </td>
                {{-- <td>
                    @if($l->foto)
                        <a href="#" class="lihat-foto" data-img="{{ asset('storage/bukti_izin/'.$l->pegawai->nip.'/'.$l->foto) }}">
                            <img src="{{ asset('storage/bukti_izin/'.$l->pegawai->nip.'/'.$l->foto) }}" alt="Thumbnail" width="40" height="40" class="rounded">
                        </a>
                    @else
                        -
                    @endif
                </td> --}}

                <td>
                    @if(!is_null($l->aprv_by_adm) && $l->aprv_by_adm !== 0)
                    <span class="badge bg-success {{$l->aprv_by_adm == Auth::user()->id ? 'approve-popup' : ''}}" data-id="{{ $l->id }}"  style="padding-left: 10px; padding-right: 10px; cursor: pointer;">Disetujui</span>
                    @elseif($l->aprv_by_adm === 0)
                       <span class="badge bg-danger approve-popup" data-id="{{ $l->id }}"  style="padding-left: 10px; padding-right: 10px; cursor: pointer;">Ditolak</span>
                    @else
                        @if($l->aprv_by_spv === 0)
                        <span class="badge bg-danger" onclick="decline()" style="padding-left: 10px; padding-right: 10px; cursor: not-allowed;">Ditolak</span>
                        @elseif(is_null($l->aprv_by_spv))
                        <span class="badge bg-warning" onclick="onnull()" style="padding-left: 10px; padding-right: 10px; cursor: not-allowed;">Menunggu</span>
                        @else
                        <span class="badge bg-warning text-dark approve-popup" data-id="{{ $l->id }}"  style="padding-left: 10px; padding-right: 10px; cursor: pointer;">Validasi</span>
                        @endif
                    @endif
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
             <div class="d-flex justify-content-center">
                {{ $lembur->links('pagination::bootstrap-4') }}
            </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
    <script type="text/javascript">
        $('.approve-popup').on('click', function(e) {
        e.preventDefault();
        const id = $(this).data('id');

        Swal.fire({
            title: 'Validasi Lemburan',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: 'Disetujui',
            confirmButtonColor: 'green',
            denyButtonText: 'Ditolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            let status = null;
            if (result.isConfirmed) {
                status = {{Auth::user()->id}};
            } else if (result.isDenied) {
                status = 0;
            }

            if (status !== null) {
                $.ajax({
                    url: `/pegawai/lembur/${id}/adm`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        aprv_by_adm : status
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

    <script type="text/javascript">
        function decline() {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Lemburan Ditolak Supervisor",
            });
        }
    </script>

    <script type="text/javascript">
        function onnull() {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Menunggu Validasi Supervisor",
            });
        }
    </script>
@endpush

{{-- buat route validasi approve nya --}}
{{-- buat pop up kalo staus ditolak spv --}}