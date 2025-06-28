<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class SecurityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من Rate Limiting
        $this->checkRateLimit($request);

        // التحقق من IP المحظورة
        $this->checkBlockedIPs($request);

        // التحقق من User Agent المشبوه
        $this->checkSuspiciousUserAgent($request);

        // إضافة Security Headers
        $response = $next($request);
        
        return $this->addSecurityHeaders($response);
    }

    /**
     * التحقق من Rate Limiting
     */
    private function checkRateLimit(Request $request): void
    {
        $key = 'security_rate_limit:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 100)) { // 100 طلب في الدقيقة
            Log::warning('تجاوز حد الطلبات المسموح', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
            ]);

            abort(429, 'تم تجاوز عدد الطلبات المسموح');
        }

        RateLimiter::hit($key, 60); // مدة النافذة: دقيقة واحدة
    }

    /**
     * التحقق من IP المحظورة
     */
    private function checkBlockedIPs(Request $request): void
    {
        $blockedIPs = config('security.blocked_ips', []);
        $clientIP = $request->ip();

        if (in_array($clientIP, $blockedIPs)) {
            Log::warning('محاولة وصول من IP محظور', [
                'ip' => $clientIP,
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
            ]);

            abort(403, 'الوصول محظور');
        }
    }

    /**
     * التحقق من User Agent المشبوه
     */
    private function checkSuspiciousUserAgent(Request $request): void
    {
        $userAgent = $request->userAgent();
        
        // قائمة User Agents المشبوهة
        $suspiciousAgents = [
            'bot', 'crawler', 'spider', 'scraper',
            'curl', 'wget', 'python', 'java',
            'postman', 'insomnia'
        ];

        foreach ($suspiciousAgents as $agent) {
            if (stripos($userAgent, $agent) !== false) {
                Log::info('User Agent مشبوه', [
                    'ip' => $request->ip(),
                    'user_agent' => $userAgent,
                    'url' => $request->fullUrl(),
                ]);
                break;
            }
        }
    }

    /**
     * إضافة Security Headers
     */
    private function addSecurityHeaders(Response $response): Response
    {
        $headers = [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' https:; connect-src 'self'; frame-ancestors 'none';",
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
            'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
        ];

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
