@extends('layouts.admin')

@section('title', 'Detail Dokumen')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Informasi Dokumen</div>
                <div class="card-body">
                    <p><strong>Judul:</strong> {{ $document->title }}</p>
                    <p><strong>Unit:</strong> {{ $document->unit }}</p>
                    <p><strong>Status:</strong> {{ $document->status }}</p>
                    <p><strong>Versi Aktif:</strong> {{ $document->current_version }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Riwayat Revisi</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach ($document->versions as $v)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Versi {{ $v->version }}</strong>
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
                        @endforeach
                    </ul>
                </div>
                @if ($document->currentVersion)
                    <div class="card mt-3">
                        <div class="card-header">Preview Dokumen (Versi Aktif)</div>
                        <div class="card-body p-0">
                            <iframe src="{{ route('documents.preview', $document->id) }}" width="100%" height="600"
                                style="border:none"></iframe>
                        </div>
                    </div>
                @endif

                {{-- <ul class="list-group list-group-flush">
                    @foreach ($document->versions as $v)
                    <li class="list-group-item d-flex justify-content-between">
                        Versi {{ $v->version }}
                        <a href="{{ route('documents.download', $v->id) }}">Download</a>
                    </li>
                    @endforeach
                </ul> --}}
            </div>
        </div>
    </div>
    </div>
    </div>



@endsection
