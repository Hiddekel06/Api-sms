<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use App\Models\SmsLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SmsLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = SmsLog::with('apiToken')->latest();

        // Filtre par token / client
        if ($request->filled('token_id')) {
            $query->where('api_token_id', $request->integer('token_id'));
        }

        // Filtre par source (web ou api)
        if ($request->filled('source')) {
            $query->where('source', $request->string('source'));
        }

        // Filtre par statut (succès ou échec)
        if ($request->filled('status')) {
            $query->where('success', $request->boolean('status'));
        }

        // Recherche par destinataire ou contenu de message
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search): void {
                $q->where('recipient', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(20)->withQueryString();
        $tokens = ApiToken::orderBy('name')->get();

        $stats = [
            'total' => SmsLog::count(),
            'api' => SmsLog::where('source', 'api')->count(),
            'web' => SmsLog::where('source', 'web')->count(),
            'success' => SmsLog::where('success', true)->count(),
            'failed' => SmsLog::where('success', false)->count(),
        ];

        return view('logs.index', compact('logs', 'tokens', 'stats'));
    }
}
