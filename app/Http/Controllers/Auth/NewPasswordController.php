<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Core\Models\Identity;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\{
    RedirectResponse,
    Request};
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Illuminate\Validation\{
    Rules,
    ValidationException};
use Inertia\{
    Inertia,
    Response};
use RuntimeException;
use Spatie\RouteAttributes\Attributes\{
    Get,
    Post};
use function is_string;

final class NewPasswordController extends Controller
{
    public function __construct(
        private readonly Dispatcher $_eventDispatcher,
        private readonly Redirector $_redirector,
        private readonly Hasher $_hasher,
        private readonly PasswordBroker $_passwordBroker
    ) {
    }

    /**
     * Display the password reset view.
     *
     * @throws RuntimeException
     */
    #[Get('/Reset-Password/{token}', name: 'password.reset', middleware: 'guest')]
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'email' => $request->str('email'),
            'token' => $request->route('token'),
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    #[Post('/Reset-Password', name: 'password.store', middleware: 'guest')]
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = $this->_passwordBroker->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Identity $user) use ($request): void {
                $user->forceFill([
                    'password' => $this->_hasher->make($request->string('password')->toString()),
                    'remember_token' => Str::random(60),
                ])->save();

                $this->_eventDispatcher->dispatch(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status === PasswordBroker::PASSWORD_RESET) {
            return $this->_redirector->route('login')
                ->with('status', trans($status));
        }

        throw ValidationException::withMessages([
            'email' => [is_string($status) ? trans($status) : $status],
        ]);
    }
}
