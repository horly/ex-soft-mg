<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decaissement extends Model
{
    use HasFactory;

    protected $table = "decaissements";

    protected $fillable = [
        'description',
        'reference_dec',
        'is_purchase',
        'amount',
        'id_pay_meth',
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

    function paymentMethode()
    {
        return $this->belongsTo('App\Models\PaymentMethod', 'id_pay_meth');
    }
}
