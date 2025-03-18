<?php

namespace App\Http\Controllers;

use App\Models\JabatanModel;
use App\Models\KantorModel;
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

    public function destroy($id)
    {
        $tenant = PerusahaanModel::findOrFail($id);
        $tenant->delete();

        return redirect()->back()->with('status', 'Tenant berhasil dihapus!');
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
        return view('master.kantoredit', compact('kantor'));
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
        $kantor->save();

        return redirect()->route('kantor')->with('status', 'Data kantor berhasil diperbarui.');
    }
    
    public function satker()
    {
        if(Auth::user()->role === 0){
            $perusahaan = PerusahaanModel::get();
            $satker = SatkerModel::paginate(15);

        return view('master.satker', compact('satker', 'perusahaan'));
        }

        if(Auth::user()->role === 3){
            $perusahaan = PerusahaanModel::get();
            $satker = SatkerModel::where('perusahaan', Auth::user()->perusahaan)
            ->paginate(15);

        return view('master.satker', compact('satker', 'perusahaan'));
        }

        if(Auth::user()->role === 1){
           $satker = SatkerModel::where('perusahaan', Auth::user()->perusahaan)
           ->paginate(15);

        return view('master.satker', compact('satker'));
        }

        
    }

    public function tambahsatker(Request $request)
    {

        if(Auth::user()->role === 0){
            $perusa = $request->perusahaan;
        } else {
            $perusa = Auth::user()->perusahaan;
        }

        $satker = new SatkerModel;

        $satker->perusahaan = $perusa;
        $satker->satuan_kerja = $request->satker;

        $satker->save();

        return back()
        ->with('status', 'berhasil');
    }

    public function jabatan()
    {
        if(Auth::user()->role === 0){
            $perusahaan = PerusahaanModel::get();
            $jabatan = JabatanModel::paginate(15);

        return view('master.jabatan', compact('jabatan', 'perusahaan'));
        } 

        if(Auth::user()->role === 3){
           $jabatan = JabatanModel::where('perusahaan', Auth::user()->perusahaan)
           ->paginate(15);

        return view('master.jabatan', compact('jabatan'));
        } 
        
        if(Auth::user()->role === 1){
           $jabatan = JabatanModel::where('perusahaan', Auth::user()->perusahaan)
           ->paginate(15);

        return view('master.jabatan', compact('jabatan'));
        }
    }

    public function tambahjabatan(Request $request)
    {
        if(Auth::user()->role === 0){
            $perusa = $request->usaha;
        } else {
            $perusa = Auth::user()->perusahaan;
        }

        $jabatan = new JabatanModel;

        $jabatan->perusahaan = $perusa;
        $jabatan->jabatan = $request->jabatan;
         $jabatan->save();

        return back()
        ->with('status', 'berhasil');
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

                $add->kantor = $kantor;
                $add->satker = $satker;
                $add->jabatan = $jabat;
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
        return response()->json([
            'offices' => $offices,
            'satkers' => $satkers,
            'positions' => $positions,
        ]);
    }
}
