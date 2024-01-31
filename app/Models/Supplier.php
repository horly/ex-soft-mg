<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = "suppliers";

    protected $fillable = [
        'type_sup',
        'reference_sup',
        'reference_number',
        'entreprise_name_sup',
        'rccm_sup',
        'id_nat_sup',
        'nif_sup',
        'account_num_sup',
        'website_sup',
        'contact_name_sup',
        'fonction_contact_sup',
        'phone_number_sup',
        'email_adress_sup',
        'address_sup',
        'id_user',
        'id_fu',
    ];

    //Un fournisseur est géré par un utilisateur
    function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }
 
    function functionalUit()
    {
        return $this->belongsTo('App\Models\FunctionalUnit', 'id_fu');
    }

    public function purchase()
    {
        return $this->hasMany('App\Models\Purchase');
    }
}
