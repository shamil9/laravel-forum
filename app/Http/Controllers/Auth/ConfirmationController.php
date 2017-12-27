<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\User;

class ConfirmationController extends Controller
{
    /**
     * Confirm user account
     *
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm($token)
    {
        try {
            User::where('confirmation_token', $token)
                ->firstOrFail()
                ->update(['confirmed' => true, 'token' => null]);
        } catch (\Exception $e) {
            return redirect(route('all.threads.index'))
                ->with('flash', 'Invalid Token');
        }

        return redirect(route('all.threads.index'))
            ->with('flash', 'Your account is now confirmed');
    }
}
