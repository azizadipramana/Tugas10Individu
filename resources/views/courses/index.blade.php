@extends('adminlte::page')

@section('title', 'Mata Kuliah | SIAKAD')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="bi bi-book-half text-warning mr-2"></i>Kelola Mata Kuliah
            </h1>
            <p class="text-muted mb-0">Manajemen kurikulum, beban SKS, dan dosen pengampu mata kuliah.</p>
        </div>
        <div class="mt-2 mt-sm-0">
            @can('create-academic-data')
                <a href="{{ route('courses.create') }}" class="btn btn-warning shadow-sm text-dark font-weight-bold">
                    <i class="bi bi-plus-circle-fill mr-1"></i> Tambah Mata Kuliah
                </a>
            @endcan
        </div>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill mr-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(auth()->user()->hasRole('Staf'))
        <div class="alert alert-warning py-2 shadow-sm d-flex align-items-center" role="alert">
            <i class="bi bi-shield-lock-fill fs-5 mr-2"></i>
            <div>
                <strong>Akses Staf:</strong> Tombol <strong>Hapus Mata Kuliah</strong> terkunci secara eksklusif untuk Admin.
            </div>
        </div>
    @endif

    <!-- Form Pencarian (Kueri when()) -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="card-title m-0 font-weight-bold text-dark">
                <i class="bi bi-funnel-fill text-warning mr-1"></i> Filter & Pencarian Mata Kuliah
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('courses.index') }}" class="row g-3">
                <div class="col-md-5">
                    <label for="search" class="form-label fw-semibold">Kata Kunci (Nama MK, Kode, Dosen)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Cari mata kuliah atau kode..." 
                               value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="prodi" class="form-label fw-semibold">Program Studi</label>
                    <select name="prodi" id="prodi" class="form-select">
                        <option value="">-- Semua Prodi --</option>
                        @foreach($prodiOptions as $option)
                            <option value="{{ $option }}" {{ request('prodi') == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="semester" class="form-label fw-semibold">Semester</label>
                    <select name="semester" id="semester" class="form-select">
                        <option value="">-- Semua --</option>
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <div class="w-100 d-flex gap-2">
                        <button type="submit" class="btn btn-warning text-dark font-weight-bold flex-fill">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary" title="Reset">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Mata Kuliah -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0 font-weight-bold text-dark">
                <i class="bi bi-table text-warning mr-1"></i> Kurikulum & Mata Kuliah
            </h5>
            <span class="badge bg-warning text-dark fs-6">{{ $courses->total() }} Mata Kuliah</span>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th>Beban SKS</th>
                        <th>Semester</th>
                        <th>Program Studi</th>
                        <th>Dosen Pengampu</th>
                        <th class="text-center" style="width: 200px;">Aksi Otorisasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $index => $course)
                        <tr>
                            <td>{{ $courses->firstItem() + $index }}</td>
                            <td><span class="badge bg-dark font-monospace">{{ $course->kode_mk }}</span></td>
                            <td class="fw-bold">{{ $course->nama_mk }}</td>
                            <td><span class="badge bg-info text-white">{{ $course->sks }} SKS</span></td>
                            <td><span class="badge bg-light text-dark border">Sem {{ $course->semester }}</span></td>
                            <td>{{ $course->program_studi }}</td>
                            <td class="text-muted">{{ $course->dosen_pengampu ?? '-' }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @can('edit-academic-data')
                                        <a href="{{ route('courses.edit', $course) }}" class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endcan

                                    {{-- HAK EKSKLUSIF ADMIN UNTUK DELETE --}}
                                    @can('delete-academic-data')
                                        <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Peringatan Otorisasi Admin:\nHapus mata kuliah {{ $course->nama_mk }} ({{ $course->kode_mk }})?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus (Admin Only)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-outline-secondary disabled" title="Terkunci: Khusus Admin" disabled>
                                            <i class="bi bi-lock-fill text-muted"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Tidak ada mata kuliah yang cocok dengan filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center flex-wrap">
            <div class="text-muted small">
                Menampilkan <strong>{{ $courses->firstItem() ?? 0 }}</strong> sampai <strong>{{ $courses->lastItem() ?? 0 }}</strong> dari <strong>{{ $courses->total() }}</strong> mata kuliah
            </div>
            <div>
                {{ $courses->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@stop

