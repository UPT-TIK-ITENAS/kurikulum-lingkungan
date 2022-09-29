<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CE;
use App\Models\CPL;
use Illuminate\Http\Request;
use Session;

class CEController extends Controller
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
                'title' => 'Matriks Course Evaluation',
                'sesi'  => Session::get('data')
            ];
            $data    = [
                'cpl'   =>  CPL::where(
                    [
                        'idprodi' => $appdata['sesi']['idprodi'],
                        'idfakultas' => $appdata['sesi']['idfakultas']
                    ]
                )->orderByRaw('CAST(SUBSTRING(kode_cpl,5,2) AS INT)', 'asc')->get(),

                'ce'    => CE::select('ce_mk.*', 'ce_mk.id as idce', 'cpmk.id as cpmk_id', 'cpmk.idmatakuliah', 'cpmk.nama_matkul', 'cpmk.kode_cpmk', 'cpmk.sks')->join('cpmk', 'cpmk.id', '=', 'ce_mk.idcpmk')->where(
                    [
                        'idprodi' => $appdata['sesi']['idprodi'],
                        'idfakultas' => $appdata['sesi']['idfakultas']
                    ]
                )->orderBy('ce_mk.idmatakuliah', 'asc')
                    ->get()
            ];

            return view('admin.matriks_ce', compact('appdata', 'data'));
        } else {
            return redirect()->route('login')->with('error', 'You are not authenticated');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
