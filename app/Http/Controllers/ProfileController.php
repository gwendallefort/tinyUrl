<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        return view('profile');
    }

    public function updateInformation(Request $request, UpdateUserProfileInformation $updater): RedirectResponse
    {
        $user = $request->user();
        $updater->update($user, $request->only('email'));
        $user->refresh();

        if (filled($user->pending_email)) {
            return back()->with('status', 'email-change-pending');
        }

        return back()->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request, UpdateUserPassword $updater): RedirectResponse
    {
        $updater->update($request->user(), $request->only('current_password', 'password', 'password_confirmation'));

        return back()->with('status', 'password-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('deleteAccount', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
