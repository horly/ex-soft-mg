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
    ];
}
