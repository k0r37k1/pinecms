<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Installer\PostInstallCleanup;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventInstalledAccess
{
    public function __construct(
        private readonly PostInstallCleanup $cleanup
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if installation is complete
        if ($this->cleanup->isInstalled()) {
            // Redirect to admin login
            return redirect('/admin/login')->with('error', 'Installation already completed.');
        }

        return $next($request);
    }
}
