<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralSource extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['referral_code', 'description'];

    public function incidents()
    {
        return $this->hasMany('App\Models\Incident', 'referral_source_id', 'id');
    }
}
