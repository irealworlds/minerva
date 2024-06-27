<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Identity;

use App\Core\Contracts\Services\ISignedUrlGenerator;
use App\Core\Models\Identity;
use App\Http\Web\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Inertia\{Response as InertiaResponse, ResponseFactory};
use InvalidArgumentException;
use RuntimeException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class CreatePasswordController extends Controller
{
    public function __construct(
        private ResponseFactory $_inertia,
        private ISignedUrlGenerator $_uriGenerator,
    ) {
    }

    /**
     * @throws AuthorizationException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    #[Get('/Identities/{identity}/Password/Create', middleware: ['signed'])]
    public function __invoke(Identity $identity): InertiaResponse
    {
        if (!empty($identity->password)) {
            throw new AuthorizationException();
        }

        return $this->_inertia->render('Identity/Password/Create', [
            'identity' => [
                'key' => $identity->getRouteKey(),
                'idNumber' => $identity->username,
                'name' => $identity->name->getFullName(),
                'emailAddress' => $identity->email,
            ],
            'actionUri' => $this->_uriGenerator->generateActionUri(
                StorePasswordController::class,
                [
                    'identity' => $identity->getRouteKey(),
                ],
            ),
        ]);
    }
}
