<?php

namespace App\Http\Controllers;

use App\Models\DeptModel;
use App\Models\JabatanModel;
use App\Models\KantorModel;
use App\Models\PegawaiModel;
use App\Models\PerusahaanModel;
use App\Models\SatkerModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MasterController extends Controller
{
    public function tenant()
    {
        $perusahaan = PerusahaanModel::paginate(10);
        
        $jabatan = JabatanModel::paginate(10);

        return view('master.tenant', compact('perusahaan'));
    }

    public function tambahtenant(Request $request)
    {

        $tambah = new PerusahaanModel;

        $tambah->perusahaan = $request->tenant;
        $tambah->alamat = $request->alamat;
        $tambah->no_tlp = $request->telp;

        $tambah->save();

        return back()
            ->with('status', 'berhasil');
    }

    public function edittenant(Request $request, $id)
    {
        $request->validate([
            'tenant' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'telp' => 'required|string|max:15',
        ]);

        $tenant = PerusahaanModel::findOrFail($id);
        $tenant->update([
            'perusahaan' => $request->tenant,
            'alamat' => $request->alamat,
            'no_tlp' => $request->telp,
        ]);

        return redirect()->back()->with('status', 'Tenant berhasil diperbarui!');
    }

    // public function destroy($id)
    // {
    //     $tenant = PerusahaanModel::findOrFail($id);
    //     $tenant->delete();

    //     return redirect()->back()->with('status', 'Tenant berhasil dihapus!');
    // }

public function destroytenant(Request $request, $id)
    {
        $request->validate([
            'password' => 'required'
        ]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            // return redirect()->back()->with('error', 'Password salah!');
            return response()->json(['status' => 'error', 'message' => 'Password salah!'], 403);
        }

    $kantor = KantorModel::where('perusahaan', $id)->delete();
    $satker = SatkerModel::where('perusahaan', $id)->delete();
    $jabatan = JabatanModel::where('perusahaan', $id)->delete();
    $pegawai = PegawaiModel::where('perusahaan', $id)->delete();


        $tenant = PerusahaanModel::findOrFail($id);
        $tenant->delete();

        // return redirect()->back()->with('status', 'Tenant berhasil dihapus!');
        return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus!']);
    }


    public function kantor()
    {
        if(Auth::user()->role === 0){
            $perusahaan = PerusahaanModel::get();
            $kantor = KantorModel::with('perusa')->paginate(15);

            return view('master.kantor', compact('kantor', 'perusahaan'));
        } else {
            $kantor = KantorModel::with('perusa')
            ->where('perusahaan', Auth::user()->perusahaan)
            ->paginate(15);

            return view('master.kantor', compact('kantor'));
        }
    }

        public function tambahkantor(Request $request)
    {
        if(Auth::user()->role === 0){
            $perusa = $request->usaha;
        } else {
            $perusa = Auth::user()->perusahaan;
        }

        $kantor = new KantorModel;

        $kantor->perusahaan = $perusa;
        $kantor->nama_kantor = $request->kantor;
        $kantor->alamat = $request->alamat;
        $kantor->radius = $request->radius;
        $kantor->lokasi = $request->lokasi;

        $kantor->save();

        return back()
            ->with('status', 'berhasil');
    }

    public function kantoredit($id)
    {
        $kantor = KantorModel::findOrFail($id);
        $kantors = KantorModel::get();
        $perusahaan = PerusahaanModel::get();
        return view('master.kantoredit', compact('kantor', 'kantors', 'perusahaan'));
    }

    public function kantorupdate(Request $request, $id)
        {
        $request->validate([
            'tenant_name' => 'required|string|max:255',
            'office_name' => 'required|string|max:255',
            'address' => 'required|string',
            'attendance_distance' => 'required|numeric',
            'location' => 'required|string', // Format: "latitude,longitude"
        ]);

        $kantor = KantorModel::findOrFail($id);
        $kantor->perusahaan = $request->tenant_name;
        $kantor->nama_kantor = $request->office_name;
        $kantor->alamat = $request->address;
        $kantor->radius = $request->attendance_distance;
        $kantor->lokasi = $request->location;

        $karyawan = PegawaiModel::where('nama_kantor', $id);
        $karyawan->update([
            'perusahaan' => $request->tenant_name,
        ]);
        $kantor->save();

        return redirect()->route('kantor')->with('status', 'Data kantor berhasil diperbarui.');
    }
    
    public function satker()
    {
        if(Auth::user()->role === 0){
            $perusahaan = PerusahaanModel::get();
            $satker = SatkerModel::paginate(15);
            $kantor = KantorModel::paginate(15);
            $departemen = DeptModel::get();

        return view('master.satker', compact('satker', 'perusahaan', 'departemen', 'kantor'));
        }

        if(Auth::user()->role === 3){
            $perusahaan = PerusahaanModel::get();
            $departemen = DeptModel::get();
            $satker = SatkerModel::where('perusahaan', Auth::user()->perusahaan)
            ->paginate(15);

        return view('master.satker', compact('satker', 'perusahaan', 'departemen'));
        }

        if(Auth::user()->role === 1){
           $satker = SatkerModel::where('perusahaan', Auth::user()->perusahaan)
           ->paginate(15);
           $departemen = DeptModel::get();
           $kantor = KantorModel::get();

        return view('master.satker', compact('satker', 'departemen', 'kantor'));
        }

        
    }

    public function tambahsatker(Request $request)
    {

        if (Auth::user()->role == 0) {
            $perusahaan = $request->perusahaan;
            $dept = $request->departemen;
            $kantor = $request->kantor;
        } elseif (Auth::user()->role == 1) {
            $perusahaan = Auth::user()->perusahaan;
            $kantor = $request->kantor;
            $dept = $request->departemen;
        } elseif (Auth::user()->role == 3) {
            $perusahaan = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;
            $dept = $request->departemen;
        }

        $satker = new SatkerModel;

        $satker->perusahaan = $perusahaan;
        $satker->kantor = $kantor;
        $satker->dept_id = $dept;
        $satker->satuan_kerja = $request->satker;

        $satker->save();

        return back()
        ->with('status', 'berhasil');
    }

    public function updatesatker(Request $request, $id)
    {
        if (Auth::user()->role == 0) {
            $perusahaan = $request->perusahaan;
            $dept = $request->departemen;
            $kantor = $request->kantor;
        } elseif (Auth::user()->role == 1) {
            $perusahaan = Auth::user()->perusahaan;
            $kantor = $request->kantor;
            $dept = $request->departemen;
        } elseif (Auth::user()->role == 3) {
            $perusahaan = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;
            $dept = $request->departemen;
        }

        $request->validate([
            'satker' => 'required|string|max:255',
        ]);

        $satker = SatkerModel::findOrFail($id);

        $satker->satuan_kerja = $request->satker;
        $satker->dept_id = $dept;
        $satker->kantor = $kantor;
        $satker->perusahaan = $perusahaan;
        $satker->save();

        return redirect()->back()->with('status', 'Satuan Kerja berhasil diperbarui!');
    }

    public function destroysatker($id)
    {
        try {
            $satker = SatkerModel::findOrFail($id);
            $satker->delete();

            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }

    public function jabatan()
    {
        if(Auth::user()->role === 0){
            $perusahaan = PerusahaanModel::get();
            $kantor = KantorModel::get();
            $departemen = DeptModel::get();
            $satker = SatkerModel::get();
            $jabatan = JabatanModel::paginate(15);

        return view('master.jabatan', compact('jabatan', 'perusahaan', 'kantor', 'departemen', 'satker'));
        } 

        if(Auth::user()->role === 3){
           $jabatan = JabatanModel::where('perusahaan', Auth::user()->perusahaan)
           ->paginate(15);

        return view('master.jabatan', compact('jabatan'));
        } 
        
        if(Auth::user()->role === 1){
           $jabatan = JabatanModel::where('perusahaan', Auth::user()->perusahaan)
           ->paginate(15);
           $kantor = KantorModel::get();
           $departemen = DeptModel::get();
           $satker = SatkerModel::get();

        return view('master.jabatan', compact('jabatan', 'kantor', 'departemen', 'satker'));
        }
    }

    public function tambahjabatan(Request $request)
    {
        if(Auth::user()->role === 0){
            $perusa = $request->perusahaan;
            $dept = $request->departemen;
            $kantor = $request->kantor;
            $satker = $request->satker;
        } else if(Auth::user()->role === 1){
            $perusa = Auth::user()->perusahaan;
            $dept = $request->departemen;
            $kantor = $request->kantor;
            $satker = $request->satker;
        } else if(Auth::user()->role === 3){
            $perusa = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;
            $dept = $request->departemen;
            $satker = $request->satker;
        }


        $jabatan = new JabatanModel;

        $jabatan->perusahaan = $perusa;
        $jabatan->kantor_id = $kantor;
        $jabatan->dept_id = $dept;
        $jabatan->satker_id = $satker;
        $jabatan->jabatan = $request->jabatan;
         $jabatan->save();

        return back()
        ->with('status', 'Jabatan berhasil ditambahkan!');
    }

    public function updatejabatan(Request $request, $id)
    {
        if(Auth::user()->role === 0){
            $perusa = $request->perusahaan;
            $dept = $request->departemen;
            $kantor = $request->kantor;
            $satker = $request->satker;
        } else if(Auth::user()->role === 1){
            $perusa = Auth::user()->perusahaan;
            $dept = $request->departemen;
            $kantor = $request->kantor;
            $satker = $request->satker;
        } else if(Auth::user()->role === 3){
            $perusa = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;
            $dept = $request->departemen;
            $satker = $request->satker;
        }

        $request->validate([
            'jabatan' => 'required|string|max:255',
        ]);

        $jabatan = JabatanModel::findOrFail($id);
        $jabatan->update([
            'perusahaan' => $perusa,
            'kantor_id' => $kantor,
            'dept_id' => $dept,
            'satker_id' => $satker,
            'jabatan' => $request->jabatan
        ]);

        return response()->json(['message' => 'Data berhasil diperbarui']);
    }

    public function destroyjabatan($id)
    {
        try {
             $jabatan = JabatanModel::findOrFail($id);
             $jabatan->delete();

            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data.'], 500);
        }
    }


    public function user()
    {
        //Superadmin
        if(Auth::user()->role === 0){
            $users = User::with('perusa', 'kant', 'jabat', 'sat')
        ->paginate(15);
        } 
        //Admin kantor
        if(Auth::user()->role === 3){
            $comId = Auth::user()->perusahaan;
            $kanId = Auth::user()->kantor;
            
            $users = User::with('perusa', 'kant', 'jabat', 'sat')
            ->where('perusahaan', $comId)
            ->where('kantor', $kanId)
        ->paginate(15);
        }

        // Admin pusat
        if(Auth::user()->role === 1){
            $comId = Auth::user()->perusahaan;

            $users = User::with('perusa', 'kant', 'jabat', 'sat')
            ->where('perusahaan', $comId)
            ->whereNot('role', 0)->paginate(15);
        }

        $perusa = PerusahaanModel::get();

        return view('master.adduser', compact('users', 'perusa'));
    }

    public function adduser(Request $request)
    { 

      $add =  new User;
        // Superadmin
        if(Auth::user()->role === 0){
            if ($request->role == 1) { //pusat
                $perusa = $request->company;
            } else if ($request->role == 2){ //user
                $perusa = $request->company;
                $kantor = $request->office;
                $satker = $request->satker;
                $jabat = $request->position;
                $dept = $request->dept;

                $add->kantor = $kantor;
                $add->satker = $satker;
                $add->jabatan = $jabat;
                $add->dept = $dept;
            } else if ($request->role == 3){ //cabang
                $perusa = $request->company;
                $kantor = $request->office;

                $add->kantor = $kantor;
            } 
        }

        //Admin Pusat
        if(Auth::user()->role === 1){
            if ($request->role == 3) {//admin cabang
                $perusa = Auth::user()->perusahaan;
                $kantor = $request->office;

                $add->kantor = $kantor;
            } else { //user
                $perusa = Auth::user()->perusahaan;
                $satker = $request->satker;
                $jabat = $request->position;
                $kantor = $request->office;
                $dept = $request->dept;

                $add->dept = $dept;
                $add->kantor = $kantor;
                $add->satker = $satker;
                $add->jabatan = $jabat;
            }
        }

        //Admin Cabang
        if(Auth::user()->role === 3){ //user
            $kantor = Auth::user()->kantor;
            $perusa = Auth::user()->perusahaan;
            $satker = $request->satker;
            $jabat = $request->position;
            $dept = $request->dept;

            $add->dept = $dept;
            $add->kantor = $kantor;
            $add->satker = $satker;
            $add->jabatan = $jabat;
        } 

        $add->name = $request->name;
        $add->email = $request->email;
        $add->password = Hash::make($request->password);
        $add->perusahaan = $perusa;
        $add->role = $request->role;

        $add->save();

        return redirect('users')
        ->with('status', 'berhasil');
    }

    public function getkonten($companyId)
    {
        $offices = KantorModel::where('perusahaan', $companyId)->get();
        $satkers = SatkerModel::where('perusahaan', $companyId)->get();
        $positions = JabatanModel::where('perusahaan', $companyId)->get();
        $depts = DeptModel::where('perusahaan', $companyId)->get();
        return response()->json([
            'offices' => $offices,
            'satkers' => $satkers,
            'positions' => $positions,
            'depts' => $depts,
        ]);
    }

    public function getsat($kantId)
    {
        $departemen = DeptModel::where('perusahaan', $kantId)->get();
        $satker = SatkerModel::where('perusahaan', $kantId)->get();

        return response()->json([
            'departemen' => $departemen,
            'satker' => $satker
        ]);
    }


    public function getSatkerByDepartemen($id)
{
    $satker = SatkerModel::where('dept_id', $id)->get();
    return response()->json([
        'satker' => $satker, 
    ]);
}

    public function getPositionBySatker($id)
{
    $positions = JabatanModel::where('satker_id', $id)->get();
    return response()->json([
        'positions' => $positions,
    ]);
}

    public function dept()
    {
        $dept = DeptModel::paginate(10);
        $perusahaan = PerusahaanModel::get();
        $kantor = KantorModel::get();

        return view('master.departemen', compact('dept', 'perusahaan', 'kantor'));
    }

    public function deptstore(Request $request)
    {
        if (Auth::user()->role == 0) {
        $request->validate([
            'perusahaan' => 'required',
            'kantor' => 'required',
            'nama_dept' => 'required|string|max:255',
        ]);

            $perusahaan = $request->perusahaan;
            $dept = $request->nama_dept;
            $kantor = $request->kantor;
        } elseif (Auth::user()->role == 1) {
        $request->validate([
            'kantor' => 'required',
            'nama_dept' => 'required|string|max:255',
        ]);

            $perusahaan = Auth::user()->perusahaan;
            $kantor = $request->kantor;
            $dept = $request->nama_dept;
        } elseif (Auth::user()->role == 3) {
        $request->validate([
            'nama_dept' => 'required|string|max:255',
        ]);
            $perusahaan = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;
            $dept = $request->nama_dept;
        }

        try {
            DeptModel::create([
                'perusahaan' => $perusahaan,
                'nama_kantor' => $kantor,
                'nama_dept' => $dept,
            ]);

            return response()->json(['success' => true, 'message' => 'Departemen berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function deptup(Request $request, $id)
    {
        if (Auth::user()->role == 0) {
        $request->validate([
            'perusahaan' => 'required',
            'kantor' => 'required',
            'nama_dept' => 'required|string|max:255',
        ]);

            $perusahaan = $request->perusahaan;
            $dept = $request->nama_dept;
            $kantor = $request->kantor;
        } elseif (Auth::user()->role == 1) {
        $request->validate([
            'kantor' => 'required',
            'nama_dept' => 'required|string|max:255',
        ]);

            $perusahaan = Auth::user()->perusahaan;
            $kantor = $request->kantor;
            $dept = $request->nama_dept;
        } elseif (Auth::user()->role == 3) {
        $request->validate([
            'nama_dept' => 'required|string|max:255',
        ]);
            $perusahaan = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;
            $dept = $request->nama_dept;
        }

    DeptModel::where('id', $id)->update($request->only(['perusahaan', 'nama_kantor', 'nama_dept']));

    return response()->json(['success' => true]);
    }

    public function deptroy($id)
    {
        $departemen = DeptModel::findOrFail($id);
        $departemen->delete();

        return response()->json(['success' => true]);
    }
}
