@extends('layouts.app')

@section('title', 'Dokumen SPO')

{{-- Ini adalah bagian penting. Pastikan layout Anda memiliki @stack('scripts') --}}
@push('styles')
    {{-- Tambahkan CSS untuk ekstensi Responsive DataTables jika belum ada di layout Anda --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@section('content')
<div class="container-fluid">

    {{-- ==================== AREA KONTROL & FILTER ==================== --}}
    {{-- Card terpisah untuk filter agar lebih bersih --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                {{-- Filter Unit --}}
                <div class="col-md-3">
                    <label for="unitFilter" class="form-label">Filter Unit</label>
                    <select id="unitFilter" name="unit" class="form-select">
                        <option value="">— Semua Unit —</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit }}">{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol Aksi --}}
                <div class="col-md-auto">
                    <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo"></i> Reset Tampilan
                    </a>
                </div>
            </div>
        </div>
    </div>


    {{-- ==================== AREA TABEL DATA ==================== --}}
    <div class="card shadow-sm">
        {{-- Header Card: Judul & Tombol Tambah --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-file-alt"></i> Daftar Dokumen</h5>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('documents.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Dokumen Baru
                </a>
            @endif
        </div>

        {{-- Body Card: Tabel --}}
        <div class="card-body p-0">
            <table id="documentTable" class="table table-striped table-hover align-middle mb-0" style="width:100%;">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama File</th>
                        <th>Unit</th>
                        <th>Kategori</th>
                        <th>Nomor Dokumen</th>
                        <th>Status</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documents as $doc)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $doc->title }}</td>
                        <td>{{ $doc->unit }}</td>
                        <td>{{ $doc->category }}</td>
                        <td>{{ $doc->document_number }}</td>
                        <td>
                            @php
                                // Menggunakan match expression untuk kode yang lebih bersih
                                $statusClass = match (strtolower($doc->status)) {
                                    'aktif' => 'success',
                                    'draft' => 'warning',
                                    'nonaktif' => 'secondary',
                                    'revisi' => 'danger',
                                    default => 'primary',
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }} text-uppercase">{{ $doc->status }}</span>
                        </td>
                        <td class="text-center">
                            {{-- Menggunakan Dropdown untuk menghemat ruang, terutama di layar kecil --}}
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop{{ $doc->id }}" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Aksi
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $doc->id }}">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('documents.show', $doc->id) }}">
                                            <i class="fas fa-eye text-info"></i> Lihat Detail
                                        </a>
                                    </li>
                                    @if (auth()->user()->role === 'admin')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('documents.edit', $doc->id) }}">
                                                <i class="fas fa-edit text-warning"></i> Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            {{-- Form hapus di dalam dropdown --}}
                                            <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

{{-- Pindahkan script ke bagian bawah agar tidak memblokir rendering halaman --}}
@push('scripts')
    {{-- Pastikan Anda sudah menyertakan jQuery, Bootstrap JS, dan DataTables JS di layout utama Anda --}}
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#documentTable').DataTable({
                pageLength: 25,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                responsive: true, // <-- FITUR RESPONSIF PENTING
                ordering: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json', // <-- BAHASA INDONESIA
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ dokumen",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            // ==================== FITUR FILTER INSTAN ====================
            // Membuat filter unit berjalan tanpa reload halaman
            $('#unitFilter').on('change', function() {
                // Kolom 'Unit' adalah kolom ke-2 (index 1)
                table.column(1).search($(this).val()).draw();
            });
        });
    </script>
@endpush
