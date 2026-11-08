@extends('adminlte::page')

@section('title', 'Ubah Data Mahasiswa | SIAKAD')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="bi bi-pencil-square text-primary mr-2"></i>Ubah Data Mahasiswa
            </h1>
            <p class="text-muted mb-0">Memperbarui informasi akademik mahasiswa: {{ $student->nama }} ({{ $student->nim }})</p>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title m-0 font-weight-bold text-dark">
                        <i class="bi bi-person-gear text-primary mr-1"></i> Edit Data Mahasiswa
                    </h5>
                </div>
                <form action="{{ route('students.update', $student) }}" method="POST">
                    @csrf
                    @method('PUT')
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
                                <label for="nim" class="form-label fw-bold">NIM (Nomor Induk Mahasiswa) <span class="text-danger">*</span></label>
                                <input type="text" name="nim" id="nim" class="form-control @error('nim') is-invalid @enderror" 
                                       value="{{ old('nim', $student->nim) }}" required>
                                @error('nim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" 
                                       value="{{ old('nama', $student->nama) }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold">Email Mahasiswa <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $student->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="jurusan" class="form-label fw-bold">Program Studi / Jurusan <span class="text-danger">*</span></label>
                                <select name="jurusan" id="jurusan" class="form-select @error('jurusan') is-invalid @enderror" required>
                                    @foreach($jurusanOptions as $option)
                                        <option value="{{ $option }}" {{ old('jurusan', $student->jurusan) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                                @error('jurusan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="angkatan" class="form-label fw-bold">Angkatan <span class="text-danger">*</span></label>
                                <select name="angkatan" id="angkatan" class="form-select @error('angkatan') is-invalid @enderror" required>
                                    @foreach(['2021', '2022', '2023', '2024', '2025', '2026'] as $year)
                                        <option value="{{ $year }}" {{ old('angkatan', $student->angkatan) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                                @error('angkatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="ipk" class="form-label fw-bold">Indeks Prestasi Kumulatif (IPK) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" max="4.00" name="ipk" id="ipk" 
                                       class="form-control @error('ipk') is-invalid @enderror" 
                                       value="{{ old('ipk', number_format($student->ipk, 2)) }}" required>
                                @error('ipk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label fw-bold">Status Akademik <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="Aktif" {{ old('status', $student->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Cuti" {{ old('status', $student->status) == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                    <option value="Lulus" {{ old('status', $student->status) == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold d-block">Jenis Kelamin <span class="text-danger">*</span></label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="genderL" value="L" {{ old('jenis_kelamin', $student->jenis_kelamin) == 'L' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="genderL">Laki-Laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="genderP" value="P" {{ old('jenis_kelamin', $student->jenis_kelamin) == 'P' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="genderP">Perempuan</label>
                                </div>
                                @error('jenis_kelamin')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="alamat" class="form-label fw-bold">Alamat Tinggal</label>
                                <textarea name="alamat" id="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $student->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light py-3 d-flex justify-content-end gap-2">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle mr-1"></i> Perbarui Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
