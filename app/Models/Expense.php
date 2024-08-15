<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $table = "expenses";

    protected $fillable = [
        'description',
        'reference_exp',
        'amount',
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
