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
        return $this->hasMany(BobotMK::class, 'id_cpl', 'kode_cpl');
    }

    public function bobotCPL()
    {
        return $this->hasMany(BobotCPL::class, 'id_cpl', 'id_cpl');
    }

    public function bobotCPLPadu()
    {
        return $this->hasMany(BobotCPLPadu::class, 'id_cpl', 'id_cpl');
    }
}
