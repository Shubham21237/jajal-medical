<?php

namespace App\Http\Requests;

use App\Models\MedicalCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMedicalCaseRequest extends FormRequest
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
        // Retrieve case ID from route parameter for unique check exclusion
        $caseId = $this->route('case') instanceof MedicalCase
            ? $this->route('case')->id
            : $this->route('case') ?? $this->route('id');

        return [
            'case_number' => [
                'required',
                'string',
                'max:64',
                Rule::unique('medical_cases', 'case_number')->ignore($caseId),
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
            'case_number.unique' => 'Another medical case is already using this Case Number.',
            'patient_code.required' => 'Patient Reference Code is required.',
            'surgeon_name.required' => 'Surgeon Name cannot be left blank.',
            'implant_type.required' => 'Implant Type is required.',
            'status.required' => 'A valid case status must be selected.',
            'status.in' => 'Selected status is invalid.',
            'priority.required' => 'A priority level must be selected.',
            'priority.in' => 'Selected priority is invalid.',
            'surgery_date.date' => 'The surgery date must be a valid calendar date.',
        ];
    }
}
