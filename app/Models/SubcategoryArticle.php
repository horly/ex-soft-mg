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
    ];

    public function categoryArticle()
    {
        return $this->belongsTo('App\Models\CategoryArticle', 'id_cat');
    }
}
