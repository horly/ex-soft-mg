<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debtor extends Model
{
    use HasFactory;

    protected $table = "debtors";

    protected $fillable = [
        'type_deb',
        'reference_deb',
        'reference_number',
        'entreprise_name_deb',
        'rccm_deb',
        'id_nat_deb',
        'nif_deb',
        'account_num_deb',
        'website_deb',
        'contact_name_deb',
        'fonction_contact_deb',
        'phone_number_deb',
        'email_adress_deb',
        'address_deb',
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
