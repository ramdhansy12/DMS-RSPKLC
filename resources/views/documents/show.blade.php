@extends('layouts.admin')

@section('title', 'Detail Dokumen')

<style>
    .version-item {
        cursor: pointer;
        transition: .18s ease;
    }

    .version-item:hover {
        background: #f1f5f9;
    }

    .version-item.active {
        background: #e8f1ff;
        border-left: 4px solid #0d6efd;
    }

    .preview-box {
        height: 650px;
        background: #f8fafc;
        position: relative;
    }

    .preview-loader {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffffc7;
        backdrop-filter: blur(2px);
        z-index: 5;
    }

    .loader {
        width: 42px;
        height: 42px;
        border: 4px solid #e5e7eb;
        border-top: 4px solid #0d6efd;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

@section('content')
    <div class="container-fluid py-4">
        <button class="btn btn-sm btn-outline-primary mb-3" onclick="window.history.back()">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </button>
        <div class="row">

            {{-- LEFT PANEL --}}
            <div class="col-lg-4 mb-4">

                {{-- INFO CARD --}}
                <div class="card shadow-sm mb-3 border-0">
                    <div class="card-header fw-bold bg-white">
                        <i class="fas fa-file-alt me-2"></i>Informasi Dokumen
                    </div>

                    <div class="card-body">
                        <dl class="row mb-0">

                            <dt class="col-5">Judul</dt>
                            <dd class="col-7">{{ $document->title }}</dd>

                            <dt class="col-5">Unit</dt>
                            <dd class="col-7">{{ $document->unit }}</dd>

                            <dt class="col-5">Status</dt>
                            <dd class="col-7">
                                @php
                                    $class = match (strtolower($document->status)) {
                                        'aktif' => 'success',
                                        'draft' => 'warning',
                                        'nonaktif' => 'secondary',
                                        default => 'primary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $class }} text-uppercase">
                                    {{ $document->status }}
                                </span>
                            </dd>

                            <dt class="col-5">Versi Aktif</dt>
                            <dd class="col-7 fw-bold text-primary" id="infoVersion">
                                {{ $document->currentVersion->version ?? '-' }}
                            </dd>

                        </dl>
                    </div>
                </div>

                {{-- VERSION LIST --}}
                <div class="card shadow-sm border-0">

                    <div class="card-header fw-bold bg-white">
                        <i class="fas fa-history me-2"></i>Riwayat Versi
                    </div>

                    <ul class="list-group line-highlight">

                        @foreach ($document->versions as $v)
                            <li class="list-group-item version-item
                                {{ optional($document->currentVersion)->id == $v->id ? 'active' : '' }}"
                                data-url="{{ route('documents.preview', $v->id) }}" data-version="{{ $v->version }}">

                                <div class="d-flex justify-content-between">

                                    <div>
                                        <div class="fw-semibold">
                                            Versi {{ $v->version }}
                                            @if (optional($document->currentVersion)->id == $v->id)
                                                <span class="badge bg-success ms-2">Aktif</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            {{ $v->created_at->format('d M Y') }}
                                        </small>
                                    </div>

                                    <a href="{{ route('documents.download', $v->id) }}" class="btn btn-sm btn-outline-dark"
                                        title="Download Versi {{ $v->version }}">
                                        <i class="fas fa-download" style="-ms-overflow-style: auto"></i>
                                    </a>

                                </div>
                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>


            {{-- RIGHT PANEL PREVIEW --}}
            <div class="col-lg-8">

                <div class="card shadow-sm border-0">

                    <div class="card-header d-flex justify-content-between bg-white">
                        <span>
                            <i class="fas fa-eye me-2"></i>
                            <strong>Preview Dokumen {{ $document->title }}</strong>
                        </span>

                        <span class="badge bg-secondary" id="previewTitle">
                            Versi {{ $document->currentVersion->version ?? '-' }}
                        </span>
                    </div>

                    <div class="preview-box">

                        <div class="preview-loader d-none" id="loader">
                            <div class="loader"></div>
                        </div>

                        @if ($document->currentVersion)
                            <iframe id="previewFrame" src="{{ route('documents.preview', $document->currentVersion->id) }}"
                                width="100%" height="100%" style="border:none">
                            </iframe>
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                Belum ada file
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const frame = document.getElementById('previewFrame')
            const loader = document.getElementById('loader')
            const infoVersion = document.getElementById('infoVersion')
            const title = document.getElementById('previewTitle')

            document.querySelectorAll('.version-item').forEach(item => {

                item.addEventListener('click', e => {

                    if (e.target.closest('a')) return

                    let url = item.dataset.url
                    let version = item.dataset.version

                    loader.classList.remove('d-none')

                    frame.onload = () => loader.classList.add('d-none')

                    frame.src = url + "?t=" + Date.now()

                    infoVersion.textContent = version
                    title.textContent = "Versi " + version

                    document.querySelectorAll('.version-item')
                        .forEach(v => v.classList.remove('active'))

                    item.classList.add('active')

                })

            })

        })
    </script>
@endpush
