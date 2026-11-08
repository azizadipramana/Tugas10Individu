@extends('adminlte::page')

@section('title', 'Tambah Dosen | SIAKAD')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="bi bi-person-plus-fill text-success mr-2"></i>Tambah Dosen Baru
            </h1>
            <p class="text-muted mb-0">Mendaftarkan tenaga pengajar baru ke dalam pangkalan data akademik.</p>
        </div>
        <a href="{{ route('lecturers.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title m-0 font-weight-bold text-dark">Formulir Data Dosen</h5>
                </div>
                <form action="{{ route('lecturers.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="nidn" class="form-label fw-bold">NIDN <span class="text-danger">*</span></label>
                                <input type="text" name="nidn" id="nidn" class="form-control @error('nidn') is-invalid @enderror" 
                                       placeholder="10 digit NIDN" value="{{ old('nidn') }}" required>
                                @error('nidn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label fw-bold">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" 
                                       placeholder="Contoh: Dr. Ir. Budi Santoso, M.Kom." value="{{ old('nama') }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold">Email Institusi <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                       placeholder="nama@lecturer.its.ac.id" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="departemen" class="form-label fw-bold">Departemen <span class="text-danger">*</span></label>
                                <select name="departemen" id="departemen" class="form-select @error('departemen') is-invalid @enderror" required>
                                    <option value="">-- Pilih Departemen --</option>
                                    @foreach($departemenOptions as $dept)
                                        <option value="{{ $dept }}" {{ old('departemen') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                    @endforeach
                                </select>
                                @error('departemen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="jabatan_akademik" class="form-label fw-bold">Jabatan Fungsional <span class="text-danger">*</span></label>
                                <select name="jabatan_akademik" id="jabatan_akademik" class="form-select @error('jabatan_akademik') is-invalid @enderror" required>
                                    @foreach($jabatanOptions as $jabatan)
                                        <option value="{{ $jabatan }}" {{ old('jabatan_akademik') == $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                                    @endforeach
                                </select>
                                @error('jabatan_akademik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-bold">Status Keaktifan <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Studi Lanjut" {{ old('status') == 'Studi Lanjut' ? 'selected' : '' }}>Studi Lanjut</option>
                                    <option value="Cuti" {{ old('status') == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light py-3 d-flex justify-content-end gap-2">
                        <a href="{{ route('lecturers.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save mr-1"></i> Simpan Dosen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

