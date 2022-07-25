<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseSanctionType extends Model
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
    protected $fillable = ['incident_id', 'sanction_code'];

    public function incident()
    {
        return $this->belongsTo('App\Models\Incident', 'incident_id', 'incident_id');
    }


    public function sanction()
    {
        return $this->belongsTo('App\Models\SanctionType', 'sanction_code', 'sanction_code');
    }
}
