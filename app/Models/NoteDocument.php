<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteDocument extends Model
{
    use HasFactory;

    protected $table = "note_documents";

    protected $fillable = [
        'type_doc',
        'type_note',
        'note_content',
        'reference_doc',
        'bold_note',
        'italic_note',
    ];
}
