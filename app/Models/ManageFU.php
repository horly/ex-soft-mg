<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageFU extends Model
{
    use HasFactory;

    protected $table = "manage_f_u_s";

    protected $fillable = [
        'id_user',
        'id_entreprise',
        'id_fu',
    ];

    /** Une gestion appartient à un user */
    function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    /** Une gestion appartient à une entreprise */
    function entreprise()
    {
        return $this->belongsTo('App\Models\Entreprise', 'id_entreprise');
    }

     /** Une gestion appartient à une fu */
     function functionalunit()
     {
         return $this->belongsTo('App\Models\FunctionalUnit', 'id_fu');
     }
}
