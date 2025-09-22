<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Cv;
use App\Models\CvMetadata;
use App\Models\WorkExperience;
use App\Models\Education;
use App\Models\Skill;
use App\Models\Language;
use App\Models\Hobby;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CvCrudTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user using the existing User model
        $this->user = User::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password_hash' => bcrypt('password'),
            'role' => 'user',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function authenticated_user_can_create_a_cv_with_education_and_skills()
    {
        $this->actingAs($this->user);

        $cvData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone_number' => '+1234567890',
            'address' => '123 Main St, City, Country',
            'date_of_birth' => '1990-01-01',
            'linkedin_profile' => 'https://linkedin.com/in/johndoe',
            'portfolio' => 'https://johndoe.com',
            'profile_summary' => 'Experienced software developer with 5+ years of experience.',
            'template_type' => 1,
            'is_public' => true,
            
            // Education data
            'education_degree' => ['Bachelor of Computer Science', 'Master of Software Engineering'],
            'education_institution' => ['University of Technology', 'Advanced University'],
            'education_start' => ['2010-09-01', '2014-09-01'],
            'education_end' => ['2014-06-01', '2016-06-01'],
            'education_current' => [false, false],
            'education_description' => ['Studied computer science fundamentals', 'Advanced software engineering'],
            
            // Work experience data
            'work_title' => ['Software Developer', 'Senior Developer'],
            'work_company' => ['Tech Corp', 'Innovation Inc'],
            'work_start' => ['2016-07-01', '2019-01-01'],
            'work_end' => ['2018-12-31', '2021-12-31'],
            'work_current' => [false, false],
            'work_description' => ['Developed web applications', 'Led development teams'],
            
            // Skills data
            'skill_name' => ['PHP', 'JavaScript', 'Laravel'],
            'skill_description' => ['Backend development', 'Frontend development', 'Framework expertise'],
            
            // Languages data
            'language_name' => ['English', 'Spanish', 'French'],
            'language_proficiency' => ['fluent', 'conversational', 'basic'],
            
            // Hobbies data
            'hobby_name' => ['Photography', 'Hiking', 'Reading'],
            'hobby_description' => ['Nature photography', 'Mountain hiking', 'Technical books'],
        ];

        $response = $this->post(route('cvs.store'), $cvData);

        $response->assertRedirect(route('cvs.index'));
        
        // Assert CV was created
        $this->assertDatabaseHas('cv', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'user_id' => $this->user->id,
        ]);

        $cv = Cv::where('name', 'John Doe')->first();
        $this->assertNotNull($cv);

        // Assert metadata was created
        $this->assertDatabaseHas('cv_metadata', [
            'cv_id' => $cv->id,
            'template_type' => 1,
            'is_public' => true,
        ]);

        // Assert education records were created
        $this->assertDatabaseHas('education', [
            'cv_id' => $cv->id,
            'degree' => 'Bachelor of Computer Science',
            'institution' => 'University of Technology',
        ]);

        $this->assertDatabaseHas('education', [
            'cv_id' => $cv->id,
            'degree' => 'Master of Software Engineering',
            'institution' => 'Advanced University',
        ]);

        // Assert work experience records were created
        $this->assertDatabaseHas('work_experience', [
            'cv_id' => $cv->id,
            'job_title' => 'Software Developer',
            'company_name' => 'Tech Corp',
        ]);

        $this->assertDatabaseHas('work_experience', [
            'cv_id' => $cv->id,
            'job_title' => 'Senior Developer',
            'company_name' => 'Innovation Inc',
        ]);

        // Assert skills were created
        $this->assertDatabaseHas('skills', [
            'cv_id' => $cv->id,
            'skill_name' => 'PHP',
        ]);

        $this->assertDatabaseHas('skills', [
            'cv_id' => $cv->id,
            'skill_name' => 'JavaScript',
        ]);

        $this->assertDatabaseHas('skills', [
            'cv_id' => $cv->id,
            'skill_name' => 'Laravel',
        ]);

        // Assert languages were created
        $this->assertDatabaseHas('languages', [
            'cv_id' => $cv->id,
            'language_name' => 'English',
            'proficiency' => 'fluent',
        ]);

        $this->assertDatabaseHas('languages', [
            'cv_id' => $cv->id,
            'language_name' => 'Spanish',
            'proficiency' => 'conversational',
        ]);

        // Assert hobbies were created
        $this->assertDatabaseHas('hobbies', [
            'cv_id' => $cv->id,
            'hobby_name' => 'Photography',
        ]);

        $this->assertDatabaseHas('hobbies', [
            'cv_id' => $cv->id,
            'hobby_name' => 'Hiking',
        ]);
    }

    /** @test */
    public function show_cv_detail_loads_all_relationships()
    {
        $this->actingAs($this->user);

        // Create a CV with all relationships
        $cv = Cv::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test CV',
            'email' => 'test@example.com',
        ]);

        // Create metadata
        CvMetadata::factory()->create([
            'cv_id' => $cv->id,
            'template_type' => 1,
            'is_public' => true,
        ]);

        // Create work experiences
        WorkExperience::factory()->count(2)->create(['cv_id' => $cv->id]);

        // Create education records
        Education::factory()->count(2)->create(['cv_id' => $cv->id]);

        // Create skills
        Skill::factory()->count(3)->create(['cv_id' => $cv->id]);

        // Create languages
        Language::factory()->count(2)->create(['cv_id' => $cv->id]);

        // Create hobbies
        Hobby::factory()->count(3)->create(['cv_id' => $cv->id]);

        $response = $this->get(route('cvs.show', $cv));

        $response->assertStatus(200);
        $response->assertViewIs('cvs.show');
        
        // Assert the view receives the CV with all relationships
        $response->assertViewHas('cv', function ($viewCv) use ($cv) {
            return $viewCv->id === $cv->id 
                && $viewCv->workExperiences->count() === 2
                && $viewCv->education->count() === 2
                && $viewCv->skills->count() === 3
                && $viewCv->languages->count() === 2
                && $viewCv->hobbies->count() === 3
                && $viewCv->metadata !== null;
        });
    }

    /** @test */
    public function pdf_export_returns_200_and_correct_headers()
    {
        $this->actingAs($this->user);

        // Create a CV with relationships
        $cv = Cv::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test CV for PDF',
            'email' => 'test@example.com',
        ]);

        // Create metadata
        CvMetadata::factory()->create([
            'cv_id' => $cv->id,
            'template_type' => 1,
            'is_public' => true,
        ]);

        // Create some sample data
        WorkExperience::factory()->create([
            'cv_id' => $cv->id,
            'job_title' => 'Software Developer',
            'company_name' => 'Test Company',
        ]);

        Education::factory()->create([
            'cv_id' => $cv->id,
            'degree' => 'Bachelor of Computer Science',
            'institution' => 'Test University',
        ]);

        Skill::factory()->create([
            'cv_id' => $cv->id,
            'skill_name' => 'PHP',
        ]);

        $response = $this->get(route('cvs.pdf', $cv));

        $response->assertStatus(200);
        
        // Assert correct headers for PDF
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', function ($value) {
            return str_contains($value, 'attachment') && str_contains($value, '.pdf');
        });
    }

    /** @test */
    public function pdf_stream_returns_200_and_correct_headers()
    {
        $this->actingAs($this->user);

        // Create a CV with relationships
        $cv = Cv::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test CV for Stream',
            'email' => 'test@example.com',
        ]);

        // Create metadata
        CvMetadata::factory()->create([
            'cv_id' => $cv->id,
            'template_type' => 1,
            'is_public' => true,
        ]);

        $response = $this->get(route('cvs.pdf.stream', $cv));

        $response->assertStatus(200);
        
        // Assert correct headers for PDF stream
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', function ($value) {
            return str_contains($value, 'inline') && str_contains($value, '.pdf');
        });
    }

    /** @test */
    public function unauthenticated_user_cannot_access_cv_routes()
    {
        $cv = Cv::factory()->create(['user_id' => $this->user->id]);

        // Test CV index
        $this->get(route('cvs.index'))->assertRedirect(route('login'));
        
        // Test CV create
        $this->get(route('cvs.create'))->assertRedirect(route('login'));
        
        // Test CV show
        $this->get(route('cvs.show', $cv))->assertRedirect(route('login'));
        
        // Test CV edit
        $this->get(route('cvs.edit', $cv))->assertRedirect(route('login'));
        
        // Test CV PDF
        $this->get(route('cvs.pdf', $cv))->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_view_their_cvs_index()
    {
        $this->actingAs($this->user);

        // Create CVs for the user
        $cv1 = Cv::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'CV 1',
        ]);

        $cv2 = Cv::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'CV 2',
        ]);

        // Create CV for another user (should not appear)
        $otherUser = User::factory()->create();
        Cv::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'Other User CV',
        ]);

        $response = $this->get(route('cvs.index'));

        $response->assertStatus(200);
        $response->assertViewIs('cvs.index');
        
        // Assert only user's CVs are shown
        $response->assertViewHas('cvs', function ($cvs) {
            return $cvs->count() === 2 
                && $cvs->pluck('name')->contains('CV 1')
                && $cvs->pluck('name')->contains('CV 2')
                && !$cvs->pluck('name')->contains('Other User CV');
        });
    }

    /** @test */
    public function authenticated_user_can_update_their_cv()
    {
        $this->actingAs($this->user);

        $cv = Cv::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Original Name',
            'email' => 'original@example.com',
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone_number' => '+1234567890',
            'address' => 'Updated Address',
            'date_of_birth' => '1990-01-01',
            'profile_summary' => 'Updated profile summary.',
            'template_type' => 2,
            'is_public' => false,
        ];

        $response = $this->put(route('cvs.update', $cv), $updateData);

        $response->assertRedirect(route('cvs.show', $cv));
        
        // Assert CV was updated
        $this->assertDatabaseHas('cv', [
            'id' => $cv->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    /** @test */
    public function authenticated_user_can_delete_their_cv()
    {
        $this->actingAs($this->user);

        $cv = Cv::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'CV to Delete',
        ]);

        $response = $this->delete(route('cvs.destroy', $cv));

        $response->assertRedirect(route('cvs.index'));
        
        // Assert CV was deleted
        $this->assertDatabaseMissing('cv', [
            'id' => $cv->id,
        ]);
    }
}