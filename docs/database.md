# Database Documentation: Jajal Medical Case Management System

## Overview
The Jajal Medical Case Management System utilizes a relational database (MySQL 8.x / MariaDB or SQLite for testing) to track specialized surgical cases, implant requisitions, patient reference codes, and multi-role user access.

---

## 1. Entity Relationship Diagram (ERD)

```
+------------------------------------+          +------------------------------------+
|               users                |          |           medical_cases            |
+------------------------------------+          +------------------------------------+
| id             : BIGINT (PK, AI)   | 1      N | id             : BIGINT (PK, AI)   |
| name           : VARCHAR(255)      | --------<| case_number    : VARCHAR(64) UNIQUE|
| email          : VARCHAR(255) UNIQUE|          | patient_code   : VARCHAR(64)       |
| password       : VARCHAR(255)      |          | surgeon_name   : VARCHAR(255)      |
| role           : ENUM(admin,viewer)|          | implant_type   : VARCHAR(255)      |
| remember_token : VARCHAR(100)      |          | status         : ENUM(...)         |
| created_at     : TIMESTAMP         |          | priority       : ENUM(Low,Med,High)|
| updated_at     : TIMESTAMP         |          | surgery_date   : DATE NULLABLE     |
+------------------------------------+          | created_by     : BIGINT (FK)       |
                                                | created_at     : TIMESTAMP         |
                                                | updated_at     : TIMESTAMP         |
                                                +------------------------------------+

+------------------------------------+
|      personal_access_tokens        | (Laravel Sanctum)
+------------------------------------+
| id             : BIGINT (PK, AI)   |
| tokenable_type : VARCHAR(255)      |
| tokenable_id   : BIGINT            |
| name           : VARCHAR(255)      |
| token          : VARCHAR(64) UNIQUE|
| abilities      : TEXT NULLABLE     |
| last_used_at   : TIMESTAMP NULLABLE|
| expires_at     : TIMESTAMP NULLABLE|
| created_at     : TIMESTAMP         |
| updated_at     : TIMESTAMP         |
+------------------------------------+
```

---

## 2. Table Specifications

### Table: `users`
Stores user identities and access control roles (`admin` or `viewer`).

| Column | Data Type | Modifiers | Description |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier for each user |
| `name` | VARCHAR(255) | NOT NULL | User's full display name |
| `email` | VARCHAR(255) | NOT NULL, UNIQUE | User login email address |
| `email_verified_at` | TIMESTAMP | NULLABLE | Email verification timestamp |
| `password` | VARCHAR(255) | NOT NULL | Bcrypt hashed password |
| `role` | VARCHAR(20) | NOT NULL, DEFAULT 'viewer' | User authorization role: `'admin'` or `'viewer'` |
| `remember_token` | VARCHAR(100) | NULLABLE | Session persistent cookie token |
| `created_at` | TIMESTAMP | NULLABLE | Record creation timestamp |
| `updated_at` | TIMESTAMP | NULLABLE | Record last update timestamp |

**Indexes:**
- `PRIMARY KEY (id)`
- `UNIQUE KEY users_email_unique (email)`

---

### Table: `medical_cases`
Stores patient reference codes, implant requirements, clinical statuses, and assigned surgeon details.

| Column | Data Type | Modifiers | Description |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Auto-generated system case identifier |
| `case_number` | VARCHAR(64) | NOT NULL, UNIQUE | Human-readable unique case number (e.g., `CAS-2025-001`) |
| `patient_code` | VARCHAR(64) | NOT NULL | De-identified dummy patient code (e.g., `PT-98214`) |
| `surgeon_name` | VARCHAR(255) | NOT NULL | Name of the primary orthopedic / neurosurgeon |
| `implant_type` | VARCHAR(255) | NOT NULL | Surgical implant model / design specification |
| `status` | ENUM | NOT NULL, DEFAULT 'Draft' | Allowed values: `Draft`, `Planning`, `In Review`, `Approved`, `Completed`, `Cancelled` |
| `priority` | ENUM | NOT NULL, DEFAULT 'Medium' | Allowed values: `Low`, `Medium`, `High` |
| `surgery_date` | DATE | NULLABLE | Scheduled surgery date (optional) |
| `notes` | TEXT | NULLABLE | Optional clinical case preparation notes |
| `created_by` | BIGINT UNSIGNED | NOT NULL | Foreign key referencing `users.id` |
| `created_at` | TIMESTAMP | NULLABLE | Case creation timestamp |
| `updated_at` | TIMESTAMP | NULLABLE | Case modification timestamp |

**Indexes & Constraints:**
- `PRIMARY KEY (id)`
- `UNIQUE KEY medical_cases_case_number_unique (case_number)`
- `INDEX medical_cases_status_index (status)`
- `INDEX medical_cases_surgeon_name_index (surgeon_name)`
- `CONSTRAINT fk_medical_cases_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE`

---

### Table: `personal_access_tokens`
Provided by **Laravel Sanctum** for token-based REST API authentication.

| Column | Data Type | Modifiers | Description |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique token identifier |
| `tokenable_type` | VARCHAR(255) | NOT NULL | Polymorphic model class (`App\Models\User`) |
| `tokenable_id` | BIGINT UNSIGNED | NOT NULL | Associated user ID |
| `name` | VARCHAR(255) | NOT NULL | Token name / purpose (e.g., `api-token`) |
| `token` | VARCHAR(64) | NOT NULL, UNIQUE | SHA-256 hashed token string |
| `abilities` | TEXT | NULLABLE | Token ability scopes (e.g., `["*"]`) |
| `last_used_at` | TIMESTAMP | NULLABLE | Timestamp when API token was last invoked |
| `expires_at` | TIMESTAMP | NULLABLE | Token expiration date |
| `created_at` | TIMESTAMP | NULLABLE | Token issue timestamp |
| `updated_at` | TIMESTAMP | NULLABLE | Token update timestamp |

---

## 3. Eloquent Model Relationships

### `App\Models\User`
```php
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    public function medicalCases()
    {
        return $this->hasMany(MedicalCase::class, 'created_by');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isViewer(): bool
    {
        return $this->role === 'viewer';
    }
}
```

### `App\Models\MedicalCase`
```php
class MedicalCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_number',
        'patient_code',
        'surgeon_name',
        'implant_type',
        'status',
        'priority',
        'surgery_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'surgery_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
```

---

## 4. Security & Compliance
- **De-identified Data:** Only synthetic patient reference codes (e.g. `PT-2025-1042`) are stored. No Protected Health Information (PHI) like real patient names, social security numbers, or addresses are stored.
- **Foreign Key Integrity:** Cascading deletes ensure orphan records are prevented.
- **Unique Indexes:** High-speed lookup and collision prevention on `case_number` at both database schema and Form Request validation levels.
