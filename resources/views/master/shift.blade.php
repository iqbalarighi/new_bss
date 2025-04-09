@extends('layouts.side.side')

@section('content')
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
<div class="container">
    <div class="row justify-content-center">
        <div class="col mw-100">
            <div class="card">
                <div class="card-header">{{ __('Daftar Shift') }}
                    <button class="btn btn-sm btn-primary float-right" data-bs-toggle="modal" data-bs-target="#tambahShiftModal"><i class="bi bi-building-add"></i></button>
                </div>
                <div class="card-body" style="overflow: auto;"> 
                        <table class="table table-striped table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Shift</th>
                            @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                                <th>Kantor</th>
                            @endif
                                <th>Satker</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shift as $index => $shi)
                                <tr>
                                    <td>{{ $shift->firstitem() + $index }}</td>
                                    <td>{{ $shi->shift }}</td>
                                @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                                    <td>{{ $shi->kant->nama_kantor }}</td>
                                @endif
                                    <td>{{ $shi->sat->satuan_kerja }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($shi->jam_masuk)->format('H:i') }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($shi->jam_keluar)->format('H:i') }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary edit-shift-btn"
                                            data-id="{{ $shi->id }}"
                                            data-shift="{{ $shi->shift }}"
                                            data-satker="{{ $shi->satker_id }}"
                                            data-kantor="{{ $shi->kantor_id }}"
                                            data-jam_masuk="{{ $shi->jam_masuk }}"
                                            data-jam_keluar="{{ $shi->jam_keluar }}">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>

                                        <form action="" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus shift ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                                        <td colspan="7" class="text-center">Belum ada shift terdaftar.</td>
                                    @else
                                        <td colspan="6" class="text-center">Belum ada shift terdaftar.</td>
                                    @endif
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="tambahShiftModal" tabindex="-1" aria-labelledby="tambahShiftModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="tambahShiftForm" action="{{ route('master.shift.store') }}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahShiftModalLabel">Tambah Shift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="shift" class="form-label">Nama Shift</label>
                    <input type="text" class="form-control" id="shift" name="shift" placeholder="Nama Shift" required>
                </div>
                @if(Auth::user()->role == 0 || Auth::user()->role == 1)
                {{-- Select Kantor --}}
                <div class="mb-3">
                    <label for="kantor_id" class="form-label">Pilih Kantor</label>
                    <select class="form-select" name="kantor_id" id="kantor_id" required>
                        <option value="" disabled selected>-- Pilih Kantor --</option>
                        @foreach($kantor as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kantor }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                {{-- Select Satker --}}
                <div class="mb-3">
                    <label for="satker_id" class="form-label">Nama Satker</label>
                    <select class="form-select" name="satker_id" id="satker_id" required>
                        <option value="" disabled selected>-- Pilih Satker --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="jam_masuk" class="form-label">Jam Masuk</label>
                    <input type="time" class="form-control" name="jam_masuk" required>
                </div>
                <div class="mb-3">
                    <label for="jam_keluar" class="form-label">Jam Keluar</label>
                    <input type="time" class="form-control" name="jam_keluar" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </div>
    </form>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editShiftModal" tabindex="-1" aria-labelledby="editShiftModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editShiftForm" action="" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" id="edit_shift_id">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editShiftModalLabel">Edit Shift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit_shift" class="form-label">Nama Shift</label>
                    <input type="text" class="form-control" id="edit_shift" name="shift" placeholder="Nama Shift" required>
                </div>
                @if(Auth::user()->role != 3)
                <div class="mb-3">
                    <label for="edit_kantor_id" class="form-label">Kantor</label>
                    <select class="form-select" id="edit_kantor_id" name="kantor_id" required>
                        <option value="" disabled selected>-- Pilih Kantor --</option>
                        @foreach($kantor as $kan)
                            <option value="{{ $kan->id }}">{{ $kan->nama_kantor }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="mb-3">
                    <label for="edit_satker_id" class="form-label">Satker</label>
                    <select class="form-select" name="satker_id" id="edit_satker_id" required>
                        <option value="" disabled selected>-- Pilih Satker --</option>
                        @foreach($satker as $sat)
                            <option value="{{ $sat->id }}" data-kantor="{{ $sat->kantor }}">{{ $sat->satuan_kerja }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="edit_jam_masuk" class="form-label">Jam Masuk</label>
                    <input type="time" class="form-control" id="edit_jam_masuk" name="jam_masuk" required>
                </div>
                <div class="mb-3">
                    <label for="edit_jam_keluar" class="form-label">Jam Keluar</label>
                    <input type="time" class="form-control" id="edit_jam_keluar" name="jam_keluar" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
        </div>
    </form>
  </div>
</div>


<div id="all-satker-options" style="display: none;">
    @foreach($satker as $sat)
        <option value="{{ $sat->id }}" data-kantor="{{ $sat->kantor }}">{{ $sat->satuan_kerja }}</option>
    @endforeach
</div>
@endsection

@push('script')
<script>
$(document).ready(function () {
    $("#tambahShiftForm").on("submit", function (e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('master.shift.store') }}", // Ganti dengan route yang sesuai
            type: "POST",
            data: $(this).serialize(),
            beforeSend: function () {
                // Optional: bisa tambahkan loader
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Tutup modal dan reset form
                    $("#tambahShiftModal").modal("hide");
                    $("#tambahShiftForm")[0].reset();

                    // Tambahkan baris baru ke tabel shift
                        window.location.reload()
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: response.message || "Terjadi kesalahan saat menyimpan data."
                    });
                }
            },
            error: function (xhr) {
                let errMsg = "Terjadi kesalahan. Silakan coba lagi.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: errMsg
                });
            }
        });
    });
});
</script>
<script>
$(document).ready(function () {
    const allSatkerOptions = $('#edit_satker_id').html();
function formatTime(timeStr) {
    return timeStr.substring(0, 5); // "07:00:00" → "07:00"
}

    $('.edit-shift-btn').on('click', function () {
        const id = $(this).data('id');
        const shift = $(this).data('shift');
        const satker = $(this).data('satker');
        const kantor = $(this).data('kantor');
        const jamMasuk = formatTime($(this).data('jam_masuk'));
        const jamKeluar = formatTime($(this).data('jam_keluar'));

        // Isi data ke form
        $('#edit_shift_id').val(id);
        $('#edit_shift').val(shift);
        $('#edit_jam_masuk').val(jamMasuk);
        $('#edit_jam_keluar').val(jamKeluar);
        $('#edit_kantor_id').val(kantor);

        // Filter satker berdasarkan kantor yang dipilih
        let filteredOptions = '<option value="" disabled>-- Pilih Satker --</option>';
        $(allSatkerOptions).filter(`option[data-kantor="${kantor}"]`).each(function () {
            const selected = this.value == satker ? 'selected' : '';
            filteredOptions += `<option value="${this.value}" data-kantor="${kantor}" ${selected}>${$(this).text()}</option>`;
        });

        $('#edit_satker_id').html(filteredOptions);

        // Tampilkan modal
        $('#editShiftModal').modal('show');
    });

    // Saat kantor diubah manual oleh user di modal
    $('#edit_kantor_id').on('change', function () {
        const selectedKantor = $(this).val();
        let filteredOptions = '<option value="" disabled selected>-- Pilih Satker --</option>';
        $(allSatkerOptions).filter(`option[data-kantor="${selectedKantor}"]`).each(function () {
            filteredOptions += this.outerHTML;
        });
        $('#edit_satker_id').html(filteredOptions);
    });
});


$('#editShiftForm').on('submit', function (e) {
        e.preventDefault();

        const id = $('#edit_shift_id').val();
        const formData = $(this).serialize();

        $.ajax({
            url: `/shift/update/${id}`, // pastikan route-nya sesuai
            method: 'POST',
            data: formData,
            beforeSend: function () {
                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Shift berhasil diperbarui.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    $('#editShiftModal').modal('hide');
                    location.reload(); // atau refresh data shift via AJAX jika ada
                });
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat memperbarui shift.'
                });
            }
        });
    });
</script>


@if(Auth::user()->role == 0 || Auth::user()->role == 1)
<script>
    $(document).ready(function () {
        $('#kantor_id').on('change', function () {
            const selectedKantor = $(this).val();
            let options = '<option value="" disabled selected>-- Pilih Satker --</option>';

            $('#all-satker-options option').each(function () {
                if ($(this).data('kantor') == selectedKantor) {
                    options += `<option value="${$(this).val()}">${$(this).text()}</option>`;
                }
            });

            $('#satker_id').html(options);
        });
    });
</script>
@else
<script>
    $(document).ready(function () {
            const selectedKantor = "{{Auth::user()->kantor}}";
            let options = '<option value="" disabled selected>-- Pilih Satker --</option>';

            $('#all-satker-options option').each(function () {
                if ($(this).data('kantor') == selectedKantor) {
                    options += `<option value="${$(this).val()}">${$(this).text()}</option>`;
                }
            });

            $('#satker_id').html(options);
    });
</script>
@endif
@endpush


{{-- nanti buat absensi gabisa absen kalo kemaren belum absen pulang --}}
