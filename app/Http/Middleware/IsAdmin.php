<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

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

                Log::debug($modules);
                Log::debug($routeName);

                if (str_contains($routeName, 'abp')) {

                    if (!in_array('abp', $modules)) {
                        return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                    }
                } else if (str_contains($routeName, 'contacts')) {
                    if (!in_array('contacts', $modules)) {
                        return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                    }
                } else if (str_contains($routeName, 'contacts-add')) {
                    if (!in_array('contacts-add', $modules)) {
                        return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                    }
                } else if (str_contains($routeName, 'contacts-list')) {
                    if (!in_array('contacts-list', $modules)) {
                        return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                    }
                } else if (str_contains($routeName, 'Deals')) {

                    if (!in_array('Deals', $modules)) {
                        return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                    }
                } else if (str_contains($routeName, 'deals-list')) {

                    if (!in_array('deals-list', $modules)) {
                        return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                    }
                } else if (str_contains($routeName, 'brokers')) {

                    if (!in_array('brokers', $modules)) {
                        return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                    }
                } else if (str_contains($routeName, 'user')) {

                    if (!in_array('users', $modules)) {
                        return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                    }
                } else if (str_contains($routeName, 'contactrole')) {

                    if (!in_array('contact-role', $modules)) {
                        return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                    }
                } else if (str_contains($routeName, 'products')) {

                    if (!in_array('products', $modules)) {
                        return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                    }
                } else if (str_contains($routeName, 'industry')) {

                    if (!in_array('industries', $modules)) {
                        return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                    }
                } else if (str_contains($routeName, 'lenders')) {

                    if (!in_array('lenders', $modules)) {
                        return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                    }
                } else if (str_contains($routeName, 'expensetype')) {

                    if (!in_array('expense-type', $modules)) {
                        return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                    }
                } else if (str_contains($routeName, 'service')) {

                    if (!in_array('services', $modules)) {
                        return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                    }
                } else if (str_contains($routeName, 'referral')) {

                    if (!in_array('referral-sources', $modules)) {
                        return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                    }
                } else if (str_contains($routeName, 'processor')) {

                    if (!in_array('processors', $modules)) {
                        return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                    }
                } else if (str_contains($routeName, 'entity')) {

                    if (!in_array('settings', $modules)) {
                        return redirect()->back()->with('error', 'You don\'t have permission to access this module');
                    }
                }
            }
            return $next($request);
        } else {
            return redirect()->route('login')->with('error', 'You have not right access. Login and try again.');
        }
    }
}
