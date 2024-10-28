<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lulusan;
use App\Models\Matakuliah;
use App\Models\CPL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Session;
use PDF;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class MainController extends Controller
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
                'title' => 'Dashboard',
                'sesi'  => Session::get('data'),
            ];
            $data = [
                'tahunAkademik' => Lulusan::where([
                    'idprodi'    => $appdata['sesi']['idprodi'],
                    'idfakultas' => $appdata['sesi']['idfakultas']
                ])->groupBy('semester_lulus')->get('semester_lulus')
            ];
            // dd($appdata);                                                                                    

            return view('admin.dashboard', compact('appdata', 'data'));
        } else {
            return redirect()->route('login')->with('error', 'Sesi anda telah habis');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listmatakuliah(Request $request)
    {
        $res = Http::post(config('app.urlApi') . 'dosen/matkul-prodi', [
            'APIKEY'    => config('app.APIKEY'),
            'tahun'     => config('app.tahun_kurikulum'),
            'prodi'     => Session::get('data')['idprodi'],
        ]);
        $json = $res->json();
        $data = $json['data'];
        $data = collect($data)->filter(function ($item) {
            return stristr($item['kdkmktbkmk'], Session::get('data')['kode']);
        });
        // $data = getMK();
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
                ->addColumn('cpl_mk', function ($row) {
                    $mk = Matakuliah::where('id_matakuliah', $row['kdkmktbkmk'])->first();
                    if (isset($mk)) {
                        $gambarmk = asset('cpl/' . $mk->cpl_mk);
                        $actionBtn =
                            '<div class="btn-group" role="group" aria-label="Action">
                                <a role="button" data-bs-toggle="modal" class="btn btn-icon btn-warning" href="" data-bs-target="#editcplMK" onclick="showedit(this)"  data-kdmk="' . $row['kdkmktbkmk'] . '" data-nmmk="' . $row['nakmktbkmk'] . '" 
                                    data-bs-tooltip="tooltip" data-bs-offset="0,8" data-bs-placement="top" data-bs-custom-class="tooltip-warning" title="Edit CPL MK">
                                    <span class="tf-icons fa-solid fa-edit"></span>
                                </a>   

                                <a role="button" data-bs-toggle="modal" class="btn btn-icon btn-success" href="" data-bs-target="#showcplMK" onclick="showedit(this)" data-kdmk="' . $row['kdkmktbkmk'] . '" data-nmmk="' . $row['nakmktbkmk'] . '" data-gambarmk="' . $gambarmk . '"
                                    data-bs-tooltip="tooltip" data-bs-offset="0,8" data-bs-placement="top" data-bs-custom-class="tooltip-warning" title="Show CPL MK">
                                    <span class="tf-icons fa-solid fa-eye"></span>
                                </a>    
                            </div>';
                    } else {
                        $actionBtn =
                            '<div class="btn-group" role="group" aria-label="Action">
                                <a role="button" data-bs-toggle="modal" class="btn btn-icon btn-warning" href="" data-bs-target="#editcplMK" onclick="showedit(this)" data-kdmk="' . $row['kdkmktbkmk'] . '" data-nmmk="' . $row['nakmktbkmk'] . '" 
                                    data-bs-tooltip="tooltip" data-bs-offset="0,8" data-bs-placement="top" data-bs-custom-class="tooltip-warning" title="Edit CPL MK">
                                    <span class="tf-icons fa-solid fa-edit"></span>
                                </a>   
        
                            </div>';
                    }

                    return $actionBtn;
                })
                ->addColumn('cpl_mhs', function ($row) {
                    $mk = Matakuliah::where('id_matakuliah', $row['kdkmktbkmk'])->first();
                    if (isset($mk) && $mk->cpl_mhs != NULL) {
                        $gambarmhs = asset('cpl/' . $mk->cpl_mhs);
                        $actionBtn =
                            '<div class="btn-group" role="group" aria-label="Action">
                                <a role="button" data-bs-toggle="modal" class="btn btn-icon btn-warning" href="" data-bs-target="#editcplMhs" onclick="show(this)"  data-kdmkk="' . $row['kdkmktbkmk'] . '" data-nmmkk="' . $row['nakmktbkmk'] . '" 
                                    data-bs-tooltip="tooltip" data-bs-offset="0,8" data-bs-placement="top" data-bs-custom-class="tooltip-warning" title="Edit CPL Mhs">
                                    <span class="tf-icons fa-solid fa-edit"></span>
                                </a>   

                                <a role="button" data-bs-toggle="modal" class="btn btn-icon btn-success" href="" data-bs-target="#showcplMhs" onclick="show(this)" data-kdmkk="' . $row['kdkmktbkmk'] . '" data-nmmkk="' . $row['nakmktbkmk'] . '" data-gambarmhs="' . $gambarmhs . '"
                                    data-bs-tooltip="tooltip" data-bs-offset="0,8" data-bs-placement="top" data-bs-custom-class="tooltip-warning" title="Show CPL Mhs">
                                    <span class="tf-icons fa-solid fa-eye"></span>
                                </a>    
                            </div>';
                    } else {
                        $actionBtn =
                            '<div class="btn-group" role="group" aria-label="Action">
                                <a role="button" data-bs-toggle="modal" class="btn btn-icon btn-warning" href="" data-bs-target="#editcplMhs" onclick="show(this)" data-kdmkk="' . $row['kdkmktbkmk'] . '" data-nmmkk="' . $row['nakmktbkmk'] . '" 
                                    data-bs-tooltip="tooltip" data-bs-offset="0,8" data-bs-placement="top" data-bs-custom-class="tooltip-warning" title="Edit CPL Mhs">
                                    <span class="tf-icons fa-solid fa-edit"></span>
                                </a>   
        
                            </div>';
                    }

                    return $actionBtn;
                })
                ->rawColumns(['cpl_mk', 'cpl_mhs'])
                ->make(true);
        }
    }

    public function storemk(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        if (Session::has('data')) {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $file_name = $file->getClientOriginalName();
                $file_name = preg_replace('!\s+!', ' ', $file_name);
                $file_name = str_replace(' ', '_', $file_name);
                $file_name = str_replace('%', '', $file_name);
                $file->move(public_path('cpl'), $file_name);

                $query = Matakuliah::updateOrCreate(
                    ['id_matakuliah' =>  request('kdmk')],
                    ['cpl_mk' =>  $file_name]
                );

                if ($query) {
                    return redirect()->back()->with('success', 'Success add');
                } else {
                    return redirect()->back()->with('error', 'Something wrong !');
                }
            }
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function storemhs(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        if (Session::has('data')) {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $file_name = $file->getClientOriginalName();
                $file_name = preg_replace('!\s+!', ' ', $file_name);
                $file_name = str_replace(' ', '_', $file_name);
                $file_name = str_replace('%', '', $file_name);
                $file->move(public_path('cpl'), $file_name);

                $query = Matakuliah::updateOrCreate(
                    ['id_matakuliah' =>  request('kdmkk')],
                    ['cpl_mhs' =>  $file_name]
                );

                if ($query) {
                    return redirect()->back()->with('success', 'Success add');
                } else {
                    return redirect()->back()->with('error', 'Something wrong !');
                }
            }
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function listmahasiswa(Request $request)
    {
        $res = Http::post(config('app.urlApi') . 'mahasiswa/ipk_prodi', [
            'APIKEY'    => config('app.APIKEY'),
            'fakultas'  => Session::get('data')['idfakultas'],
            'jurusan'   => substr(Session::get('data')['idprodi'], 1, 1),
        ]);
        $json = $res->json();
        $data = $json['data'];
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('NIMHSHSIPK', function ($row) {
                    return $row['NIMHSHSIPK'];
                })
                ->addColumn('nmmhsMSMHS', function ($row) {
                    return $row['nmmhsMSMHS'];
                })
                ->addColumn('NLIPSHSIPK', function ($row) {
                    return $row['NLIPSHSIPK'];
                })
                ->addColumn('NLIPKHSIPK', function ($row) {
                    return $row['NLIPKHSIPK'];
                })
                ->addColumn('KeterTbkod', function ($row) {
                    return $row['KeterTbkod'];
                })
                ->addColumn('action', function ($row) {
                    $data = encrypt($row['NIMHSHSIPK'] . '|' . $row['nmmhsMSMHS']);
                    $edit_url = route('admin.mahasiswa.nilai', $data);
                    $cpl_url = route('admin.mahasiswa.cpl', $data);
                    $sc_url = route('admin.mahasiswa.sc', $data);
                    $actionBtn =
                        "<div class='btn-group' role='group' aria-label='Action'>
                                <a role='button' class='btn btn-icon btn-info' href='$edit_url' data-bs-tooltip='tooltip' data-bs-offset='0,8' data-bs-placement='top' data-bs-custom-class='tooltip-info' title='Nilai Mahasiswa'>
                                   <span class='tf-icons fa-solid fa-circle-info'></span>
                                </a>
                                <a role='button' class='btn btn-icon btn-success' href='$cpl_url' data-bs-tooltip='tooltip' data-bs-offset='0,8' data-bs-placement='top' data-bs-custom-class='tooltip-success' title='CPL'>
                                   CPL
                                </a>
                            </div>";
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function index_matkul()
    {
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Data Matakuliah',
                'sesi'  => Session::get('data')
            ];
            return view('admin.matkul_index', compact('appdata'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }
    public function index_mahasiswa()
    {
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Data Mahasiswa',
                'sesi'  => Session::get('data')
            ];
            return view('admin.mahasiswa_index', compact('appdata'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }
    public function nilai_mahasiswa($datamhs)
    {
        if (Session::has('data')) {
            $datamhs = explode('|', decrypt($datamhs));
            $appdata = [
                'title' => 'Data Nilai Mahasiswa',
                'sesi'  => Session::get('data')
            ];
            $res = Http::post(config('app.urlApi') . 'mahasiswa/matkul-mhs', [
                'APIKEY'    => config('app.APIKEY'),
                'nrp'       => $datamhs[0],
            ]);
            $json = $res->json();
            $data = $json['data'];
            return view('admin.mahasiswa_nilai', compact('appdata', 'data', 'datamhs'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function index_lulusan()
    {
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Data Lulusan',
                'sesi'  => Session::get('data')
            ];

            $res = Http::post(config('app.urlApi') . 'mahasiswa/all_mahasiswa', [
                'APIKEY'    => config('app.APIKEY'),
                'fakultas'  => Session::get('data')['idfakultas'],
                'jurusan'   => substr(Session::get('data')['idprodi'], 1, 1),
            ]);
            $json = $res->json();
            $mhs = $json['data'];

            $data = [
                'mhs'            => $mhs
            ];
            return view('admin.lulusan_index', compact('appdata', 'data'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function listlulusan(Request $request)
    {
        if (Session::has('data')) {
            $appdata = [
                'title' => 'Data Lulusan',
                'sesi'  => Session::get('data')
            ];
            $data =  Lulusan::where([
                'idprodi'    => $appdata['sesi']['idprodi'],
                'idfakultas' => $appdata['sesi']['idfakultas']
            ])->get();

            if ($request->ajax()) {
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('nim', function ($row) {
                        return $row->nim;
                    })
                    ->addColumn('nama_mhs', function ($row) {
                        return $row->nama_mhs;
                    })
                    ->addColumn('semester_lulus', function ($row) {
                        return $row->semester_lulus;
                    })
                    ->addColumn('action', function ($row) {
                        $data = encrypt($row->nim . '|' . $row->nama_mhs);
                        $delete_url = route('admin.lulusan.delete', $row->id);
                        $edit_url = route('admin.mahasiswa.nilai', $data);
                        $cpl_url = route('admin.mahasiswa.cpl', $data);
                        $print =  route('admin.lulusan.printskpi', $row->nim);

                        $actionBtn =
                            "<div class='btn-group' role='group' aria-label='Action'>
                                <a role='button' class='btn btn-icon btn-info' href='$edit_url' data-bs-tooltip='tooltip' data-bs-offset='0,8' data-bs-placement='top' data-bs-custom-class='tooltip-info' title='Nilai Mahasiswa'>
                                <span class='tf-icons fa-solid fa-circle-info'></span>
                                </a>
                                <a role='button' class='btn btn-icon btn-success' href='$cpl_url' data-bs-tooltip='tooltip' data-bs-offset='0,8' data-bs-placement='top' data-bs-custom-class='tooltip-success' title='CPL'>
                                CPL
                                </a>
                                <a role='button' class='btn btn-icon btn-light' href='$print' data-bs-tooltip='tooltip' data-bs-offset='0,8' data-bs-placement='top' data-bs-custom-class='tooltip-danger' data-nama='$row->nim' title='Print SKPI'>
                                <span class='tf-icons fa-solid fa-print'></span>
                                </a>
                                <a role='button' class='btn btn-icon btn-danger del-btn' href='$delete_url' data-bs-tooltip='tooltip' data-bs-offset='0,8' data-bs-placement='top' data-bs-custom-class='tooltip-danger' data-nama='$row->nim' title='Hapus Lulusan'>
                                <span class='tf-icons fa-solid fa-trash'></span>
                                </a>
                               
                                </div>";
                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function storelulusan(Request $request)
    {
        if (Session::has('data')) {
            $sesi = Session::get('data');
            $datamhs = explode('|', $request->nim);
            $data = [
                'nim'       => $datamhs[0],
                'nama_mhs'  => $datamhs[1],
                'semester_lulus'  => str_replace('/', '', $request->semester_lulus),
                'idprodi'   => $sesi['idprodi'],
                'idfakultas' => $sesi['idfakultas']
            ];
            $query = Lulusan::insert($data);
            if ($query) {
                return redirect()->back()->with('success', 'Success add');
            } else {
                return redirect()->back()->with('error', 'Something wrong !');
            }
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function deletelulusan($id)
    {
        if (Session::has('data')) {
            $query = Lulusan::where('id', $id)->delete();
            if ($query) {
                return redirect()->back()->with('success', 'Success delete');
            } else {
                return redirect()->back()->with('error', 'Something wrong !');
            }
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    public function printskpi($nim)
    {
        $data = Lulusan::join('prodi', 'lulusan.idprodi', '=', 'prodi.id')->join('fakultas', 'prodi.id_fakultas', '=', 'fakultas.id')->where('nim', $nim)->first();
        $cpl = CPL::orderByRaw('CAST(SUBSTRING(kode_cpl,5,2) AS INT)')->get();
        $appdata = [
            'title' => 'Data Lulusan',
            'sesi'  => Session::get('data')
        ];

        $res = Http::post(config('app.urlApi') . 'mahasiswa/matkul-mhs', [
            'APIKEY'    => config('app.APIKEY'),
            'nrp'       => $nim,
        ]);
        $json = $res->json();
        $nilaimhs = $json['data'];

        $datacpl = [
            'mhs'   => $nim,
            'cpl'   => CPL::where([
                'idprodi' => $appdata['sesi']['idprodi'],
                'idfakultas' => $appdata['sesi']['idfakultas']
            ])->orderByRaw('CAST(SUBSTRING(kode_cpl,5,2) AS INT)')->get(),
        ];

        $datacpl_all = CPL::where([
            'idprodi' => $appdata['sesi']['idprodi'],
            'idfakultas' => $appdata['sesi']['idfakultas']
        ])->orderByRaw('CAST(SUBSTRING(kode_cpl,5,2) AS INT)', 'asc')->get();
        $data_json = [];
        $label = [];
        $persen = [];
        foreach ($datacpl_all as $c) {
            $bobotCPL = getNilaiCPL($c->id, $nim);
            $persenCPL = round((getNilaiCPL($c->id, $nim) / 4) * 100);
            array_push($data_json, $bobotCPL);
            array_push($label, '"' . $c->kode_cpl . '"');
            array_push($persen, $persenCPL);
        }
        // dd(implode(',', $label));
        $chartConfig = '{
            "type": "radar",
            "data": {
                "labels": [' . implode(',', $label) . '],
                "datasets": [{
                    "label": "CPL",
                    "data": [' . implode(',', $data_json) . '],
                    "fill": true,
                    "backgroundColor": "rgba(242, 145, 0, 0.2)",
                    "borderColor":"rgb(242, 145, 0)",
                }]
            },
            "options": {
                "scale":{
                "r": {
                    "angleLines": {
                        "display": true
                    },
                    "suggestedMin": 0,
                    "suggestedMax": 4
                    }
                }
            }
        }';
        $chartUrl = 'https://quickchart.io/chart?chart=' . $chartConfig . '&backgroundColor=white&weight=550&height=350&devicePixelRatio=1.0&format=png&version=3.9.1';

        // dd($chartUrl);

        $pdf = PDF::loadview('admin.lulusan_print', compact('data', 'cpl', 'datacpl', 'chartUrl'))->setPaper('A4', 'potrait')->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        return $pdf->stream();
    }
}
