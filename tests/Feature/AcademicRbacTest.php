<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\AcademicSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicRbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->seed(AcademicSeeder::class);
    }

    public function test_quick_login_works_for_admin_and_redirects_to_dashboard(): void
    {
        $response = $this->get(route('quick.login', 'admin'));

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->hasRole('Admin'));
    }

    public function test_quick_login_works_for_staf_and_redirects_to_dashboard(): void
    {
        $response = $this->get(route('quick.login', 'staf'));

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->hasRole('Staf'));
    }

    public function test_dashboard_renders_aggregate_widgets(): void
    {
        $admin = User::where('email', 'admin@akademik.its.ac.id')->first();

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Total Mahasiswa');
        $response->assertSee('Total Dosen Pengajar');
        $response->assertSee('Mata Kuliah Ditawarkan');
        $response->assertSee('Pengguna Terdaftar');
    }

    public function test_student_search_filter_with_when_query(): void
    {
        $admin = User::where('email', 'admin@akademik.its.ac.id')->first();
        $student = Student::first();

        $response = $this->actingAs($admin)->get(route('students.index', ['search' => $student->nim]));

        $response->assertStatus(200);
        $response->assertSee($student->nama);
    }

    public function test_student_filter_by_jurusan_and_status(): void
    {
        $admin = User::where('email', 'admin@akademik.its.ac.id')->first();

        $response = $this->actingAs($admin)->get(route('students.index', [
            'jurusan' => 'Teknik Informatika',
            'status' => 'Aktif',
        ]));

        $response->assertStatus(200);
    }

    public function test_course_search_filter_with_when_query(): void
    {
        $admin = User::where('email', 'admin@akademik.its.ac.id')->first();
        $course = Course::first();

        $response = $this->actingAs($admin)->get(route('courses.index', ['search' => $course->kode_mk]));

        $response->assertStatus(200);
        $response->assertSee($course->nama_mk);
    }

    public function test_admin_can_delete_student_successfully(): void
    {
        $admin = User::where('email', 'admin@akademik.its.ac.id')->first();
        $student = Student::first();

        $response = $this->actingAs($admin)->delete(route('students.destroy', $student));

        $response->assertRedirect(route('students.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    public function test_staf_is_forbidden_from_deleting_student(): void
    {
        $staf = User::where('email', 'staf@akademik.its.ac.id')->first();
        $student = Student::first();

        $response = $this->actingAs($staf)->delete(route('students.destroy', $student));

        $response->assertStatus(403);
        $this->assertDatabaseHas('students', ['id' => $student->id]);
    }

    public function test_admin_can_delete_lecturer_and_course(): void
    {
        $admin = User::where('email', 'admin@akademik.its.ac.id')->first();
        $lecturer = Lecturer::first();
        $course = Course::first();

        $responseLecturer = $this->actingAs($admin)->delete(route('lecturers.destroy', $lecturer));
        $responseLecturer->assertRedirect(route('lecturers.index'));
        $this->assertDatabaseMissing('lecturers', ['id' => $lecturer->id]);

        $responseCourse = $this->actingAs($admin)->delete(route('courses.destroy', $course));
        $responseCourse->assertRedirect(route('courses.index'));
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    public function test_staf_is_forbidden_from_deleting_lecturer_and_course(): void
    {
        $staf = User::where('email', 'staf@akademik.its.ac.id')->first();
        $lecturer = Lecturer::first();
        $course = Course::first();

        $responseLecturer = $this->actingAs($staf)->delete(route('lecturers.destroy', $lecturer));
        $responseLecturer->assertStatus(403);
        $this->assertDatabaseHas('lecturers', ['id' => $lecturer->id]);

        $responseCourse = $this->actingAs($staf)->delete(route('courses.destroy', $course));
        $responseCourse->assertStatus(403);
        $this->assertDatabaseHas('courses', ['id' => $course->id]);
    }

    public function test_staf_is_forbidden_from_accessing_user_management(): void
    {
        $staf = User::where('email', 'staf@akademik.its.ac.id')->first();

        $response = $this->actingAs($staf)->get(route('users.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_access_user_management(): void
    {
        $admin = User::where('email', 'admin@akademik.its.ac.id')->first();

        $response = $this->actingAs($admin)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna');
    }

    public function test_admin_can_update_user_role(): void
    {
        $admin = User::where('email', 'admin@akademik.its.ac.id')->first();
        $staf = User::where('email', 'staf@akademik.its.ac.id')->first();

        $response = $this->actingAs($admin)->put(route('users.updateRole', $staf), [
            'role' => 'Admin',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertTrue($staf->fresh()->hasRole('Admin'));
    }
}
