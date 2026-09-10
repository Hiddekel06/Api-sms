<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SmsApiTest extends TestCase
{
    private string $validToken = 'mfp_live_token_CHANGEZ_CE_TOKEN_ICI';

    // ---------------------------------------------------------------------------
    // Authentification
    // ---------------------------------------------------------------------------

    public function test_unauthenticated_request_returns_401(): void
    {
        $response = $this->postJson('/api/sms/send', [
            'to' => '221774517228',
            'text' => 'Test',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'status' => 401,
            ]);
    }

    public function test_request_with_invalid_token_returns_401(): void
    {
        $response = $this->withToken('invalid_token_xyz')
            ->postJson('/api/sms/send', [
                'to' => '221774517228',
                'text' => 'Test',
            ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'status' => 401,
            ]);
    }

    // ---------------------------------------------------------------------------
    // Envoi simple
    // ---------------------------------------------------------------------------

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

        $response = $this->withToken($this->validToken)
            ->postJson('/api/sms/send', [
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
        $response = $this->withToken($this->validToken)
            ->postJson('/api/sms/send', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['to', 'text']);
    }

    // ---------------------------------------------------------------------------
    // Statut
    // ---------------------------------------------------------------------------

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

        $response = $this->withToken($this->validToken)
            ->getJson('/api/sms/status/test-message-id-123');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 200,
            ]);
    }

    // ---------------------------------------------------------------------------
    // Envoi en masse
    // ---------------------------------------------------------------------------

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

        $response = $this->withToken($this->validToken)
            ->postJson('/api/sms/send-bulk', [
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

    // ---------------------------------------------------------------------------
    // Frontend / Dashboard
    // ---------------------------------------------------------------------------

    public function test_home_page_redirects_to_dashboard(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('dashboard'));
    }

    public function test_unauthenticated_user_cannot_access_dashboard(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }
}
