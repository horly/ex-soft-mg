<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviseGestionUF extends Model
{
    use HasFactory;
    protected $table = "devise_gestion_ufs";

    protected $fillable = [
        'taux',
        'default_cur_manage',
        'id_devise',
        'id_fu',
    ];

    /** Une devise de gestion appartient à une devise */
    function devise()
    {
        return $this->belongsTo('App\Models\Devise', 'id_devise');
    }


    function functionalUnit()
    {
        return $this->belongsTo('App\Models\FunctionalUnit', 'id_fu');
    }

    public function paymentMethod()
    {
        return $this->hasMany('App\Models\PaymentMethod');
    }
}
