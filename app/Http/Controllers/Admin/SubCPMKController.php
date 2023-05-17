<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CPMK;
use App\Models\SubCPMK;
use App\Models\Bobot;
use App\Models\BobotMK;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class SubCPMKController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index($idcpmk)
    // {
    //     if (Session::has('data')) {
    //         $idcpmk = decrypt($idcpmk);
    //         $appdata = [
    //             'title' => 'Data IK',
    //             'sesi'  => Session::get('data')
    //         ];
    //         $data['subcpmk'] = SubCPMK::where([
    //             'subcpmk_id_prodi'    => $appdata['sesi']['idprodi'],
    //             'subcpmk_id_fakultas' => $appdata['sesi']['idfakultas'],
    //             'subcpmk_id_cpmk'      => $idcpmk
    //         ])->get();
    //         $data['cpmk'] = CPMK::('id', $idcpmk)->first();
    //         return view('admin.subcpmk_index', compact('appdata', 'data'));
    //     } else {
    //         return redirect()->route('login')->with('error', 'You are not authenticated');
    //     }
    // }

    public function index($datamk)
    {
        $datamk = explode('|', decrypt($datamk));
        // dd($datamk);
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Kelola Sub CPMK',
                'sesi'  => Session::get('data'),
            ];
            $data = SubCPMK::where([
                'idprodi' => $appdata['sesi']['idprodi'],
                'idmatakuliah' => $datamk[0]
            ])->get();

            $cpl_mk = BobotMK::with(['cpl'])->where(['idprodi' => $appdata['sesi']['idprodi'], 'idfakultas' => $appdata['sesi']['idfakultas'], 'idmatakuliah' => $datamk[0]])
                ->where('bobot_mk', '!=', '0')->get();
            // dd($cpl_mk);

            return view('admin.subcpmk_index', compact('appdata', 'data', 'datamk', 'cpl_mk'));
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
            $data = [
                'subcpmk_kode'  => 'SC' . $request->subcpmk_kode,
                'subcpmk_nama_id'  => $request->subcpmk_nama_id,
                'subcpmk_nama_en'  => $request->subcpmk_nama_en,
                'idmatakuliah'  => $request->idmatakuliah,
                'nama_matkul'  => $request->nama_matkul,
                'nama_matkul_en'  => $request->nama_matkul_en,
                'sks'  => $request->sks,
                'idprodi'   => $sesi['idprodi'],
                'idfakultas' => $sesi['idfakultas']
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
                'subcpmk_kode'  => 'SC' . $request->subcpmk_kode,
                'subcpmk_nama_id'  => $request->subcpmk_nama_id,
                'subcpmk_nama_en'  => $request->subcpmk_nama_en,
                'idprodi'   => $sesi['idprodi'],
                'idfakultas' => $sesi['idfakultas']
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

    public function sc_mahasiswa(Request $request, $datamhs)
    {
        if (Session::has('data')) {
            $datamhs_dec = explode('|', decrypt($datamhs));
            $appdata = [
                'title' => 'Matriks Course Evaluation',
                'sesi'  => Session::get('data')
            ];

            $res = Http::post(config('app.urlApi') . 'dosen/akm-tengah', [
                'APIKEY'    => config('app.APIKEY'),
                'nrp'       => $datamhs_dec[0],
            ]);
            $json = $res->json();
            $nilaimhs = $json['data'];
            $nilai = collect($nilaimhs);
            // dd($nilaimhs);
            $data = [
                'mhs'   => $datamhs_dec,
                'en_mhs' => $datamhs,
                // 'subcpmk' =>  $getSC = Bobot::where([
                //     'idmatakuliah'     => $mk
                // ])->get(), 
                // 'datasubcpmk' =>  
                // $nilai= $nilai->map(function($item) use ($nilaimhs) {
                    
                //     $data_sc = DB::table('bobot')->where('idmatakuliah', '=',  $nilaimhs[0])->get();
                //     // sum bobot by subcpmk_kode
                //     $data_sc = $data_sc->groupBy('subcpmk_kode')->map(function ($item) {
                //     // $item = $item->sum('bobot') ;
                //         return $item->sum('bobot')/100;
                //     });
                    
                //     return [
                //         'nimhsMSMHS' => $item['nimhsMSMHS'],
                //         'nmmhsMSMHS' => $item['nmmhsMSMHS'],
                //         'kdkmkMSAKM' => $item['kdkmkMSAKM'],
                //         'nakmktbkmk' => $item['nakmktbkmk'],
                //         'seksiMSAKM' => $item['seksiMSAKM'],
                //         'JumlahPengganti' => $item['JumlahPengganti'],
                //         'JumlahReguler' => $item['JumlahReguler'],
                //         'subcpmk_kode' => [
                //             'SC1' => $item['SC1']  * $data_sc['SC1'],
                //             'SC2' => $item['SC2']  * $data_sc['SC2'],
                //             'SC3' => $item['SC3']  * $data_sc['SC3'],
                //             'SC4' => $item['SC4']  * $data_sc['SC4'],
                //             'SC5' => $item['SC5']  * $data_sc['SC5'],
                //             ]
                //         ];
                // }),              
                'datamhs' => $nilaimhs
            ];
            return view('admin.mahasiswa_sc', compact('data', 'appdata'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }
}
