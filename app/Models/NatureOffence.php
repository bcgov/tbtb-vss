<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NatureOffence extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nature_code', 'description'];

    public function offences()
    {
        return $this->hasMany('App\Models\CaseNatureOffence', 'nature_code', 'nature_code');
    }
}
