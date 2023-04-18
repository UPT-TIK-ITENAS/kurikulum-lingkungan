<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CPL;
use App\Models\MKPilihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Session;

class MKPilihanController extends Controller
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
                'title' => 'Data Mata Kuliah Pilihan',
                'sesi'  => Session::get('data')
            ];
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
            $data = $data->where('wbpiltbkur', 'P');
            $mkpilihan = getMK();
            $mkpilihan = $mkpilihan->where('wbpiltbkur', 'P');

            $pilihanMK = MKPilihan::where([
                'idprodi' => $appdata['sesi']['idprodi'],
            ])->get();

            $pilihan = array();
            foreach ($pilihanMK as $b) {
                $pilihan[$b->kode_mk] = $b->kategori;
            }

            // dd($pilihan);

            return view('admin.mkp_index', compact('appdata', 'data', 'mkpilihan', 'pilihan'));
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
        if (Session::has('data')) {
            $sesi = Session::get('data');
            foreach ($request->data as $key) {

                $data['kode_mk'] = $key['kode_mk'];
                $data['kategori'] = $key['kategori'];
                $data['idprodi'] = $sesi['idprodi'];
                $data['idfakultas'] = $sesi['idfakultas'];

                MKPilihan::updateOrCreate(
                    [
                        'idprodi' => $data['idprodi'],
                        'idfakultas' => $data['idfakultas'],
                        'kode_mk' =>  $data['kode_mk'],
                    ],
                    [
                        'kategori' => $data['kategori'],
                    ]
                );
            }
            return response()->json(['success' => 'Berhasil']);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
    }
}
