<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CE;
use App\Models\CPL;
use App\Models\CPMK;
use App\Models\SubCPMK;
use App\Models\Bobot;
use App\Models\BobotMK;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\DataTables;

class BobotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function bobot($datamk)
    {
        $datamk = explode('|', decrypt($datamk));
        // dd($datamk);
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Kelola Bobot',
                'sesi'  => Session::get('data'),
            ];
            $data['subcpmk'] = SubCPMK::where([
                'idprodi' => $appdata['sesi']['idprodi'],
                'idmatakuliah' => $datamk[0]
            ])->get();

            $data['cpmk'] = CPMK::where([
                'idprodi' => $appdata['sesi']['idprodi'],
                'idmatakuliah' => $datamk[0]
            ])->orderBy('id', 'desc')->get();

            $data['bobot'] = Bobot::where([
                'idprodi' => $appdata['sesi']['idprodi'],
                'idmatakuliah' => $datamk[0]
            ])->get();

            $bobot = array();
            foreach ($data['bobot'] as $b) {
                $bobot[$b->id_cpmk][$b->id_subcpmk] = $b->bobot;
            }
            $bobotsubcpmk = Bobot::selectRaw('sum(bobot) as totalbobot,bobot,count(DISTINCT subcpmk_kode) as totalsub')->where([
                'idprodi' => $appdata['sesi']['idprodi'], 'idmatakuliah' => $datamk[0]
            ])->groupby('idprodi')->first();
            // dd($bobotsubcpmk);

            $cpl_mk = BobotMK::with(['cpl'])->where(['idprodi' => $appdata['sesi']['idprodi'], 'idfakultas' => $appdata['sesi']['idfakultas'], 'idmatakuliah' => $datamk[0]])
                ->where('bobot_mk', '!=', '0')->get();
            // dd($cpl_mk);
            
            // dd($bobot);
            return view('admin.bobot', compact('appdata', 'data', 'datamk', 'bobot','bobotsubcpmk','cpl_mk'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function store(Request $request)
    {
        if (Session::has('data')) {
            $sesi = Session::get('data');
            // dd($sesi);
            foreach ($request->data as $key) {
                // dd($key['id_kompetensi']);
                $data['bobot'] = $key['bobot'] ?? 0;
                $data['kode_cpmk'] = $key['kode_cpmk'];
                $data['subcpmk_kode'] = $key['subcpmk_kode'];
                $data['idmatakuliah'] = $key['idmatakuliah'];
                $data['idprodi'] = $sesi['idprodi'];
                $data['idfakultas'] = $sesi['idfakultas'];
                $data['id_cpmk'] = $key['id_cpmk'];
                $data['id_subcpmk'] = $key['id_subcpmk'];

                if (Bobot::where('idmatakuliah', $key['idmatakuliah'])->where('idprodi', $sesi['idprodi'])->where('idfakultas', $sesi['idfakultas'])->where('kode_cpmk',  $key['kode_cpmk'])->where('subcpmk_kode',  $key['subcpmk_kode'])->first()) {
                    Bobot::where('idmatakuliah', $key['idmatakuliah'])->where('idprodi', $sesi['idprodi'])->where('idfakultas', $sesi['idfakultas'])->where('kode_cpmk',  $key['kode_cpmk'])->where('subcpmk_kode',  $key['subcpmk_kode'])->update($data);
                } else {
                    Bobot::create($data);
                }
            }
            return response()->json(['success' => 'Berhasil']);
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }
}
