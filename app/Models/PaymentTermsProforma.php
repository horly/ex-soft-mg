<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTermsProforma extends Model
{
    use HasFactory;

    protected $table = "payment_terms_proformas";

    protected $fillable = [
        'description',
    ];

    public function payment_terms_assigns()
    {
        return $this->hasMany('App\Models\PaymentTermsAssign');
    }
}
