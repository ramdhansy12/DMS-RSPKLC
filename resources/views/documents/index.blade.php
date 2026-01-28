@extends('layouts.admin')

@section('title', 'Dokumen SPO')

@section('content')
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
                        {{-- <th>Versi</th> --}}
                        <th>Status</th>

                        <th class="text-center" width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documents as $doc)
                        <tr>
                            <td>{{ $doc->title }}</td>
                            <td>{{ $doc->unit }}</td>
                            {{-- <td>{{ $doc->current_version }}</td> --}}
                            <td>
                                <span class="badge bg-success">{{ $doc->status }}</span>
                            </td>
                            <td class="text-center">

                                {{-- DETAIL (SEMUA USER) --}}
                                <a href="{{ route('documents.show', $doc->id) }}" class="btn btn-sm btn-info">
                                    Detail
                                </a>

                                {{-- ADMIN ONLY --}}
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
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada dokumen
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
