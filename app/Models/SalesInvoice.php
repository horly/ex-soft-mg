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
        'sub_total',
        'total',
        'vat_amount',
        'amount_received',
        'id_client',
        'id_user',
        'id_fu',
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
