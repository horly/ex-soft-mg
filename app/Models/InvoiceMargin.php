<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceMargin extends Model
{
    use HasFactory;

    protected $table = "invoice_margins";

    protected $fillable = [
        'ref_invoice',
        'margin',
        'is_proforma',
        'is_delivery_note_marge',
        'is_simple_invoice_inv',
        'invoice_saved',
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

    public function invoiceElement()
    {
        return $this->hasMany('App\Models\InvoiceElement');
    }
}
