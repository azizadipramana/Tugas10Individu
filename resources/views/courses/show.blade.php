@extends('adminlte::page')

@section('title', 'Detail Mata Kuliah | SIAKAD')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="bi bi-book-half text-warning mr-2"></i>Detail Silabus Mata Kuliah
            </h1>
            <p class="text-muted mb-0">Informasi beban studi dan kurikulum akademik.</p>
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
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 font-weight-bold text-dark">
                        <i class="bi bi-info-circle text-warning mr-1"></i> Data Mata Kuliah
                    </h5>
                    <span class="badge bg-info text-white fs-6">{{ $course->sks }} SKS</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Kode Mata Kuliah</div>
                        <div class="col-sm-8 font-monospace fs-5 text-dark fw-bold">{{ $course->kode_mk }}</div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Nama Mata Kuliah</div>
                        <div class="col-sm-8 text-dark fs-5 fw-bold">{{ $course->nama_mk }}</div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Beban Satuan Kredit Semester</div>
                        <div class="col-sm-8">{{ $course->sks }} SKS</div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Semester Penawaran</div>
                        <div class="col-sm-8">Semester {{ $course->semester }}</div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Program Studi</div>
                        <div class="col-sm-8 text-primary fw-semibold">{{ $course->program_studi }}</div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Dosen Pengampu Utama</div>
                        <div class="col-sm-8 fw-bold">{{ $course->dosen_pengampu ?? 'Belum ditentukan' }}</div>
                    </div>
                </div>
                <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <div class="d-flex gap-2">
                        @can('edit-academic-data')
                            <a href="{{ route('courses.edit', $course) }}" class="btn btn-warning text-dark font-weight-bold">
                                <i class="bi bi-pencil-square mr-1"></i> Edit Mata Kuliah
                            </a>
                        @endcan
                        @can('delete-academic-data')
                            <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Peringatan Otorisasi Admin:\nHapus mata kuliah {{ $course->nama_mk }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash mr-1"></i> Hapus Mata Kuliah
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

