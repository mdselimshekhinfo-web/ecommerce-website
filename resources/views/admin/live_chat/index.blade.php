@extends('layouts.admin')

@section('page-title', \App\Helpers\LocalizationHelper::get('admin_live_chat'))

@section('content')
<div class="space-y-6" x-data="adminLiveChat()">

    <!-- Top Command & Mode Control Bar -->
    <div class="admin-glass rounded-3xl p-6 border border-cyan-500/30 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div class="flex items-center space-x-3.5">
            <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 text-cyan-400 border border-cyan-400/30 flex items-center justify-center shadow-md">
                <i data-lucide="bot" class="w-6 h-6 animate-pulse"></i>
            </div>
            <div>
                <h2 class="font-cyber font-bold text-lg text-white flex items-center gap-2">
                    <span>{{ \App\Helpers\LocalizationHelper::get('admin_live_chat') }}</span>
                    <span class="px-2 py-0.5 rounded-md bg-cyan-500/20 text-cyan-300 text-[10px] font-mono font-bold">LIVE SUPPORT DESK</span>
                </h2>
                <p class="text-xs text-slate-400 font-mono mt-0.5">{{ \App\Helpers\LocalizationHelper::get('admin_live_chat_sub') }}</p>
            </div>
        </div>

        <!-- Controls: Auto-Pilot Switch & Agent Live Toggle -->
        <div class="flex flex-wrap items-center gap-3">
            
            <!-- 1. AI Auto-Pilot Mode Toggle -->
            <form action="{{ route('admin.live_chat.toggle_autopilot') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 rounded-xl text-xs font-mono font-bold flex items-center space-x-2 transition-all shadow-sm {{ $autoPilotStatus === '1' ? 'bg-gradient-to-r from-cyan-500/20 to-indigo-500/20 text-cyan-300 border border-cyan-400/40 hover:bg-cyan-500/30' : 'bg-slate-900 text-slate-500 border border-slate-700 hover:text-slate-300' }}"
                        title="Toggle Automatic AI Sales and Order Booking">
                    <i data-lucide="sparkles" class="w-4 h-4 {{ $autoPilotStatus === '1' ? 'text-cyan-400 animate-spin-slow' : 'text-slate-500' }}"></i>
                    <span>{{ $autoPilotStatus === '1' ? '🚀 AI Auto-Pilot: ON' : '🛑 AI Auto-Pilot: OFF' }}</span>
                </button>
            </form>

            <!-- 2. Support Agent Online / Offline Toggle -->
            <form action="{{ route('admin.live_chat.toggle_agent_status') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 rounded-xl text-xs font-mono font-bold flex items-center space-x-2 transition-all shadow-sm {{ $agentStatus === 'online' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/40 hover:bg-emerald-500/30' : 'bg-pink-500/20 text-pink-300 border border-pink-400/40 hover:bg-pink-500/30' }}">
                    <span class="w-2 h-2 rounded-full {{ $agentStatus === 'online' ? 'bg-emerald-400 animate-ping' : 'bg-pink-400' }}"></span>
                    <span>{{ $agentStatus === 'online' ? '🟢 Agents Online' : '🌙 Agents Away / Offline' }}</span>
                </button>
            </form>

        </div>
    </div>

    <!-- Main Support Desk Console (Split Layout) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-[650px]">
        
        <!-- Left: Conversations List (4 Cols) -->
        <div class="lg:col-span-4 admin-glass rounded-3xl p-4 border border-slate-800 flex flex-col h-full overflow-hidden">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <span class="font-cyber font-bold text-xs text-white uppercase tracking-wider">Active Visitors & Chats</span>
                <span class="text-[10px] font-mono text-cyan-400 font-bold" x-text="sessions.length + ' Active'"></span>
            </div>

            <!-- Sessions List -->
            <div class="flex-1 overflow-y-auto divide-y divide-slate-800/60 my-2 pr-1 space-y-1">
                <template x-for="s in sessions" :key="s.session_id">
                    <button @click="selectSession(s.session_id)" 
                            :class="activeSessionId === s.session_id ? 'bg-cyan-500/15 border-cyan-500/40 text-white' : 'hover:bg-slate-900/80 border-transparent text-slate-300'"
                            class="w-full text-left p-3 rounded-2xl border transition-all flex items-start space-x-3 group">
                        
                        <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-700 flex items-center justify-center font-bold text-cyan-400 shrink-0 text-xs">
                            <span x-text="s.customer_name ? s.customer_name.charAt(0).toUpperCase() : '?'"></span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-bold truncate text-white" x-text="s.customer_name || 'Guest Visitor'"></h4>
                                <span class="text-[9px] font-mono text-slate-500" x-text="formatTime(s.last_activity_at)"></span>
                            </div>
                            
                            <p class="text-[10px] text-slate-400 truncate mt-0.5" x-text="s.customer_phone || (s.current_page ? s.current_page.replace('http://127.0.0.1:8000', '') : 'Storefront')"></p>
                            
                            <div class="flex items-center gap-1.5 mt-1.5">
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-mono font-bold"
                                      :class="s.is_assigned_to_human ? 'bg-emerald-500/20 text-emerald-300' : 'bg-cyan-500/20 text-cyan-300'"
                                      x-text="s.is_assigned_to_human ? '👨‍💼 Agent Assigned' : '🤖 AI Auto-Pilot'">
                                </span>
                            </div>
                        </div>
                    </button>
                </template>

                <div x-show="sessions.length === 0" class="text-center py-16 text-slate-500 text-xs font-mono">
                    No active chat sessions right now.
                </div>
            </div>
        </div>

        <!-- Center & Right: Live Chat Window & Visitor Insight (8 Cols) -->
        <div class="lg:col-span-8 admin-glass rounded-3xl border border-slate-800 flex flex-col h-full overflow-hidden"
             x-show="activeSessionId">
            
            <!-- Chat Top Bar -->
            <div class="p-4 border-b border-slate-800 bg-slate-950/60 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold text-xs">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-white flex items-center gap-2">
                            <span x-text="currentSession.customer_name || 'Guest Visitor'"></span>
                            <span class="text-[10px] font-mono text-slate-500 font-normal" x-text="'(' + activeSessionId + ')'"></span>
                        </h3>
                        <p class="text-[10px] text-emerald-400 font-mono flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span x-text="currentSession.customer_phone ? '📱 ' + currentSession.customer_phone : '🌐 Online on Store'"></span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold uppercase"
                          :class="currentSession.is_assigned_to_human ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30'"
                          x-text="currentSession.is_assigned_to_human ? 'Live Agent Mode' : 'AI Auto-Pilot Handling'">
                    </span>
                </div>
            </div>

            <!-- Messages Stream Area -->
            <div class="flex-1 overflow-y-auto p-5 space-y-4 text-xs font-mono" id="adminChatStream">
                <template x-for="m in messages" :key="m.id">
                    <div :class="m.sender_type === 'agent' ? 'flex justify-end' : (m.sender_type === 'ai' ? 'flex justify-start' : 'flex justify-start')">
                        
                        <div class="max-w-[80%] space-y-1">
                            <!-- Sender Tag -->
                            <div class="text-[9px] font-bold flex items-center gap-1"
                                 :class="m.sender_type === 'agent' ? 'justify-end text-emerald-400' : (m.sender_type === 'ai' ? 'text-cyan-400' : 'text-slate-400')">
                                <span x-text="m.sender_type === 'agent' ? '👨‍💼 Support Agent (You)' : (m.sender_type === 'ai' ? '🤖 AI Auto-Pilot Sales Agent' : '👤 ' + m.sender_name)"></span>
                                <span class="text-slate-600" x-text="formatTime(m.created_at)"></span>
                            </div>

                            <!-- Bubble Message Box -->
                            <div class="p-3.5 rounded-2xl leading-relaxed whitespace-pre-line text-xs shadow-md border"
                                 :class="m.sender_type === 'agent' ? 'bg-gradient-to-r from-emerald-600 to-teal-700 text-white border-emerald-500/40 rounded-tr-none' : (m.sender_type === 'ai' ? 'bg-slate-900 border-cyan-500/30 text-cyan-100 rounded-tl-none' : 'bg-slate-950 border-slate-700 text-slate-200 rounded-tl-none')">
                                <span x-html="formatMessage(m.message)"></span>
                            </div>

                            <!-- Special Order Confirmation Receipt Payload Card -->
                            <template x-if="m.message_type === 'order_receipt' && m.payload">
                                <div class="p-3 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-300 text-xs space-y-1 shadow-lg">
                                    <div class="font-bold flex items-center justify-between">
                                        <span>✓ AI Order Created</span>
                                        <span class="font-mono" x-text="'#' + m.payload.order_number"></span>
                                    </div>
                                    <p class="text-[11px] text-slate-300" x-text="m.payload.product_name"></p>
                                    <p class="text-[10px] text-slate-400" x-text="'Bill: ৳' + Number(m.payload.total_amount).toLocaleString()"></p>
                                </div>
                            </template>
                        </div>

                    </div>
                </template>
            </div>

            <!-- Agent Reply Input Box -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/80">
                <form @submit.prevent="sendAgentMessage()" class="flex items-center space-x-2">
                    <input type="text" x-model="agentInput" placeholder="Type reply as Support Agent (takes over from AI)..."
                           class="flex-1 bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400">
                    
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 text-slate-950 font-bold text-xs uppercase tracking-wider flex items-center space-x-1.5 shadow-lg hover:scale-105 transition-all">
                        <span>Send</span>
                        <i data-lucide="send" class="w-3.5 h-3.5"></i>
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function adminLiveChat() {
        return {
            sessions: @json($sessions),
            activeSessionId: '{{ $activeSession?->session_id }}',
            currentSession: {},
            messages: [],
            agentInput: '',

            init() {
                if (this.activeSessionId) {
                    this.selectSession(this.activeSessionId);
                }
                setInterval(() => {
                    if (this.activeSessionId) {
                        this.fetchMessages();
                    }
                }, 4000);
            },

            selectSession(id) {
                this.activeSessionId = id;
                this.currentSession = this.sessions.find(s => s.session_id === id) || {};
                this.fetchMessages();
            },

            fetchMessages() {
                if (!this.activeSessionId) return;
                fetch(`/admin/live-chat/${this.activeSessionId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.messages = data.messages;
                            this.currentSession = data.session;
                            this.scrollBottom();
                        }
                    });
            },

            sendAgentMessage() {
                const text = this.agentInput.trim();
                if (!text || !this.activeSessionId) return;

                fetch(`/admin/live-chat/${this.activeSessionId}/send`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message: text })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.messages.push(data.message);
                        this.agentInput = '';
                        this.scrollBottom();
                    }
                });
            },

            formatTime(timestamp) {
                if (!timestamp) return '';
                const d = new Date(timestamp);
                return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            },

            formatMessage(text) {
                if (!text) return '';
                return text.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');
            },

            scrollBottom() {
                this.$nextTick(() => {
                    const el = document.getElementById('adminChatStream');
                    if (el) el.scrollTop = el.scrollHeight;
                });
            }
        }
    }
</script>
@endpush
