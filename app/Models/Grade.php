<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $table = "grades";

    protected $fillable = [
        'name',
    ];

    /** Un grade appartien plusieurs utilisateurs */
    /*
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
        */
}
