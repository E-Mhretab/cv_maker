<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\GuestCvTransferService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Transfer guest CVs to the logged-in user account
        $user = Auth::user();
        $guestCvTransferService = new GuestCvTransferService();
        $transferredCount = $guestCvTransferService->transferGuestCvsToUser($user);
        
        if ($transferredCount > 0) {
            \Log::info('Transferred guest CVs to logged-in user', [
                'user_id' => $user->id,
                'transferred_count' => $transferredCount
            ]);
        }

        // Redirect admin users to admin dashboard, others to regular dashboard
        if ($user->role === 'admin') {
            $redirect = redirect()->intended(route('admin.dashboard', absolute: false));
        } else {
            $redirect = redirect()->intended(route('dashboard', absolute: false));
        }
        
        // Add success message if CVs were transferred
        if ($transferredCount > 0) {
            $redirect->with('message', "We found {$transferredCount} CV(s) you created as a guest and transferred them to your account.");
            $redirect->with('type', 'success');
        }
        
        return $redirect;
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
