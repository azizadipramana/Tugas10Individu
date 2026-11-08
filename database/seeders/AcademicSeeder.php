<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Student;
use Illuminate\Database\Seeder;

class AcademicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Data Dosen Unggulan
        $featuredLecturers = [
            [
                'nidn' => '0015087501',
                'nama' => 'Prof. Dr. Ir. Joko Siswanto, M.Sc.',
                'email' => 'joko.siswanto@lecturer.its.ac.id',
                'departemen' => 'Teknik Informatika',
                'jabatan_akademik' => 'Guru Besar',
                'status' => 'Aktif',
            ],
            [
                'nidn' => '0023118203',
                'nama' => 'Dr. Raden Mas Bagus, S.Kom., M.Kom.',
                'email' => 'raden.bagus@lecturer.its.ac.id',
                'departemen' => 'Teknik Informatika',
                'jabatan_akademik' => 'Lektor Kepala',
                'status' => 'Aktif',
            ],
            [
                'nidn' => '0012048902',
                'nama' => 'Siti Nurhaliza, S.T., M.T., Ph.D.',
                'email' => 'siti.nurhaliza@lecturer.its.ac.id',
                'departemen' => 'Sistem Informasi',
                'jabatan_akademik' => 'Lektor',
                'status' => 'Aktif',
            ],
            [
                'nidn' => '0030098605',
                'nama' => 'Ahmad Fadhil, S.Kom., M.Cs.',
                'email' => 'ahmad.fadhil@lecturer.its.ac.id',
                'departemen' => 'Sains Data',
                'jabatan_akademik' => 'Lektor',
                'status' => 'Aktif',
            ],
            [
                'nidn' => '0005069101',
                'nama' => 'Dwi Lestari, S.T., M.Eng.',
                'email' => 'dwi.lestari@lecturer.its.ac.id',
                'departemen' => 'Teknologi Informasi',
                'jabatan_akademik' => 'Asisten Ahli',
                'status' => 'Studi Lanjut',
            ],
        ];

        foreach ($featuredLecturers as $lecturer) {
            Lecturer::updateOrCreate(['nidn' => $lecturer['nidn']], $lecturer);
        }

        // Tambah 15 Dosen faker realistis
        Lecturer::factory()->count(15)->create();

        // 2. Data Mata Kuliah Unggulan
        $featuredCourses = [
            [
                'kode_mk' => 'IF184101',
                'nama_mk' => 'Pemrograman Berbasis Kerangka Kerja',
                'sks' => 3,
                'semester' => 4,
                'program_studi' => 'Teknik Informatika',
                'dosen_pengampu' => 'Dr. Raden Mas Bagus, S.Kom., M.Kom.',
            ],
            [
                'kode_mk' => 'IF184201',
                'nama_mk' => 'Algoritma dan Struktur Data',
                'sks' => 4,
                'semester' => 2,
                'program_studi' => 'Teknik Informatika',
                'dosen_pengampu' => 'Prof. Dr. Ir. Joko Siswanto, M.Sc.',
            ],
            [
                'kode_mk' => 'SI184302',
                'nama_mk' => 'Sistem Basis Data Terdistribusi',
                'sks' => 3,
                'semester' => 3,
                'program_studi' => 'Sistem Informasi',
                'dosen_pengampu' => 'Siti Nurhaliza, S.T., M.T., Ph.D.',
            ],
            [
                'kode_mk' => 'DS184501',
                'nama_mk' => 'Pembelajaran Mesin & Kecerdasan Buatan',
                'sks' => 4,
                'semester' => 5,
                'program_studi' => 'Sains Data',
                'dosen_pengampu' => 'Ahmad Fadhil, S.Kom., M.Cs.',
            ],
            [
                'kode_mk' => 'IT184403',
                'nama_mk' => 'Keamanan Siber & Jaringan Komputer',
                'sks' => 3,
                'semester' => 4,
                'program_studi' => 'Teknologi Informasi',
                'dosen_pengampu' => 'Dwi Lestari, S.T., M.Eng.',
            ],
        ];

        foreach ($featuredCourses as $course) {
            Course::updateOrCreate(['kode_mk' => $course['kode_mk']], $course);
        }

        // Tambah 15 Mata Kuliah faker realistis
        Course::factory()->count(15)->create();

        // 3. Data Mahasiswa Unggulan
        $featuredStudents = [
            [
                'nim' => '5025211001',
                'nama' => 'Muhammad Azizurrohman',
                'email' => 'aziz.5025211001@student.its.ac.id',
                'jurusan' => 'Teknik Informatika',
                'angkatan' => '2021',
                'ipk' => 3.92,
                'status' => 'Aktif',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Keputih Makam No. 12, Sukolilo, Surabaya',
            ],
            [
                'nim' => '5025211002',
                'nama' => 'Adinda Putri Maharani',
                'email' => 'adinda.putri@student.its.ac.id',
                'jurusan' => 'Teknik Informatika',
                'angkatan' => '2021',
                'ipk' => 3.88,
                'status' => 'Aktif',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Gebang Wetan No. 45, Sukolilo, Surabaya',
            ],
            [
                'nim' => '5026221015',
                'nama' => 'Bintang Alamsyah Pratama',
                'email' => 'bintang.alamsyah@student.its.ac.id',
                'jurusan' => 'Sistem Informasi',
                'angkatan' => '2022',
                'ipk' => 3.75,
                'status' => 'Aktif',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Manyar Sabrangan No. 88, Mulyorejo, Surabaya',
            ],
            [
                'nim' => '5027231020',
                'nama' => 'Cantika Dewi Safitri',
                'email' => 'cantika.dewi@student.its.ac.id',
                'jurusan' => 'Sains Data',
                'angkatan' => '2023',
                'ipk' => 3.81,
                'status' => 'Aktif',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Arief Rahman Hakim No. 10, Surabaya',
            ],
            [
                'nim' => '5024221008',
                'nama' => 'Dimas Arya Yudhistira',
                'email' => 'dimas.arya@student.its.ac.id',
                'jurusan' => 'Teknik Komputer',
                'angkatan' => '2022',
                'ipk' => 3.65,
                'status' => 'Cuti',
                'jenis_kelamin' => 'L',
                'alamat' => 'Perum ITS Blok U No. 7, Surabaya',
            ],
        ];

        foreach ($featuredStudents as $student) {
            Student::updateOrCreate(['nim' => $student['nim']], $student);
        }

        // Tambah 35 Mahasiswa faker realistis
        Student::factory()->count(35)->create();
    }
}
