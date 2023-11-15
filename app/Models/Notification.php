<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = "notifications";

    protected $fillable = [
        'description',
        'link',
        'sub_id',
        'id_user',
        'id_entreprise',
        'created_at',
        'updated_at',
    ];

    /** Une notification appartient à un abonnement */
    function subscription()
    {
        return $this->belongsTo('App\Models\Subscription', 'sub_id');
    }

    /** Une notification appartient à un user */
    function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    /** Une notification appartient à une entreprise */
    function entreprise()
    {
        return $this->belongsTo('App\Models\Entreprise', 'id_entreprise');
    }

    public function read()
    {
        return $this->hasMany('App\Models\ReadNotif');
    }
}
