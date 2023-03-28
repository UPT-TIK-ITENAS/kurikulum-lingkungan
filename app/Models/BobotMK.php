<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BobotMK extends Model
{
    protected $table = 'bobot_mk';
    protected $guarded = [];


    public function cpl()
    {
        return $this->belongsTo('App\Models\CPL', 'id_cpl', 'kode_cpl');
    }
}
