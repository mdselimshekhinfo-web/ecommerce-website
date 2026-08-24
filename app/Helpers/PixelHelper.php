<?php

namespace App\Helpers;

use App\Models\ThemeSetting;

class PixelHelper
{
    public static function renderHeaderTags(): string
    {
        $settings = ThemeSetting::pluck('value', 'key')->toArray();
        $html = '';

        // 1. Google Tag Manager Head Script
        $gtmActive = !isset($settings['gtm_active']) || $settings['gtm_active'] === '1';
        if ($gtmActive && !empty($settings['gtm_id'])) {
            $gtmId = htmlspecialchars($settings['gtm_id']);
            $html .= "\n<!-- Google Tag Manager -->\n";
            $html .= "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':\n";
            $html .= "new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],\n";
            $html .= "j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=\n";
            $html .= "'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);\n";
            $html .= "})(window,document,'script','dataLayer','{$gtmId}');</script>\n";
            $html .= "<!-- End Google Tag Manager -->\n";
        }

        // 2. Google Analytics 4 (GA4)
        $ga4Active = !isset($settings['ga4_active']) || $settings['ga4_active'] === '1';
        if ($ga4Active && !empty($settings['ga4_id'])) {
            $ga4Id = htmlspecialchars($settings['ga4_id']);
            $html .= "\n<!-- Google Analytics (GA4) -->\n";
            $html .= "<script async src='https://www.googletagmanager.com/gtag/js?id={$ga4Id}'></script>\n";
            $html .= "<script>\nwindow.dataLayer = window.dataLayer || [];\nfunction gtag(){dataLayer.push(arguments);}\ngtag('js', new Date());\ngtag('config', '{$ga4Id}');\n</script>\n";
        }

        // 3. Meta / Facebook Pixel
        $fbActive = !isset($settings['fb_pixel_active']) || $settings['fb_pixel_active'] === '1';
        if ($fbActive && !empty($settings['fb_pixel_id'])) {
            $fbId = htmlspecialchars($settings['fb_pixel_id']);
            $html .= "\n<!-- Meta Pixel Code -->\n";
            $html .= "<script>\n!function(f,b,e,v,n,t,s)\n{if(f.fbq)return;n=f.fbq=function(){n.callMethod?\nn.callMethod.apply(n,arguments):n.queue.push(arguments)};\nif(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';\nn.queue=[];t=b.createElement(e);t.async=!0;\nt.src=v;s=b.getElementsByTagName(e)[0];\ns.parentNode.insertBefore(t,s)}(window, document,'script',\n'https://connect.facebook.net/en_US/fbevents.js');\nfbq('init', '{$fbId}');\nfbq('track', 'PageView');\n</script>\n";
            $html .= "<noscript><img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id={$fbId}&ev=PageView&noscript=1'/></noscript>\n";
            $html .= "<!-- End Meta Pixel Code -->\n";
        }

        // 4. TikTok Pixel
        $tiktokActive = !isset($settings['tiktok_active']) || $settings['tiktok_active'] === '1';
        if ($tiktokActive && !empty($settings['tiktok_pixel_id'])) {
            $ttId = htmlspecialchars($settings['tiktok_pixel_id']);
            $html .= "\n<!-- TikTok Pixel Code -->\n";
            $html .= "<script>\n!function (w, d, t) {\n w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=['page','track','identify','instances','debug','on','off','once','ready','alias','group','enableCookie','disableCookie','holdConsent','revokeConsent','grantConsent'],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r='https://analytics.tiktok.com/i18n/pixel/events.js',o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement('script');n.type='text/javascript',n.async=!0,n.src=r+'?sdkid='+e+'&lib='+t;e=document.getElementsByTagName('script')[0];e.parentNode.insertBefore(n,e)};\n ttq.load('{$ttId}');\n ttq.page();\n}(window, document, 'ttq');\n</script>\n";
            $html .= "<!-- End TikTok Pixel Code -->\n";
        }

        // 5. Custom Header Script
        $customActive = !isset($settings['custom_scripts_active']) || $settings['custom_scripts_active'] === '1';
        if ($customActive && !empty($settings['header_custom_code'])) {
            $html .= "\n" . $settings['header_custom_code'] . "\n";
        }

        return $html;
    }

    public static function renderBodyTags(): string
    {
        $settings = ThemeSetting::pluck('value', 'key')->toArray();
        $html = '';

        // 1. Google Tag Manager (noscript)
        $gtmActive = !isset($settings['gtm_active']) || $settings['gtm_active'] === '1';
        if ($gtmActive && !empty($settings['gtm_id'])) {
            $gtmId = htmlspecialchars($settings['gtm_id']);
            $html .= "\n<!-- Google Tag Manager (noscript) -->\n";
            $html .= "<noscript><iframe src='https://www.googletagmanager.com/ns.html?id={$gtmId}' height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>\n";
            $html .= "<!-- End Google Tag Manager (noscript) -->\n";
        }

        // 2. Custom Footer Script
        $customActive = !isset($settings['custom_scripts_active']) || $settings['custom_scripts_active'] === '1';
        if ($customActive && !empty($settings['footer_custom_code'])) {
            $html .= "\n" . $settings['footer_custom_code'] . "\n";
        }

        return $html;
    }
}
