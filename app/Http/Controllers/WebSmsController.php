<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendBulkSmsRequest;
use App\Http\Requests\SendSmsRequest;
use App\Models\SmsLog;
use App\Services\YasSmsService;
use Illuminate\Http\JsonResponse;

class WebSmsController extends Controller
{
    public function __construct(protected YasSmsService $smsService) {}

    public function send(SendSmsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = $this->smsService->sendSms(
            to: $validated['to'],
            text: $validated['text'],
            from: $validated['from'] ?? null
        );

        SmsLog::create([
            'api_token_id' => null,
            'source' => 'web',
            'type' => 'single',
            'recipient' => $validated['to'],
            'recipient_count' => 1,
            'message' => $validated['text'],
            'success' => $result['success'] ?? false,
            'message_id' => $result['message_id'] ?? null,
            'raw_response' => $result,
        ]);

        $httpStatus = (isset($result['status']) && is_int($result['status']) && $result['status'] >= 200 && $result['status'] <= 599)
            ? $result['status']
            : ($result['success'] ? 200 : 500);

        return response()->json($result, $httpStatus);
    }

    public function sendBulk(SendBulkSmsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = $this->smsService->sendBulkSms(
            recipients: $validated['recipients'],
            text: $validated['text'],
            from: $validated['from'] ?? null
        );

        SmsLog::create([
            'api_token_id' => null,
            'source' => 'web',
            'type' => 'bulk',
            'recipient' => null,
            'recipient_count' => count($validated['recipients']),
            'message' => $validated['text'],
            'success' => $result['success'] ?? false,
            'message_id' => null,
            'raw_response' => $result,
        ]);

        $httpStatus = (isset($result['status']) && is_int($result['status']) && $result['status'] >= 200 && $result['status'] <= 599)
            ? $result['status']
            : ($result['success'] ? 200 : 500);

        return response()->json($result, $httpStatus);
    }

    public function status(string $messageId): JsonResponse
    {
        $result = $this->smsService->getLogs($messageId);

        $httpStatus = (isset($result['status']) && is_int($result['status']) && $result['status'] >= 200 && $result['status'] <= 599)
            ? $result['status']
            : ($result['success'] ? 200 : 500);

        return response()->json($result, $httpStatus);
    }
}
