<?php

namespace Database\Factories;

use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lecturer>
 */
class LecturerFactory extends Factory
{
    protected $model = Lecturer::class;

    private static int $sequence = 2000;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID');

        $departemenList = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Sains Data',
            'Teknik Komputer',
            'Teknologi Informasi',
        ];

        $gelarDepan = $this->faker->randomElement(['Dr. ', 'Prof. Dr. ', 'Ir. ', '']);
        $gelarBelakang = $this->faker->randomElement([', S.Kom., M.Kom.', ', M.T., Ph.D.', ', S.T., M.Sc.', ', M.Kom.', ', Ph.D.']);
        $namaLengkap = $gelarDepan.$faker->name().$gelarBelakang;

        self::$sequence++;

        return [
            'nidn' => '00'.str_pad((string) self::$sequence, 8, '0', STR_PAD_LEFT),
            'nama' => $namaLengkap,
            'email' => 'dos'.self::$sequence.'_'.$this->faker->unique()->numberBetween(100, 99999).'@lecturer.its.ac.id',
            'departemen' => $this->faker->randomElement($departemenList),
            'jabatan_akademik' => $this->faker->randomElement(['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar']),
            'status' => $this->faker->randomElement(['Aktif', 'Aktif', 'Aktif', 'Studi Lanjut', 'Cuti']),
        ];
    }
}
