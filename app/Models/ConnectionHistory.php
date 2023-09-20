<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectionHistory extends Model
{
    use HasFactory;

    protected $table = "connection_histories";

    protected $fillable = [
        'ip',
        'platform',
        'device',
        'browser',
        'user_id',
    ];

    /** Une historique appartient à un utilisateur */
    function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
