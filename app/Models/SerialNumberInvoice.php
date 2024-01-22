<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerialNumberInvoice extends Model
{
    use HasFactory;

    protected $table = "serial_number_invoices";

    protected $fillable = [
        'description',
        'id_invoice_element',
    ];

    function invoiceElement()
    {
        return $this->belongsTo('App\Models\InvoiceElement', 'id_invoice_element');
    }
}
