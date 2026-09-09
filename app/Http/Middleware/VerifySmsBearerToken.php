<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifySmsBearerToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bearerToken = $request->bearerToken();
        $allowedTokens = config('services.yas_sms.bearer_tokens', []);

        if (empty($bearerToken) || empty($allowedTokens)) {
            return response()->json([
                'success' => false,
                'status' => 401,
                'error' => 'Non autorisé. Le jeton d\'autorisation (Bearer Token) est manquant ou non configuré.',
            ], 401);
        }

        $isValid = false;
        foreach ($allowedTokens as $validToken) {
            if (hash_equals((string) $validToken, (string) $bearerToken)) {
                $isValid = true;
                break;
            }
        }

        if (! $isValid) {
            return response()->json([
                'success' => false,
                'status' => 401,
                'error' => 'Non autorisé. Le jeton d\'autorisation (Bearer Token) fourni est invalide.',
            ], 401);
        }

        return $next($request);
    }
}
