<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessContact extends Model
{
    use HasFactory;

    protected $table = "business_contacts";

    protected $fillable = [
        'phone_number',
        'id_entreprise',
    ];

     /** Une numéro appartient à une entrepriser */
     function entreprise()
    {
        return $this->belongsTo('App\Models\Entreprise', 'id_entreprise');
    }
}
