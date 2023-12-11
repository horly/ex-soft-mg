<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = "services";

    protected $fillable = [
        'reference_serv',
        'reference_number',
        'description_serv',
        'unit_price',
        'id_cat',
        'id_user',
        'id_fu',
    ];

    public function catService()
    {
        return $this->belongsTo('App\Models\CategoryService', 'id_cat');
    }

    function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }
 
    function functionalUit()
    {
        return $this->belongsTo('App\Models\FunctionalUnit', 'id_fu');
    }
}
