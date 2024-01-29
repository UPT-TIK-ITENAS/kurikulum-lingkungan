<?php

namespace App\Http\Controllers;

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
}
