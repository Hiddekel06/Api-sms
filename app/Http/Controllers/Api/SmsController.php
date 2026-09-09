<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendBulkSmsRequest;
use App\Http\Requests\SendSmsRequest;
use App\Services\YasSmsService;
use Illuminate\Http\JsonResponse;

class SmsController extends Controller
{
    public function __construct(
        protected YasSmsService $smsService
    ) {}

    /**
     * Send an SMS message.
     */
    public function send(SendSmsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = $this->smsService->sendSms(
            to: $validated['to'],
            text: $validated['text'],
            from: $validated['from'] ?? null
        );

        $httpStatus = (isset($result['status']) && is_int($result['status']) && $result['status'] >= 200 && $result['status'] <= 599)
            ? $result['status']
            : ($result['success'] ? 200 : 500);

        return response()->json($result, $httpStatus);
    }

    /**
     * Send bulk SMS messages.
     */
    public function sendBulk(SendBulkSmsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = $this->smsService->sendBulkSms(
            recipients: $validated['recipients'],
            text: $validated['text'],
            from: $validated['from'] ?? null
        );

        $httpStatus = (isset($result['status']) && is_int($result['status']) && $result['status'] >= 200 && $result['status'] <= 599)
            ? $result['status']
            : ($result['success'] ? 200 : 500);

        return response()->json($result, $httpStatus);
    }

    /**
     * Check SMS delivery logs / status.
     */
    public function status(string $messageId): JsonResponse
    {
        $result = $this->smsService->getLogs($messageId);

        $httpStatus = (isset($result['status']) && is_int($result['status']) && $result['status'] >= 200 && $result['status'] <= 599)
            ? $result['status']
            : ($result['success'] ? 200 : 500);

        return response()->json($result, $httpStatus);
    }
}
