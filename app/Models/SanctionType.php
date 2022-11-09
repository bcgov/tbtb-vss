<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanctionType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['sanction_code', 'description', 'short_description', 'disabled'];

    public function sanctions()
    {
        return $this->hasMany('App\Models\CaseSanctionType', 'incident_id', 'incident_id');
    }

}
