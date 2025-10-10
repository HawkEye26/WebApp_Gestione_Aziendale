<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'address',
        'zip_code',
        'city',
        'province',
        'region',
        'email',
    ];

    // funzione per salvare nel database la provincia in maiuscolo
     public function setProvinceAttribute($value)
    {
        $this->attributes['province'] = strtoupper($value);
    }

    // funzione che salva la regione con la prima lettera in maiuscolo
    public function setRegionAttribute($value)
    {
        $this->attributes['region'] = ucfirst(strtolower($value));
    }
}
