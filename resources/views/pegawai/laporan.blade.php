@extends('layouts.side.side')
@section('content')

<div class="container">

@if(Session::get('success'))
<script type="text/javascript">
    Swal.fire({
  icon: "success",
  title: "{{Session::get('success')}}",
  showConfirmButton: false,
  timer: 2000
});
</script>
@endif
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between">{{ __('Daftar Pegawai') }}
                </div>
                <div class="card-body">


                    halaman laporan absensi


                </div>
            </div>
        </div>
    </div>
</div>

@endsection
