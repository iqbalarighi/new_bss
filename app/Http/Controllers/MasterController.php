<?php

namespace App\Http\Controllers;

use App\Models\DeptModel;
use App\Models\JabatanModel;
use App\Models\KantorModel;
use App\Models\PegawaiModel;
use App\Models\PengecualianAbsen;
use App\Models\PerusahaanModel;
use App\Models\ReguAnggotaModel;
use App\Models\ReguModel;
use App\Models\SatkerModel;
use App\Models\ShiftModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        if(Auth::user()->role == 0){
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
        if(Auth::user()->role == 0){
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

    public function kantroy($id)
    {
        $kantor = KantorModel::findOrFail($id);

        $kanBaru = 0;
        User::where('kantor', $id)->update(['kantor' => $kanBaru]);
        JabatanModel::where('kantor_id', $id)->update(['kantor_id' => $kanBaru]);
        SatkerModel::where('kantor', $id)->update(['kantor' => $kanBaru]);
        DeptModel::where('nama_kantor', $id)->update(['nama_kantor' => $kanBaru]);

        $kantor->delete();

        return response()->json(['success' => true]);
    }
    
    public function satker()
    {
        if(Auth::user()->role == 0){
            $perusahaan = PerusahaanModel::get();
            $satker = SatkerModel::paginate(15);
            $kantor = KantorModel::paginate(15);
            $departemen = DeptModel::get();

        return view('master.satker', compact('satker', 'perusahaan', 'departemen', 'kantor'));
        }

        if(Auth::user()->role == 3){
            $perusahaan = PerusahaanModel::get();
            $departemen = DeptModel::where('perusahaan', Auth::user()->perusahaan)
                ->where('nama_kantor', Auth::user()->kantor)
                ->get();
            $satker = SatkerModel::where('perusahaan', Auth::user()->perusahaan)
                ->where('kantor', Auth::user()->kantor)
            ->paginate(15);

        return view('master.satker', compact('satker', 'perusahaan', 'departemen'));
        }

        if(Auth::user()->role == 1){
           $satker = SatkerModel::where('perusahaan', Auth::user()->perusahaan)
           ->paginate(15);
           $departemen = DeptModel::where('perusahaan', Auth::user()->perusahaan)->get();
           $kantor = KantorModel::where('perusahaan', Auth::user()->perusahaan)->get();

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

    // Update JabatanModel
    JabatanModel::where('satker_id', $satker->id)
        ->where('dept_id', $satker->dept_id)
        ->where('kantor_id', $satker->kantor)
        ->update([
            'dept_id' => $dept,
            'kantor_id' => $kantor
        ]);

    // Update User Model
    User::where('satker', $satker->id) // Perbaikan dari 'dept' menjadi 'dept_id'
        ->where('dept', $satker->dept_id)
        ->where('kantor', $satker->kantor)
        ->update([
            'dept' => $dept,
            'kantor' => $kantor
        ]);


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

            $satkerBaru = 0;
            JabatanModel::where('satker', $id)->update(['satker' => $satkerBaru]);
            User::where('satker', $id)->update(['satker' => $satkerBaru]);

            $satker->delete();

            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data'], 500);
        }
    }

    public function jabatan()
    {
        if(Auth::user()->role == 0){
            $perusahaan = PerusahaanModel::get();
            $kantor = KantorModel::get();
            $departemen = DeptModel::get();
            $satker = SatkerModel::get();
            $jabatan = JabatanModel::paginate(15);

        return view('master.jabatan', compact('jabatan', 'perusahaan', 'kantor', 'departemen', 'satker'));
        } 

        if(Auth::user()->role == 3){
           $jabatan = JabatanModel::where('perusahaan', Auth::user()->perusahaan)
           ->where('kantor_id', Auth::user()->kantor)
           ->paginate(15);
           $departemen = DeptModel::where('perusahaan', Auth::user()->perusahaan)
           ->where('nama_kantor', Auth::user()->kantor)
           ->get();
            $satker = SatkerModel::get();

        return view('master.jabatan', compact('jabatan', 'departemen', 'satker'));
        } 
        
        if(Auth::user()->role == 1){
           $jabatan = JabatanModel::where('perusahaan', Auth::user()->perusahaan)
           ->paginate(15);
           $kantor = KantorModel::where('perusahaan', Auth::user()->perusahaan)
           ->get();
           $departemen = DeptModel::where('perusahaan', Auth::user()->perusahaan)
           ->get();
           $satker = SatkerModel::where('perusahaan', Auth::user()->perusahaan)
           ->get();

        return view('master.jabatan', compact('jabatan', 'kantor', 'departemen', 'satker'));
        }
    }

    public function tambahjabatan(Request $request)
    {
        if(Auth::user()->role == 0){
            $perusa = $request->usaha;
            $dept = $request->departemen;
            $kantor = $request->kantor;
            $satker = $request->satker;
        } else if(Auth::user()->role == 1){
            $perusa = Auth::user()->perusahaan;
            $dept = $request->departemen;
            $kantor = $request->kantor;
            $satker = $request->satker;
        } else if(Auth::user()->role == 3){
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
        if(Auth::user()->role == 0){
            $perusa = $request->perusahaan;
            $dept = $request->departemen;
            $kantor = $request->kantor;
            $satker = $request->satker;
        } else if(Auth::user()->role == 1){
            $perusa = Auth::user()->perusahaan;
            $dept = $request->departemen;
            $kantor = $request->kantor;
            $satker = $request->satker;
        } else if(Auth::user()->role == 3){
            $perusa = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;
            $dept = $request->departemen;
            $satker = $request->satker;
        }

        $request->validate([
            'jabatan' => 'required|string|max:255',
        ]);

        $jabatan = JabatanModel::findOrFail($id);

         $cek = User::where('jabatan', $jabatan->id) 
        ->where('satker', $jabatan->satker_id)
        ->where('dept', $jabatan->dept_id)
        ->where('kantor', $jabatan->kantor_id)
        ->update([
            'kantor' => $kantor,
            'dept' => $dept,
            'satker' => $satker
        ]); 
        
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

             $jabatanBaru = 0;
             User::where('jabatan', $id)->update(['jabatan' => $jabatanBaru]);

             $jabatan->delete();

            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data.'], 500);
        }
    }


    public function user()
    {
        //Superadmin
        if(Auth::user()->role == 0){
            $users = User::with('perusa', 'kant', 'jabat', 'sat')
        ->paginate(15);
        $perusa = PerusahaanModel::get();
        $kantor = KantorModel::get();
        $satker = SatkerModel::get();
        $jabat = JabatanModel::get();
        $dept = DeptModel::get();
        } 

        // Admin pusat
        if(Auth::user()->role == 1){
            $comId = Auth::user()->perusahaan;

            $users = User::with('perusa', 'kant', 'jabat', 'sat')
            ->where('perusahaan', $comId)
            ->whereNot('role', 0)->paginate(15);
        $perusa = PerusahaanModel::where('perusahaan', $comId)
            ->get();
        $kantor = KantorModel::where('perusahaan', $comId)
            ->get();

        $dept = DeptModel::where('perusahaan', $comId)
            ->get();
        $satker = SatkerModel::where('perusahaan', $comId)
            ->get();
        $jabat = JabatanModel::where('perusahaan', $comId)
            ->get();
        }

        //Admin kantor
        if(Auth::user()->role == 3){
            $comId = Auth::user()->perusahaan;
            $kanId = Auth::user()->kantor;
            
            $users = User::with('perusa', 'kant', 'jabat', 'sat')
            ->where('perusahaan', $comId)
            ->where('kantor', $kanId)
            ->paginate(15);
        $perusa = PerusahaanModel::where('perusahaan', $comId)
            ->get();
        $kantor = KantorModel::where('perusahaan', $comId)
            ->get();
        $dept = DeptModel::where('perusahaan', $comId)
            ->where('nama_kantor', $kanId)
            ->get();
        $satker = SatkerModel::where('perusahaan', $comId)
            ->where('kantor', $kanId)
            ->get();
        $jabat = JabatanModel::where('perusahaan', $comId)
            ->where('kantor_id', $kanId)
            ->get();
        }

        return view('master.adduser', compact('users', 'perusa', 'kantor', 'satker', 'jabat', 'dept'));
    }

    public function adduser(Request $request)
    { 

      $add =  new User;
        // Superadmin
        if(Auth::user()->role == 0){
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
        if(Auth::user()->role == 1){
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
        if(Auth::user()->role == 3){ //user
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

public function upuser(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $request->user_id,
        'password' => 'nullable|min:6',
        'role' => 'required|integer',
        'company' => 'nullable|integer',
        'office' => 'nullable|integer',
        'dept' => 'nullable|integer',
        'satker' => 'nullable|integer',
        'position' => 'nullable|integer',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
        ]);
    }

    $user = User::find($request->user_id);
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
    }

if(Auth::user()->role == 0){
            if ($request->role == 1) { //pusat
                $perusa = $request->company;
                $kantor = $request->office ?? 0;
                $dept = $request->dept ?? 0;
                $satker = $request->satker ?? 0;
                $jabat = $request->position ?? 0;
            } else if ($request->role == 2){ //user
                $perusa = $request->company;
                $kantor = $request->office;
                $satker = $request->satker;
                $jabat = $request->position;
                $dept = $request->dept;
            } else if ($request->role == 3){ //cabang
                $perusa = $request->company;
                $kantor = $request->office ?? 0;
                $dept = $request->dept ?? 0;
                $satker = $request->satker ?? 0;
                $jabat = $request->position ?? 0;

            } 

            $role = $request->role;

            $user->kantor = $kantor;
            $user->dept = $dept;
            $user->satker = $satker;
            $user->jabatan = $jabat;
            $user->role = $role;
        }

        //Admin Pusat
        if(Auth::user()->role == 1){
            if ($request->role == 3) {//admin cabang
                $perusa = Auth::user()->perusahaan;
                $kantor = $request->office;
                $dept = $request->dept ?? 0;
                $satker = $request->satker ?? 0;
                $jabat = $request->position ?? 0;

if ($request->role == 1 && $user->role == 1) {
    $role = $request->role;
} else if ($request->role == 3 && $user->role == 1) {
    return response()->json(['success' => false, 'message' => 'Tidak dapat merubah akun Admin Pusat menjadi Admin Cabang, hubungi Web Administrator untuk merubah data!']);
} else if ($request->role == 3 && $user->role == 2) {
    $role = $request->role;
} else {
    $role = $request->role;
}

                $user->kantor = $kantor;
                $user->dept = $dept;
                $user->satker = $satker;
                $user->jabatan = $jabat;
                $user->role = $role;
            } else { //user
                $perusa = Auth::user()->perusahaan;
                $satker = $request->satker ?? 0;
                $jabat = $request->position ?? 0;
                $kantor = $request->office ?? 0;
                $dept = $request->dept ?? 0;

if ($request->role == 2 && $user->role == 1) {
    return response()->json(['success' => false, 'message' => 'Tidak dapat merubah akun Admin Pusat menjadi User, hubungi Web Administrator untuk merubah data!']);
} else if ($request->role == 2 && $user->role == 3) {
    $role = $request->role;
} else if ($request->role == 3 && $user->role == 1) {
    return response()->json(['success' => false, 'message' => 'Tidak dapat merubah akun Admin Pusat menjadi Admin Cabang, hubungi Web Administrator untuk merubah data!']);
} else if ($request->role == 1 && $user->role == 2) {
    return response()->json(['success' => false, 'message' => 'Tidak dapat merubah akun User menjadi Admin Pusat, hubungi Web Administrator untuk merubah data!']);
} else if ($request->role == 1 && $user->role == 3) {
    return response()->json(['success' => false, 'message' => 'Tidak dapat merubah akun Admin Cabang menjadi Admin Pusat, hubungi Web Administrator untuk merubah data!']);
} else {
    $role = $request->role;
}
                $user->dept = $dept;
                $user->kantor = $kantor;
                $user->satker = $satker;
                $user->jabatan = $jabat;
                $user->role = $role;
            }
        }

        //Admin Cabang
        if(Auth::user()->role == 3){ //user
            $perusa = Auth::user()->perusahaan;
            $kantor = Auth::user()->kantor;
            $dept = $request->dept ?? 0;
            $satker = $request->satker ?? 0;
            $jabat = $request->position ?? 0;

if ($request->role == 3 && $user->role == 3) {
    $role = $request->role;
} else if ($request->role == 2 && $user->role == 3) {
    return response()->json(['success' => false, 'message' => 'Tidak dapat merubah akun Admin menjadi User, hubungi admin pusat untuk merubah data!']);
} else if ($request->role == 3) {
    return response()->json(['success' => false, 'message' => 'Tidak dapat merubah akun user menjadi admin, hubungi admin pusat untuk merubah data!']);
} else {
    $role = $request->role;
}
            $user->kantor = $kantor;
            $user->dept = $dept;
            $user->satker = $satker;
            $user->jabatan = $jabat;
            $user->role = $role;
        } 

    $user->name = $request->name;
    $user->email = $request->email;
    if ($request->password) {
        $user->password = bcrypt($request->password);
    }

    $user->perusahaan = $perusa;


    $user->save();

    return response()->json(['success' => true]);
}

public function deluser($id)
{
     $user = User::findOrFail($id);
        
        if ($user->delete()) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Gagal menghapus data.']);
   
}

    public function getkonten($companyId)
    {
        if(Auth::user()->role == 0) {
            $offices = KantorModel::where('perusahaan', $companyId)->get();
            $satkers = SatkerModel::where('perusahaan', $companyId)->get();
            $positions = JabatanModel::where('perusahaan', $companyId)->get();
            $depts = DeptModel::where('perusahaan', $companyId)->get();
        }

        if(Auth::user()->role == 1) {
            $offices = KantorModel::where('perusahaan', $companyId)
                ->get();
            $satkers = SatkerModel::where('perusahaan', $companyId)
                ->get();
            $positions = JabatanModel::where('perusahaan', $companyId)
                ->get();
            $depts = DeptModel::where('perusahaan', $companyId)
                ->get();
        }

        if(Auth::user()->role == 3) {
            $offices = KantorModel::where('perusahaan', $companyId)->get();
            $satkers = SatkerModel::where('perusahaan', $companyId)
                ->where('kantor', Auth::user()->kantor)
                ->get();
            $positions = JabatanModel::where('perusahaan', $companyId)
                ->where('kantor_id', Auth::user()->kantor)
                ->get();
            $depts = DeptModel::where('perusahaan', $companyId)
                ->where('nama_kantor', Auth::user()->kantor)
                ->get();
        }

        return response()->json([
            'offices' => $offices,
            'satkers' => $satkers,
            'positions' => $positions,
            'depts' => $depts,
        ]);
    }

    public function getsat($kantId)
    {
        $departemen = DeptModel::where('nama_kantor', $kantId)->get();
        $satker = SatkerModel::where('kantor', $kantId)->get();

        return response()->json([
            'departemen' => $departemen,
            'satker' => $satker
        ]);
    }

    public function bysatker($sat_id)
    {
        $pegawai = PegawaiModel::where('satker', $sat_id)->get();
        return response()->json($pegawai);
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
    $shift = ShiftModel::where('satker_id', $id)->get();

    return response()->json([
        'positions' => $positions,
        'shifts' => $shift,
    ]);
}

    public function dept()
    {   
        if (Auth::user()->role == 0) {
            $dept = DeptModel::paginate(10);
        $kantor = KantorModel::get();
        }

        if (Auth::user()->role == 1) {
            $dept = DeptModel::where('perusahaan', Auth::user()->perusahaan)
            ->paginate(10);
        $kantor = KantorModel::where('perusahaan', Auth::user()->perusahaan)->get();
        }

        if (Auth::user()->role == 3) {
            $dept = DeptModel::where('perusahaan', Auth::user()->perusahaan)
            ->where('nama_kantor', Auth::user()->kantor)
            ->paginate(10);
        $kantor = KantorModel::where('perusahaan', Auth::user()->perusahaan)->get();
        }

        $perusahaan = PerusahaanModel::get();

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

    // DeptModel::where('id', $id)->update($request->only(['perusahaan', 'nama_kantor', 'nama_dept']));
    $dep = DeptModel::findOrFail($id);

    // Update SatkerModel
    SatkerModel::where('dept_id', $dep->id)
        ->where('kantor', $dep->nama_kantor)
        ->update(['kantor' => $kantor]);

    // Update JabatanModel
    JabatanModel::where('dept_id', $dep->id)
        ->where('kantor_id', $dep->nama_kantor)
        ->update(['kantor_id' => $kantor]);

    // Update User Model
    User::where('dept', $dep->id) // Perbaikan dari 'dept' menjadi 'dept_id'
        ->where('kantor', $dep->nama_kantor)
        ->update(['kantor' => $kantor]);

    // Update DeptModel
    $dep->perusahaan = $perusahaan;
    $dep->nama_kantor = $kantor;
    $dep->nama_dept = $dept;
    $dep->save(); // Simpan perubahan

    return response()->json(['success' => true]);
    }

    public function deptroy($id)
    {
        $departemen = DeptModel::findOrFail($id);

        $deptBaru = 0;
        User::where('dept', $id)->update(['dept' => $deptBaru]);
        JabatanModel::where('dept_id', $id)->update(['dept_id' => $deptBaru]);
        SatkerModel::where('dept_id', $id)->update(['dept_id' => $deptBaru]);

        $departemen->delete();

        return response()->json(['success' => true]);
    }

    public function shift()
    {      
        if(Auth::user()->role == 0){
            $satker = SatkerModel::get();
            $shift = ShiftModel::paginate(15);
            $kantor = KantorModel::get();
        return view('master.shift', compact('satker', 'shift', 'kantor'));
        } elseif(Auth::user()->role == 1){
            $satker = SatkerModel::where('perusahaan', Auth::user()->perusahaan)->get();
            $kantor = KantorModel::where('perusahaan', Auth::user()->perusahaan)->get();
            $shift = ShiftModel::where('perusahaan', Auth::user()->perusahaan)->paginate(15);

        return view('master.shift', compact('satker', 'shift', 'kantor'));
        } elseif(Auth::user()->role == 3) {
            $satker = SatkerModel::where('perusahaan', Auth::user()->perusahaan)
            ->where('kantor', Auth::user()->kantor)
            ->get();

            $shift = ShiftModel::where('kantor_id', Auth::user()->kantor)
                    ->where('perusahaan', Auth::user()->perusahaan)
                    ->paginate(15);

        return view('master.shift', compact('satker', 'shift'));
        }

        
    }

    public function shiftStore(Request $request)
    {

    if (Auth::user()->role == 3) {
        $kantor = Auth::user()->kantor;

        $validator = Validator::make($request->all(), [
            'shift' => 'required|string|max:100',
            'satker_id' => 'required|exists:satker,id',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i',
        ]);
    } else {
        $kantor = $request->kantor_id;

            $validator = Validator::make($request->all(), [
            'shift' => 'required|string|max:100',
            'kantor_id' => 'required|exists:kantor,id',
            'satker_id' => 'required|exists:satker,id',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i',
        ]);
    }
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

$jamMasuk = Carbon::createFromFormat('H:i', $request->jam_masuk);
$jamKeluar = Carbon::createFromFormat('H:i', $request->jam_keluar);

// Jika jam_keluar lebih kecil dari jam_masuk, anggap lewat tengah malam
if ($jamKeluar->lessThanOrEqualTo($jamMasuk)) {
    $jamKeluar->addDay(); // Tambah 1 hari
}
$perusa = Auth::user()->perusahaan;
        ShiftModel::create([
            'perusahaan' => $perusa,
            'shift' => $request->shift,
            'kantor_id' => $kantor, // pastikan kolomnya benar
            'satker_id' => $request->satker_id, // pastikan kolomnya benar
            'jam_masuk' => $jamMasuk->format('H:i'),
            'jam_keluar' => $jamKeluar->format('H:i'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Shift berhasil ditambahkan.',
        ]);
    }

    public function shiftUpdate(Request $request, $id)
    {

    $shift = ShiftModel::findOrFail($id);
        
        if (Auth::user()->role == 3) {
        $kantor = Auth::user()->kantor;

        $validator = Validator::make($request->all(), [
            'shift' => 'required|string|max:100',
            'satker_id' => 'required|exists:satker,id',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }
    
        $shift->update([
            'shift' => $request->shift,
            'kantor_id' => $kantor, // override di sini
            'satker_id' => $request->satker_id,
            'jam_masuk' => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar,
        ]);
    } else {

            $validator = Validator::make($request->all(), [
            'shift' => 'required|string|max:100',
            'kantor_id' => 'required|exists:kantor,id',
            'satker_id' => 'required|exists:satker,id',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

    $shift->update($request->only(['shift', 'kantor_id', 'satker_id', 'jam_masuk', 'jam_keluar']));
    }

    return response()->json(['message' => 'Shift berhasil diperbarui.']);
    }
    
    public function shiftdest($id)
    {
        $shift = ShiftModel::findOrFail($id);
        $shift->delete();

        return redirect()->back()->with('success', 'Shift berhasil dihapus.');
    }
    
    public function pengecualianIndex(Request $request)
    {
        $user = Auth::user();
    
        $query = PengecualianAbsen::with('karyawan')
            ->where('perusahaan', $user->perusahaan);
    
        // 🔥 FILTER KANTOR
        if ($request->kantor) {
            $query->where('nama_kantor', $request->kantor);
        } else {
            // default role 3 tetap dibatasi kantor sendiri
            if ($user->role == 3) {
                $query->where('nama_kantor', $user->kantor);
            }
        }
    
        // 🔥 SEARCH NAMA / NIP
        if ($request->search) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%');
            });
        }
    
        $data = $query->paginate(15)->appends($request->all());
    
        // ambil list kantor
    $kantor = \App\Models\KantorModel::whereIn('id', function ($query) {
            $query->select('nama_kantor')
                  ->from('pengecualian_absen')
                  ->distinct();
        })
        ->where('perusahaan', $user->perusahaan)
        ->get();
    
        return view('master.pengecualian', compact('data', 'kantor'));
    }
    
    public function pengecualianStore(Request $request)
{
    $request->validate([
        'karyawan_id' => 'required'
        // 🔥 tanggal tidak wajib lagi
    ]);

    $user = Auth::user();

    // ambil karyawan
    $karyawan = PegawaiModel::where('id', $request->karyawan_id)
        ->where('perusahaan', $user->perusahaan)
        ->first();

    if (!$karyawan) {
        return back()->with('error', 'Data karyawan tidak ditemukan!');
    }

    // =========================
    // 🔥 PARSING TANGGAL
    // =========================
    if (is_array($request->tanggal)) {
        $tanggalList = array_filter($request->tanggal);
    } else {
        $tanggalList = array_filter(
            array_map('trim', explode(',', $request->tanggal ?? ''))
        );
    }

    // 👉 kalau kosong → jadikan null
    $tanggalList = !empty($tanggalList) ? array_values($tanggalList) : null;

    // =========================
    // 🔥 AMBIL DATA EXISTING
    // =========================
    $existing = PengecualianAbsen::where('karyawan_id', $karyawan->id)->first();

    $existingTanggal = $existing ? ($existing->tanggal ?? []) : [];

    // =========================
    // 🔥 CEK DUPLIKAT (HANYA JIKA ADA TANGGAL)
    // =========================
    if ($tanggalList && $existingTanggal) {
        $duplikat = array_intersect($existingTanggal, $tanggalList);

        if (count($duplikat)) {
            return back()->with('error', 'Tanggal sudah ada: ' . implode(', ', $duplikat));
        }
    }

    // =========================
    // 💾 SIMPAN DATA
    // =========================
    if ($existing) {

        if ($tanggalList) {
            $merged = array_unique(array_merge($existingTanggal, $tanggalList));
        } else {
            // 👉 kalau kosong, tetap simpan null
            $merged = null;
        }

        $existing->update([
            'tanggal' => $merged,
            'keterangan' => $request->keterangan
        ]);

    } else {

        PengecualianAbsen::create([
            'karyawan_id' => $karyawan->id,
            'perusahaan' => $karyawan->perusahaan,
            'nama_kantor' => $karyawan->nama_kantor,
            'keterangan' => $request->keterangan,
            'tanggal' => $tanggalList // bisa null
        ]);
    }

    return redirect()
        ->back()
        ->with('success', 'Data berhasil disimpan')
        ->with('open_modal_tambah', true);
}
    
    public function pengecualianDelete($id)
    {
        $data = PengecualianAbsen::findOrFail($id);
    
        // optional: keamanan (biar tidak bisa hapus data kantor lain)
        if ($data->perusahaan != Auth::user()->perusahaan) {
            abort(403);
        }
    
        $data->delete();
    
        return back()->with('success', 'Data berhasil dihapus');
    }
    
    
    public function getKaryawan(Request $request)
    {
        $user = Auth::user();
        $search = $request->q;
        $kantor = $request->kantor;
    
        $data = PegawaiModel::where('perusahaan', $user->perusahaan)
    
            // 🔥 FILTER KANTOR
            ->when($kantor, function ($query) use ($kantor) {
                $query->where('nama_kantor', $kantor);
            }, function ($query) use ($user) {
                // default kalau tidak pilih kantor
                if ($user->role == 3) {
                    $query->where('nama_kantor', $user->kantor);
                }
            })
    
            // 🔥 SEARCH
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%$search%")
                      ->orWhere('nip', 'like', "%$search%")
                      ->orWhere('id', 'like', "%$search%");
                });
            })
    
            ->limit(20)
            ->get();
    
        return response()->json(
            $data->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->nama_lengkap . ' - ' . $item->nip
                ];
            })
        );
    }
    
public function pengecualianUpdate(Request $request, $id)
{
    $request->validate([
        // 🔥 tanggal tidak wajib lagi
    ]);

    $data = PengecualianAbsen::findOrFail($id);

    // =========================
    // 🔥 PARSING TANGGAL
    // =========================
    if (is_array($request->tanggal)) {

        $tanggalList = [];

        foreach ($request->tanggal as $item) {
            if ($item) {
                $tanggalList = array_merge(
                    $tanggalList,
                    array_map('trim', explode(',', $item))
                );
            }
        }

    } else {

        $tanggalList = array_map(
            'trim',
            explode(',', $request->tanggal ?? '')
        );
    }

    // 🔥 buang kosong
    $tanggalList = array_values(array_filter($tanggalList));

    // 👉 kalau kosong → jadikan null
    $tanggalList = !empty($tanggalList) ? $tanggalList : null;

    // =========================
    // 💾 UPDATE DATA
    // =========================
    $data->update([
        'keterangan' => $request->keterangan,
        'tanggal'    => $tanggalList // bisa array / null
    ]);

    return back()->with('success', 'Data berhasil diupdate');
}
    
public function reguStore(Request $request)
{
    $request->validate([
        'nama_regu'     => 'required',
        'pegawai_id'    => 'nullable|array'
    ]);

    $regu = ReguModel::create([
        'nama_regu'    => $request->nama_regu,
        'perusahaan'   => Auth::user()->perusahaan,
        'supervisor_id'=> null,
        'danru_id'     => null // 🔥 tambahan
    ]);

    if (!empty($request->pegawai_id)) {
        foreach ($request->pegawai_id as $pegawai) {
            ReguAnggotaModel::create([
                'regu_id' => $regu->id,
                'pegawai_id' => $pegawai
            ]);
        }
    }

    return back()->with('success', 'Regu berhasil dibuat');
}

public function reguIndex(Request $request)
{
    $user = Auth::user();
    $search = $request->search;

    $regu = ReguModel::with('anggota.pegawai.kantor', 'anggota.pegawai.jabat', 'supervisor', 'danru')
        ->when($user->role == 1, function ($q) use ($user) {
            $q->where('perusahaan', $user->perusahaan);
        })

        // 🔥 SEARCH DATABASE
        ->when($search, function ($q) use ($search) {
            $q->where(function ($q2) use ($search) {
                $q2->where('nama_regu', 'like', "%$search%")
                   ->orWhereHas('danru', function ($q3) use ($search) {
                       $q3->where('nama_lengkap', 'like', "%$search%");
                   })
                   ->orWhereHas('supervisor', function ($q3) use ($search) {
                       $q3->where('nama_lengkap', 'like', "%$search%");
                   })
                   ->orWhereHas('anggota.pegawai', function ($q3) use ($search) {
                       $q3->where('nama_lengkap', 'like', "%$search%")
                          ->orWhere('nip', 'like', "%$search%");
                   });
            });
        })

        ->latest()
        ->paginate(10)
        ->withQueryString(); // 🔥 penting biar search tetap di pagination

    $regu->getCollection()->transform(function ($r) {
        $r->anggota = $r->anggota
            ->sortBy(function ($a) {
                $kantor = strtolower($a->pegawai->kantor->nama_kantor ?? '');
                $nama   = strtolower($a->pegawai->nama_lengkap ?? '');
                return $kantor . '|' . $nama;
            })
            ->values();

        return $r;
    });

    $listKantor = KantorModel::where('perusahaan', $user->perusahaan)->get();
    $pegawai = PegawaiModel::with('kantor')
        ->where('perusahaan', $user->perusahaan)
        ->orderBy('nama_lengkap')
        ->get();
    $pegawaiSudahMasukRegu = ReguAnggotaModel::pluck('pegawai_id')->toArray();

    return view('master.regu', compact('regu', 'pegawai', 'listKantor', 'search', 'pegawaiSudahMasukRegu'));
}

public function tambahAnggota(Request $request, $id)
{
    $request->validate([
        'pegawai_id' => 'required|array'
    ]);

    $existing = ReguAnggotaModel::where('regu_id', $id)
        ->pluck('pegawai_id')
        ->toArray();

    $dataInsert = [];

    foreach ($request->pegawai_id as $pegawaiId) {
        if (!in_array($pegawaiId, $existing)) {
            $dataInsert[] = [
                'regu_id' => $id,
                'pegawai_id' => $pegawaiId,
                'created_at' => now(), // Tambahkan ini
                'updated_at' => now(), // Tambahkan ini
            ];
        }
    }

    if (!empty($dataInsert)) {
        ReguAnggotaModel::insert($dataInsert);
    }

    return back()->with('success', 'Anggota berhasil ditambahkan');
}

public function reguDestroy($id)
{
    ReguAnggotaModel::where('regu_id', $id)->delete();
    ReguModel::destroy($id);

    return back()->with('success', 'Regu berhasil dihapus');
}

public function destroyAnggotaRegu($id)
{
    $anggota = ReguAnggotaModel::find($id);

    if ($anggota) {

        // 🔥 cek di tabel regu
        ReguModel::where('id', $anggota->regu_id)
            ->where('danru_id', $anggota->pegawai_id)
            ->update(['danru_id' => null]);

        // 🔥 optional (kalau mau sekalian supervisor)
        ReguModel::where('id', $anggota->regu_id)
            ->where('supervisor_id', $anggota->pegawai_id)
            ->update(['supervisor_id' => null]);

        // 🔥 hapus anggota
        ReguAnggotaModel::destroy($id);
    }

    return back()->with('success', 'Anggota dihapus dari regu');
}

public function setSupervisor(Request $request, $id)
{
    $request->validate([
        'supervisor_id' => 'required|exists:karyawan,id'
    ]);

    $regu = ReguModel::findOrFail($id);

    $regu->update([
        'supervisor_id' => $request->supervisor_id
    ]);

    return back()->with('success', 'Supervisor berhasil di-assign');
}

public function assignDanru(Request $request, $id)
{
    $request->validate([
        'danru_id' => 'required|exists:karyawan,id'
    ]);

    $regu = ReguModel::findOrFail($id);

    // 🔥 CEK: danru sudah dipakai di regu lain?
    $sudahDipakai = ReguModel::where('danru_id', $request->danru_id)
        ->where('id', '!=', $id)
        ->exists();

    if ($sudahDipakai) {
        return back()->with('error', 'Danru sudah digunakan di regu lain!');
    }

    // ================= HAPUS DANRU LAMA DARI ANGGOTA =================
    if ($regu->danru_id) {
        ReguAnggotaModel::where('regu_id', $regu->id)
            ->where('pegawai_id', $regu->danru_id)
            ->delete();
    }

    // ================= UPDATE DANRU =================
    $regu->update([
        'danru_id' => $request->danru_id
    ]);

    // ================= MASUKKAN DANRU BARU KE ANGGOTA =================
    ReguAnggotaModel::firstOrCreate([
        'regu_id' => $regu->id,
        'pegawai_id' => $request->danru_id
    ]);

    return back()->with('success', 'Danru berhasil di-assign');
}

    public function moveAnggota(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required',
            'regu_id' => 'required'
        ]);

        $anggota = ReguAnggotaModel::where('pegawai_id', $request->pegawai_id)->first();

        if ($anggota) {

            // 🔥 AMBIL REGU LAMA
            $reguLama = ReguModel::find($anggota->regu_id);

            // ================== INI POSISI YANG BENAR ==================
            if ($reguLama && $reguLama->danru_id == $request->pegawai_id) {
                $reguLama->update([
                    'danru_id' => null
                ]);
            }
            // ===========================================================

            // pindahkan anggota ke regu baru
            $anggota->update([
                'regu_id' => $request->regu_id
            ]);

        } else {

            ReguAnggotaModel::create([
                'pegawai_id' => $request->pegawai_id,
                'regu_id' => $request->regu_id
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Anggota berhasil dipindahkan'
        ]);
    }
}
