<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CE;
use App\Models\CPL;
use App\Models\CPMK;
use App\Models\SubCPMK;
use App\Models\Bobot;
use App\Models\BobotMK;
use App\Models\MKPilihan;
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
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Kelola Bobot',
                'sesi'  => Session::get('data'),
            ];
            $data['subcpmk'] = SubCPMK::where([
                'idprodi' => $appdata['sesi']['idprodi'],
                'idmatakuliah' => $datamk[0],
                'semester'     => $datamk[4]
            ])->get();

            $data['cpmk'] = CPMK::where([
                'idprodi' => $appdata['sesi']['idprodi'],
                'idmatakuliah' => $datamk[0],
                'semester'     => $datamk[4]
            ])->orderBy('id', 'desc')->get();

            $data['bobot'] = Bobot::where([
                'idprodi' => $appdata['sesi']['idprodi'],
                'idmatakuliah' => $datamk[0],
                'semester'     => $datamk[4]
            ])->get();

            $bobot = array();
            foreach ($data['bobot'] as $b) {
                $bobot[$b->id_cpmk][$b->id_subcpmk] = $b->bobot;
            }

            $bobotsubcpmk = Bobot::selectRaw('sum(bobot) as totalbobot,bobot')->where([
                'idprodi' => $appdata['sesi']['idprodi'], 'idmatakuliah' => $datamk[0]
            ])->groupby('idprodi')->first();
            // dd($bobotsubcpmk);

            // $cpl_mk = BobotMK::with(['cpl'])->where(['idprodi' => $appdata['sesi']['idprodi'], 'idfakultas' => $appdata['sesi']['idfakultas'], 'idmatakuliah' => $datamk[0]])
            //     ->where('bobot_mk', '!=', '0')->get();
            // // dd($cpl_mk);

            $mkpilihan = MKPilihan::where(['idprodi' => $appdata['sesi']['idprodi'], 'idfakultas' => $appdata['sesi']['idfakultas'], 'kode_mk' => $datamk[0]])->first();
            if ($mkpilihan) {
                $idmatakuliah = $mkpilihan->kategori;
            } else {
                $idmatakuliah = $datamk[0];
            }

            $cpl_mk = BobotMK::join('cpl', 'bobot_mk.id_cpl', '=', 'cpl.kode_cpl')->where(['cpl.idprodi' => $appdata['sesi']['idprodi'], 'cpl.idfakultas' => $appdata['sesi']['idfakultas'], 'idmatakuliah' => $idmatakuliah, 'bobot_mk.idprodi' => $appdata['sesi']['idprodi'], 'bobot_mk.idfakultas' => $appdata['sesi']['idfakultas']])->where('bobot_mk', '!=', '0')->get();

            // $bobotnya = getNilaiBobotSC($datamk[0]);
            // dd($bobotnya);
            // dd($bobot);
            return view('admin.bobot', compact('appdata', 'data', 'datamk', 'bobot', 'bobotsubcpmk', 'cpl_mk'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function store(Request $request)
    {
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Store Bobot',
                'sesi'  => Session::get('data'),
            ];
            // dd($appdata['sesi']['idprodi']);
            $datamk = $request->data;
            foreach ($datamk as $key) {
                $data['bobot'] = $key['bobot'] ?? 0;
                $data['kode_cpmk'] = $key['kode_cpmk'];
                $data['subcpmk_kode'] = $key['subcpmk_kode'];
                $data['idmatakuliah'] = $key['idmatakuliah'];
                $data['idprodi'] = $appdata['sesi']['idprodi'];
                $data['idfakultas'] = $appdata['sesi']['idfakultas'];
                $data['id_cpmk'] = $key['id_cpmk'];
                $data['id_subcpmk'] = $key['id_subcpmk'];
                $data['semester'] = $key['semester'];

                if (Bobot::where('idmatakuliah', $key['idmatakuliah'])->where('idprodi', $appdata['sesi']['idprodi'])->where('idfakultas', $appdata['sesi']['idfakultas'])->where('kode_cpmk',  $key['kode_cpmk'])->where('subcpmk_kode',  $key['subcpmk_kode'])->first()) {
                    Bobot::where('idmatakuliah', $key['idmatakuliah'])->where('idprodi', $appdata['sesi']['idprodi'])->where('idfakultas', $appdata['sesi']['idfakultas'])->where('kode_cpmk',  $key['kode_cpmk'])->where('subcpmk_kode',  $key['subcpmk_kode'])->update($data);
                } else {
                    Bobot::create($data);
                }
            }

            $datasc = $request->datasc;
            // dd($datamk[0]['idmatakuliah']);
            $sc1 = array_key_exists(0, $datasc) ? $datasc[0]['bobotsc'] : '0';
            $sc2 = array_key_exists(1, $datasc) ? $datasc[1]['bobotsc'] : '0';
            $sc3 = array_key_exists(2, $datasc) ? $datasc[2]['bobotsc'] : '0';
            $sc4 = array_key_exists(3, $datasc) ? $datasc[3]['bobotsc'] : '0';
            $sc5 = array_key_exists(4, $datasc) ? $datasc[4]['bobotsc'] : '0';
            $sc6 = array_key_exists(5, $datasc) ? $datasc[5]['bobotsc'] : '0';
            $sc7 = array_key_exists(6, $datasc) ? $datasc[6]['bobotsc'] : '0';
            $sc8 = array_key_exists(7, $datasc) ? $datasc[7]['bobotsc'] : '0';

            // $res = Http::post(config('app.urlApi') . 'dosen/updateBobot', [
            //     'APIKEY'    => config('app.APIKEY'),
            //     'kodemk'    => $datamk[0]['idmatakuliah'],
            //     'semester'  => $datasc[0]['semester'],
            //     'useid'     => Session::get('data')['nodosMSDOS'],
            //     'tgtup'     => date('Y-m-d'),
            //     'jatup'     => date("H:i:s"),
            //     'btsc1'     => $sc1,
            //     'btsc2'     => $sc2,
            //     'btsc3'     => $sc3,
            //     'btsc4'     => $sc4,
            //     'btsc5'     => $sc5,
            //     'btsc6'     => $sc6,
            //     'btsc7'     => $sc7,
            //     'btsc8'     => $sc8,
            // ]);
            // $json = $res->json();
            // return response()->json(['success' => $json['message']]);
            return response()->json(['success' => 'Berhasil Simpan Bobot']);
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }
}
