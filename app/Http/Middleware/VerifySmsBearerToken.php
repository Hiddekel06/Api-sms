<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
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

        if (empty($bearerToken)) {
            return response()->json([
                'success' => false,
                'status' => 401,
                'error' => 'Non autorisé. Le jeton d\'autorisation (Bearer Token) est manquant.',
            ], 401);
        }

        // Vérification en base de données (tokens gérés via le dashboard)
        $dbToken = ApiToken::active()
            ->get()
            ->first(fn (ApiToken $t) => hash_equals($t->token, $bearerToken));

        if ($dbToken) {
            $dbToken->update(['last_used_at' => now()]);
            $request->attributes->set('api_token', $dbToken);

            return $next($request);
        }

        // Fallback : tokens statiques du .env
        $envTokens = config('services.yas_sms.bearer_tokens', []);
        $isValid = collect($envTokens)->contains(
            fn (string $t) => hash_equals($t, $bearerToken)
        );

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
