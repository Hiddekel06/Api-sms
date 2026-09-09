<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SmsApiTest extends TestCase
{
    public function test_send_sms_successful(): void
    {
        Http::fake([
            'https://api.yasbusiness.sn/sms/1/text/single' => Http::response([
                'messages' => [
                    [
                        'to' => '221774517228',
                        'status' => [
                            'groupId' => 1,
                            'groupName' => 'PENDING',
                            'id' => 7,
                            'name' => 'PENDING_ENROUTE',
                            'description' => 'Message sent to next instance',
                        ],
                        'messageId' => 'test-message-id-123',
                    ],
                ],
            ], 200),
        ]);

        $response = $this->postJson('/api/sms/send', [
            'to' => '221774517228',
            'text' => 'Test message',
            'from' => 'E-fPublique',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 200,
            ]);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.yasbusiness.sn/sms/1/text/single'
                && $request['to'] === '221774517228'
                && $request['text'] === 'Test message'
                && $request['from'] === 'E-fPublique';
        });
    }

    public function test_send_sms_validation_fails_without_required_fields(): void
    {
        $response = $this->postJson('/api/sms/send', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['to', 'text']);
    }

    public function test_get_sms_status_successful(): void
    {
        Http::fake([
            'https://api.yasbusiness.sn/sms/3/logs*' => Http::response([
                'results' => [
                    [
                        'messageId' => 'test-message-id-123',
                        'to' => '221774517228',
                        'status' => [
                            'name' => 'DELIVERED_TO_HANDSET',
                            'description' => 'Message delivered to handset',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->getJson('/api/sms/status/test-message-id-123');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 200,
            ]);
    }

    public function test_send_bulk_sms_successful(): void
    {
        Http::fake([
            'https://api.yasbusiness.sn/sms/1/text/single' => Http::response([
                'messages' => [
                    [
                        'status' => ['name' => 'PENDING_ENROUTE'],
                        'messageId' => 'msg-123',
                    ],
                ],
            ], 200),
        ]);

        $response = $this->postJson('/api/sms/send-bulk', [
            'recipients' => ['221774517228', '221771234567'],
            'text' => 'Bulk message test',
            'from' => 'E-fPublique',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 200,
                'summary' => [
                    'total' => 2,
                    'sent' => 2,
                    'failed' => 0,
                ],
            ]);
    }

    public function test_home_page_renders_sms_view(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('Yas SMS Gateway');
    }
}
