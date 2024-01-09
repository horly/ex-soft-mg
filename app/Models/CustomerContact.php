<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerContact extends Model
{
    use HasFactory;

    protected $table = "customer_contacts";

    protected $fillable = [
        'fullname_cl',
        'fonction_contact_cl',
        'phone_number_cl',
        'email_adress_cl',
        'address_cl',
        'departement_cl',
        'id_client',
    ];

    function client()
    {
        return $this->belongsTo('App\Models\Client', 'id_client');
    }
}
