<?php

namespace App\Http\Middleware;

use App\Models\LogLoginAttempt;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogLoginAttempts
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $intentos = $request->session()->get('intentos', 0);
//        dd($request->all());
        $intentos++;
        $user = User::where('email', $request->input('email'))->first();
//        dd($user);

        Log::info( '#' . $intentos . ' Intento de inicio de sesión', [
            'id' => $user?->id,
            'usuario' => $request->input('email'), // O cualquier campo que identifique al usuario
            'nombre' => $user?->name,
            'ip' => $request->ip(),
            'fecha_hora' => now(),
        ]);

        $request->session()->put('intentos', $intentos);

        LogLoginAttempt::create([
            'ip' => $request->ip(),
            'email_attempt' => $request->input('email'),
            'user_id' => $user?->id,
        ]);
        return $next($request);
    }
}
