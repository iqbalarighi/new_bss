@foreach($absen as $key => $abs)
                            <tr>
                                <td class="text-center">{{$absen->firstitem() + $key}}</td>
                                <td class="text-center" style="white-space: nowrap;">{{ Carbon\Carbon::parse($abs->tgl_absen)->isoFormat('DD-MM-YYYY')}}</td>
                                <td class="text-center">{{$abs->pegawai->nip}}</td>
                                <td>{{$abs->pegawai->nama_lengkap}}</td>
                                <td>{{ $abs->shifts->shift}}</td>
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
