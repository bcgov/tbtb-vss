<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['institution_code', 'institution_name', 'institution_location_code', 'institution_type_code'];

    public function incidents()
    {
        return $this->hasMany('App\Models\Incident', 'institution_code', 'institution_code');
    }
}
