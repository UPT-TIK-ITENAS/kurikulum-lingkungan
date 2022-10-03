<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Session;
use Yajra\DataTables\DataTables;

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
                'sesi'  => Session::get('data')
            ];
            return view('admin.dashboard', compact('appdata'));
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
            'tahun'     => 2017,
            'prodi'     => Session::get('data')['idprodi'],
        ]);
        $json = $res->json();
        $data = $json['data'];
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
                ->make(true);
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
                'title' => 'Data Mahasiswa',
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
