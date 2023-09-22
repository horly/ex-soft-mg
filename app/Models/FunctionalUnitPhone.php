<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FunctionalUnitPhone extends Model
{
    use HasFactory;

    protected $table = "functional_unit_phones";

    protected $fillable = [
        'phone_number',
        'id_func_unit',
    ];

    /** Une numéro appartient à une UF */
    function functionalUnit()
    {
        return $this->belongsTo('App\Models\FunctionalUnit', 'id_func_unit');
    }
}
