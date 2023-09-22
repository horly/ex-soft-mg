<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviseGestionUF extends Model
{
    use HasFactory;
    protected $table = "devise_gestion_ufs";

    protected $fillable = [
        'taux',
        'id_devise',
    ];

    /** Une devise de gestion appartient à une devise */
    function devise()
    {
        return $this->belongsTo('App\Models\Devise', 'id_devise');
    }
}
