<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceElement extends Model
{
    use HasFactory;

    protected $table = "invoice_elements";

    protected $fillable = [
        'ref_invoice',
        'ref_article',
        'ref_service',
        'description_inv_elmnt',
        'quantity',
        'purshase_price_inv_elmnt',
        'unit_price_inv_elmnt',
        'total_price_inv_elmnt',
        'is_an_article',
        'id_marge',
        'id_user',
        'id_fu',
    ];

    function invoiceMargin()
    {
        return $this->belongsTo('App\Models\InvoiceMargin', 'id_marge');
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
