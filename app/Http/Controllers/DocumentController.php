<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class DocumentController extends Controller
{
   public function store(Request $request)
{
    $request->validate([
        'title'=>'required',
        'document_number'=>'required|unique:documents',
        'file'=>'required|mimes:pdf,docx|max:5120'
    ]);

    $document = Document::create([
        'title'=>$request->title,
        'document_number'=>$request->document_number,
        'category'=>$request->category,
        'unit'=>$request->unit,
        'current_version'=>'v1.0',
        'created_by'=>auth()->id()
    ]);

    $path = $request->file('file')
        ->store('documents/'.$request->category);

    DocumentVersion::create([
        'document_id'=>$document->id,
        'version'=>'v1.0',
        'file_path'=>$path,
        'uploaded_by'=>auth()->id()
    ]);

    return redirect()->back()->with('success','Dokumen berhasil disimpan');
}

}