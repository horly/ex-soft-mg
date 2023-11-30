<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'address',
        'phone_number',
        'matricule',
        'photo_profile_url',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'role_id',
        'grade_id',
        'sub_id',
        'id_country',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    
    /*
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];*/

    /** Un Utilisateurs appartient à un role */
    function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }

    /** Un Utilisateurs appartient à un grade */
    function grade()
    {
        return $this->belongsTo('App\Models\Grade', 'grade_id');
    }

    /** Un user contient plusieurs historique */
    public function connectionHistory()
    {
        return $this->hasMany('App\Models\History');
    }

    /** Une user appartient à un abonnement */
    function subscription()
    {
        return $this->belongsTo('App\Models\Subscription', 'sub_id');
    }

    /** Un user possède plusieurs entreprise */
    public function entreprise()
    {
        return $this->hasMany('App\Models\Entreprise');
    }

    /** Une user appartient à un pays */
    function country()
    {
        return $this->belongsTo('App\Models\Country', 'id_country');
    }

    /** Un user gère */
    public function manage()
    {
        return $this->hasMany('App\Models\Manage');
    }

    /** Un user gère une FU*/
    public function manageFU()
    {
        return $this->hasMany('App\Models\ManageFU');
    }

    /** Un user possède plusieurs notifications */
    public function notifications()
    {
        return $this->hasMany('App\Models\Notification');
    }
    
    public function read()
    {
        return $this->hasMany('App\Models\ReadNotif');
    }

    public function send()
    {
        return $this->hasMany('App\Models\ReadNotif');
    }

    public function clients()
    {
        return $this->hasMany('App\Models\Client');
    }

    public function suplliers()
    {
        return $this->hasMany('App\Models\Supplier');
    }

    public function creditors()
    {
        return $this->hasMany('App\Models\Creditor');
    }
}
