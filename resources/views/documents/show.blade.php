@extends('layouts.admin')

@section('title', 'Detail Dokumen')

@section('content')
    <div class="row">

        {{-- INFORMASI DOKUMEN --}}
        <div class="col-md-6">
            <div class="card mb-3 shadow-sm">
                <div class="card-header">Informasi Dokumen</div>
                <div class="card-body">
                    <p><strong>Judul:</strong> {{ $document->title }}</p>
                    <p><strong>Unit:</strong> {{ $document->unit }}</p>
                    <p><strong>Status:</strong>
                        <span class="badge bg-success">{{ $document->status }}</span>
                    </p>
                    <p><strong>Versi Aktif:</strong> {{ $document->current_version ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- RIWAYAT & PREVIEW --}}
        <div class="col-md-6">

            {{-- RIWAYAT REVISI --}}
            <div class="card shadow-sm">
                <div class="card-header">Riwayat Revisi</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse ($document->versions as $v)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">Versi {{ $v->version ?? '-' }}</div>
                                    <div class="text-muted small">
                                        Upload: {{ $v->created_at->format('d M Y') }}
                                    </div>
                                </div>

                                <div class="btn-group">
                                    <a href="{{ route('documents.preview', $v->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        Preview
                                    </a>
                                    <a href="{{ route('documents.download', $v->id) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        Download
                                    </a>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">
                                Belum ada versi dokumen
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- PREVIEW VERSI AKTIF --}}
            @if ($document->currentVersion)
                @php
                    $ext = strtolower(pathinfo($document->currentVersion->file_path, PATHINFO_EXTENSION));
                @endphp

                <div class="card mt-3 shadow-sm">
                    <div class="card-header">
                        Preview Dokumen (Versi Aktif)
                    </div>

                    <div class="card-body p-0">

                        {{-- PDF --}}
                        @if ($ext === 'pdf')
                            <iframe src="{{ route('documents.preview', $document->currentVersion->id) }}" width="100%"
                                height="650" style="border:none">
                            </iframe>

                            {{-- WORD / EXCEL --}}
                        @elseif (in_array($ext, ['docx', 'xlsx']))
                            <iframe
                                src="https://docs.google.com/gview?url={{ urlencode(route('documents.publicPreview', $document->currentVersion->id)) }}&embedded=true"
                                width="100%" height="650" style="border:none">
                            </iframe>

                            {{-- FILE LAIN --}}
                        @else
                            <div class="p-3 text-center text-muted">
                                Preview tidak tersedia untuk file <strong>.{{ $ext }}</strong>
                            </div>
                        @endif

                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
