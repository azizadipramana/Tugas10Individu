<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class LecturerController extends Controller implements HasMiddleware
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
     * Display a listing of lecturers with conditional search and locked pagination.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $departemen = $request->input('departemen');

        $lecturers = Lecturer::query()
            ->when($request->filled('search'), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nidn', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('departemen'), function ($query) use ($departemen) {
                $query->where('departemen', $departemen);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $departemenOptions = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Sains Data',
            'Teknik Komputer',
            'Teknologi Informasi',
        ];

        return view('lecturers.index', compact('lecturers', 'departemenOptions'));
    }

    /**
     * Show the form for creating a new lecturer.
     */
    public function create(): View
    {
        $departemenOptions = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Sains Data',
            'Teknik Komputer',
            'Teknologi Informasi',
        ];

        $jabatanOptions = ['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar'];

        return view('lecturers.create', compact('departemenOptions', 'jabatanOptions'));
    }

    /**
     * Store a newly created lecturer in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nidn' => ['required', 'string', 'max:20', 'unique:lecturers,nidn'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:lecturers,email'],
            'departemen' => ['required', 'string', 'max:100'],
            'jabatan_akademik' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:Aktif,Studi Lanjut,Cuti'],
        ]);

        Lecturer::create($validated);

        return redirect()->route('lecturers.index')
            ->with('success', 'Data Dosen berhasil ditambahkan ke sistem.');
    }

    /**
     * Display the specified lecturer.
     */
    public function show(Lecturer $lecturer): View
    {
        return view('lecturers.show', compact('lecturer'));
    }

    /**
     * Show the form for editing the specified lecturer.
     */
    public function edit(Lecturer $lecturer): View
    {
        $departemenOptions = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Sains Data',
            'Teknik Komputer',
            'Teknologi Informasi',
        ];

        $jabatanOptions = ['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar'];

        return view('lecturers.edit', compact('lecturer', 'departemenOptions', 'jabatanOptions'));
    }

    /**
     * Update the specified lecturer in storage.
     */
    public function update(Request $request, Lecturer $lecturer): RedirectResponse
    {
        $validated = $request->validate([
            'nidn' => ['required', 'string', 'max:20', Rule::unique('lecturers', 'nidn')->ignore($lecturer->id)],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('lecturers', 'email')->ignore($lecturer->id)],
            'departemen' => ['required', 'string', 'max:100'],
            'jabatan_akademik' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:Aktif,Studi Lanjut,Cuti'],
        ]);

        $lecturer->update($validated);

        return redirect()->route('lecturers.index')
            ->with('success', "Data Dosen {$lecturer->nama} berhasil diperbarui.");
    }

    /**
     * Remove the specified lecturer from storage.
     * Hak Eksklusif: Hanya Admin murni yang boleh menghapus data dosen.
     */
    public function destroy(Lecturer $lecturer): RedirectResponse
    {
        abort_if(
            ! auth()->user()->can('delete-academic-data'),
            403,
            'AKSES DITOLAK: Anda memegang peran Staf dengan akses terbatas. Hak hapus data hanya diberikan secara eksklusif kepada Admin!'
        );

        $nama = $lecturer->nama;
        $lecturer->delete();

        return redirect()->route('lecturers.index')
            ->with('success', "Data Dosen {$nama} berhasil dihapus permanen oleh Administrator.");
    }
}
