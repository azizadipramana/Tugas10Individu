@extends('adminlte::page')

@section('title', 'Kelola Pengguna & Peran | SIAKAD')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="bi bi-shield-lock-fill text-danger mr-2"></i>Manajemen Pengguna & Otorisasi RBAC
            </h1>
            <p class="text-muted mb-0">Halaman eksklusif Administrator untuk mengelola akun dan penetapan peran (Spatie Permission).</p>
        </div>
        <div class="mt-2 mt-sm-0">
            <button type="button" class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-person-plus-fill mr-1"></i> Tambah Pengguna Baru
            </button>
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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0 font-weight-bold text-dark">
                <i class="bi bi-people-fill text-danger mr-1"></i> Daftar Akun Pengguna Sistem
            </h5>
            <span class="badge bg-danger fs-6">{{ $users->count() }} Akun Terdaftar</span>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Pengguna</th>
                        <th>Email Pengguna</th>
                        <th>Peran Saat Ini (Role)</th>
                        <th>Hak Akses (Permissions)</th>
                        <th class="text-center" style="width: 250px;">Ubah Peran Dinamis</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $u)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold">{{ $u->name }}</div>
                                @if($u->id === auth()->id())
                                    <span class="badge bg-info text-dark">Akun Anda (Aktif)</span>
                                @endif
                            </td>
                            <td><code>{{ $u->email }}</code></td>
                            <td>
                                @foreach($u->roles as $role)
                                    @if($role->name === 'Admin')
                                        <span class="badge bg-danger fs-6"><i class="bi bi-shield-fill-check"></i> Admin (Akses Penuh)</span>
                                    @else
                                        <span class="badge bg-warning text-dark fs-6"><i class="bi bi-shield-lock"></i> Staf (Akses Terbatas)</span>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1" style="max-width: 380px;">
                                    @if($u->hasRole('Admin'))
                                        <span class="badge bg-success bg-opacity-25 text-success border border-success">view-dashboard</span>
                                        <span class="badge bg-success bg-opacity-25 text-success border border-success">view-academic-data</span>
                                        <span class="badge bg-success bg-opacity-25 text-success border border-success">create-academic-data</span>
                                        <span class="badge bg-success bg-opacity-25 text-success border border-success">edit-academic-data</span>
                                        <span class="badge bg-danger text-white">delete-academic-data (Eksklusif)</span>
                                        <span class="badge bg-danger text-white">manage-users (Eksklusif)</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-25 text-success border border-success">view-dashboard</span>
                                        <span class="badge bg-success bg-opacity-25 text-success border border-success">view-academic-data</span>
                                        <span class="badge bg-success bg-opacity-25 text-success border border-success">create-academic-data</span>
                                        <span class="badge bg-success bg-opacity-25 text-success border border-success">edit-academic-data</span>
                                        <span class="badge bg-secondary text-muted"><s>delete-academic-data</s></span>
                                        <span class="badge bg-secondary text-muted"><s>manage-users</s></span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('users.updateRole', $u) }}" method="POST" class="d-flex gap-2 justify-content-center">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" class="form-select form-select-sm" style="width: 120px;" {{ ($u->id === auth()->id()) ? 'disabled' : '' }}>
                                        @foreach($roles as $r)
                                            <option value="{{ $r->name }}" {{ $u->hasRole($r->name) ? 'selected' : '' }}>
                                                {{ $r->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" {{ ($u->id === auth()->id()) ? 'disabled' : '' }}>
                                        Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Pengguna Baru -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title font-weight-bold" id="addUserModalLabel">
                            <i class="bi bi-person-plus-fill mr-1"></i> Tambah Pengguna Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Rahmat Hidayat" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="rahmat@akademik.its.ac.id" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Kata Sandi (Password)</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 8 karakter" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label fw-bold">Peran Otorisasi (Role)</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="Staf">Staf (Akses Terbatas - Tanpa Izin Hapus)</option>
                                <option value="Admin">Admin (Akses Penuh - Termasuk Izin Hapus & Kelola User)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Simpan Pengguna</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
