<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = "payment_methods";

    protected $fillable = [
        'designation',
        'default',
        'institution_name',
        'iban',
        'account_number',
        'bic_swiff',
        'id_currency',
        'id_user',
        'id_fu',
    ];

    /** Une gestion appartient à un user */
    function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    /** Une gestion appartient à une entreprise */
    function entreprise()
    {
        return $this->belongsTo('App\Models\Entreprise', 'id_entreprise');
    }

    function devisegest()
    {
        return $this->belongsTo('App\Models\DeviseGestionUF', 'id_currency');
    }

    public function encaissement()
    {
        return $this->hasMany('App\Models\Encaissement');
    }

    public function decaissement()
    {
        return $this->hasMany('App\Models\Decaissement');
    }

}
