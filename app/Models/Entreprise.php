<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rccm',
        'id_nat',
        'nif',
        'address',
        'website',
        'slogan',
        'url_logo',
        'id_user',
        'id_country',
        'sub_id',
    ];

    /** Une entreprise appartient à un user */
    function role()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    /** Une entrprise possède plusieurs numéro de téléphone */
    public function contact()
    {
        return $this->hasMany('App\Models\BusinessContact');
    }

    /** Une entreprise possède plusieurs compte bancaire */
    public function bankAccount()
    {
        return $this->hasMany('App\Models\BankAccount');
    }

    /** Une entreprise possède plusieurs adrresses emaiil */
    public function email()
    {
        return $this->hasMany('App\Models\BusinessEmail');
    }

    /** Une entreprise appartient à un pays */
    function country()
    {
        return $this->belongsTo('App\Models\Country', 'id_country');
    }

    /** Une entreprise appartient à un subscription */
    function subscription()
    {
        return $this->belongsTo('App\Models\Subscription', 'sub_id');
    }

    /** Une entreprise possède plusieurs adrresses emaiil */
    public function functionalUnit()
    {
        return $this->hasMany('App\Models\FunctionalUnit');
    }
}
