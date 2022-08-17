<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaOfAudit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['area_of_audit_code', 'description'];

    public function incidents()
    {
        return $this->hasMany('App\Models\Incident', 'area_of_audit_code', 'area_of_audit_code');
    }

    public function caseAuditTypes()
    {
        return $this->hasMany('App\Models\CaseAuditType', 'area_of_audit_code', 'area_of_audit_code');
    }
}
