@extends('layouts.side.side')
@section('content')
<div class="container mw-100">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between fw-bold">{{ __('Detail Laporan Admin') }}
                    <a href="{{route('lapor.admin')}}" class="btn btn-sm btn-danger">Kembali</a>
                </div>
                <div class="card-body d-flex justify-content-center" style="overflow: auto;">
                    <div class="col-md-auto p-auto">
                    <table class="table table-responsive" width="100%">
                    <tr>
                        <td>
                        <b><center>Laporan Kegiatan Admin</center></b>
                        <b><center>{{$detail->kant->nama_kantor ?? ''}}</center></b>
                        <b><center>{{Carbon\Carbon::parse($detail->tanggal)->isoFormat('dddd, D MMMM Y')}}</center></b>
                        <b><center>Pukul {{Carbon\Carbon::parse($detail->updated_at)->isoFormat('HH:mm:ss')}} WIB</center></b>
                    </td>
                    </tr>
                    <tr>
                        <td><b>No. laporan: </b>{{$detail->no_lap}} </td> 
                    </tr>
                    <tr>
                        <td colspan="3"><b>Personil Yang Bertugas : </b></td>
                    </tr>
                    <tr>
                        <td colspan="3"><pre class="mb-0">{{$detail->personil}}</pre></td>
                    </tr>
                    <tr>
                        <td><b>Update Giat : </b></td>
                    </tr>
                    <tr>
                        <td colspan="3"><pre class="mb-0" style="text-align:justify;text-justify:inter-word;">{{$detail->kegiatan}}</pre></td>
                    </tr>
                    <tr>
                        <td><b>Keterangan : </b></td>
                    </tr>
                    <tr>
                        <td colspan="3"><pre class="mb-0">{{$detail->keterangan}}</pre></td>
                    </tr>
                    <tr>
                        <td align="center" colspan="3"><b>Dokumentasi : </b>
                            <p></p>
                            @if ($detail->foto != null)
                    @foreach(explode('|',$detail->foto) as $item)
                    <img  src="{{asset('storage/laporan/admin')}}/{{$detail->no_lap}}/{{$item}}" style="width:280px; margin-bottom: 5pt"> &nbsp;
                    @endforeach
                        @else
                        Harap Upload Foto Dokumentasi
                        @endif
                        </td>
                    </tr>
                    
                    </table>
                @if($detail->user_id == Auth::user()->id || Auth::user()->role == "0"|| Auth::user()->role == "1"|| Auth::user()->role == "3")
                <center><a href="/laporan/admin/pdf/{{$detail->id}}" target="_blank"><span class="btn btn-primary btn-sm ml-2">Download Laporan</span></a></center>
                @endif
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection