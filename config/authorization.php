<?php

declare(strict_types=1);

use App\Core\Enums\Permission;

return [

    /*
    |--------------------------------------------------------------------------
    | Permissions Enum
    |--------------------------------------------------------------------------
    |
    | The enum which contains this application's permissions.
    | MUST implement Codestage\Authorization\Contracts\IPermissionEnum.
    |
    */
    'permissions_enum' => Permission::class,
];
