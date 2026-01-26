@extends('layouts.admin')

@section('title', 'Dokumen SPO')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between">
            <strong>Daftar Dokumen SPO</strong>
            <a href="{{ route('documents.create') }}" class="btn btn-primary btn-sm">
                + Tambah Dokumen
            </a>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Judul</th>
                        <th>Unit</th>
                        <th>Versi</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documents as $doc)
                        <tr>
                            <td>{{ $doc->title }}</td>
                            <td>{{ $doc->unit }}</td>
                            <td>{{ $doc->current_version }}</td>
                            <td>
                                <span class="badge bg-success">{{ $doc->status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('documents.show', $doc->id) }}" class="btn btn-sm btn-info">
                                    Detail
                                </a>
                                <a href="{{ route('documents.edit', $doc->id) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
