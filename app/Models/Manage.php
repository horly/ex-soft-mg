<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manage extends Model
{
    use HasFactory;

    protected $table = "manages";

    protected $fillable = [
        'id_user',
        'id_entreprise',
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
}
