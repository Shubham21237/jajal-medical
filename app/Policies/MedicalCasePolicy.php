<?php

namespace App\Policies;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MedicalCasePolicy
{
    /**
     * Determine whether the user can view any medical cases.
     * Both Admin and Viewer can view cases.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'viewer']);
    }

    /**
     * Determine whether the user can view the specific medical case.
     * Both Admin and Viewer can view case details.
     */
    public function view(User $user, MedicalCase $medicalCase): bool
    {
        return in_array($user->role, ['admin', 'viewer']);
    }

    /**
     * Determine whether the user can create medical cases.
     * Only Admin can create.
     */
    public function create(User $user): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Unauthorized: Only administrators are permitted to create medical cases.');
    }

    /**
     * Determine whether the user can update the medical case.
     * Only Admin can update.
     */
    public function update(User $user, MedicalCase $medicalCase): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Unauthorized: Only administrators are permitted to edit medical cases.');
    }

    /**
     * Determine whether the user can delete the medical case.
     * Only Admin can delete.
     */
    public function delete(User $user, MedicalCase $medicalCase): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Unauthorized: Only administrators are permitted to delete medical cases.');
    }
}
