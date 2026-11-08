@extends('adminlte::page')

@section('title', 'Matriks Hak Akses RBAC | SIAKAD')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="bi bi-shield-check text-primary mr-2"></i>Matriks Otorisasi Role-Based Access Control (RBAC)
            </h1>
            <p class="text-muted mb-0">Dokumentasi teknis pemetaan peran (Roles) dan hak akses (Permissions) paket Spatie.</p>
        </div>
        <div class="mt-2 mt-sm-0">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <!-- Tabel Matriks Perizinan -->
        <div class="col-lg-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title m-0 font-weight-bold text-dark">
                        <i class="bi bi-grid-3x3-gap-fill text-primary mr-1"></i> Matriks Hak Akses Antar Peran (Admin vs Staf)
                    </h5>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0 text-center">
                        <thead class="table-light">
                            <tr>
                                <th class="text-start" style="width: 280px;">Nama Izin (Permission)</th>
                                <th class="text-start">Deskripsi Fungsionalitas</th>
                                <th style="width: 180px;" class="bg-danger bg-opacity-10 text-danger fw-bold">Admin (Akses Penuh)</th>
                                <th style="width: 180px;" class="bg-warning bg-opacity-10 text-warning-emphasis fw-bold">Staf (Akses Terbatas)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-start font-monospace"><code>view-dashboard</code></td>
                                <td class="text-start">Melihat statistik agregat dan widget box di beranda admin.</td>
                                <td><span class="badge bg-success fs-6"><i class="bi bi-check-lg"></i> Diizinkan</span></td>
                                <td><span class="badge bg-success fs-6"><i class="bi bi-check-lg"></i> Diizinkan</span></td>
                            </tr>
                            <tr>
                                <td class="text-start font-monospace"><code>view-academic-data</code></td>
                                <td class="text-start">Melihat daftar tabel data Mahasiswa, Dosen, dan Mata Kuliah beserta filter kueri <code>when()</code>.</td>
                                <td><span class="badge bg-success fs-6"><i class="bi bi-check-lg"></i> Diizinkan</span></td>
                                <td><span class="badge bg-success fs-6"><i class="bi bi-check-lg"></i> Diizinkan</span></td>
                            </tr>
                            <tr>
                                <td class="text-start font-monospace"><code>create-academic-data</code></td>
                                <td class="text-start">Menambahkan entri baru Mahasiswa, Dosen, dan Mata Kuliah ke database.</td>
                                <td><span class="badge bg-success fs-6"><i class="bi bi-check-lg"></i> Diizinkan</span></td>
                                <td><span class="badge bg-success fs-6"><i class="bi bi-check-lg"></i> Diizinkan</span></td>
                            </tr>
                            <tr>
                                <td class="text-start font-monospace"><code>edit-academic-data</code></td>
                                <td class="text-start">Memperbarui informasi data akademik yang sudah ada.</td>
                                <td><span class="badge bg-success fs-6"><i class="bi bi-check-lg"></i> Diizinkan</span></td>
                                <td><span class="badge bg-success fs-6"><i class="bi bi-check-lg"></i> Diizinkan</span></td>
                            </tr>
                            <tr class="table-danger bg-opacity-25">
                                <td class="text-start font-monospace fw-bold text-danger"><code>delete-academic-data</code></td>
                                <td class="text-start text-danger fw-semibold">
                                    <i class="bi bi-exclamation-triangle-fill mr-1"></i> Hak Eksklusif: Menghapus data secara permanen. Tombol di-render via <code>@can</code> dan dieksekusi via <code>abort_if</code> / middleware.
                                </td>
                                <td><span class="badge bg-danger fs-6"><i class="bi bi-check-lg"></i> Diizinkan (Eksklusif)</span></td>
                                <td><span class="badge bg-secondary fs-6"><i class="bi bi-x-lg"></i> DITOLAK (403)</span></td>
                            </tr>
                            <tr class="table-danger bg-opacity-25">
                                <td class="text-start font-monospace fw-bold text-danger"><code>manage-users</code></td>
                                <td class="text-start text-danger fw-semibold">
                                    <i class="bi bi-exclamation-triangle-fill mr-1"></i> Hak Eksklusif: Mengakses modul kelola pengguna dan mengganti peran RBAC. Menu disembunyikan otomatis oleh filter AdminLTE.
                                </td>
                                <td><span class="badge bg-danger fs-6"><i class="bi bi-check-lg"></i> Diizinkan (Eksklusif)</span></td>
                                <td><span class="badge bg-secondary fs-6"><i class="bi bi-x-lg"></i> DITOLAK (403)</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tiga Pilar Pengamanan RBAC -->
    <div class="row">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="card-title m-0 font-weight-bold">
                        <i class="bi bi-database mr-1"></i> 1. Database Fisik Spatie
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">
                        Hak akses disimpan dinamis di tabel relasional database:
                    </p>
                    <ul class="small mb-0">
                        <li><code>roles</code> (Admin, Staf)</li>
                        <li><code>permissions</code> (6 jenis izin)</li>
                        <li><code>model_has_roles</code> (penugasan peran user)</li>
                        <li><code>role_has_permissions</code> (pemetaan peran ke izin)</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-success text-white py-3">
                    <h6 class="card-title m-0 font-weight-bold">
                        <i class="bi bi-shield-shaded mr-1"></i> 2. Pengaman Controller
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">
                        Menolak manipulasi URL langsung dengan constructor / <code>HasMiddleware</code> dan validasi ganda server-side:
                    </p>
                    <pre class="bg-light p-2 rounded small text-dark"><code>abort_if(
  !auth()->user()->can('delete-academic-data'),
  403,
  'Akses Ditolak: Khusus Admin!'
);</code></pre>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-warning text-dark py-3">
                    <h6 class="card-title m-0 font-weight-bold">
                        <i class="bi bi-eye-slash-fill mr-1"></i> 3. Perlindungan Visual Blade
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">
                        Tombol aksi hapus dan menu navigasi hanya dirender apabila pengguna memiliki izin:
                    </p>
                    <pre class="bg-light p-2 rounded small text-dark"><code>@can('delete-academic-data')
  &lt;button class="btn btn-danger"&gt;
    Hapus Data
  &lt;/button&gt;
@endcan</code></pre>
                </div>
            </div>
        </div>
    </div>
@stop

