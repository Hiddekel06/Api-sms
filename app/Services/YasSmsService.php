<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YasSmsService
{
    protected string $baseUrl;

    protected string $username;

    protected string $password;

    protected string $defaultFrom;

    protected bool $verifySsl;

    public function __construct()
    {
        $this->baseUrl = (string) config('services.yas_sms.base_url', 'https://api.yasbusiness.sn');
        $this->username = (string) config('services.yas_sms.username', '');
        $this->password = (string) config('services.yas_sms.password', '');
        $this->defaultFrom = (string) config('services.yas_sms.default_from', 'E-fPublique');
        $this->verifySsl = (bool) config('services.yas_sms.verify_ssl', false);
    }

    /**
     * Get a configured HTTP client with Basic Auth and headers.
     */
    protected function client(): PendingRequest
    {
        $client = Http::baseUrl($this->baseUrl)
            ->withBasicAuth($this->username, $this->password)
            ->acceptJson()
            ->asJson()
            ->timeout(15);

        if (! $this->verifySsl) {
            $client->withoutVerifying();
        }

        return $client;
    }

    /**
     * Send a single SMS.
     *
     * @param  string  $to  Recipient phone number (e.g., 221774517228)
     * @param  string  $text  SMS text body
     * @param  string|null  $from  Sender ID (optional, defaults to config)
     * @return array<string, mixed>
     */
    public function sendSms(string $to, string $text, ?string $from = null): array
    {
        $sender = $from ?: $this->defaultFrom;

        $payload = [
            'from' => $sender,
            'to' => $to,
            'text' => $text,
        ];

        try {
            $response = $this->client()->post('/sms/1/text/single', $payload);

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $response->json() ?? $response->body(),
            ];
        } catch (\Throwable $e) {
            Log::error('Yas SMS send error: '.$e->getMessage(), [
                'to' => $to,
                'exception' => $e,
            ]);

            return [
                'success' => false,
                'status' => 500,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send bulk SMS messages to multiple recipients.
     *
     * @param  array<int, string>  $recipients
     * @return array<string, mixed>
     */
    public function sendBulkSms(array $recipients, string $text, ?string $from = null): array
    {
        // Clean and deduplicate recipient list
        $cleanRecipients = array_values(array_unique(array_filter(array_map(function ($num) {
            return trim(preg_replace('/[\s\-]/', '', (string) $num));
        }, $recipients))));

        if (empty($cleanRecipients)) {
            return [
                'success' => false,
                'status' => 422,
                'error' => 'Aucun destinataire valide fourni.',
                'summary' => [
                    'total' => 0,
                    'sent' => 0,
                    'failed' => 0,
                ],
                'results' => [],
            ];
        }

        $results = [];
        $sentCount = 0;
        $failedCount = 0;

        foreach ($cleanRecipients as $to) {
            $res = $this->sendSms($to, $text, $from);
            $results[] = [
                'to' => $to,
                'success' => $res['success'],
                'status' => $res['status'],
                'data' => $res['data'] ?? null,
                'error' => $res['error'] ?? null,
            ];

            if ($res['success']) {
                $sentCount++;
            } else {
                $failedCount++;
            }
        }

        return [
            'success' => $failedCount === 0,
            'status' => 200,
            'summary' => [
                'total' => count($cleanRecipients),
                'sent' => $sentCount,
                'failed' => $failedCount,
            ],
            'results' => $results,
        ];
    }

    /**
     * Get SMS status / logs by message ID.
     *
     * @return array<string, mixed>
     */
    public function getLogs(string $messageId): array
    {
        try {
            $response = $this->client()->get('/sms/3/logs', [
                'messageId' => $messageId,
            ]);

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $response->json() ?? $response->body(),
            ];
        } catch (\Throwable $e) {
            Log::error('Yas SMS status error: '.$e->getMessage(), [
                'messageId' => $messageId,
                'exception' => $e,
            ]);

            return [
                'success' => false,
                'status' => 500,
                'error' => $e->getMessage(),
            ];
        }
    }
}
