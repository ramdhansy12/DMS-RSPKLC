@extends('layouts.app')

@section('title', 'Dokumen SPO')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
        .card-header {
            background: #fff;
            border-bottom: 1px solid #eef1f5;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .badge {
            font-size: .75rem;
            padding: .45em .65em;
        }

        .filter-bar {
            background: #f8fafc;
            border-radius: 12px;
            padding: 15px;
        }

        .dataTables_wrapper .dataTables_filter {
            display: none;
        }

        .table thead th {
            white-space: nowrap;
        }

        @media(max-width:768px) {
            .card-header h5 {
                font-size: 1rem;
            }
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid">

        {{-- ================= FILTER BAR ================= --}}
        <div class="filter-bar mb-3 shadow-sm">

            <div class="row g-3 align-items-end">

                <div class="col-lg-3 col-md-6">
                    <label class="form-label small text-muted">Cari Dokumen</label>
                    <input type="text" id="searchInput" class="form-control" placeholder="Ketik nama dokumen...">
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label small text-muted">Filter Unit</label>
                    <select id="unitFilter" class="form-select">
                        <option value="">Semua Unit</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit }}">{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-auto col-md-6 d-grid">
                    <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-sync-alt me-1"></i> Reset
                    </a>
                </div>

                @if (auth()->user()->role === 'admin')
                    <div class="col-lg-auto col-md-6 d-grid ms-lg-auto">
                        <a href="{{ route('documents.create') }}" class="btn btn-primary shadow-sm">
                            <i class="fas fa-plus me-1"></i> Tambah Dokumen
                        </a>
                    </div>
                @endif

            </div>
        </div>


        {{-- ================= TABLE ================= --}}
        <div class="card shadow-sm border-0 rounded-4">

            <div class="card-header">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-folder-open me-2 text-primary"></i>
                    Manajemen Dokumen
                </h5>
            </div>

            <div class="card-body p-0">

                <table id="documentTable" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th width="50" style="grid-template: min-content">NO.</th>
                            <th>NAMA DOKUMEN</th>
                            <th>UNIT</th>
                            <th>KATEGORI FILE</th>
                            <th>NO.DOKUMEN</th>
                            <th>STATUS</th>
                            <th width="90" class="text-center">AKSI</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($documents as $doc)
                            <tr>

                                <td class="text-muted">{{ $loop->iteration }}</td>

                                <td>
                                    <div class="fw-semibold">{{ $doc->title }}</div>
                                    {{-- <small class="text-muted">ID: {{ $doc->id }}</small> --}}
                                    {{-- <small class="text-muted d-block">{{ $doc->created_at->format('d M Y') }}</small> --}}
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
                                        $statusClass = match (strtolower($doc->status)) {
                                            'aktif' => 'success',
                                            'draft' => 'warning',
                                            'nonaktif' => 'danger',
                                            'revisi' => 'danger',
                                            default => 'primary',
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ $doc->status }}
                                    </span>
                                </td>

                                <td class="text-center">

                                    {{-- <div class="btn-group" style="block-size: auto">

                                        <a href="{{ route('documents.show', $doc->id) }}"
                                            class="btn btn-sm btn-light border" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if (auth()->user()->role === 'admin')
                                            <a href="{{ route('documents.edit', $doc->id) }}"
                                                class="btn btn-sm btn-light border" title="Edit">
                                                <i class="fas fa-pen"></i>
                                            </a>

                                            <form method="POST" action="{{ route('documents.destroy', $doc->id) }}"
                                                onsubmit="return confirm('Hapus dokumen ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-light border text-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif

                                    </div> --}}

                                    <div class="d-flex align-items-center gap-2">

                                        <a href="{{ route('documents.show', $doc->id) }}"
                                            class="btn btn-sm btn-light border" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if (auth()->user()->role === 'admin')
                                            <a href="{{ route('documents.edit', $doc->id) }}"
                                                class="btn btn-sm btn-light border" title="Edit">
                                                <i class="fas fa-pen"></i>
                                            </a>

                                            <form method="POST" action="{{ route('documents.destroy', $doc->id) }}"
                                                onsubmit="return confirm('Hapus dokumen ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-sm btn-light border text-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
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
    </div>
@endsection


@push('scripts')
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(function() {

            let table = $('#documentTable').DataTable({
                pageLength: 25,
                responsive: true,
                ordering: true,
                autoWidth: false,
                dom: "t<'d-flex justify-content-between align-items-center p-2'lip>",
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                }
            });

            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });

            $('#unitFilter').on('change', function() {
                table.column(2).search(this.value).draw();
            });

        });
    </script>
@endpush
