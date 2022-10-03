<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CE;
use App\Models\CPL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CPLController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Session::has('data')) {
            $appdata = [
                'title' => 'CPL',
                'sesi'  => Session::get('data')
            ];
            $cpl = CPL::where(['idprodi' => $appdata['sesi']['idprodi'], 'idfakultas' => $appdata['sesi']['idfakultas']])->orderByRaw('CAST(SUBSTRING(kode_cpl,5,2) AS INT)', 'asc')->get();
            return view('admin.cpl_index', compact('appdata', 'cpl'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function store(Request $request)
    {
        if (Session::has('data')) {
            $sesi = Session::get('data');
            $data = [
                'kode_cpl'  => 'CPL-' . $request->kode_cpl,
                'nama_cpl'  => $request->nama_cpl,
                'idprodi'   => $sesi['idprodi'],
                'idfakultas' => $sesi['idfakultas']
            ];
            $query = CPL::insert($data);
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
                'kode_cpl'  => 'CPL-' . $request->kode_cpl,
                'nama_cpl'  => $request->nama_cpl,
                'idprodi'   => $sesi['idprodi'],
                'idfakultas' => $sesi['idfakultas']
            ];
            $query = CPL::where('id', $id)->update($data);
            if ($query) {
                return redirect()->back()->with('success', 'Success update');
            } else {
                return redirect()->back()->with('error', 'Something wrong !');
            }
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function delete($id)
    {
        if (Session::has('data')) {
            $query = CPL::where('id', $id)->delete();
            if ($query) {
                return redirect()->back()->with('success', 'Success delete');
            } else {
                return redirect()->back()->with('error', 'Something wrong !');
            }
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function cpl_mahasiswa(Request $request, $datamhs)
    {
        if (Session::has('data')) {
            $datamhs = explode('|', decrypt($datamhs));
            $appdata = [
                'title' => 'Matriks Course Evaluation',
                'sesi'  => Session::get('data')
            ];

            $res = Http::post(config('app.urlApi') . 'mahasiswa/matkul-mhs', [
                'APIKEY'    => config('app.APIKEY'),
                'nrp'       => $datamhs[0],
            ]);
            $json = $res->json();
            $nilaimhs = $json['data'];

            $data = [
                'mhs'   => $datamhs,
                'cpl'   => CPL::where([
                    'idprodi' => $appdata['sesi']['idprodi'], 'idfakultas' => $appdata['sesi']['idfakultas']
                ])->orderByRaw('CAST(SUBSTRING(kode_cpl,5,2) AS INT)', 'asc')->get(),
                'ce'    => CE::select('ce_mk.*', 'ce_mk.id as idce', 'cpmk.id as cpmk_id', 'cpmk.idmatakuliah', 'cpmk.nama_matkul', 'cpmk.kode_cpmk', 'cpmk.sks')->join('cpmk', 'cpmk.id', '=', 'ce_mk.idcpmk')->where(
                    [
                        'idprodi' => $appdata['sesi']['idprodi'],
                        'idfakultas' => $appdata['sesi']['idfakultas']
                    ]
                )->orderBy('cpmk.idmatakuliah', 'asc')->orderByRaw('CAST(SUBSTRING(cpmk.kode_cpmk,6,2) AS INT)', 'asc')
                    ->get(),
                'nilai' => $nilaimhs
            ];
            return view('admin.mahasiswa_cpl', compact('data', 'appdata'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }
}
