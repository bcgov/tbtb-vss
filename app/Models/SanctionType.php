<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanctionType extends Model
{
    use HasFactory;

    public function sanctions()
    {
        return $this->hasMany('App\Models\CaseSanctionType', 'incident_id', 'incident_id');
    }
}
