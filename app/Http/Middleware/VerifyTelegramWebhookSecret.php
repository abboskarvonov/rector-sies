<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTelegramWebhookSecret
{
    /**
     * Verify the X-Telegram-Bot-Api-Secret-Token header sent by Telegram.
     *
     * Telegram attaches this header on every webhook delivery when a
     * secret_token was provided during setWebhook(). Requests that are
     * missing or have a wrong token are rejected with 403.
     *
     * If TELEGRAM_WEBHOOK_SECRET is not configured the middleware is a no-op
     * (useful during local development), but a warning is logged.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $configured = config('services.telegram.webhook_secret');

        // Secret not configured → skip check (log a warning so it's visible).
        if (blank($configured)) {
            logger()->warning('VerifyTelegramWebhookSecret: TELEGRAM_WEBHOOK_SECRET is not set — skipping token check.');
            return $next($request);
        }

        $incoming = $request->header('X-Telegram-Bot-Api-Secret-Token', '');

        // Constant-time comparison to prevent timing attacks.
        if (!hash_equals($configured, $incoming)) {
            logger()->warning('VerifyTelegramWebhookSecret: invalid or missing secret token.', [
                'ip' => $request->ip(),
            ]);

            return response()->json(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
