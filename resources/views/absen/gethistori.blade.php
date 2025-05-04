@if($get->isEmpty())
<div class="alert alert-outline-warning text-center">Belum Ada Data Rekap Absensi</div>
@else
@foreach($get as $key => $item )
	<div class="card p-1 mb-2">
	    <h5>{{Carbon\carbon::parse($item->tgl_absen)->locale('id')->translatedFormat('l, d M Y')}} ({{$item->pegawai->shifts->shift}})</h5>
        <div class="d-flex justify-content-around align-items-center">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('storage/absensi/'.$item->pegawai->nip.'/'.$item->foto_in) }}" alt="Foto Masuk" class="rounded" width="50">
                <div class="text-center pl-1 {{$item->jam_in > $item->shifts->jam_masuk ? 'text-danger' : ''}}">
                    <span class="d-block">Masuk</span>
                    <strong>{{$item->jam_in}}</strong>
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
	            <img src="{{ asset('storage/absensi/'.$item->pegawai->nip.'/'.$item->foto_out) }}" alt="Foto Masuk" class="rounded" width="50">
	            <div class="text-center pl-1">
	                <span class="d-block">Pulang</span>
	                <strong>{{$item->jam_out}}</strong>
	            </div>
	        </div>
	        @endif
	    </div>
	</div>
@endforeach
@endif