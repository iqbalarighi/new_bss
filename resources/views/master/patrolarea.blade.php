@extends('layouts.side.side')

@section('content')

<div class="container mw-100">
	<style>
        .modal.fade .modal-dialog {
            transform: scale(0.8);
            transition: transform 0.3s ease-out;
        }
        .modal.show .modal-dialog {
            transform: scale(1);
        }
         thead {
            text-align: center;
            vertical-align: middle !important;
        }
    </style>
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header fw-bold">{{ __('Area Patroli') }}
                    <button type="button" class="btn btn-primary float-end btn-sm" data-bs-toggle="modal" data-bs-target="#modalCheckpoint">
					    Tambah Checkpoint
					</button>
                </div>

                <div class="card-body" style="overflow-x: auto;">

					<div class="modal fade" id="modalCheckpoint" tabindex="-1" aria-labelledby="modalCheckpointLabel" aria-hidden="true">
					  <div class="modal-dialog">
					    <div class="modal-content">
					      <form method="POST" action="{{ route('checkpoints.store') }}">
					        @csrf
					        <div class="modal-header">
					          <h5 class="modal-title" id="modalCheckpointLabel">Tambah Checkpoint</h5>
					          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
					        </div>
					        <div class="modal-body">
					        	@php
								    use App\Models\KantorModel;

								    $user = Auth::user();
								    $kantor = [];

								    if ($user && in_array($user->role, [0, 1])) {
								        $kantor = KantorModel::where('perusahaan', $user->perusahaan)->get();
								    }
								@endphp
					          @if(Auth::user()->role == 0 || Auth::user()->role == 1)
			                    <div class="mb-3">
			                        <label for="kantor" class="form-label">Kantor</label>
			                        <select name="kantor" id="kantor" class="form-select" required>
			                            <option selected disabled value="">Pilih Kantor</option>
			                            @foreach($kantor as $office)
			                            <option value="{{$office->id}}">{{$office->nama_kantor}}</option>
			                            @endforeach
			                        </select>
			                    </div>
			                     @endif
					          <div class="mb-3">
					            <label for="nama" class="form-label">Nama Checkpoint</label>
					            <input type="text" class="form-control" name="nama" id="nama" required>
					          </div>
					          <div class="mb-3">
					            <label for="deskripsi" class="form-label">Deskripsi Tugas</label>
					            <input type="text" class="form-control" name="deskripsi" id="deskripsi" required>
					          </div>
					          <div class="mb-3">
					            <label for="lokasi" class="form-label">Lokasi</label>
					            <input type="text" class="form-control" name="lokasi" id="lokasi" required>
					          </div>
					        </div>
					        <div class="modal-footer">
					          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
					          <button type="submit" class="btn btn-primary">Simpan</button>
					        </div>
					      </form>
					    </div>
					  </div>
					</div>


					<table class="table table-bordered">
						<thead class="table-dark">
							<tr>
							<th>No</th>
							@if(Auth::user()->role == 0)       
		                    <th>Perusahaan</th>
		                    @endif
							@if(Auth::user()->role == 1)
		                    <th>Gedung/Kantor</th>
		                    @endif
							<th>Checkpoint</th>
							<th>Lokasi</th>
							<th>Deskripsi</th>
							<th>QRCode</th>
						</tr>
						</thead>
						<tbody>
							@foreach($show as $n => $s)
								<tr>
								    <td>{{ $show->firstItem() + $n }}</td>
								    @if(Auth::user()->role == 0) 
								    <td>{{ $s->perusa->perusahaan }}</td>
								    @endif
								    @if(Auth::user()->role == 1)
								    <td>{{ $s->kant->nama_kantor }}</td>
								    @endif
								    <td>{{ $s->nama }}</td>
								    <td>{{ $s->lokasi }}</td>
								    <td>{{ $s->deskripsi }}</td>
								    <td>
								        <button class="btn btn-primary btn-sm view-qr"
								                data-nama="{{ $s->nama }}"
								                data-kode="{{ $s->kode_unik }}">
								            Lihat QR
								        </button>

								        {{-- Elemen tersembunyi untuk menyimpan QR --}}
								        <div id="qr-{{ $s->kode_unik }}" class="d-none">
								            {!! QrCode::size(200)->generate($s->kode_unik) !!}
								        </div>
								    </td>
								</tr>
								@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.view-qr').on('click', function () {
        const nama = $(this).data('nama');
        const kode = $(this).data('kode');
        const qrHtml = $('#qr-' + kode).html();

        Swal.fire({
            title: 'QR Code: ' + nama,
            html: `
                <div id="swal-qr">${qrHtml}</div>
                <p><strong>Kode:</strong> ${kode}</p>
                <a id="downloadQR" class="btn btn-success mt-2">Download PNG</a>
            `,
            didOpen: () => {
                const svgElement = document.querySelector('#swal-qr svg');
                const xml = new XMLSerializer().serializeToString(svgElement);
                const svgBlob = new Blob([xml], { type: 'image/svg+xml;charset=utf-8' });
                const url = URL.createObjectURL(svgBlob);

                const image = new Image();
                image.onload = function () {
                    const canvas = document.createElement('canvas');
                    canvas.width = image.width;
                    canvas.height = image.height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(image, 0, 0);
                    URL.revokeObjectURL(url);

                    const pngData = canvas.toDataURL('image/png');

                    const link = document.getElementById('downloadQR');
                    link.href = pngData;
                    link.download = `QR-${nama}.png`;
                };
                image.src = url;
            }
        });
    });
});
</script>
@endpush