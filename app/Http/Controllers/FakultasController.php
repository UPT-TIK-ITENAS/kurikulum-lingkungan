<?php

namespace App\Http\Controllers;

use App\Models\Bobot;
use App\Models\BobotMK;
use App\Models\CE;
use App\Models\CPMK;
use App\Models\Matakuliah;
use App\Models\MKPilihan;
use App\Models\Pengampu;
use App\Models\Prodi;
use App\Models\SubCPMK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Session;
use Yajra\DataTables\DataTables;

class FakultasController extends Controller
{
    public function index()
    {
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Dashboard',
                'sesi'  => Session::get('data'),
            ];

            return view('fakultas.dashboard', compact('appdata'));
        } else {
            return redirect()->route('login')->with('error', 'Sesi anda telah habis');
        }
    }

    public function index_cpmk()
    {
        if (Session::has('data')) {
            $appdata = [
                'title' => 'CPMK',
                'sesi'  => Session::get('data'),
                'prodi' => Prodi::where('id_fakultas', Session::get('data')['idprodi'])->get(['id', 'nama_prodi'])
            ];
            return view('fakultas.cpmk_index', compact('appdata'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function listmatakuliah(Request $request)
    {

        $data = Pengampu::where([
            'prodi'     => $request->prodi,
            'semester'  => $request->semester
        ])->get();
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kode_mk', function ($row) {

                    return $row['kode_mk'];
                })
                ->addColumn('nama_mk', function ($row) {
                    return $row['nama_mk'];
                })
                ->addColumn('nama_mk_en', function ($row) {
                    return $row['nama_mk_en'];
                })
                ->addColumn('sks', function ($row) {
                    return $row['sks'];
                })
                ->addColumn('status_mk', function ($row) {
                    return $row['status_mk'];
                })
                ->addColumn('action', function ($row) {
                    $data = encrypt($row['kode_mk'] . '|' . $row['nama_mk'] . '|' . $row['nama_mk_en'] . '|' . $row['sks'] . '|' . $row['semester'] . '|' . $row['prodi']);
                    $edit_url = route('fakultas.cpmk.kelola', $data);
                    $url_subcpmk = route('fakultas.subcpmk.index', $data);
                    $url_bobot = route('fakultas.bobot', $data);
                    $actionBtn =
                        '<div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-search"></i></button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                            <a class="dropdown-item" href="' . $edit_url . '">CPMK</a>
                            <a class="dropdown-item" href="' . $url_subcpmk . '">Sub CPMK</a>
                            <a class="dropdown-item" href="' . $url_bobot . '">Bobot</a>
                            </div>
                        </div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function kelola($datamk)
    {
        $datamk = explode('|', decrypt($datamk));
        if (Session::has('data')) {

            $appdata = [
                'title' => 'Kelola CPMK',
                'sesi'  => Session::get('data'),
            ];
            $prodi = Prodi::where('id', $datamk[5])->first();
            $data = CPMK::where([
                'idprodi'      => $prodi->id,
                'idmatakuliah' => $datamk[0],
                'semester'     => $datamk[4]
            ])->get();


            $cpl_mk = BobotMK::join('cpl', 'bobot_mk.id_cpl', '=', 'cpl.kode_cpl')->where(['cpl.idprodi' => $prodi->id, 'cpl.idfakultas' => $prodi->id_fakultas, 'idmatakuliah' => $datamk[0], 'bobot_mk.idprodi' => $prodi->id, 'bobot_mk.idfakultas' => $prodi->id_fakultas])->where('bobot_mk', '!=', '0')->get();

            return view('fakultas.cpmk_kelola', compact('appdata', 'data', 'datamk', 'cpl_mk'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function store(Request $request)
    {
        if (Session::has('data')) {
            $sesi = Session::get('data');
            $semester = $request->semester;

            $data = [
                'kode_cpmk'  => 'CPMK-' . $request->kode_cpmk,
                'nama_cpmk'  => $request->nama_cpmk,
                'idmatakuliah'  => $request->idmatakuliah,
                'nama_matkul'  => $request->nama_matkul,
                'nama_matkul_en'  => $request->nama_matkul_en,
                'sks'  => $request->sks,
                'idprodi'   => Session::get('prodi'),
                'idfakultas' => Session::get('fakultas'),
                'semester' => $semester
            ];
            $query = CPMK::insert($data);
            if ($query) {
                return redirect()->back()->with('success', 'Success add');
            } else {
                return redirect()->back()->with('error', 'Something wrong !');
            }
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function update(Request $request, $id)
    {
        if (Session::has('data')) {
            $sesi = Session::get('data');
            $data = [
                'kode_cpmk'  => 'CPMK-' . $request->kode_cpmk,
                'nama_cpmk'  => $request->nama_cpmk,
                'idmatakuliah'  => $request->idmatakuliah,
                'nama_matkul'  => $request->nama_matkul,
                'nama_matkul_en'  => $request->nama_matkul_en,
                'sks'  => $request->sks,
                'idprodi'   => Session::get('prodi'),
                'idfakultas' => Session::get('fakultas'),
                'semester' => $request->semester
            ];
            $query = CPMK::where('id', $id)->update($data);
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
            $query = CPMK::where('id', $id)->delete();
            if ($query) {
                CE::where('idcpmk', $id)->delete();
                return redirect()->back()->with('success', 'Success delete');
            } else {
                return redirect()->back()->with('error', 'Something wrong !');
            }
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function index_subcpmk($datamk)
    {
        $datamk = explode('|', decrypt($datamk));
        $sesi = Session::get('data');
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Kelola Sub CPMK',
                'sesi'  => $sesi,
            ];
            $prodi = Prodi::where('id', $datamk[5])->first();

            $data = SubCPMK::where([
                'idprodi' => $prodi->id,
                'idmatakuliah' => $datamk[0],
                'semester'     => $datamk[4]
            ])->get();

            $cpl_mk = BobotMK::join('cpl', 'bobot_mk.id_cpl', '=', 'cpl.kode_cpl')->where(['cpl.idprodi' => $prodi->id, 'cpl.idfakultas' => $prodi->id_fakultas, 'idmatakuliah' => $datamk[0], 'bobot_mk.idprodi' => $prodi->id, 'bobot_mk.idfakultas' => $prodi->id_fakultas])->where('bobot_mk', '!=', '0')->get();

            return view('fakultas.subcpmk_index', compact('appdata', 'data', 'datamk', 'cpl_mk'));
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
    public function store_subcpmk(Request $request)
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
                'idprodi'   => Session::get('prodi'),
                'idfakultas' => Session::get('fakultas'),
                'semester' => $request->semester
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
    public function update_subcpmk(Request $request, $id)
    {
        if (Session::has('data')) {
            $sesi = Session::get('data');
            $data = [
                'subcpmk_kode'  => 'SC' . $request->subcpmk_kode,
                'subcpmk_nama_id'  => $request->subcpmk_nama_id,
                'subcpmk_nama_en'  => $request->subcpmk_nama_en,
                'idprodi'   => Session::get('prodi'),
                'idfakultas' => Session::get('fakultas'),
                'semester' => $request->semester
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
    public function delete_subcpmk($id)
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

            $res = Http::post(config('app.urlApi') . 'fakultas/akm-tengah', [
                'APIKEY'    => config('app.APIKEY'),
                'nrp'       => $datamhs_dec[0],
            ]);
            $json = $res->json();
            $nilaimhs = $json['data'];

            $data = [
                'mhs'   => $datamhs_dec,
                'en_mhs' => $datamhs,
                'datamhs' => $nilaimhs
            ];

            return view('fakultas.mahasiswa_sc', compact('data', 'appdata'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function sc_mahasiswadetail($mk)
    {
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Matriks Course Evaluation',
                'sesi'  => Session::get('data')
            ];
            $datamhs_dec = explode('|', decrypt($mk));

            $res = Http::post(config('app.urlApi') . 'fakultas/akm-tengah', [
                'APIKEY'    => config('app.APIKEY'),
                'nrp'       => $datamhs_dec[1],
            ]);
            $json = $res->json();
            $nilaimhs = $json['data'];
            $nilai = collect($nilaimhs);
            $mkp = MKPilihan::where('kode_mk', $datamhs_dec[0])->first();

            if ($mkp) {
                $filter_nilai = $nilai->where('kdkmkMSAKM', $datamhs_dec[0])->first();
                $data_sc = DB::table('bobot')->where('idmatakuliah', $mkp->kategori)->where('idprodi', Session::get('prodi'))->get();
            } else {
                $filter_nilai = $nilai->where('kdkmkMSAKM', $datamhs_dec[0])->first();
                $data_sc = DB::table('bobot')->where('idmatakuliah', '=', $datamhs_dec[0])->get();
            }
            // sum bobot by subcpmk_kode
            $data_sc = $data_sc->groupBy('subcpmk_kode')->map(function ($item) {
                return $item->sum('bobot') / 100;
            });
            $data_sc = $data_sc->map(function ($item, $key) use ($filter_nilai) {
                if (array_key_exists($key, $filter_nilai)) {
                    return $item * $filter_nilai[$key];
                } else {
                    return 0;
                }
            });


            // dd($data_sc);
            $response = [
                'nimhsMSMHS' => $filter_nilai['nimhsMSMHS'],
                'nmmhsMSMHS' => $filter_nilai['nmmhsMSMHS'],
                'kdkmkMSAKM' => $filter_nilai['kdkmkMSAKM'],
                'nakmktbkmk' => $filter_nilai['nakmktbkmk'],
                'seksiMSAKM' => $filter_nilai['seksiMSAKM'],
                'JumlahPengganti' => $filter_nilai['JumlahPengganti'],
                'JumlahReguler' => $filter_nilai['JumlahReguler'],
                'subcpmk_kode' => $data_sc
            ];
            return response()->json($response);
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }
    public function bobot($datamk)
    {
        $datamk = explode('|', decrypt($datamk));
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Kelola Bobot',
                'sesi'  => Session::get('data'),
            ];
            $prodi = Prodi::where('id', $datamk[5])->first();

            $data['subcpmk'] = SubCPMK::where([
                'idprodi' => $prodi->id,
                'idmatakuliah' => $datamk[0],
                'semester' => $datamk[4]
            ])->get();
            $data['cpmk'] = CPMK::where([
                'idprodi' => $prodi->id,
                'idmatakuliah' => $datamk[0],
                'semester' => $datamk[4]
            ])->orderBy('id', 'desc')->get();

            $data['bobot'] = Bobot::where([
                'idprodi' => $prodi->id,
                'idmatakuliah' => $datamk[0],
                'semester' => $datamk[4]
            ])->get();
            // dd($data);
            $bobot = array();
            foreach ($data['bobot'] as $b) {
                $bobot[$b->id_cpmk][$b->id_subcpmk] = $b->bobot;
            }

            $bobotsubcpmk = Bobot::selectRaw('sum(bobot) as totalbobot,bobot')->where([
                'idprodi' => $prodi->id, 'idmatakuliah' => $datamk[0], 'semester' => $datamk[4]
            ])->groupby('idprodi')->first();
            // dd($bobotsubcpmk);

            // $cpl_mk = BobotMK::with(['cpl'])->where(['idprodi' => Session::get('prodi'), 'idfakultas' => $appdata['sesi']['idfakultas'], 'idmatakuliah' => $datamk[0]])
            //     ->where('bobot_mk', '!=', '0')->get();
            // // dd($cpl_mk);

            $cpl_mk = BobotMK::join('cpl', 'bobot_mk.id_cpl', '=', 'cpl.kode_cpl')->where(['cpl.idprodi' => $prodi->id, 'cpl.idfakultas' => $prodi->id_fakultas, 'idmatakuliah' => $datamk[0], 'bobot_mk.idprodi' => $prodi->id, 'bobot_mk.idfakultas' => $prodi->id_fakultas])->where('bobot_mk', '!=', '0')->get();

            // $bobotnya = getNilaiBobotSC($datamk[0]);
            // dd($bobotnya);
            return view('fakultas.bobot', compact('appdata', 'data', 'datamk', 'bobot', 'bobotsubcpmk', 'cpl_mk'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function store_bobot(Request $request)
    {
        if (Session::has('data')) {
            $sesi = Session::get('data');
            $datamk = $request->data;
            foreach ($datamk as $key) {
                $data['bobot'] = $key['bobot'] ?? 0;
                $data['kode_cpmk'] = $key['kode_cpmk'];
                $data['subcpmk_kode'] = $key['subcpmk_kode'];
                $data['idmatakuliah'] = $key['idmatakuliah'];
                $data['idprodi'] = Session::get('prodi');
                $data['idfakultas'] = Session::get('fakultas');
                $data['id_cpmk'] = $key['id_cpmk'];
                $data['id_subcpmk'] = $key['id_subcpmk'];
                $data['semester'] = $key['semester'];

                if (Bobot::where('idmatakuliah', $key['idmatakuliah'])->where('idprodi', Session::get('prodi'))->where('idfakultas', Session::get('fakultas'))->where('kode_cpmk',  $key['kode_cpmk'])->where('subcpmk_kode',  $key['subcpmk_kode'])->first()) {
                    Bobot::where('idmatakuliah', $key['idmatakuliah'])->where('idprodi', Session::get('prodi'))->where('idfakultas', Session::get('fakultas'))->where('kode_cpmk',  $key['kode_cpmk'])->where('subcpmk_kode',  $key['subcpmk_kode'])->update($data);
                } else {
                    Bobot::create($data);
                }
            }

            $datasc = $request->datasc;
            $sc1 = array_key_exists(0, $datasc) ? $datasc[0]['bobotsc'] : '0';
            $sc2 = array_key_exists(1, $datasc) ? $datasc[1]['bobotsc'] : '0';
            $sc3 = array_key_exists(2, $datasc) ? $datasc[2]['bobotsc'] : '0';
            $sc4 = array_key_exists(3, $datasc) ? $datasc[3]['bobotsc'] : '0';
            $sc5 = array_key_exists(4, $datasc) ? $datasc[4]['bobotsc'] : '0';
            $sc6 = array_key_exists(5, $datasc) ? $datasc[5]['bobotsc'] : '0';
            $sc7 = array_key_exists(6, $datasc) ? $datasc[6]['bobotsc'] : '0';
            $sc8 = array_key_exists(7, $datasc) ? $datasc[7]['bobotsc'] : '0';

            $res = Http::post(config('app.urlApi') . 'fakultas/updateBobot', [
                'APIKEY'    => config('app.APIKEY'),
                'kodemk'    => $datamk[0]['idmatakuliah'],
                'semester'  => $datasc[0]['semester'],
                'useid'     => Session::get('data')['nodosMSDOS'],
                'tgtup'     => date('Y-m-d'),
                'jatup'     => date("H:i:s"),
                'btsc1'     => $sc1,
                'btsc2'     => $sc2,
                'btsc3'     => $sc3,
                'btsc4'     => $sc4,
                'btsc5'     => $sc5,
                'btsc6'     => $sc6,
                'btsc7'     => $sc7,
                'btsc8'     => $sc8,
            ]);
            $json = $res->json();
            return response()->json(['success' => $json['message']]);
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function index_matkul()
    {
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Data Matakuliah',
                'sesi'  => Session::get('data'),
                'prodi' => Prodi::where('id_fakultas', Session::get('data')['idprodi'])->get(['id', 'nama_prodi'])
            ];
            // dd($appdata);
            return view('fakultas.matkul_index', compact('appdata'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }


    public function listmk(Request $request)
    {
        if ($request->prodi) {
            $prodi = Prodi::where('id', $request->prodi)->first();
            $res = Http::post(config('app.urlApi') . 'dosen/matkul-prodi', [
                'APIKEY'    => config('app.APIKEY'),
                'tahun'     => config('app.tahun_kurikulum'),
                'prodi'     => $prodi->id,
            ]);
            $json = $res->json();
            $data = $json['data'];
            $kode = $prodi->kode;

            $data = collect($data)->filter(function ($item, $kode) {
                return stristr($item['kdkmktbkmk'], $kode);
            });
        } else {
            $data = [];
        }

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kdkmktbkmk', function ($row) {
                    return $row['kdkmktbkmk'];
                })
                ->addColumn('nakmktbkmk', function ($row) {
                    return $row['nakmktbkmk'];
                })
                ->addColumn('nakmitbkmk', function ($row) {
                    return $row['nakmitbkmk'];
                })
                ->addColumn('sksmktbkmk', function ($row) {
                    return $row['sksmktbkmk'];
                })
                ->addColumn('wbpiltbkur', function ($row) {
                    return $row['wbpiltbkur'];
                })
                // ->addColumn('cpl_mk', function ($row) {
                //     $mk = Matakuliah::where('id_matakuliah', $row['kdkmktbkmk'])->first();
                //     if (isset($mk)) {
                //         $gambarmk = asset('cpl/' . $mk->cpl_mk);
                //         $actionBtn =
                //             '<div class="btn-group" role="group" aria-label="Action">
                //                 <a role="button" data-bs-toggle="modal" class="btn btn-icon btn-warning" href="" data-bs-target="#editcplMK" onclick="showedit(this)"  data-kdmk="' . $row['kdkmktbkmk'] . '" data-nmmk="' . $row['nakmktbkmk'] . '" 
                //                     data-bs-tooltip="tooltip" data-bs-offset="0,8" data-bs-placement="top" data-bs-custom-class="tooltip-warning" title="Edit CPL MK">
                //                     <span class="tf-icons fa-solid fa-edit"></span>
                //                 </a>   

                //                 <a role="button" data-bs-toggle="modal" class="btn btn-icon btn-success" href="" data-bs-target="#showcplMK" onclick="showedit(this)" data-kdmk="' . $row['kdkmktbkmk'] . '" data-nmmk="' . $row['nakmktbkmk'] . '" data-gambarmk="' . $gambarmk . '"
                //                     data-bs-tooltip="tooltip" data-bs-offset="0,8" data-bs-placement="top" data-bs-custom-class="tooltip-warning" title="Show CPL MK">
                //                     <span class="tf-icons fa-solid fa-eye"></span>
                //                 </a>    
                //             </div>';
                //     } else {
                //         $actionBtn =
                //             '<div class="btn-group" role="group" aria-label="Action">
                //                 <a role="button" data-bs-toggle="modal" class="btn btn-icon btn-warning" href="" data-bs-target="#editcplMK" onclick="showedit(this)" data-kdmk="' . $row['kdkmktbkmk'] . '" data-nmmk="' . $row['nakmktbkmk'] . '" 
                //                     data-bs-tooltip="tooltip" data-bs-offset="0,8" data-bs-placement="top" data-bs-custom-class="tooltip-warning" title="Edit CPL MK">
                //                     <span class="tf-icons fa-solid fa-edit"></span>
                //                 </a>   

                //             </div>';
                //     }

                //     return $actionBtn;
                // })
                // ->addColumn('cpl_mhs', function ($row) {
                //     $mk = Matakuliah::where('id_matakuliah', $row['kdkmktbkmk'])->first();
                //     if (isset($mk) && $mk->cpl_mhs != NULL) {
                //         $gambarmhs = asset('cpl/' . $mk->cpl_mhs);
                //         $actionBtn =
                //             '<div class="btn-group" role="group" aria-label="Action">
                //                 <a role="button" data-bs-toggle="modal" class="btn btn-icon btn-warning" href="" data-bs-target="#editcplMhs" onclick="show(this)"  data-kdmkk="' . $row['kdkmktbkmk'] . '" data-nmmkk="' . $row['nakmktbkmk'] . '" 
                //                     data-bs-tooltip="tooltip" data-bs-offset="0,8" data-bs-placement="top" data-bs-custom-class="tooltip-warning" title="Edit CPL Mhs">
                //                     <span class="tf-icons fa-solid fa-edit"></span>
                //                 </a>   

                //                 <a role="button" data-bs-toggle="modal" class="btn btn-icon btn-success" href="" data-bs-target="#showcplMhs" onclick="show(this)" data-kdmkk="' . $row['kdkmktbkmk'] . '" data-nmmkk="' . $row['nakmktbkmk'] . '" data-gambarmhs="' . $gambarmhs . '"
                //                     data-bs-tooltip="tooltip" data-bs-offset="0,8" data-bs-placement="top" data-bs-custom-class="tooltip-warning" title="Show CPL Mhs">
                //                     <span class="tf-icons fa-solid fa-eye"></span>
                //                 </a>    
                //             </div>';
                //     } else {
                //         $actionBtn =
                //             '<div class="btn-group" role="group" aria-label="Action">
                //                 <a role="button" data-bs-toggle="modal" class="btn btn-icon btn-warning" href="" data-bs-target="#editcplMhs" onclick="show(this)" data-kdmkk="' . $row['kdkmktbkmk'] . '" data-nmmkk="' . $row['nakmktbkmk'] . '" 
                //                     data-bs-tooltip="tooltip" data-bs-offset="0,8" data-bs-placement="top" data-bs-custom-class="tooltip-warning" title="Edit CPL Mhs">
                //                     <span class="tf-icons fa-solid fa-edit"></span>
                //                 </a>   

                //             </div>';
                //     }

                //     return $actionBtn;
                // })
                // ->rawColumns(['cpl_mk', 'cpl_mhs'])
                ->make(true);
        }
        // $data = getMK();
    }
}
