<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Document;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // KPI
        $totalDocuments = Document::count();
        $activeDocuments = Document::where('status', 'Aktif')->count();
        $nonActiveDocuments = Document::where('status', 'NonAktif')->count();
        $draftDocuments = Document::where('status', 'Draft')->count();

        // Dokumen per Unit
        $documentsPerUnit = Document::select('unit', DB::raw('count(*) as total'))
            ->groupBy('unit')
            ->pluck('total', 'unit');

        // Dokumen Per Kategori
        $documentsPerCategory = Document::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');

        // Status Dokumen
        $documentsByStatus = Document::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // Upload per Bulan (12 bulan terakhir)
        $monthlyUpload = Document::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total')
            )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');

        return view('dashboard', compact(
            'totalDocuments',
            'activeDocuments',
            'nonActiveDocuments',
            'draftDocuments',
            'documentsPerUnit',
            'documentsPerCategory',
            'documentsByStatus',
            'monthlyUpload'
        ));
    }
}
