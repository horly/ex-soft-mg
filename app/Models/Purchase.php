<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $table = "purchases";

    protected $fillable = [
        'reference_purch',
        'designation',
        'amount',
        'id_supplier',
        'id_user',
        'id_fu',
        'due_date',
    ];

    function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'id_supplier');
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
