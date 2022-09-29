<?php

use App\Models\CE;

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
