<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devise extends Model
{
    use HasFactory;

    protected $table = "devises";

    protected $fillable = [
        'motto',
        'motto_en',
        'currency_symbol',
        'iso_code',
    ];

    /** Une devise comporte plusieurs compte bancaire */
    public function bankAccocunt()
    {
        return $this->hasMany('App\Models\BankAccount');
    }

    /** Une devise comporte plusieurs compte bancaire */
    public function deviseGestUf()
    {
        return $this->hasMany('App\Models\DeviseGestionUF');
    }
}
