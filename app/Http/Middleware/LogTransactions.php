<?php

namespace App\Http\Middleware;

use App\Models\Club;
use App\Models\LogTransaction;
use App\Models\Team;
use App\Models\Tournament;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogTransactions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param string $resourceName Nombre del recurso
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next,string $resource): Response
    {
        // Obtener el método de la solicitud
        $method = $request->getMethod();
        $details = json_encode($request->all()); // Convertir el array a formato JSON

        if($resource == 'clubs'){
            $club = Club::where('name', $request->input('name'))->first();
            //$details = 'nombre: ' . $request->name . ', profesor: ' . $request->coach;

            // Verificar si hay usuarios relacionados y agregar su información a los detalles
         /*    if ($request->users) {
                $details .= ', users: ';
                foreach ($request->users as $user) {
                    $details .= '[id: ' . $user->id . ', nombre: ' . $user->name . ', email: ' . $user->email . '], ';
                }
            } */
            if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
                Log::info('Log de Transacciones', [
                    'id_usuario' => auth()->id(), // user authentication
                    'acción' => $method, // HTTP method used for the request
                    'recurso' => $resource, // Name of the resource being accessed
                    'id_recurso' => $club?->id, // ID of the resource, if applicable
                    'detalles' => $request->all(), // Any additional details you want to log
                ]);

              /*   LogTransaction::create([
                    'user_id' => auth()->id(), // Assuming you have user authentication
                    'action' => $method, // HTTP method used for the request
                    'resource' => $resource, // Name of the resource being accessed
                    'resource_id' => $club?->id, // ID of the resource, if applicable
                    'details' => $details, // Any additional details you want to log
                ]); */
            }
        }else if($resource == 'teams'){
            $team = Team::where('name', $request->input('name'))->first();
            if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
                Log::info('Log de Transacciones', [
                    'id_usuario' => auth()->id(), // user authentication
                    'acción' => $method, // HTTP method used for the request
                    'recurso' => $resource, // Name of the resource being accessed
                    'id_recurso' => $team?->id, // ID of the resource, if applicable
                    'detalles' => $request->all(), // Any additional details you want to log
                ]);
/*                 LogTransaction::create([
                    'user_id' => auth()->id(), // Assuming you have user authentication
                    'action' => $method, // HTTP method used for the request
                    'resource' => $resource, // Name of the resource being accessed
                    'resource_id' => $team?->id, // ID of the resource, if applicable
                    'details' => $request->all(), // Any additional details you want to log
                ]); */
            }
        }
        // Procesar la solicitud
        $response = $next($request);

        return $response;
    }
}



/*   LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => $request->getMethod(), // HTTP method used for the request
            'resource' => 'clubs', // Name of the resource being accessed
            'resource_id' => null, // ID of the resource, if applicable
            'details' => $request->all(), // Any additional details you want to log
        ]); */
        