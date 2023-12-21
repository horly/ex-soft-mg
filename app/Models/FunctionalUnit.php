<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FunctionalUnit extends Model
{
    use HasFactory;

    protected $table = "functional_units";

    protected $fillable = [
        'name',
        'address',
        'id_entreprise',
    ];

    /** Une entreprise appartient à un subscription */
    function entreprise()
    {
        return $this->belongsTo('App\Models\Entreprise', 'id_entreprise');
    }

    /** Une UF possède plusieurs numéro de téléphone */
    public function phones()
    {
        return $this->hasMany('App\Models\FunctionalUnitPhone');
    }

    /** Une UF possède plusieurs numéro de téléphone */
    public function emails()
    {
        return $this->hasMany('App\Models\FunctionalunitEmail');
    }

    /** Un user gère une FU*/
    public function manageFU()
    {
        return $this->hasMany('App\Models\ManageFU');
    }

    public function deviseGestionFU()
    {
        return $this->hasMany('App\Models\DeviseGestionUF');
    }

    public function clients()
    {
        return $this->hasMany('App\Models\Client');
    }

    public function suplliers()
    {
        return $this->hasMany('App\Models\Supplier');
    }

    public function creditors()
    {
        return $this->hasMany('App\Models\Creditor');
    }

    public function debtors()
    {
        return $this->hasMany('App\Models\Debtor');
    }

    public function articleCategory()
    {
        return $this->hasMany('App\Models\CategoryArticle');
    }

    public function articleSubCategory()
    {
        return $this->hasMany('App\Models\SubcategoryArticle');
    }

    public function article()
    {
        return $this->hasMany('App\Models\Article');
    }

    public function serviceSubCategory()
    {
        return $this->hasMany('App\Models\CategoryService');
    }

    public function service()
    {
        return $this->hasMany('App\Models\Service');
    }

    public function paymentMethod()
    {
        return $this->hasMany('App\Models\PaymentMethod');
    }

    public function salesInvoice()
    {
        return $this->hasMany('App\Models\SalesInvoice');
    }

    public function invoiceMargin()
    {
        return $this->hasMany('App\Models\InvoiceMargin');
    }

    public function invoiceElement()
    {
        return $this->hasMany('App\Models\InvoiceElement');
    }
}

