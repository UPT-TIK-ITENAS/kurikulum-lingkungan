<?php

use App\Models\CE;
use Illuminate\Support\Facades\Http;

if (!function_exists('getBobot')) {
    function getBobot($ce, $cpmk, $cpl)
    {
        $cek_matriks = CE::where([
            'id'        => $ce,
            'idcpmk'    => $cpmk,
            'idcpl'     => $cpl
        ])->first();
        if (empty($cek_matriks)) {
            $td = "";
        } else {
            $td = $cek_matriks->bobot_cpl;
        }
        return $td;
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
        foreach ($getCE as $ce) {
        }
    }
}
