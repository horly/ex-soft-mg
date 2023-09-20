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
}
