<?php

declare(strict_types=1);

namespace App\Core\Enums;

use Codestage\Authorization\Contracts\IPermissionEnum;

enum Permission: string implements IPermissionEnum
{
    case InstitutionsCreate = 'institutions.create';
    case InstitutionDelete = 'institutions.delete';
    case StudentGroupCreate = 'student_groups.create';
    case StudentGroupUpdate = 'student_groups.update';
    case StudentGroupDelete = 'student_groups.delete';
}
