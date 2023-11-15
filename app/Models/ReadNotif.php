<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadNotif extends Model
{
    use HasFactory;

    protected $table = "read_notifs";

    protected $fillable = [
        'read',
        'id_user',
        'id_notif',
        'id_sender',
    ];

    function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    function sender()
    {
        return $this->belongsTo('App\Models\User', 'id_sender');
    }

    function notification()
    {
        return $this->belongsTo('App\Models\Notification', 'id_notif');
    }
}
