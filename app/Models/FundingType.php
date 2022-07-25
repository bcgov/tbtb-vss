<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundingType extends Model
{
    use HasFactory;


    public function caseFundings()
    {
        return $this->hasMany('App\Models\CaseFunding','funding_type', 'funding_type');
    }
}
