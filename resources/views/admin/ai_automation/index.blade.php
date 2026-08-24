@extends('layouts.admin')

@section('page-title', \App\Helpers\LocalizationHelper::get('admin_ai_automation'))

@section('content')
<div class="space-y-6" x-data="aiAutomationHub()">

    <!-- Header Banner -->
    <div class="admin-glass rounded-3xl p-6 border border-purple-500/30 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div class="flex items-center space-x-3.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-purple-500/20 to-pink-500/20 text-purple-400 border border-purple-400/30 flex items-center justify-center shadow-lg">
                <i data-lucide="zap" class="w-6 h-6 animate-pulse"></i>
            </div>
            <div>
                <h2 class="font-cyber font-bold text-lg text-white flex items-center gap-2">
                    <span>{{ \App\Helpers\LocalizationHelper::get('admin_ai_automation') }}</span>
                    <span class="px-2 py-0.5 rounded-md bg-purple-500/20 text-purple-300 text-[10px] font-mono font-bold">ZERO-TOUCH DISPATCH</span>
                </h2>
                <p class="text-xs text-slate-400 font-mono mt-0.5">{{ \App\Helpers\LocalizationHelper::get('admin_ai_automation_sub') }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('sitemap') }}" target="_blank" 
               class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-700 text-xs font-mono text-cyan-300 hover:border-cyan-400 transition-all flex items-center space-x-1.5">
                <i data-lucide="globe" class="w-4 h-4"></i>
                <span>sitemap.xml</span>
            </a>
            
            <form action="{{ route('admin.ai_automation.generate_seo') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-500 via-pink-500 to-indigo-600 text-white font-bold text-xs font-mono uppercase tracking-wider flex items-center space-x-2 shadow-lg hover:scale-105 transition-all">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span>Generate AI SEO For All</span>
                </button>
            </form>
        </div>
    </div>

    <!-- 4 KPI Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-2">
            <span class="text-slate-400 text-xs font-mono">SEO Optimized Products</span>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black font-mono text-white">{{ $seoOptimizedCount }} / {{ $productsCount }}</h3>
                <span class="text-xs font-mono text-emerald-400 font-bold">100% Google Ready</span>
            </div>
            <div class="w-full bg-slate-900 h-1.5 rounded-full overflow-hidden">
                <div class="bg-gradient-to-r from-cyan-400 to-purple-500 h-full rounded-full" style="width: {{ $productsCount > 0 ? ($seoOptimizedCount / $productsCount) * 100 : 100 }}%"></div>
            </div>
        </div>

        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-2">
            <span class="text-slate-400 text-xs font-mono">WhatsApp Auto-Verified</span>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black font-mono text-emerald-400">{{ $verifiedOrdersCount }}</h3>
                <span class="text-xs font-mono text-slate-400">Zero Return Risk</span>
            </div>
            <div class="text-[10px] text-slate-500 font-mono">Instant confirmation via Meta Webhook</div>
        </div>

        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-2">
            <span class="text-slate-400 text-xs font-mono">AI Voice Agent Calls</span>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black font-mono text-purple-400">Active</h3>
                <span class="text-xs font-mono text-cyan-400">Bangla Speech AI</span>
            </div>
            <div class="text-[10px] text-slate-500 font-mono">Automated voice confirmation calls</div>
        </div>

        <div class="admin-glass p-5 rounded-2xl border border-slate-800 space-y-2">
            <span class="text-slate-400 text-xs font-mono">Auto Courier Dispatch</span>
            <div class="flex items-baseline justify-between">
                <h3 class="text-2xl font-black font-mono text-amber-400">Steadfast API</h3>
                <span class="text-xs font-mono text-emerald-400">Auto-Booked</span>
            </div>
            <div class="text-[10px] text-slate-500 font-mono">1-Click automatic consignment & tracking</div>
        </div>

    </div>

    <!-- 3 Core Interactive Modules Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Module 1: AI Auto-SEO & Google Rich Snippet Engine -->
        <div class="admin-glass rounded-3xl p-6 border border-slate-800 space-y-4 flex flex-col justify-between">
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-cyber font-bold text-sm text-white">Google Auto-SEO & Schema</h4>
                        <p class="text-[11px] text-slate-400 font-mono">JSON-LD Rich Snippet & Meta Tags</p>
                    </div>
                </div>

                <!-- Google Search Live Card Mockup -->
                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-1.5 font-sans select-none">
                    <div class="flex items-center space-x-2 text-[11px] text-slate-400">
                        <span class="text-cyan-400">https://nexusdokan.bd</span>
                        <span>› product › earbuds</span>
                    </div>
                    <h5 class="text-sm font-semibold text-blue-400 hover:underline cursor-pointer">
                        AuraBlade ANC Cyber Earbuds Pro - Buy in BD | ৳2,950
                    </h5>
                    <div class="flex items-center space-x-1 text-[11px] text-amber-400">
                        <span>★★★★★</span>
                        <span class="text-slate-400">Rating: 5.0 • ৳2,950 • In stock • 24h Delivery</span>
                    </div>
                    <p class="text-xs text-slate-300 leading-snug">
                        Buy original AuraBlade ANC Cyber Earbuds Pro in Bangladesh. Official warranty, cash on delivery & bKash available at NEXUS DOKAN.
                    </p>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-800/80">
                <form action="{{ route('admin.ai_automation.generate_seo') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-cyan-500/20 text-cyan-300 border border-cyan-400/40 hover:bg-cyan-500/30 text-xs font-mono font-bold transition-all flex items-center justify-center space-x-1.5">
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        <span>Re-Sync All Product Schemas</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Module 2: WhatsApp Verification & Auto-Courier Dispatch Simulator -->
        <div class="admin-glass rounded-3xl p-6 border border-slate-800 space-y-4 flex flex-col justify-between">
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                        <i data-lucide="message-square" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-cyber font-bold text-sm text-white">WhatsApp Verification Engine</h4>
                        <p class="text-[11px] text-emerald-400 font-mono">Incoming 'হ্যাঁ' ➔ Auto-Courier Book</p>
                    </div>
                </div>

                <!-- Simulator Chat Box -->
                <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 space-y-2 text-xs font-mono">
                    <div class="p-2.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 text-[11px] leading-relaxed">
                        🤖 <b>AI WhatsApp Prompt:</b><br>
                        "আসসালামু আলাইকুম তানভীর ভাই! অর্ডারটি কনফার্ম করতে 'হ্যাঁ' অথবা বাতিল করতে 'না' লিখে পাঠান।"
                    </div>

                    <div class="flex items-center space-x-2 pt-1">
                        <input type="text" x-model="waInput" placeholder="Customer WhatsApp reply (e.g. হ্যাঁ)"
                               class="flex-1 bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-emerald-400">
                        <button @click="testWhatsAppReply()" class="px-3 py-2 rounded-xl bg-emerald-500 text-slate-950 font-bold text-xs hover:bg-emerald-400 transition-all">
                            Send
                        </button>
                    </div>

                    <template x-if="waResult">
                        <div class="p-2.5 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 text-[11px] leading-relaxed space-y-1">
                            <p class="font-bold">✓ Steadfast Courier Auto-Booked!</p>
                            <p class="text-[10px] text-slate-300" x-text="'Tracking: #' + waResult.tracking_code"></p>
                        </div>
                    </template>
                </div>
            </div>

            <div class="text-[11px] text-slate-400 font-mono">
                ⚡ Meta Webhook Active: When customer replies "হ্যাঁ", order auto-transitions to <b>Processing</b> with Steadfast Tracking Code.
            </div>
        </div>

        <!-- Module 3: AI Voice Calling Confirmation Agent -->
        <div class="admin-glass rounded-3xl p-6 border border-slate-800 space-y-4 flex flex-col justify-between">
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center">
                        <i data-lucide="phone-call" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-cyber font-bold text-sm text-white">AI Voice Calling Agent</h4>
                        <p class="text-[11px] text-purple-400 font-mono">Conversational Bangla Voice AI</p>
                    </div>
                </div>

                <!-- Voice Script & Direct Phone Dialer -->
                <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 space-y-3 text-xs font-mono">
                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 font-bold uppercase">Phone Number to Call:</label>
                        <div class="flex items-center space-x-2">
                            <input type="text" x-model="dialPhone" placeholder="019XXXXXXXX"
                                   class="flex-1 bg-slate-900 border border-purple-500/40 rounded-xl px-3 py-2 text-xs font-bold text-purple-300 focus:outline-none focus:border-purple-400">
                            <button @click="dialCustomVoiceCall()" 
                                    :disabled="calling"
                                    class="px-4 py-2 rounded-xl bg-gradient-to-r from-purple-500 to-pink-600 text-white font-bold text-xs uppercase hover:scale-105 transition-all flex items-center space-x-1.5 shadow-lg disabled:opacity-50">
                                <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                <span x-text="calling ? 'Ringing...' : 'Dial Call'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Live Active Call Simulation Screen -->
                    <div x-show="callActive" x-cloak class="p-3 rounded-xl bg-purple-950/60 border border-purple-400/50 space-y-2 animate-pulse">
                        <div class="flex items-center justify-between text-purple-200">
                            <span class="font-bold flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                <span>📞 In-Call: <b x-text="dialPhone"></b></span>
                            </span>
                            <span class="text-[10px] text-emerald-300 font-bold font-mono">CONNECTED</span>
                        </div>
                        <p class="text-[11px] text-slate-200 leading-relaxed italic" x-text="currentVoiceScript"></p>
                        
                        <div class="pt-2 flex items-center gap-2 border-t border-purple-800/60">
                            <button @click="confirmVoiceResponse('হ্যাঁ আমি অর্ডারটি কনফার্ম করছি')" class="flex-1 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-[11px] transition-all">
                                🗣️ Say: "হ্যাঁ নিব" (Confirm)
                            </button>
                            <button @click="confirmVoiceResponse('না বাতিল করুন')" class="px-3 py-1.5 rounded-lg bg-pink-600/80 hover:bg-pink-500 text-white font-bold text-[11px] transition-all">
                                ❌ "না" (Cancel)
                            </button>
                        </div>
                    </div>

                    <template x-if="voiceResult">
                        <div class="p-2.5 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 text-[11px] leading-relaxed space-y-1">
                            <p class="font-bold flex items-center gap-1">
                                <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-400"></i>
                                <span>Voice Call Result: Auto-Dispatched!</span>
                            </p>
                            <p class="text-[10px] text-slate-300" x-text="voiceResult.ai_voice_reply"></p>
                        </div>
                    </template>
                </div>
            </div>

            <div class="text-[11px] text-slate-400 font-mono">
                🎙️ Telephony Gateway: Voice call engine synthesized in natural Bengali. Connect Twilio / Vapi SID for cellular telecom broadcast.
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function aiAutomationHub() {
        return {
            waInput: 'হ্যাঁ পাঠিয়ে দিন',
            waResult: null,
            dialPhone: '01947521688',
            calling: false,
            callActive: false,
            currentVoiceScript: '',
            voiceResult: null,

            testWhatsAppReply() {
                const text = this.waInput.trim();
                if (!text) return;

                fetch('{{ route("admin.ai_automation.simulate_whatsapp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ phone: this.dialPhone, reply: text })
                })
                .then(res => res.json())
                .then(data => {
                    this.waResult = data;
                    alert('WhatsApp AI Action: ' + (data.action || 'Processed') + '\n\n' + data.reply);
                });
            },

            dialCustomVoiceCall() {
                this.calling = true;
                this.voiceResult = null;

                fetch('{{ route("admin.ai_automation.dial_voice") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ phone: this.dialPhone })
                })
                .then(res => res.json())
                .then(data => {
                    this.calling = false;
                    this.callActive = true;
                    this.currentVoiceScript = data.voice_script;

                    // Speak Bengali Voice Script aloud using browser SpeechSynthesis (TTS)
                    if ('speechSynthesis' in window) {
                        const utterance = new SpeechSynthesisUtterance(data.voice_script);
                        utterance.lang = 'bn-BD';
                        utterance.rate = 0.95;
                        window.speechSynthesis.speak(utterance);
                    }
                })
                .catch(() => {
                    this.calling = false;
                });
            },

            confirmVoiceResponse(customerVoice) {
                this.callActive = false;
                if ('speechSynthesis' in window) {
                    window.speechSynthesis.cancel();
                }

                // Simulate order voice processing
                fetch('/admin/ai-automation/1/simulate-voice', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ voice_input: customerVoice })
                })
                .then(res => res.json())
                .then(data => {
                    this.voiceResult = data;
                    if ('speechSynthesis' in window && data.ai_voice_reply) {
                        const confirmUtterance = new SpeechSynthesisUtterance(data.ai_voice_reply);
                        confirmUtterance.lang = 'bn-BD';
                        window.speechSynthesis.speak(confirmUtterance);
                    }
                });
            }
        }
    }
</script>
@endpush
