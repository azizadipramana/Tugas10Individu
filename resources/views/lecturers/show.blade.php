@extends('adminlte::page')

@section('title', 'Detail Dosen | SIAKAD')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="bi bi-person-lines-fill text-success mr-2"></i>Profil Tenaga Pengajar
            </h1>
            <p class="text-muted mb-0">Informasi fungsional dan biodata dosen pengampu.</p>
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
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 font-weight-bold text-dark">
                        <i class="bi bi-person-vcard text-success mr-1"></i> Data Tenaga Pendidik
                    </h5>
                    <span class="badge {{ $lecturer->status === 'Aktif' ? 'bg-success' : 'bg-warning text-dark' }} fs-6">
                        {{ $lecturer->status }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Nomor Induk Dosen (NIDN)</div>
                        <div class="col-sm-8 font-monospace fs-5 text-dark fw-bold">{{ $lecturer->nidn }}</div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Nama Lengkap & Gelar</div>
                        <div class="col-sm-8 text-dark fs-5 fw-bold">{{ $lecturer->nama }}</div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Email Dosen</div>
                        <div class="col-sm-8"><a href="mailto:{{ $lecturer->email }}">{{ $lecturer->email }}</a></div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Departemen</div>
                        <div class="col-sm-8 text-primary fw-semibold">{{ $lecturer->departemen }}</div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Jabatan Akademik</div>
                        <div class="col-sm-8"><span class="badge bg-light text-dark border fs-6">{{ $lecturer->jabatan_akademik }}</span></div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Tanggal Bergabung</div>
                        <div class="col-sm-8 text-muted">{{ $lecturer->created_at->format('d F Y') }}</div>
                    </div>
                </div>
                <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('lecturers.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <div class="d-flex gap-2">
                        @can('edit-academic-data')
                            <a href="{{ route('lecturers.edit', $lecturer) }}" class="btn btn-success">
                                <i class="bi bi-pencil-square mr-1"></i> Edit Dosen
                            </a>
                        @endcan
                        @can('delete-academic-data')
                            <form action="{{ route('lecturers.destroy', $lecturer) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Peringatan Otorisasi Admin:\nHapus data dosen {{ $lecturer->nama }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash mr-1"></i> Hapus Dosen
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
