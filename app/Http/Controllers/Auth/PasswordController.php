<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\Rules\Password;

final class PasswordController extends Controller
{
    function __construct(
        private readonly Redirector $_redirector,
        private readonly Hasher $_hasher
    ) {
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => $this->_hasher->make($validated['password']),
        ]);

        return $this->_redirector->back();
    }
}
