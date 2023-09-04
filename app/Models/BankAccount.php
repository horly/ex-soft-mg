<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number',
        'account_title',
        'id_entreprise',
    ];

     /** Un compte appartient à une entrepriser */
     function entreprise()
    {
        return $this->belongsTo('App\Models\Entreprise', 'id_entreprise');
    }
}
