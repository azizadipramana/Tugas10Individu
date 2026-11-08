@extends('adminlte::page')

@section('title', 'Edit Mata Kuliah | SIAKAD')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="bi bi-pencil-square text-warning mr-2"></i>Edit Mata Kuliah
            </h1>
            <p class="text-muted mb-0">Perbarui informasi: {{ $course->nama_mk }} ({{ $course->kode_mk }})</p>
        </div>
        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title m-0 font-weight-bold text-dark">Formulir Pembaruan Mata Kuliah</h5>
                </div>
                <form action="{{ route('courses.update', $course) }}" method="POST">
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
                                <label for="kode_mk" class="form-label fw-bold">Kode Mata Kuliah <span class="text-danger">*</span></label>
                                <input type="text" name="kode_mk" id="kode_mk" class="form-control @error('kode_mk') is-invalid @enderror" 
                                       value="{{ old('kode_mk', $course->kode_mk) }}" required>
                                @error('kode_mk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nama_mk" class="form-label fw-bold">Nama Mata Kuliah <span class="text-danger">*</span></label>
                                <input type="text" name="nama_mk" id="nama_mk" class="form-control @error('nama_mk') is-invalid @enderror" 
                                       value="{{ old('nama_mk', $course->nama_mk) }}" required>
                                @error('nama_mk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="sks" class="form-label fw-bold">Beban SKS <span class="text-danger">*</span></label>
                                <select name="sks" id="sks" class="form-select @error('sks') is-invalid @enderror" required>
                                    @foreach([1, 2, 3, 4, 6] as $credit)
                                        <option value="{{ $credit }}" {{ old('sks', $course->sks) == $credit ? 'selected' : '' }}>{{ $credit }} SKS</option>
                                    @endforeach
                                </select>
                                @error('sks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="semester" class="form-label fw-bold">Semester Ditawarkan <span class="text-danger">*</span></label>
                                <select name="semester" id="semester" class="form-select @error('semester') is-invalid @enderror" required>
                                    @for($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}" {{ old('semester', $course->semester) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                                    @endfor
                                </select>
                                @error('semester')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="program_studi" class="form-label fw-bold">Program Studi <span class="text-danger">*</span></label>
                                <select name="program_studi" id="program_studi" class="form-select @error('program_studi') is-invalid @enderror" required>
                                    @foreach($prodiOptions as $option)
                                        <option value="{{ $option }}" {{ old('program_studi', $course->program_studi) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                                @error('program_studi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="dosen_pengampu" class="form-label fw-bold">Dosen Pengampu Utama</label>
                                <select name="dosen_pengampu" id="dosen_pengampu" class="form-select @error('dosen_pengampu') is-invalid @enderror">
                                    <option value="">-- Pilih Dosen Pengampu --</option>
                                    @foreach($lecturers as $lecturer)
                                        <option value="{{ $lecturer->nama }}" {{ old('dosen_pengampu', $course->dosen_pengampu) == $lecturer->nama ? 'selected' : '' }}>
                                            {{ $lecturer->nama }} ({{ $lecturer->departemen }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('dosen_pengampu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light py-3 d-flex justify-content-end gap-2">
                        <a href="{{ route('courses.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning text-dark font-weight-bold">
                            <i class="bi bi-check2-circle mr-1"></i> Perbarui Mata Kuliah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

