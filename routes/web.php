<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LuckyWheelController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminThemeController;
use App\Http\Controllers\Admin\AdminSupplierController;
use App\Http\Controllers\Admin\AdminPurchaseOrderController;
use App\Http\Controllers\Admin\AdminCourierController;
use App\Http\Controllers\Admin\AdminSmsController;
use App\Http\Controllers\Admin\AdminAbandonedCartController;
use App\Http\Controllers\Admin\AdminAnalyticsController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminPixelController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminLandingPageController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\AdminBlacklistController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\AdminGatewayController;
use App\Http\Controllers\Admin\AdminLiveChatController;
use App\Http\Controllers\Admin\AdminAiAutomationController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LiveChatController;

// Public Store Routes
Route::get('/language/toggle', [LanguageController::class, 'toggle'])->name('language.toggle');
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::post('/product/{slug}/review', [ProductController::class, 'submitReview'])->name('product.review.submit');
Route::get('/quick-view/{id}', [ProductController::class, 'quickView'])->name('product.quickview');
Route::get('/api/search-live', [ProductController::class, 'searchLive'])->name('api.search');

// Public Policy & Landing Pages
Route::get('/landing/{slug}', [LandingPageController::class, 'show'])->name('landing.show');
Route::post('/landing/{slug}/order', [LandingPageController::class, 'processDirectOrder'])->name('landing.order');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// Cart & Coupons
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply_coupon');
Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.remove_coupon');
Route::get('/cart/drawer-data', [CartController::class, 'getDrawerData'])->name('cart.drawer_data');

// Checkout & Orders
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/order/success/{orderNumber}', [OrderController::class, 'success'])->name('order.success');
Route::get('/track-order', [OrderController::class, 'track'])->name('order.track');
Route::get('/order/invoice/{orderNumber}', [OrderController::class, 'invoice'])->name('order.invoice');

// Gamified Features & AI Assistant
Route::post('/lucky-wheel/spin', [LuckyWheelController::class, 'spin'])->name('lucky.spin');
Route::post('/ai-assistant/ask', [AssistantController::class, 'ask'])->name('assistant.ask');

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/quick-login/{role}', [AuthController::class, 'quickLogin'])->name('quick.login');

// Customer Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::post('/customer/profile', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');
    Route::post('/customer/wishlist/toggle', [CustomerController::class, 'toggleWishlist'])->name('customer.wishlist.toggle');
    Route::post('/customer/addresses', [CustomerController::class, 'storeAddress'])->name('customer.address.store');
    Route::delete('/customer/addresses/{id}', [CustomerController::class, 'deleteAddress'])->name('customer.address.delete');
});

// Admin Panel
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/language/toggle', [LanguageController::class, 'toggle'])->name('language.toggle');
    Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');
    
    // Users & Staff Management
    Route::resource('staff', AdminStaffController::class);
    
    // Visual Theme Studio & Customizer
    Route::get('/theme-builder', [AdminThemeController::class, 'index'])->name('theme.index');
    Route::get('/theme-studio', [AdminThemeController::class, 'studio'])->name('theme.studio');
    Route::post('/theme-studio/save', [AdminThemeController::class, 'saveStudio'])->name('theme.save_studio');
    Route::post('/theme-builder/settings', [AdminThemeController::class, 'updateSettings'])->name('theme.update_settings');
    Route::post('/theme-builder/section/{key}', [AdminThemeController::class, 'updateSection'])->name('theme.update_section');
    Route::post('/theme-builder/reset', [AdminThemeController::class, 'resetDefaults'])->name('theme.reset_defaults');

    // Marketing, Pixels & Tracking
    Route::get('/marketing/pixels', [AdminPixelController::class, 'index'])->name('marketing.pixels');
    Route::post('/marketing/pixels/update', [AdminPixelController::class, 'updateSingle'])->name('marketing.pixels.update_single');
    Route::post('/marketing/pixels/toggle/{key}', [AdminPixelController::class, 'toggle'])->name('marketing.pixels.toggle');
    Route::get('/marketing/pixels/test/{tracker}', [AdminPixelController::class, 'test'])->name('marketing.pixels.test');

    // 1-Page Landing Pages
    Route::resource('landing-pages', AdminLandingPageController::class);

    // Customer Reviews Moderation
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews', [AdminReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{id}/status', [AdminReviewController::class, 'updateStatus'])->name('reviews.update_status');
    Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Policy & Content Pages
    Route::resource('pages', AdminPageController::class);

    // Fraud Shield & IP Blocklist
    Route::get('/fraud/blacklist', [AdminBlacklistController::class, 'index'])->name('fraud.blacklist');
    Route::post('/fraud/blacklist', [AdminBlacklistController::class, 'store'])->name('fraud.blacklist.store');
    Route::delete('/fraud/blacklist/{id}', [AdminBlacklistController::class, 'destroy'])->name('fraud.blacklist.destroy');

    // Modular Gateways & API Integration Hub
    Route::get('/gateways', [AdminGatewayController::class, 'index'])->name('gateways.index');
    Route::put('/gateways/{id}', [AdminGatewayController::class, 'update'])->name('gateways.update');
    Route::post('/gateways', [AdminGatewayController::class, 'store'])->name('gateways.store');
    Route::post('/gateways/{id}/toggle', [AdminGatewayController::class, 'toggle'])->name('gateways.toggle');
    Route::get('/gateways/{id}/test', [AdminGatewayController::class, 'testConnection'])->name('gateways.test');
    Route::delete('/gateways/{id}', [AdminGatewayController::class, 'destroy'])->name('gateways.destroy');

    // Store Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [AdminSettingController::class, 'update'])->name('settings.update');

    // Supplier Ledger & Inventory
    Route::resource('suppliers', AdminSupplierController::class)->except(['edit', 'update']);
    Route::post('/suppliers/{id}/payment', [AdminSupplierController::class, 'addPayment'])->name('suppliers.add_payment');
    Route::get('/purchase-orders', [AdminPurchaseOrderController::class, 'index'])->name('purchase_orders.index');
    Route::get('/purchase-orders/create', [AdminPurchaseOrderController::class, 'create'])->name('purchase_orders.create');
    Route::post('/purchase-orders', [AdminPurchaseOrderController::class, 'store'])->name('purchase_orders.store');
    Route::get('/purchase-orders/{id}', [AdminPurchaseOrderController::class, 'show'])->name('purchase_orders.show');
    Route::post('/purchase-orders/{id}/receive', [AdminPurchaseOrderController::class, 'receive'])->name('purchase_orders.receive');

    // Courier Hub & 1-Click Booking
    Route::post('/orders/{id}/book-courier', [AdminCourierController::class, 'bookConsignment'])->name('orders.book_courier');
    Route::get('/orders/{id}/courier-label', [AdminCourierController::class, 'printLabel'])->name('orders.courier_label');

    // SMS Notifications Hub
    Route::get('/sms', [AdminSmsController::class, 'index'])->name('sms.index');
    Route::post('/sms/send-custom', [AdminSmsController::class, 'sendCustom'])->name('sms.send_custom');

    // Abandoned Cart Recovery
    Route::get('/abandoned-carts', [AdminAbandonedCartController::class, 'index'])->name('abandoned_carts.index');
    Route::post('/abandoned-carts/{id}/sms', [AdminAbandonedCartController::class, 'sendSmsReminder'])->name('abandoned_carts.send_sms');
    Route::post('/abandoned-carts/{id}/status', [AdminAbandonedCartController::class, 'updateStatus'])->name('abandoned_carts.update_status');

    // Profit & Loss (P&L) & Export
    Route::get('/analytics/pnl', [AdminAnalyticsController::class, 'pnl'])->name('analytics.pnl');
    Route::get('/analytics/export-orders', [AdminAnalyticsController::class, 'exportOrders'])->name('analytics.export_orders');

    // Customer CRM & VIP
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [AdminCustomerController::class, 'show'])->name('customers.show');

    // Product Management
    Route::resource('products', AdminProductController::class)->except(['show']);
    
    // Order Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update_status');
    
    // Coupon Management
    Route::get('/coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
    Route::post('/coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
    Route::delete('/coupons/{id}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');
    
    // Category Management
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Live Support Desk & AI Auto-Pilot Hub
    Route::get('/live-chat', [AdminLiveChatController::class, 'index'])->name('live_chat.index');
    Route::get('/live-chat/{sessionId}', [AdminLiveChatController::class, 'show'])->name('live_chat.show');
    Route::post('/live-chat/{sessionId}/send', [AdminLiveChatController::class, 'sendAgentMessage'])->name('live_chat.send');
    Route::post('/live-chat/toggle-autopilot', [AdminLiveChatController::class, 'toggleAutoPilot'])->name('live_chat.toggle_autopilot');
    Route::post('/live-chat/toggle-agent-status', [AdminLiveChatController::class, 'toggleAgentStatus'])->name('live_chat.toggle_agent_status');
    Route::post('/live-chat/{sessionId}/close', [AdminLiveChatController::class, 'closeSession'])->name('live_chat.close');

    // Enterprise AI Automation Command Hub (Auto-SEO, WhatsApp Verification & Voice Calling Agent)
    Route::get('/ai-automation', [AdminAiAutomationController::class, 'index'])->name('ai_automation.index');
    Route::post('/ai-automation/generate-seo', [AdminAiAutomationController::class, 'generateAllSeo'])->name('ai_automation.generate_seo');
    Route::post('/ai-automation/simulate-whatsapp', [AdminAiAutomationController::class, 'simulateWhatsAppReply'])->name('ai_automation.simulate_whatsapp');
    Route::post('/ai-automation/dial-voice', [AdminAiAutomationController::class, 'dialVoiceCall'])->name('ai_automation.dial_voice');
    Route::post('/ai-automation/{id}/simulate-voice', [AdminAiAutomationController::class, 'simulateVoiceCall'])->name('ai_automation.simulate_voice');
});

// Dynamic XML Sitemap for Google
Route::get('/sitemap.xml', [AdminAiAutomationController::class, 'sitemap'])->name('sitemap');

// Public Live Chat & AI Auto-Pilot APIs
Route::post('/api/live-chat/init', [LiveChatController::class, 'initSession'])->name('live_chat.init');
Route::post('/api/live-chat/send', [LiveChatController::class, 'sendMessage'])->name('live_chat.send_user');
Route::get('/api/live-chat/poll', [LiveChatController::class, 'pollMessages'])->name('live_chat.poll');
Route::post('/api/live-chat/request-agent', [LiveChatController::class, 'requestHumanAgent'])->name('live_chat.request_agent');

