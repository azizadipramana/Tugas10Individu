@extends('adminlte::page')

@section('title', 'Data Mahasiswa | SIAKAD')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="bi bi-people-fill text-primary mr-2"></i>Kelola Data Mahasiswa
            </h1>
            <p class="text-muted mb-0">Manajemen data profil dan status akademik mahasiswa.</p>
        </div>
        <div class="mt-2 mt-sm-0">
            @can('create-academic-data')
                <a href="{{ route('students.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-person-plus-fill mr-1"></i> Tambah Mahasiswa
                </a>
            @endcan
        </div>
    </div>
@stop

@section('content')
    {{-- Notifikasi Sukses / Pesan Error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill mr-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Info Hak Akses Role --}}
    @if(auth()->user()->hasRole('Staf'))
        <div class="alert alert-warning py-2 shadow-sm d-flex align-items-center" role="alert">
            <i class="bi bi-shield-lock-fill fs-5 mr-2"></i>
            <div>
                <strong>Perhatian Hak Akses:</strong> Anda masuk sebagai <strong>Staf</strong>. Anda dapat melihat, menambah, dan mengubah data, tetapi tombol <strong>Hapus Data</strong> dikunci secara eksklusif untuk Admin.
            </div>
        </div>
    @endif

    <!-- Card Filter Pencarian (Tantangan Tambahan Nilai Plus A+) -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="card-title m-0 font-weight-bold text-dark">
                <i class="bi bi-funnel-fill text-primary mr-1"></i> Filter & Pencarian Data Mahasiswa
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('students.index') }}" class="row g-3">
                <div class="col-md-5">
                    <label for="search" class="form-label fw-semibold">Kata Kunci (Nama, NIM, atau Email)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Cari berdasarkan nama, NIM, atau email..." 
                               value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="jurusan" class="form-label fw-semibold">Program Studi / Jurusan</label>
                    <select name="jurusan" id="jurusan" class="form-select">
                        <option value="">-- Semua Jurusan --</option>
                        @foreach($jurusanOptions as $option)
                            <option value="{{ $option }}" {{ request('jurusan') == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="status" class="form-label fw-semibold">Status Akademik</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Cuti" {{ request('status') == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="Lulus" {{ request('status') == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <div class="w-100 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-filter"></i> Terapkan
                        </button>
                        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Mahasiswa -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0 font-weight-bold text-dark">
                <i class="bi bi-table text-primary mr-1"></i> Daftar Mahasiswa Terdaftar
            </h5>
            <span class="badge bg-primary fs-6">{{ $students->total() }} Total Data</span>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Program Studi</th>
                        <th>Angkatan</th>
                        <th>IPK</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 220px;">Aksi Otorisasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $student)
                        <tr>
                            <td>{{ $students->firstItem() + $index }}</td>
                            <td><span class="badge bg-secondary font-monospace">{{ $student->nim }}</span></td>
                            <td>
                                <div class="fw-bold">{{ $student->nama }}</div>
                                <small class="text-muted">{{ $student->email }}</small>
                            </td>
                            <td>{{ $student->jurusan }}</td>
                            <td>{{ $student->angkatan }}</td>
                            <td>
                                <span class="badge {{ $student->ipk >= 3.5 ? 'bg-success' : 'bg-info' }}">
                                    {{ number_format($student->ipk, 2) }}
                                </span>
                            </td>
                            <td>
                                @if($student->status === 'Aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($student->status === 'Cuti')
                                    <span class="badge bg-warning text-dark">Cuti</span>
                                @else
                                    <span class="badge bg-primary">Lulus</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    {{-- Tombol Detail: Terbuka untuk semua yang memiliki izin view --}}
                                    <a href="{{ route('students.show', $student) }}" class="btn btn-outline-info" title="Lihat Profil">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- Tombol Edit: Terbuka untuk Admin dan Staf --}}
                                    @can('edit-academic-data')
                                        <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-primary" title="Ubah Data">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endcan

                                    {{-- ATURAN WAJIB: HAK EKSKLUSIF ADMIN --}}
                                    {{-- Tombol Hapus Data HANYA boleh dirender visual jika user memegang peran Admin murni / izin delete-academic-data --}}
                                    @can('delete-academic-data')
                                        <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Peringatan Otorisasi Admin:\nApakah Anda yakin ingin menghapus data mahasiswa {{ $student->nama }} (NIM: {{ $student->nim }}) secara permanen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus Data (Eksklusif Admin)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        {{-- Visual Feedback untuk Staf bahwa fitur ini dibatasi --}}
                                        <span class="d-inline-block" tabindex="0" title="Akses Ditolak: Hanya Admin yang berhak menghapus data">
                                            <button class="btn btn-outline-secondary disabled" style="pointer-events: none;" disabled>
                                                <i class="bi bi-lock-fill text-muted"></i>
                                            </button>
                                        </span>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                Tidak ditemukan data mahasiswa yang sesuai dengan kata kunci pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Footer Card dengan Pagination (Dilengkapi withQueryString) -->
        <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center flex-wrap">
            <div class="text-muted small mb-2 mb-sm-0">
                Menampilkan <strong>{{ $students->firstItem() ?? 0 }}</strong> sampai <strong>{{ $students->lastItem() ?? 0 }}</strong> dari <strong>{{ $students->total() }}</strong> mahasiswa
            </div>
            <div>
                {{ $students->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@stop
