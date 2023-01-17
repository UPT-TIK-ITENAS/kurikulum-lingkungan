<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IK extends Model
{
    use HasFactory;
    protected $table = 'ik';
    protected $guarded = [];

    public function cpl()
    {
        return $this->belongsTo('App\Models\CPL', 'ik_id_cpl', 'id');
    }
}
