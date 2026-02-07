@extends('layouts.admin')

@section('title', 'Detail Dokumen')

@section('content')
    <div class="row">

        {{-- SEARCH --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <input type="text" id="searchVersion" class="form-control" placeholder="🔍 Cari versi dokumen...">
            </div>
        </div>

        <div class="col-md-12">

            {{-- INFORMASI DOKUMEN --}}
            <div class="card mb-3 shadow-sm">
                <div class="card-header">Informasi Dokumen</div>
                <div class="card-body">
                    <p><strong>Judul:</strong> {{ $document->title }}</p>
                    <p><strong>Unit:</strong> {{ $document->unit }}</p>
                    <p><strong>Status:</strong>
                        <span class="badge bg-success">{{ $document->status }}</span>
                    </p>
                    <p><strong>Versi Aktif:</strong>
                        {{ $document->currentVersion->version ?? '-' }}
                    </p>
                </div>
            </div>

            {{-- RIWAYAT REVISI --}}
            <div class="card shadow-sm">
                <div class="card-header">Riwayat Revisi</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">

                        @forelse ($document->versions as $v)
                            <li class="list-group-item d-flex justify-content-between align-items-center version-item
                            {{ optional($document->currentVersion)->id === $v->id ? 'bg-light' : '' }}"
                                data-version="{{ strtolower($v->version) }}">

                                <div>
                                    <div class="fw-semibold">
                                        Versi {{ $v->version ?? '-' }}

                                        @if (optional($document->currentVersion)->id === $v->id)
                                            <span class="badge bg-primary ms-2">Aktif</span>
                                        @endif
                                    </div>

                                    <div class="text-muted small">
                                        Upload: {{ $v->created_at->format('d M Y') }}
                                    </div>
                                </div>

                                <div class="btn-group">
                                    {{-- PREVIEW TANPA TAB BARU --}}
                                    <a href="{{ route('documents.preview', $v->id) }}"
                                        class="btn btn-sm btn-outline-primary btn-preview"
                                        data-url="{{ route('documents.preview', $v->id) }}">
                                        Preview
                                    </a>


                                    {{-- DOWNLOAD TETAP --}}
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

            {{-- PREVIEW IFRAME --}}
            <div class="card mt-3 shadow-sm">
                <div class="card-header">
                    Preview Dokumen
                </div>

                <div class="card-body p-0" id="preview-container">
                    @if ($document->currentVersion)
                        <iframe id="docPreview"
                            src="{{ route('documents.preview', $document->currentVersion->id) }}?t={{ time() }}"
                            width="100%" height="650" style="border:none">
                        </iframe>
                    @else
                        <div class="p-3 text-center text-muted">
                            Belum ada dokumen untuk ditampilkan
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const searchInput = document.getElementById('searchVersion');
            const items = document.querySelectorAll('.version-item');
            const iframe = document.getElementById('docPreview');

            // SEARCH
            searchInput.addEventListener('input', function() {
                const keyword = this.value.toLowerCase();

                items.forEach(item => {
                    const version = item.dataset.version;
                    item.style.display = version.includes(keyword) ? '' : 'none';
                });
            });

            // PREVIEW TANPA PINDAH HALAMAN
            document.querySelectorAll('.btn-preview').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (!iframe) return;

                    const url = this.dataset.url;
                    iframe.src = url + '?t=' + Date.now(); // anti-cache
                });
            });

        });
    </script>
@endpush
