<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Public\Institutions;

use Exception;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ReadInstitutionController
{
    /**
     * @throws Exception
     */
    #[
        Get(
            '/Public/Institutions/{institution}',
            name: 'public.institutions.show',
        ),
    ]
    public function __invoke(): never
    {
        throw new Exception('Not implemented'); // TODO
    }
}
