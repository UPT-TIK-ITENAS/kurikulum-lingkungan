<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BobotCPLPadu;
use App\Models\BobotCPL;
use App\Models\BobotMK;
use App\Models\CE;
use App\Models\CPL;
use App\Models\Lulusan;
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
    public function index(Request $request)
    {
        if (Session::has('data')) {
            $appdata = [
                'title' => 'CPL',
                'sesi'  => Session::get('data')
            ];
            $cpl = CPL::where(['idprodi' => $appdata['sesi']['idprodi'], 'idfakultas' => $appdata['sesi']['idfakultas']])->get();

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
                'nama_cpleng'  => $request->nama_cpleng,
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
                'nama_cpleng'  => $request->nama_cpleng,
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
            $datamhs_dec = explode('|', decrypt($datamhs));
            $appdata = [
                'title' => 'Matriks Course Evaluation',
                'sesi'  => Session::get('data')
            ];

            $res = Http::post(config('app.urlApi') . 'mahasiswa/matkul-mhs', [
                'APIKEY'    => config('app.APIKEY'),
                'nrp'       => $datamhs_dec[0],
            ]);
            $json = $res->json();
            $nilaimhs = $json['data'];

            $data = [
                'mhs'   => $datamhs_dec,
                'en_mhs' => $datamhs,
                'cpl'   => CPL::where([
                    'idprodi' => $appdata['sesi']['idprodi'], 'idfakultas' => $appdata['sesi']['idfakultas']
                ])->get(),
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

    public function getLabelCPLChart($datamhs)
    {
        if (Session::has('data')) {
            $datamhs = explode('|', decrypt($datamhs));
            $appdata = [
                'title' => 'Data Label Chart CPL',
                'sesi'  => Session::get('data')
            ];
            $data = CPL::where([
                'idprodi' => $appdata['sesi']['idprodi'], 'idfakultas' => $appdata['sesi']['idfakultas']
            ])->get();
            $data_json = [];
            $label = [];
            $persen = [];
            foreach ($data as $c) {
                $bobotCPL = getNilaiCPL($c->id, $datamhs[0]);
                $persenCPL = round((getNilaiCPL($c->id, $datamhs[0]) / 4) * 100);
                array_push($data_json, $bobotCPL);
                array_push($label, $c->kode_cpl);
                array_push($persen, $persenCPL);
            }

            return response()->json(['cpl' => $label, 'bobot' => $data_json, 'max_bobot' => max($data_json), 'persen' => $persen, 'max_persen' => max($persen)]);
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function getCPLPerMHS($nim, $semester)
    {
        $appdata = [
            'title' => 'Data Label Chart CPL',
            'sesi'  => Session::get('data')
        ];
        $data = CPL::where([
            'idprodi' => $appdata['sesi']['idprodi'], 'idfakultas' => $appdata['sesi']['idfakultas']
        ])->get();
        $data_cpl = [];
        foreach ($data as $c) {
            $bobotCPL = getNilaiCPLBySemester($c->id, $nim, $semester);
            array_push($data_cpl, [
                'kode_cpl'  => $c->kode_cpl,
                'bobot_cpl' => $bobotCPL,
            ]);
        }
        return $data_cpl;
    }

    public function getLabelCPLChartBySemester(Request $request)
    {
        if (Session::has('data')) {
            $label_cpl_json = [];
            $bobot_cpl_json = [];
            $data_cpl = [];

            $appdata = [
                'title' => 'Data Label Chart CPL By Semester',
                'sesi'  => Session::get('data')
            ];
            $data = CPL::where([
                'idprodi' => $appdata['sesi']['idprodi'], 'idfakultas' => $appdata['sesi']['idfakultas']
            ])->get();

            // $res = Http::post(config('app.urlApi') . 'mahasiswa/ipk_prodi_semester', [
            //     'APIKEY'    => config('app.APIKEY'),
            //     'fakultas'  => $appdata['sesi']['idfakultas'],
            //     'jurusan'   => substr($appdata['sesi']['idprodi'], 1, 1),
            //     'semester'  => $request->semester
            // ]);
            // $json = $res->json();

            $listmhs = Lulusan::where([
                'idprodi'    => $appdata['sesi']['idprodi'],
                'idfakultas' => $appdata['sesi']['idfakultas'],
                'semester_lulus' => $request->semester
            ])->get();

            foreach ($listmhs as $m) {
                array_push($data_cpl, $this->getCPLPerMHS($m->nim, $request->semester));
            }

            foreach ($data as $d) {
                $avg_per_cpl = 0;
                $total_bobot_cpl = 0;
                foreach ($data_cpl as $cpl) {
                    foreach ($cpl as $c) {
                        if ($d->kode_cpl == $c['kode_cpl']) {
                            $total_bobot_cpl += $c['bobot_cpl'];
                        }
                    }
                }
                $avg_per_cpl = $total_bobot_cpl / count($data_cpl);
                array_push($label_cpl_json, $d->kode_cpl);
                array_push($bobot_cpl_json, $avg_per_cpl);
            }

            return response()->json(['cpl' => $label_cpl_json, 'bobot' => $bobot_cpl_json, 'max_bobot' => max($bobot_cpl_json)]);
            // return response()->json($data_cpl);
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }
    public function getLabelCPLChartMhsBySemester(Request $request)
    {
        if (Session::has('data')) {
            $label_cpl_json = [];
            $bobot_cpl_json = [];
            $data_cpl = [];

            $appdata = [
                'title' => 'Data Label Chart CPL By Semester',
                'sesi'  => Session::get('data')
            ];
            $data = CPL::where([
                'idprodi' => $appdata['sesi']['idprodi'], 'idfakultas' => $appdata['sesi']['idfakultas']
            ])->get();

            $res = Http::post(config('app.urlApi') . 'mahasiswa/ipk_prodi_semester', [
                'APIKEY'    => config('app.APIKEY'),
                'fakultas'  => $appdata['sesi']['idfakultas'],
                'jurusan'   => substr($appdata['sesi']['idprodi'], 1, 1),
                'semester'  => $request->semester
            ]);
            $json = $res->json();
            $listmhs = $json['data'];
            // $listmhs = Lulusan::where([
            //     'idprodi'    => $appdata['sesi']['idprodi'],
            //     'idfakultas' => $appdata['sesi']['idfakultas'],
            //     'semester_lulus' => $request->semester
            // ])->get();

            foreach ($listmhs as $m) {
                array_push($data_cpl, $this->getCPLPerMHS($m['NIMHSHSIPK'], $request->semester));
            }

            foreach ($data as $d) {
                $avg_per_cpl = 0;
                $total_bobot_cpl = 0;
                foreach ($data_cpl as $cpl) {
                    foreach ($cpl as $c) {
                        if ($d->kode_cpl == $c['kode_cpl']) {
                            $total_bobot_cpl += $c['bobot_cpl'];
                        }
                    }
                }
                $avg_per_cpl = $total_bobot_cpl / count($data_cpl);
                array_push($label_cpl_json, $d->kode_cpl);
                array_push($bobot_cpl_json, $avg_per_cpl);
            }

            return response()->json(['cpl' => $label_cpl_json, 'bobot' => $bobot_cpl_json, 'max_bobot' => max($bobot_cpl_json)]);
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function getBobotCPLPadu()
    {
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Bobot CPL Padu',
                'sesi'  => Session::get('data')
            ];
            $cpl = CPL::selectRaw('cpl.*, CAST(SUBSTRING(kode_cpl,5,2) AS INT) as kode')->where(['idprodi' => $appdata['sesi']['idprodi'], 'idfakultas' => $appdata['sesi']['idfakultas']])->orderby('kode')->get();
            $totalcpl = CPL::where(['idprodi' => $appdata['sesi']['idprodi'], 'idfakultas' => $appdata['sesi']['idfakultas']])->count();
            // dd($totalcpl);
            $res = Http::post(config('app.urlApi') . 'dosen/matkul-prodi', [
                'APIKEY'    => config('app.APIKEY'),
                'tahun'     => config('app.tahun_kurikulum'),
                'prodi'     => Session::get('data')['idprodi'],
            ]);
            $json = $res->json();
            $dataMatkul = $json['data'];

            $bobotcpl = BobotCPLPadu::where([
                'idprodi' => $appdata['sesi']['idprodi'],
            ])->get();

            $bobot = array();
            foreach ($bobotcpl as $b) {
                $bobot[$b->idmatakuliah][$b->id_cpl] = $b->bobot;
            }

            $total_nilai = BobotCPLPadu::selectRaw('sum(bobot) as totalbobot')->where([
                'idprodi' => $appdata['sesi']['idprodi'],
            ])->groupby('idprodi')->first();
            // dd($total_nilai);
            return view('admin.cpl_bobot_padu', compact('appdata', 'cpl', 'dataMatkul', 'bobotcpl', 'bobot', 'total_nilai', 'totalcpl'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function storeBobotCPLPadu(Request $request)
    {
        if (Session::has('data')) {
            $sesi = Session::get('data');
            // dd($sesi);
            foreach ($request->data as $key) {

                // dd($request->data);
                $data['idmatakuliah'] = $key['idmatakuliah'];
                $data['idprodi'] = $sesi['idprodi'];
                $data['idfakultas'] = $sesi['idfakultas'];
                $data['id_cpl'] = $key['id_cpl'];
                $data['bobot'] = $key['bobot'] ?? 0;
                $data['bobot_mk'] = number_format(($key['bobot'] / $key['bobot_mk']) * 100, 1) ?? 0;
                $data['bobot_cpl'] = number_format(($key['bobot'] / $key['bobot_cpl']) * 100, 1) ?? 0;

                // dd($data['bobot_cpl']);

                BobotCPLPadu::updateOrCreate(
                    [
                        'idmatakuliah' =>  $data['idmatakuliah'],
                        'idprodi' => $data['idprodi'],
                        'idfakultas' => $data['idfakultas'],
                        'id_cpl' => $data['id_cpl'],
                        'bobot' =>  $data['bobot'],
                    ],
                );

                BobotMK::updateOrCreate(
                    [
                        'idmatakuliah' =>  $data['idmatakuliah'],
                        'idprodi' => $data['idprodi'],
                        'idfakultas' => $data['idfakultas'],
                        'id_cpl' => $data['id_cpl'],
                        'bobot_mk' =>  $data['bobot_mk'],
                    ],
                );

                BobotCPL::updateOrCreate(
                    [
                        'idmatakuliah' =>  $data['idmatakuliah'],
                        'idprodi' => $data['idprodi'],
                        'idfakultas' => $data['idfakultas'],
                        'id_cpl' => $data['id_cpl'],
                        'bobot_cpl' =>  $data['bobot_cpl'],
                    ],
                );
            }



            return response()->json(['success' => 'Berhasil']);
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }


    public function matriksCPLMK()
    {
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Bobot CPL Mata Kuliah',
                'sesi'  => Session::get('data')
            ];
            $cpl_mk = CPL::selectRaw('cpl.*, CAST(SUBSTRING(kode_cpl,5,2) AS INT) as kode')->where(['idprodi' => $appdata['sesi']['idprodi'], 'idfakultas' => $appdata['sesi']['idfakultas']])->orderby('kode')->get();

            $res = Http::post(config('app.urlApi') . 'dosen/matkul-prodi', [
                'APIKEY'    => config('app.APIKEY'),
                'tahun'     => config('app.tahun_kurikulum'),
                'prodi'     => Session::get('data')['idprodi'],
            ]);
            $json = $res->json();
            $dataMatkul = $json['data'];
            $bobotmk = BobotMK::where([
                'idprodi' => $appdata['sesi']['idprodi'],
            ])->get();

            $bobot = array();
            foreach ($bobotmk as $b) {
                $bobot[$b->idmatakuliah][$b->id_cpl] = $b->bobot_mk;
            }

            return view('admin.matriks_cpl_mk', compact('appdata', 'cpl_mk', 'dataMatkul', 'bobot', 'bobotmk'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function matriksCPL()
    {
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Bobot CPL Mata Kuliah',
                'sesi'  => Session::get('data')
            ];
            $cpl = CPL::selectRaw('cpl.*, CAST(SUBSTRING(kode_cpl,5,2) AS INT) as kode')->where(['idprodi' => $appdata['sesi']['idprodi'], 'idfakultas' => $appdata['sesi']['idfakultas']])->orderby('kode')->get();

            $res = Http::post(config('app.urlApi') . 'dosen/matkul-prodi', [
                'APIKEY'    => config('app.APIKEY'),
                'tahun'     => config('app.tahun_kurikulum'),
                'prodi'     => Session::get('data')['idprodi'],
            ]);
            $json = $res->json();
            $dataMatkul = $json['data'];

            $bobotcpl = BobotCPL::where([
                'idprodi' => $appdata['sesi']['idprodi'],
            ])->get();

            $bobot = array();
            foreach ($bobotcpl as $b) {
                $bobot[$b->idmatakuliah][$b->id_cpl] = $b->bobot_cpl;
            }

            // dd($bobot);

            return view('admin.matriks_cpl2', compact('appdata', 'cpl', 'dataMatkul', 'bobot', 'bobotcpl'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }
}
