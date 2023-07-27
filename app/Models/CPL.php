<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CPL extends Model
{
    protected $table = 'cpl';
    protected $guarded = [];

    public function bobot_mk()
    {
        return $this->hasMany('App\Models\BobotMK', 'id_cpl', 'kode_cpl');
    }
}
