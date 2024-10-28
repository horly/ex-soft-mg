<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTermsAssign extends Model
{
    use HasFactory;

    protected $table = "payment_terms_assigns";

    protected $fillable = [
        'purcentage',
        'day_number',
        'id_payment_terms',
        'ref_invoice'
    ];

    function payment_terms_proformas()
    {
        return $this->belongsTo('App\Models\PaymentTermsProforma', 'id_payment_terms');
    }
}
