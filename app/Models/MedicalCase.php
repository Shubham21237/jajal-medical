<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalCase extends Model
{
    use HasFactory;

    /**
     * Permitted statuses
     */
    public const STATUSES = [
        'Draft',
        'Planning',
        'In Review',
        'Approved',
        'Completed',
        'Cancelled',
    ];

    /**
     * Permitted priorities
     */
    public const PRIORITIES = [
        'Low',
        'Medium',
        'High',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'medical_cases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'surgery_date' => 'date:Y-m-d',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: The user who created this case.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to search by case number or surgeon name.
     */
    public function scopeSearch($query, ?string $term)
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('case_number', 'like', "%{$term}%")
              ->orWhere('surgeon_name', 'like', "%{$term}%")
              ->orWhere('patient_code', 'like', "%{$term}%")
              ->orWhere('implant_type', 'like', "%{$term}%");
        });
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeFilterStatus($query, ?string $status)
    {
        if (!empty($status) && in_array($status, self::STATUSES)) {
            return $query->where('status', $status);
        }

        return $query;
    }
}
