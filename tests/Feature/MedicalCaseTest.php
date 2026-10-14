<?php

namespace Tests\Feature;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicalCaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Admin can create a medical case.
     */
    public function test_admin_can_create_a_medical_case(): void
    {
        $admin = User::factory()->admin()->create();

        $caseData = [
            'case_number' => 'CAS-2025-9001',
            'patient_code' => 'PT-99001',
            'surgeon_name' => 'Dr. Marcus Vance, FACS',
            'implant_type' => 'Titanium Cranial Reconstruction Mesh',
            'status' => 'Planning',
            'priority' => 'High',
            'surgery_date' => '2025-11-20',
            'notes' => 'Synthetic patient data for test verification.',
        ];

        $response = $this->actingAs($admin)->post('/cases', $caseData);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('medical_cases', [
            'case_number' => 'CAS-2025-9001',
            'patient_code' => 'PT-99001',
            'surgeon_name' => 'Dr. Marcus Vance, FACS',
            'created_by' => $admin->id,
        ]);
    }

    /**
     * Test: Duplicate case number is rejected by validation.
     */
    public function test_duplicate_case_number_is_rejected(): void
    {
        $admin = User::factory()->admin()->create();

        // Create first case
        MedicalCase::factory()->create([
            'case_number' => 'CAS-UNIQUE-001',
            'created_by' => $admin->id,
        ]);

        // Attempt to create second case with same number
        $duplicateData = [
            'case_number' => 'CAS-UNIQUE-001',
            'patient_code' => 'PT-99002',
            'surgeon_name' => 'Dr. Sarah Jenkins, MD',
            'implant_type' => 'Ceramic Hip Prosthesis',
            'status' => 'Draft',
            'priority' => 'Medium',
        ];

        $response = $this->actingAs($admin)->post('/cases', $duplicateData);

        $response->assertSessionHasErrors('case_number');
        $this->assertDatabaseCount('medical_cases', 1);
    }

    /**
     * Test: Viewer cannot delete a medical case (Policy authorization check).
     */
    public function test_viewer_cannot_delete_a_case(): void
    {
        $admin = User::factory()->admin()->create();
        $viewer = User::factory()->viewer()->create();

        $case = MedicalCase::factory()->create([
            'case_number' => 'CAS-NODELETE-01',
            'created_by' => $admin->id,
        ]);

        // Acting as viewer, attempt delete
        $response = $this->actingAs($viewer)->delete("/cases/{$case->id}");

        // Must return 403 Forbidden
        $response->assertForbidden();

        // The record must still exist in the database
        $this->assertDatabaseHas('medical_cases', [
            'id' => $case->id,
        ]);
    }

    /**
     * Test: Viewer cannot create a case.
     */
    public function test_viewer_cannot_create_a_case(): void
    {
        $viewer = User::factory()->viewer()->create();

        $caseData = [
            'case_number' => 'CAS-VIEWER-999',
            'patient_code' => 'PT-88888',
            'surgeon_name' => 'Dr. Emily Zhao, MD',
            'implant_type' => 'Spinal Fusion Cage',
            'status' => 'Draft',
            'priority' => 'Low',
        ];

        $response = $this->actingAs($viewer)->post('/cases', $caseData);

        $response->assertForbidden();
        $this->assertDatabaseMissing('medical_cases', [
            'case_number' => 'CAS-VIEWER-999',
        ]);
    }

    /**
     * Test: API Sanctum endpoints enforce role authorization.
     */
    public function test_api_viewer_cannot_delete_case_via_sanctum(): void
    {
        $admin = User::factory()->admin()->create();
        $viewer = User::factory()->viewer()->create();

        $case = MedicalCase::factory()->create([
            'case_number' => 'CAS-API-DEL-01',
            'created_by' => $admin->id,
        ]);

        // Create Sanctum token for viewer
        $token = $viewer->createToken('viewer-test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/cases/{$case->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('medical_cases', ['id' => $case->id]);
    }
}
