<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'start_date',
        'end_date',
    ];


    /** Un abonnement contient plusieurs users */
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

     /** Un abonnement contient entreprises users */
     public function entreprises()
     {
         return $this->hasMany('App\Models\Entreprise');
     }
}
