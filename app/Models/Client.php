<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = "clients";

    protected $fillable = [
        'type',
        'entreprise_name_cl',
        'rccm_cl',
        'id_nat_cl',
        'nif_cl',
        'account_num_cl',
        'website_cl',
        'contact_name_cl',
        'fonction_contact_cl',
        'phone_number_cl',
        'email_adress_cl',
        'address_cl',
        'id_user',
        'id_fu',
    ];

    //Un client est géré par un utilisateur
    function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    function functionalUit()
    {
        return $this->belongsTo('App\Models\FunctionalUnit', 'id_fu');
    }
}
