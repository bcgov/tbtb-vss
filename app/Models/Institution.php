<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    public function incident()
    {
        return $this->belongsTo('App\Models\Incident', 'incident_id', 'incident_id');
    }
}
