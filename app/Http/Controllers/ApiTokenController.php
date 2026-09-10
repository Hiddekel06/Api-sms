<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApiTokenController extends Controller
{
    public function index(): View
    {
        $tokens = ApiToken::latest()->get();

        return view('tokens.index', compact('tokens'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $rawToken = bin2hex(random_bytes(32));

        ApiToken::create([
            'name' => $request->string('name'),
            'token' => $rawToken,
        ]);

        return redirect()->route('tokens.index')
            ->with('generated_token', $rawToken)
            ->with('success', 'Token créé avec succès.');
    }

    public function revoke(ApiToken $apiToken): RedirectResponse
    {
        $apiToken->update(['revoked_at' => now()]);

        return redirect()->route('tokens.index')
            ->with('success', "Le token « {$apiToken->name} » a été révoqué.");
    }

    public function destroy(ApiToken $apiToken): RedirectResponse
    {
        $apiToken->delete();

        return redirect()->route('tokens.index')
            ->with('success', "Le token « {$apiToken->name} » a été supprimé.");
    }
}
