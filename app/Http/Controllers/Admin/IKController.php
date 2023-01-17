<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CPL;
use App\Models\IK;
use Illuminate\Http\Request;
use Session;

class IKController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($idcpl)
    {
        if (Session::has('data')) {
            $idcpl = decrypt($idcpl);
            $appdata = [
                'title' => 'Data IK',
                'sesi'  => Session::get('data')
            ];
            $data['ik'] = IK::where([
                'ik_id_prodi'   => $appdata['sesi']['idprodi'],
                'ik_id_fakultas' => $appdata['sesi']['idfakultas'],
                'ik_id_cpl'     => $idcpl
            ])->orderByRaw('CAST(SUBSTRING(ik_kode,4,3) AS INT)', 'asc')->get();
            $data['cpl'] = CPL::where('id', $idcpl)->first();
            // dd($data[0]->cpl->nama_cpl);
            return view('admin.ik_index', compact('appdata', 'data'));
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
            $idcpl = decrypt($request->datacpl);
            $data = [
                'ik_kode'  => 'IK-' . $request->ik_kode,
                'ik_nama_id'  => $request->ik_nama_id,
                'ik_nama_en'  => $request->ik_nama_en,
                'ik_id_cpl'  => $idcpl,
                'ik_id_prodi'   => $sesi['idprodi'],
                'ik_id_fakultas' => $sesi['idfakultas']
            ];
            $query = IK::insert($data);
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
                'ik_kode'  => 'IK-' . $request->ik_kode,
                'ik_nama_id'  => $request->ik_nama_id,
                'ik_nama_en'  => $request->ik_nama_en,
                'ik_id_prodi'   => $sesi['idprodi'],
                'ik_id_fakultas' => $sesi['idfakultas']
            ];
            $query = IK::where('ik_id', $id)->update($data);
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
            $query = IK::where('ik_id', $id)->delete();
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
