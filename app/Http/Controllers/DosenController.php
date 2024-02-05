<?php

namespace App\Http\Controllers;

use App\Models\BobotMK;
use App\Models\CPMK;
use App\Models\Pengampu;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Http\Request;
use Session;
use Yajra\DataTables\DataTables;

class DosenController extends Controller
{
    public function index()
    {
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Dashboard',
                'sesi'  => Session::get('data'),
            ];
            return view('dosen.dashboard', compact('appdata'));
        } else {
            return redirect()->route('login')->with('error', 'Sesi anda telah habis');
        }
    }

    public function index_cpmk()
    {
        if (Session::has('data')) {
            $appdata = [
                'title' => 'CPMK',
                'sesi'  => Session::get('data')
            ];
            return view('dosen.cpmk_index', compact('appdata'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function listmatakuliah(Request $request)
    {

        $data = Pengampu::where([
            'nodos'     => Session::get('data')['nodosMSDOS'],
            'semester'  => $request->semester
        ])->get();
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kode_mk', function ($row) {

                    return $row['kode_mk'];
                    // if (stristr($row['kdkmktbkmk'], 'MKP-')) {
                    //     $data = '-';
                    // } else {
                    //     $data = $row['kdkmktbkmk'];
                    // }
                    // return $data;
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
                    $data = encrypt($row['kode_mk'] . '|' . $row['nama_mk'] . '|' . $row['nama_mk_en'] . '|' . $row['sks'] . '|' . $row['semester']);
                    $edit_url = route('dosen.cpmk.kelola', $data);
                    $url_subcpmk = route('dosen.subcpmk.index', $data);
                    $url_bobot = route('dosen.bobot', $data);
                    $actionBtn =
                        '<div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-search"></i></button>
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

    public function kelola($datamk)
    {
        $datamk = explode('|', decrypt($datamk));
        if (Session::has('data')) {

            $appdata = [
                'title' => 'Kelola CPMK',
                'sesi'  => Session::get('data'),
            ];
            $data = CPMK::where([
                'idprodi'      => $appdata['sesi']['kdfakMSDOS'] . $appdata['sesi']['kdjurMSDOS'],
                'idmatakuliah' => $datamk[0],
                'semester'     => Session::get('semester')
            ])->get();

            $cpl_mk = BobotMK::join('cpl', 'bobot_mk.id_cpl', '=', 'cpl.kode_cpl')->where(['cpl.idprodi' => $appdata['sesi']['kdfakMSDOS'] . $appdata['sesi']['kdjurMSDOS'], 'cpl.idfakultas' => $appdata['sesi']['kdfakMSDOS'], 'idmatakuliah' => $datamk[0], 'bobot_mk.idprodi' => $appdata['sesi']['kdfakMSDOS'] . $appdata['sesi']['kdjurMSDOS'], 'bobot_mk.idfakultas' => $appdata['sesi']['kdfakMSDOS']])->where('bobot_mk', '!=', '0')->get();

            return view('dosen.cpmk_kelola', compact('appdata', 'data', 'datamk', 'cpl_mk'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function store(Request $request)
    {
        if (Session::has('data')) {
            $sesi = Session::get('data');
            $semester = Session::get('semester');

            $data = [
                'kode_cpmk'  => 'CPMK-' . $request->kode_cpmk,
                'nama_cpmk'  => $request->nama_cpmk,
                'idmatakuliah'  => $request->idmatakuliah,
                'nama_matkul'  => $request->nama_matkul,
                'nama_matkul_en'  => $request->nama_matkul_en,
                'sks'  => $request->sks,
                'idprodi'   => $sesi['kdfakMSDOS'] . $sesi['kdjurMSDOS'],
                'idfakultas' => $sesi['kdfakMSDOS'],
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
                'idprodi'   => $sesi['kdfakMSDOS'] . $sesi['kdjurMSDOS'],
                'idfakultas' => $sesi['kdfakMSDOS'],
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
}
