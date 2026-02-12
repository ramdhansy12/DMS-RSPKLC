@extends('layouts.admin')

@section('title', 'Detail Dokumen')
<style>
.version-item {
    cursor: pointer;
    transition: .2s;
}

.version-item:hover {
    background: #f8fafc;
}

.active-version {
    background: #eef6ff !important;
    border-left: 4px solid #0d6efd;
}

.preview-wrapper {
    min-height: 600px;
    background: #f9fafb;
}

@media(max-width:991px) {
    .preview-wrapper {
        min-height: 400px;
    }
}
</style>


@section('content')
<div class="container-fluid py-4">
    <div class="row">

        {{-- ==================== KOLOM KIRI: INFORMASI & RIWAYAT ==================== --}}
        {{-- Di layar besar, lebar 4/12. Di layar kecil, penuh 12/12 --}}
        <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">

            {{-- INFORMASI DOKUMEN --}}
            <div class="card mb-3 shadow-sm">
                <div class="card-header d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i> Informasi Dokumen
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Judul</dt>
                        <dd class="col-sm-8">{{ $document->title }}</dd>

                        <dt class="col-sm-4">Unit</dt>
                        <dd class="col-sm-8">{{ $document->unit }}</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            @php
                            $statusClass = match (strtolower($document->status)) {
                            'aktif' => 'success',
                            'draft' => 'warning',
                            'nonaktif' => 'secondary',
                            default => 'primary',
                            };
                            @endphp
                            <span class="badge bg-{{ $statusClass }} text-uppercase">{{ $document->status }}</span>
                        </dd>

                        <dt class="col-sm-4">Versi Aktif</dt>
                        <dd class="col-sm-8">
                            <span id="infoVersion" class="fw-bold text-primary">
                                {{ $document->currentVersion->version ?? '-' }}
                            </span>
                        </dd>
                    </dl>
                </div>
            </div>


            {{-- RIWAYAT REVISI --}}
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Revisi
                </div>

                <div class="card-body p-0">

                    <div class="row g-0">

                        {{-- LIST VERSI --}}
                        <div class="col-lg-4 border-end">
                            <ul class="list-group list-group-flush version-list">

                                @forelse ($document->versions as $v)
                                <li class="list-group-item version-item d-flex justify-content-between align-items-center
                            {{ optional($document->currentVersion)->id === $v->id ? 'active-version' : '' }}"
                                    data-url="{{ route('documents.preview', $v->id) }}">

                                    <div>
                                        <div class="fw-semibold">
                                            Versi {{ $v->version ?? '-' }}

                                            @if (optional($document->currentVersion)->id === $v->id)
                                            <span class="badge rounded-pill bg-success ms-2">
                                                Aktif
                                            </span>
                                            @endif
                                        </div>

                                        <small class="text-muted">
                                            {{ $v->created_at->format('d M Y') }}
                                        </small>
                                    </div>

                                    <a href="{{ route('documents.download', $v->id) }}"
                                        class="btn btn-sm btn-light border" title="Download">
                                        ⬇
                                    </a>

                                </li>
                                @empty
                                <li class="list-group-item text-center text-muted py-4">
                                    Belum ada versi dokumen
                                </li>
                                @endforelse

                            </ul>
                        </div>

                        {{-- PREVIEW --}}
                        <div class="col-lg-8">

                            <div class="preview-wrapper">

                                <div id="previewPlaceholder" class="text-center text-muted py-5">
                                    <i class="bi bi-file-earmark-text fs-1 d-block mb-3"></i>
                                    Pilih versi dokumen untuk preview
                                </div>

                                <iframe id="previewFrame" class="w-100 d-none"
                                    style="height:600px; border:none; border-radius:0 0 1rem 0;">
                                </iframe>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- ==================== KOLOM KANAN: PREVIEW (HANYA DESKTOP) ==================== --}}
        {{-- PREVIEW IFRAME --}}
        <div class="col-lg-8 d-none d-lg-block">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-eye me-2"></i> Preview Dokumen {{ $document->title }}</span>
                    <span id="previewTitle" class="badge bg-secondary">
                        Versi {{ $document->currentVersion->version ?? '-' }}
                    </span>
                </div>
                <div class="card-body p-0 position-relative" style="height: 650px;">
                    @if ($document->currentVersion)
                    <!-- 3. IFRAME (Ditampilkan di belakang loader) -->
                    {{-- PREVIEW IFRAME --}}
                    <div class="card mt-3 shadow-sm">
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
                    @else
                    <!-- 4. PLACEHOLDER (Jika dokumen sama sekali belum ada) -->
                    <div class="p-5 text-center text-muted h-100 d-flex flex-column justify-content-center">
                        <i class="fas fa-file-alt fa-5x mb-3"></i>
                        <h5>Belum Ada Dokumen</h5>
                        <p>Pilih atau unggah versi dokumen untuk melihat preview di sini.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ==================== MODAL PREVIEW (UNTUK MOBILE/TABLET) ==================== --}}
        <!-- Modal ini akan muncul di layar kecil saat tombol preview diklik -->
        <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="previewModalLabel">Preview Dokumen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <iframe id="modalIframe" src="" width="100%" height="100%" style="border: none;"></iframe>
                    </div>
                </div>
            </div>
        </div>

        @endsection

        @push('scripts')
        <script>
        document.querySelectorAll('.version-item').forEach(item => {
            item.addEventListener('click', function() {

                let url = this.dataset.url

                document.getElementById('previewFrame').src = url
                document.getElementById('previewFrame').classList.remove('d-none')
                document.getElementById('previewPlaceholder').classList.add('d-none')

                document.querySelectorAll('.version-item')
                    .forEach(el => el.classList.remove('active-version'))

                this.classList.add('active-version')
            })
        })
        document.addEventListener('DOMContentLoaded', function() {
            const desktopIframe = document.getElementById('docPreview');
            const mobileIframe = document.getElementById('modalIframe');
            const infoVersion = document.getElementById('infoVersion');
            const previewTitle = document.getElementById('previewTitle');
            const previewLoader = document.getElementById('preview-loader');
            const previewError = document.getElementById('preview-error');
            let previewModal;
            let loadingTimeout;

            const modalEl = document.getElementById('previewModal');
            if (modalEl) {
                previewModal = new bootstrap.Modal(modalEl);
            }

            function showError() {
                previewLoader.style.display = 'none';
                previewError.style.display = 'flex';
                if (desktopIframe) desktopIframe.style.visibility = 'hidden';
            }

            function loadPreview(url) {
                if (window.innerWidth >= 992 && desktopIframe) {
                    clearTimeout(loadingTimeout);
                    desktopIframe.onload = null;
                    desktopIframe.onerror = null;

                    previewLoader.style.display = 'flex';
                    previewError.style.display = 'none';
                    desktopIframe.style.visibility = 'hidden';

                    desktopIframe.onload = function() {
                        clearTimeout(loadingTimeout);
                        previewLoader.style.display = 'none';
                        desktopIframe.style.visibility = 'visible';
                    };

                    desktopIframe.onerror = function() {
                        clearTimeout(loadingTimeout);
                        showError();
                    };

                    loadingTimeout = setTimeout(() => {
                        console.error("Preview loading timed out.");
                        showError();
                    }, 15000);

                    desktopIframe.src = url + '?t=' + Date.now();

                } else if (window.innerWidth < 992 && mobileIframe && previewModal) {
                    mobileIframe.src = url + '?t=' + Date.now();
                    previewModal.show();
                }
            }

            function updatePreview(versionId, versionText, url) {
                if (infoVersion) infoVersion.textContent = versionText;
                if (previewTitle) previewTitle.textContent = 'Versi ' + versionText;

                document.querySelectorAll('.version-item').forEach(item => {
                    item.classList.remove('active');
                    const badge = item.querySelector('.badge');
                    if (badge && badge.textContent === 'Aktif') badge.remove();
                });

                const activeItem = document.querySelector(`[data-version="${versionText.toLowerCase()}"]`);
                if (activeItem) {
                    activeItem.classList.add('active');
                    const title = activeItem.querySelector('h6');
                    if (title && !title.querySelector('.badge')) {
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-light text-dark ms-2';
                        badge.textContent = 'Aktif';
                        title.appendChild(badge);
                    }
                }

                loadPreview(url);
            }

            document.querySelectorAll('.version-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    if (e.target.closest('a')) return;

                    const versionId = this.dataset.id;
                    const versionText = this.dataset.version;
                    const previewUrl = this.dataset.url;

                    if (previewUrl) {
                        updatePreview(versionId, versionText, previewUrl);
                    } else {
                        console.error('URL preview tidak ditemukan.');
                    }
                });
            });
        });
        </script>
        @endpush