@extends('adminlte::page')

@section('title', 'Detail Mahasiswa | SIAKAD')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="bi bi-person-lines-fill text-primary mr-2"></i>Detail Profil Mahasiswa
            </h1>
            <p class="text-muted mb-0">Informasi lengkap data akademik mahasiswa.</p>
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
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 font-weight-bold text-dark">
                        <i class="bi bi-person-vcard text-primary mr-1"></i> Kartu Informasi Mahasiswa
                    </h5>
                    <span class="badge {{ $student->status === 'Aktif' ? 'bg-success' : ($student->status === 'Cuti' ? 'bg-warning text-dark' : 'bg-primary') }} fs-6">
                        Status: {{ $student->status }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Nomor Induk Mahasiswa (NIM)</div>
                        <div class="col-sm-8 font-monospace fs-5 text-dark fw-bold">{{ $student->nim }}</div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Nama Lengkap</div>
                        <div class="col-sm-8 text-dark fs-5">{{ $student->nama }}</div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Email Resmi</div>
                        <div class="col-sm-8"><a href="mailto:{{ $student->email }}">{{ $student->email }}</a></div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Program Studi / Jurusan</div>
                        <div class="col-sm-8 fw-semibold text-primary">{{ $student->jurusan }}</div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Tahun Angkatan</div>
                        <div class="col-sm-8">{{ $student->angkatan }}</div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Indeks Prestasi Kumulatif (IPK)</div>
                        <div class="col-sm-8">
                            <span class="badge {{ $student->ipk >= 3.5 ? 'bg-success' : 'bg-info' }} fs-6">
                                {{ number_format($student->ipk, 2) }} / 4.00
                            </span>
                        </div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Jenis Kelamin</div>
                        <div class="col-sm-8">{{ $student->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}</div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Alamat Tinggal</div>
                        <div class="col-sm-8 text-muted">{{ $student->alamat ?? 'Belum diisi.' }}</div>
                    </div>
                    <hr class="my-2 text-muted opacity-25">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Waktu Registrasi</div>
                        <div class="col-sm-8 text-muted">{{ $student->created_at->format('d F Y, H:i') }} WIB</div>
                    </div>
                </div>
                <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <div class="d-flex gap-2">
                        @can('edit-academic-data')
                            <a href="{{ route('students.edit', $student) }}" class="btn btn-primary">
                                <i class="bi bi-pencil-square mr-1"></i> Edit Mahasiswa
                            </a>
                        @endcan
                        @can('delete-academic-data')
                            <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Peringatan Otorisasi Admin:\nApakah Anda yakin ingin menghapus data mahasiswa ini secara permanen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash mr-1"></i> Hapus Mahasiswa
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

