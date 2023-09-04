<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'id_entreprise',
    ];

     /** Un compte appartient à une entrepriser */
     function entreprise()
    {
        return $this->belongsTo('App\Models\Entreprise', 'id_entreprise');
    }
}
