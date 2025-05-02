<?php

namespace App\Http\Controllers;

use App\Models\DeptModel;
use App\Models\JabatanModel;
use App\Models\KantorModel;
use App\Models\PegawaiModel;
use App\Models\PerusahaanModel;
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
        $shift = ShiftModel::paginate(10);
        
        if(Auth::user()->role == 0){
            $satker = SatkerModel::get();
        $shift = ShiftModel::paginate(10);
        $kantor = KantorModel::get();
        return view('master.shift', compact('satker', 'shift', 'kantor'));
        } elseif(Auth::user()->role == 1){
            $satker = SatkerModel::where('perusahaan', Auth::user()->perusahaan)->get();
            $kantor = KantorModel::where('perusahaan', Auth::user()->perusahaan)->get();
        $shift = ShiftModel::paginate(10);
        return view('master.shift', compact('satker', 'shift', 'kantor'));
        } elseif(Auth::user()->role == 3) {
            $satker = SatkerModel::where('perusahaan', Auth::user()->perusahaan)
            ->where('kantor', Auth::user()->kantor)
            ->get();
        $shift = ShiftModel::where('kantor_id', Auth::user()->kantor)->paginate(10);
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

        ShiftModel::create([
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

    
}
