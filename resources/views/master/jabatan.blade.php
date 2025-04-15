@extends('layouts.side.side')

@section('content')
<div class="container mw-100">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header">{{ __('Daftar Jabatan') }}
                    <button class="btn btn-sm btn-primary float-right" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-building-add"></i></button>
                </div>

                
@if (Session::get('status'))
<script>
    Swal.fire({
      title: "Berhasil",
      text: "{{Session::get('status')}}",
      icon: "success",
      timer: 1500
    });
</script>
@endif
<style>
    .modal.fade .modal-dialog {
        transform: scale(0.8);
        transition: transform 0.3s ease-out;
    }
    .modal.show .modal-dialog {
        transform: scale(1);
    }
    #map {
        width: 100%;
        height: 50vh;
        min-height: 300px;
    }
</style>
<!-- Modal Tambah Jabatan -->                    
<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('jabatan')}}/tambah" method="POST">
                    @csrf

                    @if(Auth::user()->role == 0)
                    <div class="mb-3">
                        <label for="tenantName" class="form-label">Nama Perusahaan</label>
                        <select name="usaha" id="tenantName" class="form-select" required>
                            <option selected disabled value="">Pilih Perusahaan</option>
                            @foreach($perusahaan as $usaha)
                            <option value="{{$usaha->id}}">{{$usaha->perusahaan}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    @if(Auth::user()->role == 0 || Auth::user()->role == 1 )
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
                    @if(Auth::user()->role == 3 )
                    <div class="mb-3">
                        <label for="departemen" class="form-label">Departemen</label>
                        <select name="departemen" id="departemen" class="form-select" required>
                            <option selected disabled value="">Pilih Kantor</option>
                            @foreach($departemen as $dept)
                            <option value="{{$dept->id}}">{{$dept->nama_dept}}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <div class="mb-3">
                        <label for="departemen" class="form-label">Departemen</label>
                        <select name="departemen" id="departemen" class="form-select" required>
                            <option selected disabled value="">Pilih Departemen</option>
                        
                        </select>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="satker" class="form-label">Satuan Kerja</label>
                        <select name="satker" id="satker" class="form-select" required>
                            <option selected disabled value="">Pilih Satuan Kerja</option>
                            
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Jabatan" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal Edit Jabatan -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditJabatan" method="POST">
                    @csrf
                    <input type="hidden" id="editId">
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" class="form-control" id="editJabatan" name="jabatan" required>
                    </div>
                     @if(Auth::user()->role == 0)
                        <div class="mb-3">
                            <label for="perusahaan" class="form-label">Nama Perusahaan</label>
                            <select name="perusahaan" id="editPerusahaan" class="form-select" required>
                                <option selected disabled value="">Pilih Perusahaan</option>
                            @foreach($perusahaan as $usaha)
                                <option value="{{$usaha->id}}">{{$usaha->perusahaan}}</option>
                            @endforeach
                            </select>
                        </div>
                        @endif
                        @if(Auth::user()->role == 0 || Auth::user()->role == 1 )
                    <div class="mb-3">
                        <label for="kantor" class="form-label">Kantor</label>
                        <select name="kantor" id="editKantor" class="form-select" required>
                            <option selected disabled value="">Pilih Kantor</option>
                            @foreach($kantor as $office)
                            <option value="{{$office->id}}">{{$office->nama_kantor}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label for="departemen" class="form-label">Departemen</label>
                        <select name="departemen" id="editDepartemen" class="form-select" required>
                            <option selected disabled value="">Pilih Departemen</option>
                            @foreach($departemen as $dept)
                            <option value="{{$dept->id}}">{{$dept->nama_dept}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="satker" class="form-label">Satuan Kerja</label>
                        <select name="satker" id="editSatker" class="form-select" required>
                            <option selected disabled value="">Pilih Satuan Kerja</option>
                            @foreach($satker as $unit)
                            <option value="{{$unit->id}}">{{$unit->satuan_kerja}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="card-body" style="overflow-x: auto;">
<!-- Tabel Data -->
<table class="table table-striped table-bordered table-hover">
    <thead class="text-center table-dark">
        <tr>
            <th>No</th>
            <th>Jabatan</th>
            @if(Auth::user()->role == 0)
            <th>Perusahaan</th>
            @endif
            @if(Auth::user()->role == 0 || Auth::user()->role == 1 )
            <th class="text-start position-relative">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Kantor</span>
                    <div class="dropdown">
                        <i class="fas fa-filter ms-2 text-white" role="button" data-bs-toggle="dropdown"></i>
                        <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width: 150px;">
                            <li>
                                <select id="filterKantor" class="form-select form-select-sm" onchange="filterTable('kantor', this.value)">
                                    <option value="">Semua</option>
                                    @foreach($kantor as $kantor)
                                        <option value="{{ $kantor->nama_kantor }}">{{ $kantor->nama_kantor }}</option>
                                    @endforeach
                                </select>
                            </li>
                        </ul>
                    </div>
                </div>
            </th>
            @endif
            <th class="text-start position-relative">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Departemen</span>
                    <div class="dropdown">
                        <i class="fas fa-filter ms-2 text-white" role="button" data-bs-toggle="dropdown"></i>
                        <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width: 150px;">
                            <li>
                                <select id="filterDepartemen" class="form-select form-select-sm" onchange="filterTable('departemen', this.value)">
                                    <option value="">Semua</option>
                                    @foreach($departemen->unique('nama_dept') as $d)
                                        <option value="{{ $d->nama_dept }}">{{ $d->nama_dept }}</option>
                                    @endforeach
                                </select>
                            </li>
                        </ul>
                    </div>
                </div>
            </th>
            <th class="text-start position-relative">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Satuan Kerja</span>
                    <div class="dropdown">
                        <i class="fas fa-filter ms-2 text-white" role="button" data-bs-toggle="dropdown"></i>
                        <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width: 150px;">
                            <li>
                                <select id="filterSatker" class="form-select form-select-sm" onchange="filterTable('satker', this.value)">
                                    <option value="">Semua</option>
                                    @foreach($satker->unique('satuan_kerja') as $s)
                                        <option value="{{ $s->satuan_kerja }}">{{ $s->satuan_kerja }}</option>
                                    @endforeach
                                </select>
                            </li>
                        </ul>
                    </div>
                </div>
            </th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jabatan as $key => $item)
        <tr id="row-{{$item->id}}">
            <td>{{ $jabatan->firstItem() + $key }}</td>
            <td>{{ $item->jabatan }}</td>
            @if(Auth::user()->role == 0)
            <td>{{ $item->perusa->perusahaan }}</td>
            @endif
            @if(Auth::user()->role == 0 || Auth::user()->role == 1 )
            <td>{{ $item->kantor_id == 0 ? '-' : $item->kant->nama_kantor }}</td>
            @endif
            <td>{{ $item->dept_id == 0 ? '-' : $item->deptmn->nama_dept }}</td>
            <td>{{ $item->satker_id == 0 ? '-' : $item->sat->satuan_kerja }}</td>
            <td>
                <button class="btn btn-sm btn-primary btnEdit" 
                data-id="{{ $item->id }}" 
                data-jabatan="{{ $item->jabatan }}"
                data-satker="{{ $item->satker_id }}"
                data-dept="{{ $item->dept_id }}"
                data-kantor="{{ $item->kantor_id }}"
                data-perusahaan="{{ $item->perusahaan }}"
                >Edit</button>
                <button class="btn btn-sm btn-danger btnHapus" data-id="{{ $item->id }}">Hapus</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@push('script')
<script>
    $(document).ready(function () {
        // Saat tombol edit diklik
        $(document).on('click', '.btnEdit', function () {
            let id = $(this).data('id');
            let jabatan = $(this).data('jabatan');
            let perusahaan = $(this).data('perusahaan');
            let kantor = $(this).data('kantor');
            let departemen = $(this).data('dept');
            let satker = $(this).data('satker');

            $('#editId').val(id);
            $('#editJabatan').val(jabatan);
            $('#editPerusahaan').val(perusahaan);
            $('#editKantor').val(kantor);
            $('#editDepartemen').html('<option value="">Loading...</option>');
            $('#editSatker').html('<option value="">Loading...</option>');
            $('#nmdept').hide();

            // Load departemen dan satker berdasarkan kantor
            if (kantor) {
                $.ajax({
                    url: '/get-sat/' + kantor,
                    type: 'GET',
                    success: function (response) {
                        let departemenOptions = '<option value="">Pilih Departemen</option>';
                        let satkerOptions = '<option value="">Pilih Satuan Kerja</option>';

                        response.departemen.forEach(function (dept) {
                            departemenOptions += `<option value="${dept.id}" ${dept.id == departemen ? 'selected' : ''}>${dept.nama_dept}</option>`;
                        });

                        response.satker.forEach(function (s) {
                            satkerOptions += `<option value="${s.id}" ${s.id == satker ? 'selected' : ''}>${s.satuan_kerja}</option>`;
                        });

                        $('#editDepartemen').html(departemenOptions);
                        $('#editSatker').html(satkerOptions);
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }

            $('#modalEdit').modal('show');
        });

        // Saat kantor diubah
        $('#editKantor').change(function () {
            let kantorId = $(this).val();
            if (kantorId) {
                $.ajax({
                    url: '/get-sat/' + kantorId,
                    type: 'GET',
                    success: function (response) {
                        let departemenOptions = '<option value="">Pilih Departemen</option>';
                        let satkerOptions = '<option value="">Pilih Satuan Kerja</option>';

                        response.departemen.forEach(function (dept) {
                            departemenOptions += `<option value="${dept.id}">${dept.nama_dept}</option>`;
                        });

                        response.satker.forEach(function (satker) {
                            satkerOptions += `<option value="${satker.id}">${satker.nama_satker}</option>`;
                        });

                        $('#editDepartemen').html(departemenOptions);
                        $('#editSatker').html(satkerOptions);
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
            } else {
                $('#editDepartemen').html('<option value="">Pilih Departemen</option>');
                $('#editSatker').html('<option value="">Pilih Satuan Kerja</option>');
            }
        });

        // Saat departemen diubah
        $('#editDepartemen').change(function () {
            let departemenId = $(this).val();
            if (departemenId) {
                $.ajax({
                    url: '/get-satker-by-departemen/' + departemenId,
                    type: 'GET',
                    success: function (response) {
                        let satkerOptions = '<option value="">Pilih Satuan Kerja</option>';
                        response.satker.forEach(function (s) {
                            satkerOptions += `<option value="${s.id}">${s.satuan_kerja}</option>`;
                        });
                        $('#editSatker').html(satkerOptions);
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
            } else {
                $('#editSatker').html('<option value="">Pilih Satuan Kerja</option>');
            }
        });

        // Submit form edit
$('#formEditJabatan').submit(function (e) {
    e.preventDefault();

    let id = $('#editId').val();
    let jabatan = $('#editJabatan').val();
    let perusahaan = $('#editPerusahaan').val();
    let kantor = $('#editKantor').val();
    let departemen = $('#editDepartemen').val();
    let satker = $('#editSatker').val();

    Swal.fire({
        title: 'Peringatan!',
        text: 'Perubahan dapat berdampak pada data terkait. Lanjutkan?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, simpan perubahan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/jabatan/edit/' + id,
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    jabatan: jabatan,
                    perusahaan: perusahaan,
                    kantor: kantor,
                    departemen: departemen,
                    satker: satker
                },
                success: function (response) {
                    Swal.fire({
                        title: "Berhasil",
                        icon: "success",
                        text: "Berhasil perbarui Jabatan!",
                        timer: 2000,
                        showConfirmButton: false
                    });
                    location.reload();
                },
                error: function () {
                    Swal.fire({
                        title: "Gagal",
                        icon: "error",
                        text: "Terjadi kesalahan saat memperbarui data."
                    });
                }
            });
        }
    });
});

        // Hapus data
        $('.btnHapus').click(function () {
            let id = $(this).data('id');
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Menghapus...",
                        text: "Mohon tunggu",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    fetch("/jabatan/hapus/" + id, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": '{{ csrf_token() }}'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire("Terhapus!", data.message, "success");
                                document.getElementById("row-" + id).remove();
                            } else {
                                Swal.fire("Gagal!", data.message, "error");
                            }
                        })
                        .catch(error => {
                            Swal.fire("Error!", "Gagal menghapus data.", "error");
                        });
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#kantor').change(function() {
            let perusahaanId = $(this).val();
            if (perusahaanId) {
                $.ajax({
                    url: '/get-sat/' + perusahaanId,
                    type: 'GET',
                    success: function(response) {
                        let departemenOptions = '<option value="">Pilih Departemen</option>';
                        let satkerOptions = '<option value="">Pilih Satuan Kerja</option>';

                        response.departemen.forEach(function(dept) {
                            departemenOptions += `<option value="${dept.id}">${dept.nama_dept}</option>`;
                        });
                        response.satker.forEach(function(satker) {
                            satkerOptions += `<option value="${satker.id}">${satker.satuan_kerja}</option>`;
                        });

                        $('#departemen').html(departemenOptions);
                        $('#satker').html(satkerOptions);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }
        });

        $('#departemen').change(function() {
            let departemenId = $(this).val();
            if (departemenId) {
                $.ajax({
                    url: '/get-satker-by-departemen/' + departemenId,
                    type: 'GET',
                    success: function(response) {
                        let satkerOptions = '<option value="">Pilih Satuan Kerja</option>';
                        response.satker.forEach(function(satker) {
                            satkerOptions += `<option value="${satker.id}">${satker.satuan_kerja}</option>`;
                        });
                        $('#satker').html(satkerOptions);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#editKantor').change(function() {
            let perusahaanId = $(this).val();
            if (perusahaanId) {
                $.ajax({
                    url: '/get-sat/' + perusahaanId,
                    type: 'GET',
                    success: function(response) {
                        let departemenOptions = '<option value="">Pilih Departemen</option>';
                        let satkerOptions = '<option value="">Pilih Satuan Kerja</option>';

                        response.departemen.forEach(function(dept) {
                            departemenOptions += `<option value="${dept.id}">${dept.nama_dept}</option>`;

                             let nm = dept.nama_dept;

                $('#nmdept').empty().append('Departemen : '+nm).show();
                        });
                        response.satker.forEach(function(satker) {
                            satkerOptions += `<option value="${satker.id}">${satker.nama_satker}</option>`;
                        });

                        $('#editDepartemen').html(departemenOptions);
                        $('#editSatker').html(satkerOptions);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }
        });

        $('#editDepartemen').change(function() {
            let departemenId = $(this).val();
            if (departemenId) {
                $.ajax({
                    url: '/get-satker-by-departemen/' + departemenId,
                    type: 'GET',
                    success: function(response) {
                        let satkerOptions = '<option value="">Pilih Satuan Kerja</option>';
                        response.satker.forEach(function(satker) {
                            satkerOptions += `<option value="${satker.id}">${satker.satuan_kerja}</option>`;
                        });
                        $('#editSatker').html(satkerOptions);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }
        });
    });
</script>
<script>
    function filterTable(type, value) {
        const selectedValue = value.toLowerCase();
        const rows = document.querySelectorAll("table tbody tr");

        // Tentukan index kolom berdasarkan role user
        let kantorIndex = {{ Auth::user()->role == 0 || Auth::user()->role == 1 ? 2 : 1 }};
        let departemenIndex = kantorIndex + 1;
        let satkerIndex = departemenIndex + 1;  // Tentukan index kolom untuk satuan kerja (satker) sesuai tabelmu

        rows.forEach(row => {
            const cells = row.querySelectorAll("td");
            if (!cells.length) return;

            let isMatch = true;

            if (type === 'kantor' && kantorIndex >= 0) {
                const cellValue = cells[kantorIndex].innerText.toLowerCase();
                if (selectedValue && cellValue !== selectedValue) isMatch = false;
            }

            if (type === 'departemen') {
                const cellValue = cells[departemenIndex].innerText.toLowerCase();
                if (selectedValue && cellValue !== selectedValue) isMatch = false;
            }

            if (type === 'satker') {  // Filter berdasarkan satuan kerja
                const cellValue = cells[satkerIndex].innerText.toLowerCase();
                if (selectedValue && cellValue !== selectedValue) isMatch = false;
            }

            row.style.display = isMatch ? '' : 'none';
        });
    }
</script>

@endpush
