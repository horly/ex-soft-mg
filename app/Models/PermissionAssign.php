<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionAssign extends Model
{
    use HasFactory;

    protected $table = "permission_assigns";

    protected $fillable = [
        'id_user',
        'id_fu',
        'id_perms',
        'group',
    ];

    function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    function functionalUnit()
    {
        return $this->belongsTo('App\Models\FunctionalUnit', 'id_fu');
    }

    function permissions()
    {
        return $this->belongsTo('App\Models\Permission', 'id_perms');
    }
}
