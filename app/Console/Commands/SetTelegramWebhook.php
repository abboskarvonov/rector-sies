<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:set-webhook
                            {--url=      : Webhook URL (defaults to APP_URL + /api/telegram/webhook)}
                            {--delete    : Remove the currently registered webhook}
                            {--info      : Show current webhook info without making changes}';

    protected $description = 'Register (or remove) the Telegram bot webhook URL';

    public function handle(): int
    {
        $token  = config('services.telegram.bot_token');
        $secret = config('services.telegram.webhook_secret');

        if (blank($token)) {
            $this->error('TELEGRAM_BOT_TOKEN is not set in your .env file.');
            return self::FAILURE;
        }

        $telegram = new Api($token);

        // ── Info only ──────────────────────────────────────────────────────────
        if ($this->option('info')) {
            return $this->showInfo($telegram);
        }

        // ── Delete webhook ─────────────────────────────────────────────────────
        if ($this->option('delete')) {
            $this->info('Removing webhook...');

            try {
                $result = $telegram->deleteWebhook();
            } catch (TelegramSDKException $e) {
                $this->error('Failed to delete webhook: ' . $e->getMessage());
                return self::FAILURE;
            }

            if ($result) {
                $this->info('✓ Webhook removed successfully.');
                return self::SUCCESS;
            }

            $this->error('Telegram returned a failure response.');
            return self::FAILURE;
        }

        // ── Set webhook ────────────────────────────────────────────────────────
        $url = $this->option('url')
            ?: rtrim(config('app.url'), '/') . '/api/telegram/webhook';

        if (!str_starts_with($url, 'https://')) {
            $this->error('Webhook URL must start with https://. Got: ' . $url);
            $this->line('Tip: set APP_URL=https://yourdomain.com in your .env file.');
            return self::FAILURE;
        }

        $params = [
            'url'             => $url,
            'allowed_updates' => ['message', 'callback_query'],
        ];

        if (!blank($secret)) {
            $params['secret_token'] = $secret;
            $this->line('  Secret token: <fg=green>enabled</>');
        } else {
            $this->warn('  TELEGRAM_WEBHOOK_SECRET is not set — webhook will be unprotected.');
        }

        $this->info("Setting webhook to: <fg=cyan>{$url}</>");

        try {
            $result = $telegram->setWebhook($params);
        } catch (TelegramSDKException $e) {
            $this->error('Telegram SDK error: ' . $e->getMessage());
            return self::FAILURE;
        }

        if ($result) {
            $this->info('✓ Webhook registered successfully.');
            $this->newLine();
            $this->showInfo($telegram);
            return self::SUCCESS;
        }

        $this->error('Telegram returned a failure response. Check your bot token and URL.');
        return self::FAILURE;
    }

    private function showInfo(Api $telegram): int
    {
        try {
            $info = $telegram->getWebhookInfo();
        } catch (TelegramSDKException $e) {
            $this->error('Could not fetch webhook info: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->table(
            ['Field', 'Value'],
            [
                ['URL',                    $info->getUrl() ?: '<none>'],
                ['Has custom certificate', $info->getHasCustomCertificate() ? 'yes' : 'no'],
                ['Pending update count',   $info->getPendingUpdateCount()],
                ['Last error date',        $info->getLastErrorDate()
                    ? date('Y-m-d H:i:s', $info->getLastErrorDate())
                    : '—'],
                ['Last error message',     $info->getLastErrorMessage() ?: '—'],
                ['Max connections',        $info->getMaxConnections() ?: '—'],
            ]
        );

        return self::SUCCESS;
    }
}
