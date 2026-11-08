@extends('adminlte::page')

@section('title', 'Data Dosen | SIAKAD')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="bi bi-person-badge-fill text-success mr-2"></i>Kelola Data Dosen Pengajar
            </h1>
            <p class="text-muted mb-0">Manajemen profil tenaga pengajar dan jabatan fungsional akademik.</p>
        </div>
        <div class="mt-2 mt-sm-0">
            @can('create-academic-data')
                <a href="{{ route('lecturers.create') }}" class="btn btn-success shadow-sm">
                    <i class="bi bi-person-plus-fill mr-1"></i> Tambah Dosen
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
                <strong>Akses Staf:</strong> Tombol <strong>Hapus Dosen</strong> terkunci secara eksklusif untuk Admin.
            </div>
        </div>
    @endif

    <!-- Card Pencarian (Conditional when()) -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="card-title m-0 font-weight-bold text-dark">
                <i class="bi bi-funnel-fill text-success mr-1"></i> Filter & Pencarian Data Dosen
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('lecturers.index') }}" class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label fw-semibold">Kata Kunci (Nama, NIDN, atau Email)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Cari nama dosen atau NIDN..." 
                               value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="departemen" class="form-label fw-semibold">Departemen</label>
                    <select name="departemen" id="departemen" class="form-select">
                        <option value="">-- Semua Departemen --</option>
                        @foreach($departemenOptions as $dept)
                            <option value="{{ $dept }}" {{ request('departemen') == $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <div class="w-100 d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-fill">
                            <i class="bi bi-filter"></i> Cari
                        </button>
                        <a href="{{ route('lecturers.index') }}" class="btn btn-outline-secondary" title="Reset">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Dosen -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0 font-weight-bold text-dark">
                <i class="bi bi-table text-success mr-1"></i> Daftar Dosen Terdaftar
            </h5>
            <span class="badge bg-success fs-6">{{ $lecturers->total() }} Total Dosen</span>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>NIDN</th>
                        <th>Nama Dosen & Gelar</th>
                        <th>Departemen</th>
                        <th>Jabatan Akademik</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 200px;">Aksi Otorisasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lecturers as $index => $lecturer)
                        <tr>
                            <td>{{ $lecturers->firstItem() + $index }}</td>
                            <td><span class="badge bg-secondary font-monospace">{{ $lecturer->nidn }}</span></td>
                            <td>
                                <div class="fw-bold">{{ $lecturer->nama }}</div>
                                <small class="text-muted">{{ $lecturer->email }}</small>
                            </td>
                            <td>{{ $lecturer->departemen }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $lecturer->jabatan_akademik }}</span></td>
                            <td>
                                @if($lecturer->status === 'Aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($lecturer->status === 'Studi Lanjut')
                                    <span class="badge bg-primary">Studi Lanjut</span>
                                @else
                                    <span class="badge bg-warning text-dark">Cuti</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('lecturers.show', $lecturer) }}" class="btn btn-outline-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @can('edit-academic-data')
                                        <a href="{{ route('lecturers.edit', $lecturer) }}" class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endcan

                                    {{-- HAK EKSKLUSIF ADMIN UNTUK DELETE --}}
                                    @can('delete-academic-data')
                                        <form action="{{ route('lecturers.destroy', $lecturer) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Peringatan Otorisasi Admin:\nHapus data dosen {{ $lecturer->nama }}?');">
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
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ada data dosen yang sesuai pencarian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center flex-wrap">
            <div class="text-muted small">
                Menampilkan <strong>{{ $lecturers->firstItem() ?? 0 }}</strong> sampai <strong>{{ $lecturers->lastItem() ?? 0 }}</strong> dari <strong>{{ $lecturers->total() }}</strong> dosen
            </div>
            <div>
                {{ $lecturers->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@stop

