<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Session;

class DoubleSessionProtector
{
	protected function handlePage(Request $request, Closure $next) {
		if(!str_starts_with($request->route()->getName(), 'auth.protection')) {
			return redirect()
					->to(route('auth.protection.index', ['ReturnUrl' => url()->full()]), 302); 
		}
		
		return $next($request);
	}
	
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
		if($request->session()->get('bypass-block-screen', false))
			return $next($request);
		
		/* */
		
		$record = Session::where('ip_address', $request->ip());
		if($record->exists()) {
			foreach($record->get() as $session) {
				if($session->id != session()->getId())
					return $this->handlePage($request, $next);
			}
		}
		
		if(str_starts_with($request->route()->getName(), 'auth.protection')) {
			$returnUrl = $request->input('ReturnUrl');
			
			if(!$returnUrl)
				$returnUrl = route('home.landing');
			
			return redirect(route('home.landing'), 302);
		}
		
        return $next($request);
    }
}
