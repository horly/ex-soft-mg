<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubcategoryArticle extends Model
{
    use HasFactory;

    protected $table = "subcategory_articles";

    protected $fillable = [
        'name_subcat_art',
        'reference_number',
        'reference_subcat_art',
        'id_cat',
        'id_user',
        'id_fu',
        'default',
    ];

    public function categoryArticle()
    {
        return $this->belongsTo('App\Models\CategoryArticle', 'id_cat');
    }

    function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    function functionalUit()
    {
        return $this->belongsTo('App\Models\FunctionalUnit', 'id_fu');
    }

    public function article()
    {
        return $this->hasMany('App\Models\Article');
    }
}
