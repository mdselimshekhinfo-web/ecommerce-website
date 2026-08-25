<?php

namespace App\Http\Controllers;

use App\Models\ThemeSetting;
use App\Services\WhatsAppVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    /**
     * Meta Webhook Verification (GET)
     */
    public function verifyWebhook(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        $configuredToken = ThemeSetting::get('whatsapp_webhook_token', 'nexus_wa_token_2026');

        if ($mode === 'subscribe' && $token === $configuredToken) {
            return response($challenge, 200);
        }

        return response('Forbidden', 403);
    }

    /**
     * Handle Incoming WhatsApp Message (POST)
     */
    public function handleIncoming(Request $request)
    {
        $data = $request->all();
        Log::info('Incoming WhatsApp Webhook Payload:', $data);

        try {
            $entry = $data['entry'][0]['changes'][0]['value'] ?? null;
            if ($entry && isset($entry['messages'][0])) {
                $messageObj = $entry['messages'][0];
                $senderPhone = $messageObj['from'] ?? '';
                $messageText = '';

                if (isset($messageObj['text']['body'])) {
                    $messageText = $messageObj['text']['body'];
                } elseif (isset($messageObj['button']['text'])) {
                    $messageText = $messageObj['button']['text'];
                } elseif (isset($messageObj['interactive']['button_reply']['title'])) {
                    $messageText = $messageObj['interactive']['button_reply']['title'];
                }

                if ($senderPhone && $messageText) {
                    $result = WhatsAppVerificationService::processIncomingReply($senderPhone, $messageText);
                    Log::info('WhatsApp AI Processed Result:', $result);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error processing WhatsApp webhook: ' . $e->getMessage());
        }

        return response()->json(['status' => 'EVENT_RECEIVED'], 200);
    }
}
