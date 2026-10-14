# Requirements Traceability Matrix: Jajal Medical Case Management System

## Overview
This document maps business, functional, and security requirements to their exact architectural implementation, database constraints, controller logic, policy authorization, and automated test cases.

---

## Traceability Matrix

| Req ID | Requirement Description | Architecture / Implementation | Security & Validation | Automated Verification Test | Status |
|---|---|---|---|---|---|
| **REQ-01** | **Role-Based Access Control (RBAC):** Admin users can view, create, edit, update status, and delete cases. Viewer users can only view cases and details. Action buttons must be hidden in UI, and protected on the backend using Policies/Gates. | • `app/Policies/MedicalCasePolicy.php`<br>• `app/Http/Controllers/MedicalCaseController.php`<br>• Blade `@can('create', App\Models\MedicalCase::class)` directive | • Policy methods `viewAny`, `view`, `create`, `update`, `delete`<br>• Returns HTTP 403 Forbidden for unauthorized requests<br>• Backend checks via `$this->authorize('delete', $case)` | `tests/Feature/MedicalCaseTest.php`:<br>`test_viewer_cannot_delete_case()`<br>`test_guest_cannot_access_protected_pages()` | **VERIFIED** |
| **REQ-02** | **Unique Case Number & Clinical Validation:** Every medical case requires a globally unique case number, required dummy patient code, surgeon name, valid implant type, constrained status and priority, and valid surgery date. Uniqueness must hold during creation and updates. | • `app/Http/Requests/StoreMedicalCaseRequest.php`<br>• `app/Http/Requests/UpdateMedicalCaseRequest.php`<br>• Database unique constraint on `medical_cases.case_number` | • Laravel Rule: `'case_number' => ['required', 'string', Rule::unique('medical_cases')->ignore($this->case)]`<br>• In enum validation: `Rule::in(['Draft','Planning','In Review','Approved','Completed','Cancelled'])`<br>• Returns HTTP 422 with readable field error messages | `tests/Feature/MedicalCaseTest.php`:<br>`test_admin_can_create_case()`<br>`test_duplicate_case_number_is_rejected()` | **VERIFIED** |
| **REQ-03** | **Sanctum REST API with Equal Authorization:** Provide API endpoints (`/api/login`, `/api/logout`, `/api/cases`, `/api/cases/{id}`) secured with Laravel Sanctum tokens, returning standardized JSON with consistent HTTP status codes (200, 201, 401, 403, 404, 422). | • `routes/api.php`<br>• `app/Http/Controllers/Api/AuthController.php`<br>• `app/Http/Controllers/Api/MedicalCaseController.php`<br>• Laravel Sanctum token guard `auth:sanctum` | • Plaintext Bearer token generation via `$user->createToken('api-token')->plainTextToken`<br>• Sanctum revocation via `$user->currentAccessToken()->delete()`<br>• Controller policy checks `$this->authorize(...)` returning JSON 403 | `tests/Feature/MedicalCaseTest.php`:<br>`test_api_endpoints_enforce_sanctum_and_roles()`<br>`tests/Feature/AuthTest.php`:<br>`test_api_login_returns_token()` | **VERIFIED** |
| **REQ-04** | **Dashboard Metrics & Case Pagination:** Case listing must paginate at 10 items per page, support case number or surgeon search, filter by status, and show dashboard counts for Total, Planning, In Review, Approved, Completed. | • `app/Http/Controllers/MedicalCaseController.php@index`<br>• Eloquent `->when(...)` scopes<br>• `MedicalCase::paginate(10)->withQueryString()`<br>• `resources/views/dashboard.blade.php`<br>• `resources/views/cases/index.blade.php` | • Query injection safe using Eloquent parameter binding<br>• Responsive Blade layout with clean medical blue styling and empty states | `tests/Feature/MedicalCaseTest.php`:<br>`test_dashboard_displays_correct_metrics()`<br>`test_case_list_pagination_and_filters()` | **VERIFIED** |
| **REQ-05** | **Dummy Clinical Data Compliance:** Protect patient privacy. All generated seeders, factories, and demo records must use synthetic patient codes (e.g. `PT-2025-XXXX`) and zero real protected health information (PHI). | • `database/factories/MedicalCaseFactory.php`<br>• `database/seeders/MedicalCaseSeeder.php`<br>• Seed 2 demo users (Admin & Viewer) and 20+ realistic dummy medical cases | • Automated regex assertion on `patient_code` matches `^PT-[0-9]{4,6}$`<br>• No PII fields in database schema | `tests/Feature/MedicalCaseTest.php`:<br>`test_seeder_populates_valid_dummy_cases()` | **VERIFIED** |

---

## Verification Summary Table

| Requirement | Test Method | Expected Result | Actual Result |
|---|---|---|---|
| REQ-01 RBAC | `test_viewer_cannot_delete_case` | HTTP 403 Forbidden | PASS |
| REQ-02 Validation | `test_duplicate_case_number_is_rejected` | HTTP 422 Unprocessable Entity | PASS |
| REQ-03 API Auth | `test_api_login_returns_token` | HTTP 200 + Bearer token | PASS |
| REQ-04 Metrics | `test_dashboard_displays_correct_metrics` | Total & Status counts match DB | PASS |
| REQ-05 Seeders | `test_seeder_populates_valid_dummy_cases` | >= 20 cases with valid patient codes | PASS |
