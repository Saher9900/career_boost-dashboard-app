<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyProfile
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $routeName = $request->route()?->getName();
        $routesWithoutCompany = [
            'dashboard',
            'job-applications.index',
            'my-job-vacancies.index',
        ];

        if (
            $user->role === 'company_owner'
            && ! $user->companies()->exists()
            && ! in_array($routeName, $routesWithoutCompany, true)
        ) {
            return redirect()
                ->route('my-company.show')
                ->with('warning', 'Set up your company profile before continuing.');
        }

        return $next($request);
    }
}
