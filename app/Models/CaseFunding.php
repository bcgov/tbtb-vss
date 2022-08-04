<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaseFunding extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['incident_id', 'application_number', 'funding_type', 'fund_entry_date', 'over_award', 'prevented_funding', 'deleted_by_user_id'];

    public function incident()
    {
        return $this->belongsTo('App\Models\Incident', 'incident_id', 'incident_id');
    }

    public function fundingType()
    {
        return $this->belongsTo('App\Models\FundingType', 'funding_type', 'funding_type');
    }
}
