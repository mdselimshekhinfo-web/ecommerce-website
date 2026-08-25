<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Services\AutoPilotSalesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LiveChatController extends Controller
{
    /**
     * Initialize or fetch current customer session
     */
    public function initSession(Request $request)
    {
        $sessionId = $request->session()->get('live_chat_session_id');

        if (!$sessionId || !ChatSession::where('session_id', $sessionId)->exists()) {
            $sessionId = 'SES-' . strtoupper(Str::random(10));
            $request->session()->put('live_chat_session_id', $sessionId);

            $session = ChatSession::create([
                'session_id' => $sessionId,
                'user_id' => Auth::id(),
                'customer_name' => Auth::check() ? Auth::user()->name : 'Guest Visitor',
                'customer_phone' => Auth::check() ? Auth::user()->phone : null,
                'current_page' => $request->input('current_page', url()->previous()),
                'cart_summary' => $request->input('cart_summary', []),
                'is_assigned_to_human' => false,
                'status' => 'auto_pilot',
                'last_activity_at' => now(),
            ]);

            // Welcome greeting
            ChatMessage::create([
                'session_id' => $sessionId,
                'sender_type' => 'ai',
                'sender_name' => 'Aura AI',
                'message' => "👋 হ্যালো! আমি Aura AI, আপনার শপিং ও সাপোর্ট অ্যাসিস্ট্যান্ট। যেকোনো প্রোডাক্ট, ডেলিভারি চার্জ, কুপন বা সরাসরি অর্ডারের জন্য মেসেজ দিন!",
                'message_type' => 'text',
            ]);
        } else {
            $session = ChatSession::where('session_id', $sessionId)->first();
            $session->update([
                'current_page' => $request->input('current_page', $session->current_page),
                'cart_summary' => $request->input('cart_summary', $session->cart_summary),
                'last_activity_at' => now(),
            ]);
        }

        $messages = $session->messages()->get();

        return response()->json([
            'success' => true,
            'session_id' => $sessionId,
            'is_assigned_to_human' => $session->is_assigned_to_human,
            'messages' => $messages,
        ]);
    }

    /**
     * Send customer message
     */
    public function sendMessage(Request $request)
    {
        $sessionId = $request->session()->get('live_chat_session_id');
        if (!$sessionId) {
            return response()->json(['error' => 'Session expired'], 400);
        }

        $session = ChatSession::where('session_id', $sessionId)->firstOrFail();
        $messageText = trim($request->input('message', ''));

        if (empty($messageText)) {
            return response()->json(['error' => 'Empty message'], 422);
        }

        // Store Customer message
        $userMsg = ChatMessage::create([
            'session_id' => $sessionId,
            'sender_type' => 'customer',
            'sender_name' => $session->customer_name ?: 'Customer',
            'message' => $messageText,
            'message_type' => 'text',
            'is_read' => false,
        ]);

        $session->update(['last_activity_at' => now()]);

        // If not assigned to human agent, process with AI Auto-Pilot Service
        if (!$session->is_assigned_to_human) {
            $aiResponse = AutoPilotSalesService::processCustomerMessage($session, $messageText);

            $botMsg = ChatMessage::create([
                'session_id' => $sessionId,
                'sender_type' => 'ai',
                'sender_name' => 'Aura AI',
                'message' => $aiResponse['reply'],
                'message_type' => $aiResponse['type'] ?? 'text',
                'payload' => $aiResponse['payload'] ?? null,
                'is_read' => true,
            ]);

            return response()->json([
                'success' => true,
                'user_message' => $userMsg,
                'reply' => $botMsg,
                'chips' => $aiResponse['chips'] ?? null,
                'is_assigned_to_human' => $session->is_assigned_to_human,
            ]);
        }

        return response()->json([
            'success' => true,
            'user_message' => $userMsg,
            'reply' => null,
            'is_assigned_to_human' => true,
        ]);
    }

    /**
     * Poll latest messages
     */
    public function pollMessages(Request $request)
    {
        $sessionId = $request->session()->get('live_chat_session_id');
        if (!$sessionId) {
            return response()->json(['messages' => []]);
        }

        $session = ChatSession::where('session_id', $sessionId)->first();
        if (!$session) {
            return response()->json(['messages' => []]);
        }

        $lastId = (int) $request->input('last_id', 0);
        $messages = ChatMessage::where('session_id', $sessionId)
            ->where('id', '>', $lastId)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'is_assigned_to_human' => $session->is_assigned_to_human,
            'messages' => $messages,
        ]);
    }

    /**
     * Transfer to human agent
     */
    public function requestHumanAgent(Request $request)
    {
        $sessionId = $request->session()->get('live_chat_session_id');
        if (!$sessionId) {
            return response()->json(['error' => 'No session'], 400);
        }

        $session = ChatSession::where('session_id', $sessionId)->firstOrFail();
        $aiResponse = AutoPilotSalesService::processCustomerMessage($session, 'প্রতিনিধির সাথে কথা বলতে চাই');

        $botMsg = ChatMessage::create([
            'session_id' => $sessionId,
            'sender_type' => 'ai',
            'sender_name' => 'Aura AI',
            'message' => $aiResponse['reply'],
            'message_type' => $aiResponse['type'] ?? 'text',
            'payload' => $aiResponse['payload'] ?? null,
            'is_read' => true,
        ]);

        return response()->json([
            'success' => true,
            'is_assigned_to_human' => $session->is_assigned_to_human,
            'reply' => $botMsg,
        ]);
    }
}
