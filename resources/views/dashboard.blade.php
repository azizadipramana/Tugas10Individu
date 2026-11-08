@extends('adminlte::page')

@section('title', 'Dashboard Portal Akademik')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="bi bi-speedometer2 text-primary mr-2"></i>Dashboard Portal Akademik
            </h1>
            <p class="text-muted mb-0">Selamat datang di Panel Manajemen Akademik Terpadu.</p>
        </div>
        <div class="mt-2 mt-sm-0">
            @if(auth()->user()->hasRole('Admin'))
                <span class="badge bg-danger p-2 fs-6 shadow-sm">
                    <i class="bi bi-shield-fill-check mr-1"></i> Peran: ADMINISTRATOR (Akses Penuh)
                </span>
            @else
                <span class="badge bg-warning text-dark p-2 fs-6 shadow-sm">
                    <i class="bi bi-shield-lock mr-1"></i> Peran: STAF AKADEMIK (Akses Terbatas)
                </span>
            @endif
        </div>
    </div>
@stop

@section('content')
    {{-- Notifikasi Session / Flash Message --}}
    @if(session('status'))
        <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-info-circle-fill mr-2"></i> {!! session('status') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Banner Otorisasi RBAC Dinamis --}}
    <div class="card mb-4 border-0 shadow-sm {{ auth()->user()->hasRole('Admin') ? 'bg-light' : 'bg-light' }}">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="font-weight-bold mb-1">
                        <i class="bi bi-person-circle mr-1"></i> Halo, {{ auth()->user()->name }}!
                    </h5>
                    <p class="text-muted mb-0">
                        Anda terautentikasi dengan email <strong>{{ auth()->user()->email }}</strong>.
                        @if(auth()->user()->hasRole('Admin'))
                            Sebagai <strong>Admin</strong>, Anda memiliki hak istimewa penuh termasuk <em>menambah, mengubah, menghapus data akademik</em>, serta <em>mengelola perizinan pengguna sistem</em>.
                        @else
                            Sebagai <strong>Staf</strong>, Anda memiliki hak <em>melihat, menambah, dan mengubah data</em>. Tombol <strong>Hapus Data</strong> disembunyikan dan diproteksi di tingkat server.
                        @endif
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('rbac.info') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-info-circle mr-1"></i> Panduan Matriks RBAC
                    </a>
                    @role('Admin')
                    <a href="{{ route('users.index') }}" class="btn btn-danger btn-sm ml-1">
                        <i class="bi bi-shield-lock-fill mr-1"></i> Kelola Hak Akses
                    </a>
                    @endrole
                </div>
            </div>
        </div>
    </div>

    {{-- ATURAN WAJIB 4: Minimal 3 Widget Box Statistik Dinamis (Hasil Model count()) --}}
    <div class="row">
        <!-- Widget 1: Total Mahasiswa -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info text-white shadow-sm rounded">
                <div class="inner p-3">
                    <h3 class="font-weight-bold">{{ number_format($totalMahasiswa) }}</h3>
                    <p class="mb-0">Total Mahasiswa</p>
                </div>
                <div class="icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <a href="{{ route('students.index') }}" class="small-box-footer text-white text-decoration-none py-1 d-block text-center bg-black bg-opacity-25">
                    Lihat Data Mahasiswa <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

        <!-- Widget 2: Total Dosen Pengajar -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success text-white shadow-sm rounded">
                <div class="inner p-3">
                    <h3 class="font-weight-bold">{{ number_format($totalDosen) }}</h3>
                    <p class="mb-0">Total Dosen Pengajar</p>
                </div>
                <div class="icon">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <a href="{{ route('lecturers.index') }}" class="small-box-footer text-white text-decoration-none py-1 d-block text-center bg-black bg-opacity-25">
                    Lihat Data Dosen <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

        <!-- Widget 3: Total Mata Kuliah -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning text-dark shadow-sm rounded">
                <div class="inner p-3">
                    <h3 class="font-weight-bold">{{ number_format($totalMataKuliah) }}</h3>
                    <p class="mb-0">Mata Kuliah Ditawarkan</p>
                </div>
                <div class="icon">
                    <i class="bi bi-book-half"></i>
                </div>
                <a href="{{ route('courses.index') }}" class="small-box-footer text-dark text-decoration-none py-1 d-block text-center bg-black bg-opacity-10">
                    Lihat Mata Kuliah <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

        <!-- Widget 4: Total Pengguna Sistem -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger text-white shadow-sm rounded">
                <div class="inner p-3">
                    <h3 class="font-weight-bold">{{ number_format($totalUsers) }}</h3>
                    <p class="mb-0">Pengguna Terdaftar</p>
                </div>
                <div class="icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                @role('Admin')
                <a href="{{ route('users.index') }}" class="small-box-footer text-white text-decoration-none py-1 d-block text-center bg-black bg-opacity-25">
                    Kelola Pengguna & Peran <i class="bi bi-arrow-right-circle"></i>
                </a>
                @else
                <span class="small-box-footer text-white-50 py-1 d-block text-center bg-black bg-opacity-25">
                    <i class="bi bi-lock-fill"></i> Akses Terbatas Admin
                </span>
                @endrole
            </div>
        </div>
    </div>

    {{-- Detail Sub-Agregat Status Mahasiswa --}}
    <div class="row mt-2">
        <div class="col-md-4">
            <div class="info-box shadow-sm mb-3">
                <span class="info-box-icon bg-success"><i class="bi bi-person-check-fill"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Mahasiswa Aktif</span>
                    <span class="info-box-number text-success fs-5">{{ $mahasiswaAktif }} Mahasiswa</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box shadow-sm mb-3">
                <span class="info-box-icon bg-warning text-white"><i class="bi bi-person-dash-fill"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Mahasiswa Cuti</span>
                    <span class="info-box-number text-warning fs-5">{{ $mahasiswaCuti }} Mahasiswa</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box shadow-sm mb-3">
                <span class="info-box-icon bg-primary"><i class="bi bi-mortarboard-fill"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Mahasiswa Lulus</span>
                    <span class="info-box-number text-primary fs-5">{{ $mahasiswaLulus }} Alumni</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Baris Tabel Agregat --}}
    <div class="row mt-2">
        <!-- Mahasiswa Terbaru -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 font-weight-bold text-dark">
                        <i class="bi bi-clock-history text-primary mr-1"></i> Registrasi Mahasiswa Terbaru
                    </h5>
                    <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>NIM</th>
                                <th>Nama Lengkap</th>
                                <th>Jurusan</th>
                                <th>Angkatan</th>
                                <th>IPK</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentStudents as $student)
                                <tr>
                                    <td><span class="badge bg-secondary font-monospace">{{ $student->nim }}</span></td>
                                    <td class="font-weight-bold">{{ $student->nama }}</td>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">Belum ada data mahasiswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Distribusi Program Studi -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title m-0 font-weight-bold text-dark">
                        <i class="bi bi-pie-chart-fill text-info mr-1"></i> Distribusi Mahasiswa per Prodi
                    </h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($prodiStats as $stat)
                            @php
                                $percent = $totalMahasiswa > 0 ? round(($stat->count / $totalMahasiswa) * 100, 1) : 0;
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <span class="fw-semibold">{{ $stat->jurusan }}</span>
                                    <div class="progress mt-1" style="height: 5px; width: 140px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary rounded-pill">{{ $stat->count }}</span>
                                    <small class="text-muted d-block">{{ $percent }}%</small>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop
