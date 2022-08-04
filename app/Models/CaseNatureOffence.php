<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseNatureOffence extends Model
{
    use HasFactory;

    protected $primaryKey = null;

    public $incrementing = false;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['incident_id', 'nature_code'];

    public function incident()
    {
        return $this->belongsTo('App\Models\Incident', 'incident_id', 'incident_id');
    }

    public function offence()
    {
        return $this->belongsTo('App\Models\NatureOffence', 'nature_code', 'nature_code');
    }
}
