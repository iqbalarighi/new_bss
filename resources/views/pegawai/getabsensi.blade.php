@foreach($absen as $key => $abs)
                            <tr>
                                <td class="text-center">{{$absen->firstitem() + $key}}</td>
                                <td class="text-center">{{$abs->pegawai->nip}}</td>
                                <td>{{$abs->pegawai->nama_lengkap}}</td>
                                <td>{{ $abs->shift == 0 ? 'Non Shift' : ($abs->shift == 1 ? 'Shift Pagi' : 'Shift Siang') }}</td>
@if(Auth::user()->role == 0 || Auth::user()->role == 1)<td>{{$abs->pegawai->kantor->nama_kantor}}</td> @endif
                                <td>{{$abs->pegawai->deptmn->nama_dept}}</td>
                                <td>{{$abs->pegawai->sat->satuan_kerja}}</td>
                                <td class="text-center @if($abs->shift == 0)
                                    {{$abs->jam_in >= '08:00' ? 'text-danger' : ''}}
                                    @elseif($abs->shift == 1)
                                    {{$abs->jam_in >= '07:00' ? 'text-danger' : ''}}
                                    @elseif($abs->shift == 2)
                                    {{$abs->jam_in >= '13:00' ? 'text-danger' : ''}}
                                    @endif">{{$abs->jam_in}}</td>
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
                                <td class="text-center @if($abs->shift == 0)
                                    {{$abs->jam_in >= '08:00' ? 'text-danger' : ''}}
                                    @elseif($abs->shift == 1)
                                    {{$abs->jam_in >= '07:00' ? 'text-danger' : ''}}
                                    @elseif($abs->shift == 2)
                                    {{$abs->jam_in >= '13:00' ? 'text-danger' : ''}}
                                    @endif">

                                    @php
                                    switch ($abs->shift) {
                                        case '0':
                                            $jamStandar = Carbon\Carbon::parse('08:00');
                                            break;
                                        case '1':
                                            $jamStandar = Carbon\Carbon::parse('07:00');
                                            break;
                                        default: // non shift
                                            $jamStandar = Carbon\Carbon::parse('13:00');
                                            break;
                                    }

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
