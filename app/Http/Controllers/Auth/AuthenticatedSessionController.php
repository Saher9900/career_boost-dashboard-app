<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

        return redirect()->to($this->landingUrl($request));
    }

    /**
     * Resolve where to send the user after a successful login.
     *
     * The session's intended URL is honoured only when the user's role may
     * reach it; otherwise the user lands on their role's home instead of being
     * bounced into a 403.
     */
    private function landingUrl(Request $request): string
    {
        $fallback = $request->user()->role === 'company_owner'
            ? route('my-company.show', absolute: false)
            : route('dashboard', absolute: false);

        $intended = $request->session()->pull('url.intended');

        if (! is_string($intended) || $intended === '') {
            return $fallback;
        }

        $path = parse_url($intended, PHP_URL_PATH) ?: '/';

        try {
            $route = Route::getRoutes()->match(Request::create($path, 'GET'));
        } catch (HttpException) {
            return $fallback;
        }

        foreach ($route->gatherMiddleware() as $middleware) {
            if (! is_string($middleware) || ! str_starts_with($middleware, 'access_rules:')) {
                continue;
            }

            $allowedRoles = explode(',', substr($middleware, strlen('access_rules:')));

            if (! in_array($request->user()->role, $allowedRoles, true)) {
                return $fallback;
            }
        }

        return $intended;
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
