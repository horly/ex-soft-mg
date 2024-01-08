<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encaissement extends Model
{
    use HasFactory;

    protected $table = "encaissements";

    protected $fillable = [
        'description',
        'reference_enc',
        'is_invoice',
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
