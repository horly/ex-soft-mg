<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;
    
    protected $table = "bank_accounts";

    protected $fillable = [
        'account_number',
        'account_title',
        'account_title',
        'id_devise',
        'id_entreprise',
    ];

     /** Un compte appartient à une entreprise */
    function entreprise()
    {
        return $this->belongsTo('App\Models\Entreprise', 'id_entreprise');
    }

    /** Un compte appartient à une devise */
    function devise()
    {
        return $this->belongsTo('App\Models\Devise', 'id_devise');
    }
}
