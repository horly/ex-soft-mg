<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseMargin extends Model
{
    protected $table = "purchase_margins";

    use HasFactory;

    protected $fillable = [
        'ref_purchase',
        'margin',
        'is_simple_purchase',
        'is_purchase_order',
        'purchase_saved',
        'id_user',
        'id_fu',
    ];

    function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }
 
    function functionalUit()
    {
        return $this->belongsTo('App\Models\FunctionalUnit', 'id_fu');
    }
}
