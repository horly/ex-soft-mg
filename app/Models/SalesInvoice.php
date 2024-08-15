<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    use HasFactory;

    protected $table = "sales_invoices";

    protected $fillable = [
        'reference_sales_invoice',
        'reference_number',
        'concern_invoice',
        'sub_total',
        'total',
        'vat_amount',
        'amount_received',
        'discount_choice',
        'discount_type',
        'discount_value',
        'vat',
        'discount_apply_amount',
        'is_proforma_inv',
        'is_delivery_note',
        'is_simple_invoice',
        'id_client',
        'id_user',
        'id_fu',
        'id_contact',
        'due_date',
    ];

    function client()
    {
        return $this->belongsTo('App\Models\Client', 'id_client');
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
