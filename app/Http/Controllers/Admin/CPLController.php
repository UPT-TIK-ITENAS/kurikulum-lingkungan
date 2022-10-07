<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            ])->orderByRaw('CAST(SUBSTRING(kode_cpl,5,2) AS INT)', 'asc')->get();
            $data_json = [];
            $label = [];
            foreach ($data as $c) {
                $bobotCPL = getNilaiCPL($c->id, $datamhs[0]);
                array_push($data_json, $bobotCPL);
                array_push($label, $c->kode_cpl);
            }

            return response()->json(['cpl' => $label, 'bobot' => $data_json]);
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
        ])->orderByRaw('CAST(SUBSTRING(kode_cpl,5,2) AS INT)', 'asc')->get();
        $data_cpl = [];
        foreach ($data as $c) {
            $bobotCPL = getNilaiCPLBySemester($c->id, $nim, $semester);
            array_push($data_cpl, [
                'kode_cpl'  => $c->kode_cpl,
                'bobot_cpl' => $bobotCPL
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
            ])->orderByRaw('CAST(SUBSTRING(kode_cpl,5,2) AS INT)', 'asc')->get();

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

            return response()->json(['cpl' => $label_cpl_json, 'bobot' => $bobot_cpl_json]);
            // return response()->json($data_cpl);
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }
}
