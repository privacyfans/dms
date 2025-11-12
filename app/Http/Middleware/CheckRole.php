<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah role adalah 'spv' atau 'team_verifikator'

        if (Session('role')=='spv3' ||
            Session('role')=='spv4' ||
            Session('role')=='spv5' ||
            Session('role')=='team_verifikator_lvl1' ||
            Session('role')=='team_verifikator_lvl2') {
            return $next($request);
        }

        return redirect()->route('index')->with('error', 'Anda tidak memiliki akses');

    }
}

?>