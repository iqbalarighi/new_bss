@extends('layouts.absen.absen')

@section('header')
<div class="appHeader text-light" style="background-color: #ef3b3b;">
    <div class="left">
        <a href="{{route('absen.lapor')}}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" class="ion-icon"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Detail Laporan</div>
    <div class="right"><a href="{{ route('absen.editlap', $detail->id) }}" class="btn btn-sm btn-warning" style="text-align: center;">Edit</a></div>
</div>
@endsection

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (Session::get('success'))
<script type="text/javascript">
    Swal.fire({
  icon: "success",
  title: "{{Session::get('success')}}",
  showConfirmButton: true,
});
</script>
@endif
@if (Session::get('error'))
<script type="text/javascript">
    Swal.fire({
  icon: "warning",
  title: "{{Session::get('error')}}",
  showConfirmButton: true,
});
</script>
@endif
@php
\Carbon\Carbon::setLocale('id');
@endphp

                <div class="card-body d-flex justify-content-center" style="overflow: auto; margin-top: 4rem; margin-bottom: 5rem;">
                    <div class="col-md-auto p-auto">
                    <table class="table" width="100%">
                    <tr>
                        <td>
                        <b><center>Laporan Kegiatan {{$detail->sat->satuan_kerja}}</center></b>
                        <b><center>{{$detail->kant->nama_kantor ?? ''}}</center></b>
                        <b><center>{{Carbon\Carbon::parse($detail->created_at)->isoFormat('dddd, D MMMM Y')}}</center></b>
                        <b><center>Pukul {{Carbon\Carbon::parse($detail->created_at)->isoFormat('HH:mm:ss')}} WIB</center></b>
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
                        <td colspan="3">
                            <div style="text-align:justify; text-justify:inter-word; white-space:pre-wrap; word-wrap:break-word;" class="mb-0">{{$detail->kegiatan}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Keterangan : </b></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div style="white-space:pre-wrap; word-wrap:break-word;" class="mb-0">{{$detail->keterangan}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" colspan="3"><b>Dokumentasi : </b>
                            <p></p>
                            @if ($detail->foto != null)
                    @foreach(explode('|',$detail->foto) as $item)
                    <img  src="{{asset('storage/laporan')}}/{{$detail->no_lap}}/{{$item}}" style="width:280px; margin-bottom: 5pt"> &nbsp;
                    @endforeach
                        @else
                        Harap Upload Foto Dokumentasi
                        @endif
                        </td>
                    </tr>
                    </table>
                <center><a href="{{ route('absen.savepdf', $detail->id)}}" target="_blank"><span class="btn btn-primary btn-sm ml-2">Download Laporan</span></a></center>
                </div>
                </div>

@endsection