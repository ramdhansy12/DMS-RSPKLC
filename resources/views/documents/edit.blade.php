@extends('layouts.admin')

@section('title', 'Edit Dokumen')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Dokumen</h5>
                    </div>
                    <div class="card-body">
                        <!-- TAMBAHKAN ATRIBUT INI -->
                        <form action="{{ route('documents.update', $document->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Field Judul -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Dokumen</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $document->title) }}"
                                    placeholder="Masukkan judul dokumen" required>
                                @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Field Unit -->
                            <div class="mb-3">
                                <label for="unit" class="form-label">Unit / Bagian</label>
                                <input type="text" class="form-control @error('unit') is-invalid @enderror"
                                    id="unit" name="unit" value="{{ old('unit', $document->unit) }}"
                                    placeholder="Contoh: Keperawatan, Farmasi, dll." required>
                                @error('unit')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Field Status -->
                            <div class="mb-4">
                                <label for="status" class="form-label">Status Dokumen</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="AKTIF" @selected(old('status', $document->status) == 'AKTIF')>✅ AKTIF</option>
                                    <option value="NONAKTIF" @selected(old('status', $document->status) == 'NONAKTIF')>❌ NONAKTIF</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- TAMBAHKAN BAGIAN INI: UPLOAD FILE BARU -->
                            <hr>
                            <div class="mb-3">
                                <label for="file" class="form-label">Upload File Revisi (Opsional)</label>
                                <input type="file" class="form-control @error('file') is-invalid @enderror"
                                    id="file" name="file" accept=".pdf,.doc,.docx">
                                <div class="form-text">Meng-upload file baru akan membuat versi dokumen yang baru dan
                                    menggantikan versi aktif saat ini.</div>
                                @error('file')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('documents.show', $document->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
