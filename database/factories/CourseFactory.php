<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    private static int $sequence = 500;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $courses = [
            'Pemrograman Berbasis Kerangka Kerja',
            'Algoritma dan Struktur Data',
            'Basis Data Lanjut',
            'Rekayasa Perangkat Lunak',
            'Kecerdasan Buatan dan Pembelajaran Mesin',
            'Jaringan Komputer dan Keamanan Siber',
            'Pengembangan Aplikasi Bergerak',
            'Sistem Operasi dan Cloud Computing',
            'Interaksi Manusia dan Komputer',
            'Pemrograman Berorientasi Objek',
            'Arsitektur Komputer dan Sistem Tertanam',
            'Manajemen Proyek Teknologi Informasi',
        ];

        $prodis = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Sains Data',
            'Teknik Komputer',
            'Teknologi Informasi',
        ];

        self::$sequence++;
        $codePrefix = $this->faker->randomElement(['IF', 'SI', 'DS', 'EC', 'IT']);
        $kodeMk = $codePrefix.'28'.str_pad((string) self::$sequence, 4, '0', STR_PAD_LEFT);

        return [
            'kode_mk' => $kodeMk,
            'nama_mk' => $this->faker->randomElement($courses).' '.$this->faker->randomElement(['A', 'B', 'C', 'Reguler', 'Terapan']),
            'sks' => $this->faker->randomElement([2, 3, 3, 4]),
            'semester' => $this->faker->numberBetween(1, 8),
            'program_studi' => $this->faker->randomElement($prodis),
            'dosen_pengampu' => 'Dr. '.$this->faker->name().', M.Kom.',
        ];
    }
}
