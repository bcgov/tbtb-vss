<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseAuditType extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['incident_id', 'area_of_audit_code', 'audit_type'];


    public function incident()
    {
        return $this->belongsTo('App\Models\Incident', 'incident_id', 'incident_id');
    }

    public function audit()
    {
        return $this->belongsTo('App\Models\AreaOfAudit', 'area_of_audit_code', 'area_of_audit_code');
    }
}
