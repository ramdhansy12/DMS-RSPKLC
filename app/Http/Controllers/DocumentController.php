<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentVersion;
use Illuminate\Http\Request;


class DocumentController extends Controller
{

public function index(Request $request)
{
    $query = Document::with('creator');

    // SEARCH berdasarkan judul dokumen
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    // FILTER berdasarkan unit
    if ($request->filled('unit')) {
        $query->where('unit', $request->unit);
    }

    $documents = $query->latest()->get();

    // Ambil daftar unit (unik)
    $units = Document::select('unit')
        ->distinct()
        ->orderBy('unit')
        ->pluck('unit');

            // ⏩ PAGINATION
    $documents = $query
        ->latest()
        ->paginate(10)          // jumlah per halaman
        ->withQueryString();    // jaga parameter search & filte

    return view('documents.index', compact('documents', 'units'));
}

    // public function index()
    // {
    //     $documents = Document::with('creator')->latest()->get();
    //     return view('documents.index', compact('documents'));
    // }

    public function create()
    {
        return view('documents.create');
    }

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
            'current_version' => 1,
            'created_by' => auth()->id(),
        ]);

        $path = $request->file('file')->store('documents', 'private');

        $version = DocumentVersion::create([
            'document_id' => $document->id,
            'version' => '1.0',
            'file_path' => $path,
            'uploaded_by' => auth()->id(),
        ]);

        // 🔥 SET VERSI AKTIF PAKAI ID
        $document->update([
            'current_version' => $version->id,
        ]);



        return redirect()
            ->route('documents.show', $document->id)
            ->with('success', 'Dokumen berhasil ditambahkan');
    }

    public function edit(Document $document)
    {
        return view('documents.edit', compact('document'));
    }

    public function show(Document $document)
    {
        $document->load([
            'creator',
            'versions.uploader',
            'currentVersion'
        ]);

        return view('documents.show', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title' => 'required',
            'unit' => 'required',
            'status' => 'required',
            'file' => 'nullable|mimes:pdf,doc,docx|max:10240',
        ]);

        $document->update([
            'title' => $request->title,
            'unit' => $request->unit,
            'status' => $request->status,
        ]);

        if ($request->hasFile('file')) {

            $next = $document->versions()->count();
            $versionLabel = '1.' . $next;

            $path = $request->file('file')->store('documents', 'private');

            $version = DocumentVersion::create([
                'document_id' => $document->id,
                'version' => $versionLabel,
                'file_path' => $path,
                'notes' => 'Revisi dokumen',
                'uploaded_by' => auth()->id(),
            ]);

            // 🔥 UPDATE VERSI AKTIF
            $document->update([
                'current_version' => $version->id,
            ]);
        }

        return redirect()
            ->route('documents.show', $document->id)
            ->with('success', 'Dokumen berhasil diperbarui');
    }

    public function addVersion(Request $request, Document $document)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx|max:10240',
        ]);

        $next = $document->versions()->count();
        $versionLabel = '1.' . $next;

        $path = $request->file('file')->store('documents', 'private');

        $version = DocumentVersion::create([
            'document_id' => $document->id,
            'version' => $versionLabel,
            'file_path' => $path,
            'notes' => $request->notes,
            'uploaded_by' => auth()->id(),
        ]);

        $document->update([
            'current_version' => $version->id,
        ]);

        return back()->with('success', 'Versi baru berhasil diupload');
    }

    public function preview($id)
    {
        $version = DocumentVersion::findOrFail($id);

        $path = storage_path('app/private/' . $version->file_path);

        abort_if(!file_exists($path), 404);

        return response()->file($path, [
            'Content-Type' => mime_content_type($path),
            'Content-Disposition' => 'inline',
        ]);
    }

    public function download($id)
    {
        $version = DocumentVersion::findOrFail($id);

        $path = storage_path('app/private/' . $version->file_path);

        abort_if(!file_exists($path), 404);

        return response()->download($path);
    }

    public function destroy(Document $document)
    {
        foreach ($document->versions as $version) {
            $path = storage_path('app/private/' . $version->file_path);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $document->versions()->delete();
        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('success', 'Dokumen berhasil dihapus');
    }
}