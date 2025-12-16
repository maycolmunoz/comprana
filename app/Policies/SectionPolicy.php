<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Section;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SectionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Section');
    }

    public function view(AuthUser $authUser, Section $section): bool
    {
        return $authUser->can('View:Section');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Section');
    }

    public function update(AuthUser $authUser, Section $section): bool
    {
        return $authUser->can('Update:Section');
    }

    public function delete(AuthUser $authUser, Section $section): bool
    {
        return $authUser->can('Delete:Section');
    }

    public function restore(AuthUser $authUser, Section $section): bool
    {
        return $authUser->can('Restore:Section');
    }

    public function forceDelete(AuthUser $authUser, Section $section): bool
    {
        return $authUser->can('ForceDelete:Section');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Section');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Section');
    }

    public function replicate(AuthUser $authUser, Section $section): bool
    {
        return $authUser->can('Replicate:Section');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Section');
    }
}
