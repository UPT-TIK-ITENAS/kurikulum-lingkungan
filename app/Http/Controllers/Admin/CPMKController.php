<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BobotCPL;
use App\Models\BobotCPLPadu;
use App\Models\BobotMK;
use App\Models\CE;
use App\Models\CPL;
use App\Models\CPMK;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\DataTables;

class CPMKController extends Controller
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
                'title' => 'CPMK',
                'sesi'  => Session::get('data')
            ];
            return view('admin.cpmk_index', compact('appdata'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function listmatakuliah(Request $request)
    {
        // $res = Http::post(config('app.urlApi') . 'dosen/matkul-prodi', [
        //     'APIKEY'    => config('app.APIKEY'),
        //     'tahun'     => config('app.tahun_kurikulum'),
        //     'prodi'     => Session::get('data')['idprodi'],
        // ]);
        // $json = $res->json();
        // $data = $json['data'];
        // $data = collect($data)->filter(function ($item) {
        //     return stristr($item['kdkmktbkmk'], Session::get('data')['kode']);
        // });
        $data = getMK();
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kdkmktbkmk', function ($row) {

                    if (stristr($row['kdkmktbkmk'], 'MKP-')) {
                        $data = '-';
                    } else {
                        $data = $row['kdkmktbkmk'];
                    }
                    return $data;
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
                ->addColumn('action', function ($row) {
                    $data = encrypt($row['kdkmktbkmk'] . '|' . $row['nakmktbkmk'] . '|' . $row['nakmitbkmk'] . '|' . $row['sksmktbkmk']);
                    $edit_url = route('admin.cpmk.kelola', $data);
                    $url_subcpmk = route('admin.subcpmk.index', $data);
                    $url_bobot = route('admin.bobot', $data);
                    $actionBtn = '<div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-warning dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-search"></i></button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                            <a class="dropdown-item" href="' . $edit_url . '">CPMK</a>
                            <a class="dropdown-item" href="' . $url_subcpmk . '">Sub CPMK</a>
                            <a class="dropdown-item" href="' . $url_bobot . '">Bobot</a>
                            <a class="dropdown-item" href="' . $url_bobot . '">Edit Data</a>
                            </div>
                        </div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function kelola($datamk)
    {
        $datamk = explode('|', decrypt($datamk));
        // dd($datamk);
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Kelola CPMK',
                'sesi'  => Session::get('data'),
            ];
            $data = CPMK::where([
                'idprodi' => $appdata['sesi']['idprodi'],
                'idmatakuliah' => $datamk[0]
            ])->get();

            // $cpl_mk = BobotMK::with(['cpl'])->where([
            //     'idprodi' => $appdata['sesi']['idprodi'],
            //     'idfakultas' => $appdata['sesi']['idfakultas'],
            //     'idmatakuliah' => $datamk[0]
            // ])->where('bobot_mk', '!=', '0')->get();
            $cpl_mk = BobotMK::join('cpl', 'bobot_mk.id_cpl', '=', 'cpl.kode_cpl')->where(['cpl.idprodi' => $appdata['sesi']['idprodi'], 'cpl.idfakultas' => $appdata['sesi']['idfakultas'], 'idmatakuliah' => $datamk[0], 'bobot_mk.idprodi' => $appdata['sesi']['idprodi'], 'bobot_mk.idfakultas' => $appdata['sesi']['idfakultas']])->where('bobot_mk', '!=', '0')->get();


            // dd($cpl_mk);
            return view('admin.cpmk_kelola', compact('appdata', 'data', 'datamk', 'cpl_mk'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function store(Request $request)
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
                'idprodi'   => $sesi['idprodi'],
                'idfakultas' => $sesi['idfakultas']
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
                'kode_cpmk'  => 'CPMK-' . $request->kode_cpmk,
                'nama_cpmk'  => $request->nama_cpmk,
                'idmatakuliah'  => $request->idmatakuliah,
                'nama_matkul'  => $request->nama_matkul,
                'nama_matkul_en'  => $request->nama_matkul_en,
                'sks'  => $request->sks,
                'idprodi'   => $sesi['idprodi'],
                'idfakultas' => $sesi['idfakultas']
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

    public function cpl($id)
    {
        if (Session::has('data')) {
            $id = decrypt($id);
            $sesi = Session::get('data');
            $data = [
                'cpmk'  => CPMK::where('id', $id)->first(),
                'ce'    => CE::select('cpl.id as cpl_id', 'ce_mk.id as idce', 'ce_mk.*', 'cpl.*')->join('cpl', 'cpl.id', '=', 'ce_mk.idcpl')->where('idcpmk', $id)->get(),
                'cpl'   => CPL::where([
                    'idprodi'      => $sesi['idprodi'],
                    'idfakultas'   => $sesi['idfakultas']
                ])->get()
            ];
            // dd($data);
            $appdata = [
                'title' => 'CPMK - CPL',
                'sesi'  => $sesi
            ];
            return view('admin.cpmk_cpl', compact('appdata', 'data'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function store_cpmk_cpl(Request $request)
    {
        if (Session::has('data')) {
            $data = [
                'idcpmk'        => $request->idcpmk,
                'idcpl'         => $request->idcpl,
                'idmatakuliah'  => $request->idmatakuliah,
                'bobot_cpl'     => str_replace(',', '.', $request->bobot_cpl),
                'bobot_cpmk'    => str_replace(',', '.', $request->bobot_cpmk),
            ];
            $query = CE::insert($data);
            if ($query) {
                return redirect()->back()->with('success', 'Success add');
            } else {
                return redirect()->back()->with('error', 'Something wrong !');
            }
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function update_cpmk_cpl(Request $request, $id)
    {
        if (Session::has('data')) {
            $data = [
                'idcpmk'        => $request->idcpmk,
                'idcpl'         => $request->idcpl,
                'idmatakuliah'  => $request->idmatakuliah,
                'bobot_cpl'     => str_replace(',', '.', $request->bobot_cpl),
                'bobot_cpmk'    => str_replace(',', '.', $request->bobot_cpmk),
            ];
            // dd($data);
            $query = CE::where('id', $id)->update($data);
            if ($query) {
                return redirect()->back()->with('success', 'Success update');
            } else {
                return redirect()->back()->with('error', 'Something wrong !');
            }
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function delete_cpmk_cpl($id)
    {
        if (Session::has('data')) {
            $query = CE::where('id', $id)->delete();
            if ($query) {
                return redirect()->back()->with('success', 'Success delete');
            } else {
                return redirect()->back()->with('error', 'Something wrong !');
            }
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

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
            ])->get();

            return view('admin.bobot', compact('appdata', 'data', 'datamk'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }
}
