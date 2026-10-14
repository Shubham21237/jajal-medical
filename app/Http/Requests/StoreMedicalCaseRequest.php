<?php

namespace App\Http\Requests;

use App\Models\MedicalCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMedicalCaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'case_number' => [
                'required',
                'string',
                'max:64',
                Rule::unique('medical_cases', 'case_number'),
            ],
            'patient_code' => ['required', 'string', 'max:64'],
            'surgeon_name' => ['required', 'string', 'max:255'],
            'implant_type' => ['required', 'string', 'max:255'],
            'status' => [
                'required',
                'string',
                Rule::in(MedicalCase::STATUSES),
            ],
            'priority' => [
                'required',
                'string',
                Rule::in(MedicalCase::PRIORITIES),
            ],
            'surgery_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'case_number.required' => 'The Case Number is mandatory.',
            'case_number.unique' => 'This Case Number has already been registered in the system. Case numbers must be unique.',
            'patient_code.required' => 'Patient Reference Code is required (use dummy code format e.g. PT-2025-XXXX).',
            'surgeon_name.required' => 'The operating Surgeon Name is required.',
            'implant_type.required' => 'The Implant Type specification is required.',
            'status.required' => 'A valid case status must be selected.',
            'status.in' => 'Selected status is invalid. Permitted values: Draft, Planning, In Review, Approved, Completed, Cancelled.',
            'priority.required' => 'A priority level must be selected.',
            'priority.in' => 'Selected priority is invalid. Permitted values: Low, Medium, High.',
            'surgery_date.date' => 'The surgery date must be a valid calendar date format (YYYY-MM-DD).',
        ];
    }
}
