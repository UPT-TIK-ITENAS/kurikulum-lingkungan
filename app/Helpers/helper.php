<?php

use App\Models\Bobot;
use App\Models\BobotCPL;
use App\Models\BobotCPLPadu;
use App\Models\BobotMK;
use App\Models\CE;
use App\Models\MKPilihan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;


if (!function_exists('getMK')) {
    function getMK()
    {
        $res = Http::post(config('app.urlApi') . 'dosen/matkul-prodi', [
            'APIKEY'    => config('app.APIKEY'),
            'tahun'     => config('app.tahun_kurikulum'),
            'prodi'     => Session::get('data')['idprodi'],
        ]);
        $json = $res->json();
        $data = $json['data'];
        $data1 = collect($data)->filter(function ($item) {
            return stristr($item['kdkmktbkmk'], Session::get('data')['kode']);
        });
        $counter = 1;
        $counter1 = 1;
        $data1 = $data1->map(function ($item) use (&$counter, &$counter1) {


            if ($item['wbpiltbkur'] == 'P') {
                return [
                    'kdkmktbkmk' => 'MKP-' . '' . $counter1++,
                    'nakmktbkmk' => 'Mata Kuliah Pilihan' . ' ' . $counter++,
                    'nakmitbkmk' => 'Mata Kuliah Pilihan',
                    'sksmktbkmk' => $item['sksmktbkmk'],
                    'wbpiltbkur' => $item['wbpiltbkur'],
                    'prodi' => $item['prodi'],
                    'kdfaktbkur' => $item['kdfaktbkur'],
                    'kdjurtbkur' => $item['kdjurtbkur'],
                    'kodemkasli' => $item['kdkmktbkmk'],
                    'namamkasli' => $item['nakmktbkmk'],
                ];
            } else {
                return [
                    'kdkmktbkmk' => $item['kdkmktbkmk'],
                    'nakmktbkmk' => $item['nakmktbkmk'],
                    'nakmitbkmk' => $item['nakmitbkmk'],
                    'sksmktbkmk' => $item['sksmktbkmk'],
                    'wbpiltbkur' => $item['wbpiltbkur'],
                    'prodi' => $item['prodi'],
                    'kdfaktbkur' => $item['kdfaktbkur'],
                    'kdjurtbkur' => $item['kdjurtbkur']
                ];
            }
            // end foreach


        });
        // dd($data1);
        return $data1;
    }
}

if (!function_exists('getBobot')) {
    function getBobot($ce, $cpmk, $cpl, $tipe)
    {
        $cek_matriks = CE::where([
            'id'        => $ce,
            'idcpmk'    => $cpmk,
            'idcpl'     => $cpl
        ])->first();

        if ($tipe == 'totalbobot') {
            $bobot = CE::where('idcpl', $cpl)->sum('bobot_cpl');
            return $bobot;
        } elseif ($tipe == 'bobot') {
            if (empty($cek_matriks)) {
                $td = "";
            } else {
                $td = $cek_matriks->bobot_cpl;
            }
            return $td;
        }
    }
}

if (!function_exists('getBobotCpl')) {
    function getBobotCpl($mk, $cpl, $tipe)
    {
        $cek_matriks = CE::where([
            // 'id'        => $ce,
            'idmatakuliah'  => $mk,
            'idcpl'     => $cpl
        ])->groupby('idcpl')->sum('bobot_cpmk');
        // return $cek_matriks;
        if ($tipe == 'totalbobot') {
            $bobot = CE::where('idmatakuliah', $mk)->sum('bobot_cpmk');
            return round($bobot, 0) . " %";
        } elseif ($tipe == 'bobot') {

            if (empty($cek_matriks)) {
                $td = "";
            } else {
                $td = round($cek_matriks, 0) . " %";
            }
            return $td;
        }
    }
}


if (!function_exists('getNilaiCPL')) {
    function getNilaiCPL($cpl, $nim)
    {
        $getCE = CE::where([
            'idcpl'     => $cpl
        ])->get();
        $res = Http::post(config('app.urlApi') . 'mahasiswa/matkul-mhs', [
            'APIKEY'    => config('app.APIKEY'),
            'nrp'       => $nim,
        ]);
        $json = $res->json();
        $nilaimhs = $json['data'];
        // dd($res);
        $total_nilai = 0;
        $array_nilai = [];
        for ($i = 0; $i < count($getCE); $i++) {
            for ($j = 0; $j < count($nilaimhs); $j++) {
                if ($getCE[$i]['idmatakuliah'] == $nilaimhs[$j]['KDKMKHSNIL']) {
                    $bobot = $getCE[$i]['bobot_cpl'] * $nilaimhs[$j]['BOBOTHSNIL'];
                    $total_nilai += $bobot;
                    array_push($array_nilai, $bobot);
                }
            }
        }

        return $total_nilai;
    }
}

if (!function_exists('getNilaiCPLBySemester')) {
    function getNilaiCPLBySemester($cpl, $nim, $semester)
    {
        $getCE = CE::where([
            'idcpl'     => $cpl
        ])->get();
        $res = Http::post(config('app.urlApi') . 'mahasiswa/matkul-mhs-semester', [
            'APIKEY'    => config('app.APIKEY'),
            'nrp'       => $nim,
            'semester'  => $semester
        ]);
        $json = $res->json();
        $nilaimhs = $json['data'];

        $total_nilai = 0;
        $array_nilai = [];
        for ($i = 0; $i < count($getCE); $i++) {
            for ($j = 0; $j < count($nilaimhs); $j++) {
                if ($getCE[$i]['idmatakuliah'] == $nilaimhs[$j]['KDKMKHSNIL']) {
                    $bobot = $getCE[$i]['bobot_cpl'] * $nilaimhs[$j]['BOBOTHSNIL'];
                    $total_nilai += $bobot;
                    array_push($array_nilai, $bobot);
                }
            }
        }

        return $total_nilai;
    }
}
if (!function_exists('totalCPMK')) {
    function totalCPMK($cpmk)
    {
        $appdata = [
            'title' => 'Kelola Bobot',
            'sesi'  => Session::get('data'),
        ];
        $total_nilai = Bobot::selectRaw('sum(bobot) as totalbobot')->where([
            'idprodi' => $appdata['sesi']['idprodi'],
            'id_cpmk' => $cpmk
        ])->groupby('id_cpmk')->get();


        return !empty($total_nilai[0]) ? $total_nilai[0]->totalbobot : 0;
    }
}

if (!function_exists('totalBobotCPLPaduPerMK')) {
    function totalBobotCPLPaduPerMK($mk, $tipe)
    {
        $appdata = [
            'title' => 'Kelola Bobot',
            'sesi'  => Session::get('data'),
        ];

        if ($tipe == 'cpl_padu') {
            $total_nilai = BobotCPLPadu::selectRaw('sum(bobot) as totalbobot')->where([
                'idprodi' => $appdata['sesi']['idprodi'],
                'idmatakuliah' => $mk,
            ])->groupby('idmatakuliah')->get();
        } elseif ($tipe == 'mk') {
            $total_nilai = BobotMK::selectRaw('sum(bobot_mk) as totalbobot')->where([
                'idprodi' => $appdata['sesi']['idprodi'],
                'idmatakuliah' => $mk,
            ])->groupby('idmatakuliah')->get();
        } elseif ($tipe == 'cpl') {
            $total_nilai = BobotCPL::selectRaw('sum(bobot_cpl) as totalbobot')->where([
                'idprodi' => $appdata['sesi']['idprodi'],
                'idmatakuliah' => $mk,
            ])->groupby('idmatakuliah')->get();
        }



        return !empty($total_nilai[0]) ? $total_nilai[0]->totalbobot : 0;
    }
}

if (!function_exists('totalBobotCPLPaduPerCPL')) {
    function totalBobotCPLPaduPerCPL($cpl)
    {
        $appdata = [
            'title' => 'Kelola Bobot',
            'sesi'  => Session::get('data'),
        ];
        $total_nilai = BobotCPLPadu::selectRaw('sum(bobot) as totalbobot')->where([
            'idprodi' => $appdata['sesi']['idprodi'],
            'id_cpl' => $cpl
        ])->groupby('id_cpl')->get();


        return !empty($total_nilai[0]) ? round($total_nilai[0]->totalbobot) : 0;
    }
}

if (!function_exists('totalBobotPerCPL')) {
    function totalBobotPerCPL($cpl)
    {
        $appdata = [
            'title' => 'Kelola Bobot',
            'sesi'  => Session::get('data'),
        ];
        $total_nilai = BobotCPL::selectRaw('sum(bobot_cpl) as totalbobot')->where([
            'idprodi' => $appdata['sesi']['idprodi'],
            'id_cpl' => $cpl
        ])->groupby('id_cpl')->get();


        return !empty($total_nilai[0]) ? $total_nilai[0]->totalbobot : 0;
    }
}

if (!function_exists('totalBobotPerMK')) {
    function totalBobotPerMK($id_matakuliah)
    {
        $appdata = [
            'title' => 'Kelola Bobot',
            'sesi'  => Session::get('data'),
        ];
        $total_nilai = BobotMK::selectRaw('sum(bobot_mk) as totalbobot')->where([
            'idprodi' => $appdata['sesi']['idprodi'],
            'idmatakuliah' => $id_matakuliah
        ])->groupby('idmatakuliah')->get();


        return !empty($total_nilai[0]) ? $total_nilai[0]->totalbobot : 0;
    }
}

if (!function_exists('getNilaiBobotSC')) {
    function getNilaiBobotSC($mk, $nrp)
    {
        $getSC = Bobot::where([
            'idmatakuliah'     => $mk
        ])->get();

        $res = Http::post(config('app.urlApi') . 'dosen/akm-tengah', [
            'APIKEY'    => config('app.APIKEY'),
            'nrp'       => $nrp,
        ]);
        $json = $res->json();
        $nilaimhs = $json['data'];
        $nilaimhs = collect($nilaimhs);
        $nilaimhs = $nilaimhs->map(function ($item) use ($mk) {

            $data_sc = DB::table('bobot')->where('idmatakuliah', '=',  $mk)->get();
            // sum bobot by subcpmk_kode
            $data_sc = $data_sc->groupBy('subcpmk_kode')->map(function ($item) {
                // $item = $item->sum('bobot') ;
                return $item->sum('bobot') / 100;
            });

            return [
                'nimhsMSMHS' => $item['nimhsMSMHS'],
                'nmmhsMSMHS' => $item['nmmhsMSMHS'],
                'kdkmkMSAKM' => $item['kdkmkMSAKM'],
                'nakmktbkmk' => $item['nakmktbkmk'],
                'seksiMSAKM' => $item['seksiMSAKM'],
                'JumlahPengganti' => $item['JumlahPengganti'],
                'JumlahReguler' => $item['JumlahReguler'],
                'subcpmk_kode' => [
                    'SC1' => $item['SC1']  * $data_sc['SC1'],
                    'SC2' => $item['SC2']  * $data_sc['SC2'],
                    'SC3' => $item['SC3']  * $data_sc['SC3'],
                    'SC4' => $item['SC4']  * $data_sc['SC4'],
                    'SC5' => $item['SC5']  * $data_sc['SC5'],
                ]
            ];
        });

        // $total_nilai = 0;
        // $array_nilai = [];
        // for ($i = 0; $i < count($getSC); $i++) {
        //     for ($j = 0; $j < count($nilaimhs); $j++) {
        //         if ($getSC[$i]['idmatakuliah'] == $nilaimhs[$j]['kdkmkMSAKM']) {
        //             if($getSC[$i]['subcpmk_kode'] == $nilaimhs[$j]['subcpmk_kode']){
        //                 $nilaimhs[$j]['subcpmk_kode'] * $getSC[$i]['subcpmk_kode'];
        //                 $total_nilai += $bobot;
        //                 array_push($array_nilai, $bobot);
        //             }
        //         }
        //     }
        // }

        return $nilaimhs[0];
    }
}

if (!function_exists('totalCPL')) {
    function totalCPL($nrp)
    {

        $res = Http::post(config('app.urlApi') . 'dosen/akm-tengah', [
            'APIKEY'    => config('app.APIKEY'),
            'nrp'       => $nrp,
        ]);
        $json = $res->json();
        $nilaimhs = $json['data'];
        $nilai = collect($nilaimhs);
        $mappedNilai = $nilai->map(function ($item) {
            $mkp = MKPilihan::where('kode_mk', $item['kdkmkMSAKM'])->first();
            if ($mkp) {
                $data_sc = DB::table('bobot')->where('idmatakuliah', $mkp->kategori)->where('idprodi', session()->get('data')->idprodi)->get();
            } else {
                $data_sc = DB::table('bobot')->where('idmatakuliah', '=', $item['kdkmkMSAKM'])->get();
            }
            // sum bobot by subcpmk_kode
            $data_sc = $data_sc->groupBy('subcpmk_kode')->map(function ($item) {
                // $item = $item->sum('bobot') ;
                return $item->sum('bobot') / 100;
            });
            $data_sc = $data_sc->map(function ($item2, $key) use ($item) {
                if (array_key_exists($key, $item)) {
                    return $item2 * $item[$key];
                } else {
                    return 0;
                }
            })->sum();

            $bobot_cpl = DB::table('bobot_cpl')->where('idmatakuliah', $item['kdkmkMSAKM'])->get();
            $mappedBobotCpl = $bobot_cpl->map(function ($item, $key) use ($data_sc) {
                $item->hasil = (($item->bobot_cpl) / 100) * $data_sc;
                return $item = [
                    'idcpl' => $item->id_cpl,
                    'hasil' => $item->hasil,

                ];
            });
            $item['data_sc'] = $mappedBobotCpl ?? 0;

            return $item;
        });

        $groupedData = collect($mappedNilai)->flatMap(function ($item) {
            return $item['data_sc'];
        })->groupBy('idcpl')->map(function ($group) {
            return [
                'idcpl' => $group[0]['idcpl'],
                'total' => $group->sum('hasil'),
            ];
        })->values()->toArray();


        return $groupedData;
    }
}
