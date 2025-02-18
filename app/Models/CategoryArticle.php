<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryArticle extends Model
{
    use HasFactory;

    protected $table = "category_articles";

    protected $fillable = [
        'name_cat_art',
        'reference_number',
        'reference_cat_art',
        'id_user',
        'id_fu',
        'default',
    ];

    public function subCategory()
    {
        return $this->hasMany('App\Models\SubcategoryArticle');
    }

    function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    function functionalUit()
    {
        return $this->belongsTo('App\Models\FunctionalUnit', 'id_fu');
    }
}
