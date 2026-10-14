<?php

namespace Database\Factories;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalCase>
 */
class MedicalCaseFactory extends Factory
{
    protected $model = MedicalCase::class;

    protected static int $counter = 100;

    public function definition(): array
    {
        static::$counter++;

        $implantTypes = [
            'Titanium Cranial Mesh 3D',
            'Ceramic Hip Prosthesis - Dual Mobility',
            'Thoracolumbar Spinal Fusion Cage',
            'Cobalt-Chrome Bicondylar Knee Joint',
            'PEEK Patient-Specific Mandibular Plate',
            'Bioresorbable Vascular Scaffold',
            'Custom Orbital Floor Mesh Implant',
            'Anterior Cervical Interbody Spacer',
            'Trabecular Metal Acetabular Cup',
            'Glenoid Anatomical Shoulder Component'
        ];

        $surgeons = [
            'Dr. Sarah Jenkins, MD',
            'Dr. Marcus Vance, FACS',
            'Dr. Alistair Sterling, MD',
            'Dr. Emily Zhao, MD',
            'Dr. Nathanial Croft, FRCS',
            'Dr. Priya Patel, MD',
            'Dr. Carlos Mendoza, FACS'
        ];

        return [
            'case_number' => sprintf('CAS-2025-%04d', static::$counter),
            'patient_code' => sprintf('PT-%05d', fake()->numberBetween(10000, 99999)),
            'surgeon_name' => fake()->randomElement($surgeons),
            'implant_type' => fake()->randomElement($implantTypes),
            'status' => fake()->randomElement(MedicalCase::STATUSES),
            'priority' => fake()->randomElement(MedicalCase::PRIORITIES),
            'surgery_date' => fake()->optional(0.8)->dateTimeBetween('now', '+60 days')?->format('Y-m-d'),
            'notes' => fake()->optional(0.6)->sentence(10),
            'created_by' => User::factory(),
        ];
    }
}
