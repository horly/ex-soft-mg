<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $table = "articles";

    protected $fillable = [
        'reference_art',
        'reference_number',
        'description_art',
        'unit_price',
        'number_in_stock',
        'id_sub_cat',
        'id_user',
        'id_fu',
    ];

    public function subcatArticle()
    {
        return $this->belongsTo('App\Models\SubcategoryArticle', 'id_sub_cat');
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
