<?php

use App\Models\Bobot;
use App\Models\BobotCPL;
use App\Models\BobotCPLPadu;
use App\Models\BobotMK;
use App\Models\CE;
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
