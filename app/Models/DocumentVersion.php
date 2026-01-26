<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'version',
        'file_path',
        'notes',
        'uploaded_by'
    ];

    // 1️⃣ MILIK SATU DOCUMENT
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    // 2️⃣ USER YANG UPLOAD
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}