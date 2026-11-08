<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class StudentController extends Controller implements HasMiddleware
{
    /**
     * Define controller middleware permissions to secure endpoints.
     */
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:view-academic-data', only: ['index', 'show']),
            new Middleware('permission:create-academic-data', only: ['create', 'store']),
            new Middleware('permission:edit-academic-data', only: ['edit', 'update']),
            new Middleware('permission:delete-academic-data', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of students with conditional search and locked pagination.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $jurusan = $request->input('jurusan');
        $status = $request->input('status');

        // Tantangan Tambahan: Form pencarian data menggunakan kueri kondisional when()
        $students = Student::query()
            ->when($request->filled('search'), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('jurusan'), function ($query) use ($jurusan) {
                $query->where('jurusan', $jurusan);
            })
            ->when($request->filled('status'), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            // Tantangan Tambahan: Pengunci Link Pagination via withQueryString()
            ->paginate(10)
            ->withQueryString();

        $jurusanOptions = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Sains Data',
            'Teknik Komputer',
            'Rekayasa Perangkat Lunak',
            'Teknologi Informasi',
        ];

        return view('students.index', compact('students', 'jurusanOptions'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create(): View
    {
        $jurusanOptions = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Sains Data',
            'Teknik Komputer',
            'Rekayasa Perangkat Lunak',
            'Teknologi Informasi',
        ];

        return view('students.create', compact('jurusanOptions'));
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nim' => ['required', 'string', 'max:20', 'unique:students,nim'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:students,email'],
            'jurusan' => ['required', 'string', 'max:100'],
            'angkatan' => ['required', 'string', 'max:4'],
            'ipk' => ['required', 'numeric', 'between:0,4.00'],
            'status' => ['required', 'in:Aktif,Cuti,Lulus'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['nullable', 'string', 'max:500'],
        ]);

        Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', 'Data Mahasiswa berhasil ditambahkan ke database.');
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student): View
    {
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student): View
    {
        $jurusanOptions = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Sains Data',
            'Teknik Komputer',
            'Rekayasa Perangkat Lunak',
            'Teknologi Informasi',
        ];

        return view('students.edit', compact('student', 'jurusanOptions'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'nim' => ['required', 'string', 'max:20', Rule::unique('students', 'nim')->ignore($student->id)],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('students', 'email')->ignore($student->id)],
            'jurusan' => ['required', 'string', 'max:100'],
            'angkatan' => ['required', 'string', 'max:4'],
            'ipk' => ['required', 'numeric', 'between:0,4.00'],
            'status' => ['required', 'in:Aktif,Cuti,Lulus'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['nullable', 'string', 'max:500'],
        ]);

        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', "Data Mahasiswa {$student->nama} ({$student->nim}) berhasil diperbarui.");
    }

    /**
     * Remove the specified student from storage.
     * Hak Eksklusif: Hanya dieksekusi apabila pengguna memegang peran Admin / izin delete-academic-data.
     */
    public function destroy(Student $student): RedirectResponse
    {
        // Pengaman berlapis ganda (server-side explicit authorization check)
        abort_if(
            ! auth()->user()->can('delete-academic-data'),
            403,
            'AKSES DITOLAK: Anda memegang peran Staf dengan akses terbatas. Hak hapus data hanya diberikan secara eksklusif kepada Admin!'
        );

        $nama = $student->nama;
        $nim = $student->nim;
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', "Data Mahasiswa {$nama} (NIM: {$nim}) berhasil dihapus permanen oleh Administrator.");
    }
}
