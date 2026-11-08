<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecturer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class CourseController extends Controller implements HasMiddleware
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
     * Display a listing of courses with conditional search and locked pagination.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $semester = $request->input('semester');
        $prodi = $request->input('prodi');

        $courses = Course::query()
            ->when($request->filled('search'), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_mk', 'like', "%{$search}%")
                        ->orWhere('kode_mk', 'like', "%{$search}%")
                        ->orWhere('dosen_pengampu', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('semester'), function ($query) use ($semester) {
                $query->where('semester', $semester);
            })
            ->when($request->filled('prodi'), function ($query) use ($prodi) {
                $query->where('program_studi', $prodi);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $prodiOptions = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Sains Data',
            'Teknik Komputer',
            'Teknologi Informasi',
        ];

        return view('courses.index', compact('courses', 'prodiOptions'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create(): View
    {
        $prodiOptions = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Sains Data',
            'Teknik Komputer',
            'Teknologi Informasi',
        ];

        $lecturers = Lecturer::orderBy('nama')->get();

        return view('courses.create', compact('prodiOptions', 'lecturers'));
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_mk' => ['required', 'string', 'max:20', 'unique:courses,kode_mk'],
            'nama_mk' => ['required', 'string', 'max:255'],
            'sks' => ['required', 'integer', 'between:1,6'],
            'semester' => ['required', 'integer', 'between:1,8'],
            'program_studi' => ['required', 'string', 'max:100'],
            'dosen_pengampu' => ['nullable', 'string', 'max:255'],
        ]);

        Course::create($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Mata Kuliah baru berhasil disimpan.');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course): View
    {
        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course): View
    {
        $prodiOptions = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Sains Data',
            'Teknik Komputer',
            'Teknologi Informasi',
        ];

        $lecturers = Lecturer::orderBy('nama')->get();

        return view('courses.edit', compact('course', 'prodiOptions', 'lecturers'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'kode_mk' => ['required', 'string', 'max:20', Rule::unique('courses', 'kode_mk')->ignore($course->id)],
            'nama_mk' => ['required', 'string', 'max:255'],
            'sks' => ['required', 'integer', 'between:1,6'],
            'semester' => ['required', 'integer', 'between:1,8'],
            'program_studi' => ['required', 'string', 'max:100'],
            'dosen_pengampu' => ['nullable', 'string', 'max:255'],
        ]);

        $course->update($validated);

        return redirect()->route('courses.index')
            ->with('success', "Data Mata Kuliah {$course->nama_mk} ({$course->kode_mk}) berhasil diperbarui.");
    }

    /**
     * Remove the specified course from storage.
     * Hak Eksklusif: Hanya Admin murni yang boleh menghapus data mata kuliah.
     */
    public function destroy(Course $course): RedirectResponse
    {
        abort_if(
            ! auth()->user()->can('delete-academic-data'),
            403,
            'AKSES DITOLAK: Anda memegang peran Staf dengan akses terbatas. Hak hapus data hanya diberikan secara eksklusif kepada Admin!'
        );

        $namaMk = $course->nama_mk;
        $kodeMk = $course->kode_mk;
        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', "Mata Kuliah {$namaMk} ({$kodeMk}) berhasil dihapus permanen oleh Administrator.");
    }
}
