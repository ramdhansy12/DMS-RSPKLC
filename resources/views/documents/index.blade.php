@extends('layouts.admin')

@section('title', 'Dokumen SPO')

@section('content')

    {{-- SEARCH & FILTER --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('documents.index') }}">
                <div class="row g-2">

                    <div class="col-md-5">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="🔍 Cari nama dokumen...">
                    </div>

                    <div class="col-md-2">
                        <select name="unit" class="form-select">
                            <option value="">— Semua Unit —</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit }}" {{ request('unit') === $unit ? 'selected' : '' }}>
                                    {{ $unit }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-primary w-100">
                            Filter
                        </button>

                        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary w-100">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Daftar Dokumen</strong>

            @if (auth()->user()->role === 'admin')
                <a href="{{ route('documents.create') }}" class="btn btn-primary btn-sm">
                    + Tambah Dokumen
                </a>
            @endif
        </div>

        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama File</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th class="text-center" width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documents as $doc)
                        <tr>
                            <td>{{ $doc->title }}</td>
                            <td>{{ $doc->unit }}</td>
                            <td>
                                <span class="badge bg-success">
                                    {{ $doc->status }}
                                </span>
                            </td>
                            <td class="text-center">

                                <a href="{{ route('documents.show', $doc->id) }}" class="btn btn-sm btn-info">
                                    Detail
                                </a>

                                @if (auth()->user()->role === 'admin')
                                    <a href="{{ route('documents.edit', $doc->id) }}" class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('documents.destroy', $doc->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            Hapus
                                        </button>
                                    </form>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Tidak ada dokumen
                            </td>
                        </tr>
                    @endforelse

                </tbody>


            </table>
            @if ($documents->hasPages())
                <div class="card-footer">
                    {{ $documents->links() }}
                </div>
            @endif

        </div>

    </div>


@endsection
