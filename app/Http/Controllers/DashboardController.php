<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DashboardController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:view-dashboard'),
        ];
    }

    /**
     * Render backoffice aggregate dashboard.
     */
    public function index(): View
    {
        // Widget Dashboard Agregat: Minimal 3 widget box statistik dinamis via model count()
        $totalMahasiswa = Student::count();
        $totalDosen = Lecturer::count();
        $totalMataKuliah = Course::count();
        $totalUsers = User::count();

        // Data statistik tambahan untuk mempercantik panel dashboard
        $mahasiswaAktif = Student::where('status', 'Aktif')->count();
        $mahasiswaCuti = Student::where('status', 'Cuti')->count();
        $mahasiswaLulus = Student::where('status', 'Lulus')->count();

        // Data registrasi mahasiswa terbaru
        $recentStudents = Student::latest()->take(6)->get();

        // Distribusi program studi
        $prodiStats = Student::selectRaw('jurusan, count(*) as count')
            ->groupBy('jurusan')
            ->orderByDesc('count')
            ->get();

        return view('dashboard', compact(
            'totalMahasiswa',
            'totalDosen',
            'totalMataKuliah',
            'totalUsers',
            'mahasiswaAktif',
            'mahasiswaCuti',
            'mahasiswaLulus',
            'recentStudents',
            'prodiStats'
        ));
    }
}
