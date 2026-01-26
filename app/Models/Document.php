<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    // 1️⃣ Kolom yang boleh diisi (WAJIB)
    protected $fillable = [
        'title',
        'document_number',
        'category',
        'unit',
        'status',
        'current_version',
        'created_by'
    ];

    // 2️⃣ RELATIONSHIP KE USER (PEMBUAT)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // 3️⃣ RELATIONSHIP KE VERSI
    public function versions()
    {
        return $this->hasMany(DocumentVersion::class);
    }

    // 4️⃣ Versi TERBARU (HELPER)
    public function currentVersion()
    {
        return $this->belongsTo(DocumentVersion::class, 'current_version');
    }

}
