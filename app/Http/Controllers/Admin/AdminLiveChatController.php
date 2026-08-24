<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Models\ThemeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLiveChatController extends Controller
{
    /**
     * Display the Admin Live Support Desk & Auto-Pilot Hub
     */
    public function index()
    {
        $sessions = ChatSession::with(['latestMessage', 'user'])
            ->orderBy('last_activity_at', 'desc')
            ->take(50)
            ->get();

        $activeSession = $sessions->first();
        $autoPilotStatus = ThemeSetting::get('ai_autopilot_mode', '1');
        $agentStatus = ThemeSetting::get('agent_online_status', 'online');

        return view('admin.live_chat.index', compact('sessions', 'activeSession', 'autoPilotStatus', 'agentStatus'));
    }

    /**
     * Fetch messages and visitor details for a session
     */
    public function show(string $sessionId)
    {
        $session = ChatSession::where('session_id', $sessionId)->with('user')->firstOrFail();
        $messages = $session->messages()->get();

        // Mark unread customer messages as read
        ChatMessage::where('session_id', $sessionId)
            ->where('sender_type', 'customer')
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'session' => $session,
            'messages' => $messages,
        ]);
    }

    /**
     * Send agent message
     */
    public function sendAgentMessage(Request $request, string $sessionId)
    {
        $session = ChatSession::where('session_id', $sessionId)->firstOrFail();
        $text = trim($request->input('message', ''));

        if (empty($text)) {
            return response()->json(['error' => 'Message is required'], 422);
        }

        $agentMsg = ChatMessage::create([
            'session_id' => $sessionId,
            'sender_type' => 'agent',
            'sender_name' => Auth::user()->name ?: 'Support Agent',
            'message' => $text,
            'message_type' => 'text',
            'is_read' => true,
        ]);

        $session->update([
            'is_assigned_to_human' => true,
            'agent_id' => Auth::id(),
            'status' => 'active',
            'last_activity_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $agentMsg,
        ]);
    }

    /**
     * Toggle AI Auto-Pilot Mode (ON/OFF)
     */
    public function toggleAutoPilot()
    {
        $current = ThemeSetting::get('ai_autopilot_mode', '1');
        $next = ($current === '1') ? '0' : '1';
        ThemeSetting::set('ai_autopilot_mode', $next);

        return back()->with('success', $next === '1' ? 'AI Auto-Pilot Sales Mode Enabled 🚀' : 'AI Auto-Pilot Sales Mode Disabled (Manual Only) 🛑');
    }

    /**
     * Toggle Human Agent Status (Online / Offline)
     */
    public function toggleAgentStatus()
    {
        $current = ThemeSetting::get('agent_online_status', 'online');
        $next = ($current === 'online') ? 'offline' : 'online';
        ThemeSetting::set('agent_online_status', $next);

        return back()->with('success', $next === 'online' ? 'Support Agents are now ONLINE 🟢' : 'Support Agents marked OFFLINE (Auto-Pilot Active) 🌙');
    }

    /**
     * Close or archive a session
     */
    public function closeSession(string $sessionId)
    {
        $session = ChatSession::where('session_id', $sessionId)->firstOrFail();
        $session->update(['status' => 'closed']);

        return response()->json(['success' => true]);
    }
}
