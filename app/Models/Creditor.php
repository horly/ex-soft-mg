<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creditor extends Model
{
    use HasFactory;

    protected $table = "creditors";

    protected $fillable = [
        'type_cr',
        'reference_cr',
        'reference_number',
        'entreprise_name_cr',
        'rccm_cr',
        'id_nat_cr',
        'nif_cr',
        'account_num_cr',
        'website_cr',
        'contact_name_cr',
        'fonction_contact_cr',
        'phone_number_cr',
        'email_adress_cr',
        'address_cr',
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
}
