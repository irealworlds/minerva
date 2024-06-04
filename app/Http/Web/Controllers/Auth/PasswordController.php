<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Auth;

use App\Core\Models\Identity;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\Requests\Auth\PasswordUpdateRequest;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Spatie\RouteAttributes\Attributes\Put;
use Throwable;

final readonly class PasswordController extends Controller
{
    public function __construct(
        private Redirector $_redirector,
        private Hasher $_hasher,
        private AuthManager $_authManager,
    ) {
    }

    /**
     * Update the user's password.
     *
     * @throws Throwable
     */
    #[Put('/Password', name: 'password.update')]
    #[Authorize]
    public function update(PasswordUpdateRequest $request): RedirectResponse
    {
        /** @var Identity $user */
        $user = $this->_authManager->guard()->user();
        $user->password = $this->_hasher->make($request->password);
        $user->saveOrFail();

        return $this->_redirector->back();
    }
}
