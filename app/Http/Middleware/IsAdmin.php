<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use App\Models\Modules;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->role === 'subadmin')) {
            $routeName = $request->route()->getName();
            $role = Auth::user()->role;

            if ($role == 'subadmin') {
                $modules = Auth::user()->modules()->pluck('module_name')->toArray();

                //Log::debug($modules);
                //Log::debug($routeName);

                // Fetch all module names and their route patterns from the database
                $modulePatterns = Modules::pluck('route_pattern', 'module_name')->toArray();

                // Check permissions for the current route
                if (!$this->checkModulePermissions($routeName, $modules, $modulePatterns)) {
                    return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                }
            }

            return $next($request);
        } else {
            return redirect()->route('login')->with('error', 'You have not right access. Login and try again.');
        }
    }

    /**
     * Check if the user has permissions for the requested module.
     *
     * @param string $routeName
     * @param array $userModules
     * @param array $modulePatterns
     * @return bool
     */
    private function checkModulePermissions($routeName, $userModules, $modulePatterns)
    {
        // Iterate through each module pattern and check if it matches the route name
        foreach ($modulePatterns as $module => $pattern) {
            if (fnmatch($pattern, $routeName) && !in_array($module, $userModules)) {
                return false;
            }
        }

        return true;
    }
}
