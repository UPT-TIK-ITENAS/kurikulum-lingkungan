<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

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
                    $actionBtn =
                        "<div class='btn-group' role='group' aria-label='Action'>
                                <a role='button' class='btn btn-icon btn-warning' style='padding: 15px 25px;' href='$edit_url' data-bs-tooltip='tooltip' data-bs-offset='0,8' data-bs-placement='top' data-bs-custom-class='tooltip-warning' title='Kelola CPMK'>
                                    CPMK
                                </a>
                                <a role='button' class='btn btn-icon btn-success' style='padding: 15px 32px;' href='$url_subcpmk' data-bs-tooltip='tooltip' data-bs-offset='0,8' data-bs-placement='top' data-bs-custom-class='tooltip-warning' title='Kelola Sub CPMK'>
                                Sub CPMK
                                </a>
                                <a role='button' class='btn btn-icon btn-primary' style='padding: 15px 25px;' href='$url_bobot' data-bs-tooltip='tooltip' data-bs-offset='0,8' data-bs-placement='top' data-bs-custom-class='tooltip-warning' title='Kelola Bobot'> Bobot</a>
                                </a>
                            </div>";
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
