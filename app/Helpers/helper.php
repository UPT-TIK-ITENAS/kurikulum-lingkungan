<?php

use App\Models\Bobot;
use App\Models\CE;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

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

        return $total_nilai[0]->totalbobot;
    }
}
