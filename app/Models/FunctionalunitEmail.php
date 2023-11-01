<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FunctionalunitEmail extends Model
{
    use HasFactory;
    protected $table = "functionalunit_emails";

    protected $fillable = [
        'email',
        'id_func_unit',
    ];

    /** Un email appartient à une UF */
    function functionalUnit()
    {
        return $this->belongsTo('App\Models\FunctionalUnit', 'id_func_unit');
    }
}
