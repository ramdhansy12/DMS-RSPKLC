<?php

namespace App\Http\Controllers;
use App\Models\Document;
use App\Models\DocumentVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('creator')
            ->latest()
            ->get();

        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

//     public function store(Request $request)
// {
//     $request->validate([
//         'title' => 'required',
//         'document_number' => 'required|unique:documents',
//         'category' => 'required',
//         'unit' => 'required',
//         'file' => 'required|mimes:pdf,doc,docx|max:10240',
//     ]);

//     // 1. Simpan dokumen (metadata)
//     $document = Document::create([
//         'title' => $request->title,
//         'document_number' => $request->document_number,
//         'category' => $request->category,
//         'unit' => $request->unit,
//         'status' => 'AKTIF',
//         'created_by' => auth()->id(),
//         'current_version' => '1.0',
//     ]);

//     // 2. Simpan file
//     $path = $request->file('file')->store('documents');

//     // 3. Simpan versi pertama
//     DocumentVersion::create([
//         'document_id' => $document->id,
//         'version' => '1.0',
//         'file_path' => $path,
//         'uploaded_by' => auth()->id(),
//     ]);

//     return redirect()->route('documents.index')
//         ->with('success', 'Dokumen berhasil ditambahkan');
// }

public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'document_number' => 'required|unique:documents',
        'category' => 'required',
        'unit' => 'required',
        'file' => 'required|mimes:pdf,doc,docx|max:10240',
    ]);

    $document = Document::create([
        'title' => $request->title,
        'document_number' => $request->document_number,
        'category' => $request->category,
        'unit' => $request->unit,
        'status' => 'AKTIF',
        'created_by' => auth()->id(),
        'current_version' => '1.0',
    ]);

    // SIMPAN FILE KE PUBLIC
    // $path = $request->file('file')->store('documents', 'public');
    // SIMPAN FILE KE PRIVATE
    $path = $request->file('file')->store('documents', 'public');

    // BUAT VERSI PERTAMA
    $version = DocumentVersion::create([
        'document_id' => $document->id,
        'version' => '1.0',
        'file_path' => $path,
        'uploaded_by' => auth()->id(),
    ]);

    // SET VERSI AKTIF (PAKAI ID)
    $document->update([
        'current_version' => $version->id,
    ]);

    return redirect()->route('documents.index')
        ->with('success', 'Dokumen berhasil ditambahkan');
}

    public function show(Document $document)
{
    $document->load(['creator', 'versions.uploader']);

    return view('documents.show', compact('document'));
}

public function edit(Document $document)
{
    return view('documents.edit', compact('document'));
}


public function update(Request $request, Document $document)
{
    $request->validate([
        'title' => 'required',
        'unit' => 'required',
        'status' => 'required',
    ]);

    $document->update([
        'title' => $request->title,
        'unit' => $request->unit,
        'status' => $request->status,
    ]);

    return redirect()->route('documents.show', $document->id)
        ->with('success', 'Dokumen diperbarui');
}


// public function addVersion(Request $request, Document $document)
// {
//     $request->validate([
//         'file' => 'required|mimes:pdf,doc,docx|max:10240',
//     ]);

//     $latestVersion = $document->versions()->count();
//     $newVersion = '1.' . $latestVersion;

//     $path = $request->file('file')->store('documents');

//     DocumentVersion::create([
//         'document_id' => $document->id,
//         'version' => $newVersion,
//         'file_path' => $path,
//         'notes' => $request->notes,
//         'uploaded_by' => auth()->id(),
//     ]);

//     $document->update([
//         'current_version' => $newVersion,
//     ]);

//     return back()->with('success', 'Revisi berhasil diupload');
// }


public function addVersion(Request $request, Document $document)
{
    $request->validate([
        'file' => 'required|mimes:pdf,doc,docx|max:10240',
    ]);

    $latest = $document->versions()->count();
    $newVersionNumber = '1.' . $latest;

    $path = $request->file('file')->store('documents', 'public');

    $version = DocumentVersion::create([
        'document_id' => $document->id,
        'version' => $newVersionNumber,
        'file_path' => $path,
        'notes' => $request->notes,
        'uploaded_by' => auth()->id(),
    ]);

    // UPDATE CURRENT VERSION KE ID
    $document->update([
        'current_version' => $version->id,
    ]);

    return back()->with('success', 'Revisi berhasil diupload');
}


public function download($versionId)
{
    $version = DocumentVersion::findOrFail($versionId);

    $path = storage_path('app/private/' . $version->file_path);

    if (!file_exists($path)) {
        abort(404, 'File tidak ditemukan');
    }

    return response()->download($path);
}


// public function preview($versionId)
// {
//     $version = DocumentVersion::findOrFail($versionId);

//     // Hanya preview PDF
//     if (!str_ends_with($version->file_path, '.pdf')) {
//         return redirect()
//             ->route('documents.download', $versionId);
//     }

//     return response()->file(
//         storage_path('app/' . $version->file_path),
//         ['Content-Type' => 'application/pdf']
//     );
// }

public function preview($id)
{
    $version = DocumentVersion::findOrFail($id);

    $path = storage_path('app/private/' . $version->file_path);

    if (!file_exists($path)) {
        abort(404, 'File tidak ditemukan');
    }

    // hanya preview PDF
    if (!str_ends_with(strtolower($path), '.pdf')) {
        return redirect()->route('documents.download', $id);
    }

    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline'
    ]);
}


}
