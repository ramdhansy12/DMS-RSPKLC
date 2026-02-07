@extends('layouts.admin')

@section('title', 'Tambah Dokumen')

@section('content')


    <div class="card shadow-sm">
        <div class="card-header">
            <strong>Tambah Dokumen Baru</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-3">
                    <label class="col-md-3 col-form-label">Judul Dokumen</label>
                    <div class="col-md-9">
                        <input type="text" name="title" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-3 col-form-label">Nomor Dokumen</label>
                    <div class="col-md-9">
                        <input type="text" name="document_number" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-3 col-form-label">Kategori</label>
                    <div class="col-md-9">
                        <select name="category" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="SPO">SPO</option>
                            <option value="MOU">MOU</option>
                            <option value="KEBIJAKAN">KEBIJAKAN</option>
                            <option value="PEDOMAN">PEDOMAN</option>
                            <option value="LAINNYA">LAINNYA</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-md-3 col-form-label">Unit</label>
                    <div class="col-md-9">
                        <select name="unit" class="form-select" required>
                            <option value="">-- Pilih Unit --</option>
                            <option value="IT">IT</option>
                            <option value="QA">QA</option>
                            <option value="HRD">HRD</option>
                            <option value="PPI">PPI</option>
                            <option value="REKAM MEDIS">REKAM MEDIS</option>
                            <option value="KEPERAWATAN">KEPERAWATAN</option>
                            <option value="RAWAT INAP">RAWAT INAP</option>
                            <option value="RAWAT JALAN">RAWAT JALAN</option>
                            <option value="CASEMIX">CASEMIX</option>
                            <option value="RADIOLOGI">RADIOLOGI</option>
                            <option value="LABORATORIUM">LABORATORIUM</option>
                            <option value="FARMASI">FARMASI</option>
                            <option value="PURCASHING">PURCASHING</option>
                            <option value="LOGUM">LOGUM</option>
                            <option value="LOGMED">LOGMED</option>
                            <option value="KEUANGAN">KEUANGAN</option>
                            <option value="MCU">MEDICAL CHECK UP</option>
                            <option value="UMUM">UMUM</option>
                            <option value="OK">OK</option>
                            <option value="IGD">IGD</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-4">
                    <label class="col-md-3 col-form-label">File Dokumen</label>
                    <div class="col-md-9">
                        <input type="file" name="file" class="form-control" required>
                        <small class="text-muted">
                            Format PDF/DOCX. Maks 10MB.
                        </small>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Simpan Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
