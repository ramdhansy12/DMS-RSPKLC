@extends('layouts.app')
@section('title', 'Manajemen Dokumen')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<style>
.page-toolbar {
    background: #fff;
    border-radius: 14px;
    padding: 18px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
}

.stat-card {
    background: #f8fafc;
    border-radius: 12px;
    padding: 12px 15px;
}

.stat-card h6 {
    font-size: .75rem;
    color: #6b7280;
    margin: 0;
}

.stat-card strong {
    font-size: 1.2rem;
}

.table-container {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(0, 0, 0, .06);
}

.table thead th {
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.doc-title {
    font-weight: 600;
}

.doc-meta {
    font-size: .75rem;
    color: #9ca3af;
}

.status-pill {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 600;
}
</style>
@endpush


@section('content')
<div class="container-fluid">

    {{-- ================= HEADER TOOLBAR ================= --}}
    <div class="page-toolbar mb-3">

        <div class="row g-3 align-items-center">

            {{-- SEARCH --}}
            <div class="col-lg-4">
                <input id="globalSearch" type="text" class="form-control"
                    placeholder="Cari dokumen, nomor, kategori...">
            </div>

            {{-- FILTER --}}
            <div class="col-lg-3">
                <select id="unitFilter" class="form-select">
                    <option value="">Semua Unit</option>
                    @foreach ($units as $unit)
                    <option value="{{ $unit }}">{{ $unit }}</option>
                    @endforeach
                </select>
            </div>

            {{-- ACTION --}}
            @if (auth()->user()->role === 'admin')
            <div class="col-lg-auto ms-auto">
                <a href="{{ route('documents.create') }}" class="btn btn-primary px-4 shadow-sm">
                    + Upload Dokumen
                </a>
            </div>
            @endif

        </div>


        {{-- MINI STATS --}}
        {{-- <div class="row mt-3 g-3">

                <div class="col-md-3">
                    <div class="stat-card">
                        <h6>Total Dokumen</h6>
                        <strong>{{ $documents->count() }}</strong>
    </div>
</div>

<div class="col-md-3">
    <div class="stat-card">
        <h6>Unit</h6>
        <strong>{{ $documents->pluck('unit')->unique()->count() }}</strong>
    </div>
</div>

<div class="col-md-3">
    <div class="stat-card">
        <h6>Aktif</h6>
        <strong>{{ $documents->where('status', 'Aktif')->count() }}</strong>
    </div>
</div>

<div class="col-md-3">
    <div class="stat-card">
        <h6>Draft</h6>
        <strong>{{ $documents->where('status', 'Draft')->count() }}</strong>
    </div>
</div>

</div> --}}
</div>



{{-- ================= TABLE ================= --}}
<div class="table-container">

    <table id="docTable" class="table align-middle mb-0">

        <thead class="table-light">
            <tr>
                <th width="40">NO.</th>
                <th>NAMA DOKUMEN</th>
                <th>UNIT</th>
                <th>KATEGORI</th>
                <th>NO. DOKUMEN</th>
                <th>STATUS</th>
                <th width="110">AKSI</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($documents as $doc)
            <tr>

                <td>{{ $loop->iteration }}</td>

                <td>
                    <div class="doc-title">{{ $doc->title }}</div>
                    <div class="doc-meta">
                        ID {{ $doc->id }} • {{ $doc->created_at->format('d M Y') }}
                    </div>
                </td>

                <td>{{ $doc->unit }}</td>

                <td>
                    <span class="badge bg-light text-dark border">
                        {{ $doc->category }}
                    </span>
                </td>

                <td class="text-muted">{{ $doc->document_number }}</td>

                <td>
                    @php
                    $color = match (strtolower($doc->status)) {
                    'aktif' => 'success',
                    'draft' => 'warning',
                    'nonaktif' => 'danger',
                    'revisi' => 'danger',
                    default => 'primary',
                    };
                    @endphp

                    <span class="badge bg-{{ $color }} text-white">
                        {{ $doc->status }}
                    </span>
                </td>

                <td>
                    <div class="d-flex gap-1">

                        <a href="{{ route('documents.show', $doc->id) }}" class="btn btn-sm btn-light border"><i
                                class="fa fa-eye" title="Lihat Dokumen"></i></a>

                        @if (auth()->user()->role === 'admin')
                        <a href="{{ route('documents.edit', $doc->id) }}" class="btn btn-sm btn-light border"><i
                                class="fa fa-edit" title="Edit Dokumen"></i></a>

                        <form method="POST" action="{{ route('documents.destroy', $doc->id) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-light border text-danger">
                                <i class="fa fa-trash" title="Hapus Dokumen"></i>
                            </button>
                        </form>
                        @endif

                    </div>
                </td>

            </tr>
            @endforeach
        </tbody>

    </table>
</div>
</div>
@endsection



@push('scripts')
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(function() {

    let table = $('#docTable').DataTable({
        pageLength: 25,
        responsive: true,
        autoWidth: false,
        dom: "t<'d-flex justify-content-between align-items-center p-3'lip>",
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        }
    });

    $('#globalSearch').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('#unitFilter').on('change', function() {
        table.column(2).search(this.value).draw();
    });

});
</script>
@endpush