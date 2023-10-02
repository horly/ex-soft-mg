<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = "countries";

    protected $fillable = [
        'code',
        'alpha2',
        'alpha3',
        'name_gb',
        'name_fr',
        'telephone_code',
        'vat_rat',
    ];

        /** Un pays possède plusieurs entreprise */
        public function companies()
        {
            return $this->hasMany('App\Models\Entreprise');
        }

        /** Un user possède plusieurs users */
        public function users()
        {
            return $this->hasMany('App\Models\User');
        }
  
}
