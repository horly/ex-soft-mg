<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FunctionalUnit extends Model
{
    use HasFactory;

    protected $table = "functional_units";

    protected $fillable = [
        'name',
        'address',
        'id_entreprise',
    ];

    /** Une entreprise appartient à un subscription */
    function entreprise()
    {
        return $this->belongsTo('App\Models\Entreprise', 'id_entreprise');
    }

    /** Une UF possède plusieurs numéro de téléphone */
    public function phones()
    {
        return $this->hasMany('App\Models\FunctionalUnitPhone');
    }

    /** Une UF possède plusieurs numéro de téléphone */
    public function emails()
    {
        return $this->hasMany('App\Models\FunctionalunitEmail');
    }

    /** Un user gère une FU*/
    public function manageFU()
    {
        return $this->hasMany('App\Models\ManageFU');
    }

    public function deviseGestionFU()
    {
        return $this->hasMany('App\Models\DeviseGestionUF');
    }

    public function client()
    {
        return $this->hasMany('App\Models\Client');
    }
}

