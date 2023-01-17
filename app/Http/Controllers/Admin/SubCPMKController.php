<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CPMK;
use App\Models\SubCPMK;
use Session;
use Illuminate\Http\Request;

class SubCPMKController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($idcpmk)
    {
        if (Session::has('data')) {
            $idcpmk = decrypt($idcpmk);
            $appdata = [
                'title' => 'Data IK',
                'sesi'  => Session::get('data')
            ];
            $data['subcpmk'] = SubCPMK::where([
                'subcpmk_id_prodi'    => $appdata['sesi']['idprodi'],
                'subcpmk_id_fakultas' => $appdata['sesi']['idfakultas'],
                'subcpmk_id_cpmk'      => $idcpmk
            ])->orderByRaw('CAST(SUBSTRING(subcpmk_kode,9,3) AS INT)', 'asc')->get();
            $data['cpmk'] = CPMK::where('id', $idcpmk)->first();
            return view('admin.subcpmk_index', compact('appdata', 'data'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Session::has('data')) {
            $sesi = Session::get('data');
            $datacpmk = decrypt($request->datacpmk);
            $data = [
                'subcpmk_kode'  => 'SubCPMK-' . $request->subcpmk_kode,
                'subcpmk_nama_id'  => $request->subcpmk_nama_id,
                'subcpmk_nama_en'  => $request->subcpmk_nama_en,
                'subcpmk_bobot'  => $request->subcpmk_bobot,
                'subcpmk_id_cpmk'  => $datacpmk->id,
                'subcpmk_id_matkul'  => $datacpmk->idmatakuliah,
                'subcpmk_nama_matkul'  => $datacpmk->nama_matkul,
                'subcpmk_nama_matkul_en'  => $datacpmk->nama_matkul_en,
                'subcpmk_sks_matkul'  => $datacpmk->sks,
                'subcpmk_id_prodi'   => $sesi['idprodi'],
                'subcpmk_id_fakultas' => $sesi['idfakultas']
            ];
            $query = SubCPMK::insert($data);
            if ($query) {
                return redirect()->back()->with('success', 'Success add');
            } else {
                return redirect()->back()->with('error', 'Something wrong !');
            }
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Session::has('data')) {
            $sesi = Session::get('data');
            $data = [
                'subcpmk_kode'  => 'SubCPMK-' . $request->subcpmk_kode,
                'subcpmk_nama_id'  => $request->subcpmk_nama_id,
                'subcpmk_nama_en'  => $request->subcpmk_nama_en,
                'subcpmk_bobot'  => $request->subcpmk_bobot,
                'subcpmk_id_prodi'   => $sesi['idprodi'],
                'subcpmk_id_fakultas' => $sesi['idfakultas']
            ];
            $query = SubCPMK::where('subcpmk_id', $id)->update($data);
            if ($query) {
                return redirect()->back()->with('success', 'Success update');
            } else {
                return redirect()->back()->with('error', 'Something wrong !');
            }
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (Session::has('data')) {
            $query = SubCPMK::where('subcpmk_id', $id)->delete();
            if ($query) {
                return redirect()->back()->with('success', 'Success delete');
            } else {
                return redirect()->back()->with('error', 'Something wrong !');
            }
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }
}
