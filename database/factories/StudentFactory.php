<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    private static int $sequence = 1000;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID');

        $jurusanList = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Sains Data',
            'Teknik Komputer',
            'Rekayasa Perangkat Lunak',
            'Teknologi Informasi',
        ];

        $gender = $this->faker->randomElement(['L', 'P']);
        $genderFaker = ($gender === 'L') ? 'male' : 'female';
        $nama = $faker->name($genderFaker);
        $angkatan = $this->faker->randomElement(['2021', '2022', '2023', '2024', '2025']);
        $kodeProdi = match ($this->faker->randomElement($jurusanList)) {
            'Teknik Informatika' => '5025',
            'Sistem Informasi' => '5026',
            'Sains Data' => '5027',
            'Teknik Komputer' => '5024',
            'Rekayasa Perangkat Lunak' => '5028',
            default => '5029',
        };

        self::$sequence++;
        $nim = $kodeProdi.substr($angkatan, -2).'10'.str_pad((string) self::$sequence, 4, '0', STR_PAD_LEFT);

        return [
            'nim' => $nim,
            'nama' => $nama,
            'email' => 'mhs'.self::$sequence.'_'.$this->faker->unique()->numberBetween(100, 99999).'@student.its.ac.id',
            'jurusan' => $this->faker->randomElement($jurusanList),
            'angkatan' => $angkatan,
            'ipk' => $this->faker->randomFloat(2, 2.75, 4.00),
            'status' => $this->faker->randomElement(['Aktif', 'Aktif', 'Aktif', 'Cuti', 'Lulus']),
            'jenis_kelamin' => $gender,
            'alamat' => $faker->address(),
        ];
    }
}
