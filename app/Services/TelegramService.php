<?php

namespace App\Services;

use App\Models\Appeal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    private string $apiBase;
    private string $chatId;

    public function __construct()
    {
        $token        = config('services.telegram.bot_token');
        $this->chatId = (string) config('services.telegram.chat_id');
        $this->apiBase = "https://api.telegram.org/bot{$token}";
    }

    /**
     * Send a new-appeal notification with inline status-change buttons.
     */
    public function sendNewAppealNotification(Appeal $appeal): void
    {
        $text = implode("\n", [
            "📨 <b>New Appeal Received</b>",
            "",
            "🔖 <b>Tracking:</b> {$appeal->tracking_code}",
            "👤 <b>Name:</b> {$appeal->full_name}",
            "📞 <b>Phone:</b> {$appeal->phone}",
            "📧 <b>Email:</b> " . ($appeal->email ?? '—'),
            "📂 <b>Subject:</b> {$appeal->subject}",
            "",
            "📝 <b>Message:</b>",
            $appeal->body,
        ]);

        $this->sendMessage($this->chatId, $text, $this->buildStatusKeyboard($appeal->id));
    }

    /**
     * Notify the operator chat that an admin has sent a response via the panel.
     */
    public function notifyResponseSent(Appeal $appeal, string $responseText): void
    {
        $preview = mb_strlen($responseText) > 200
            ? mb_substr($responseText, 0, 200) . '…'
            : $responseText;

        $text = implode("\n", [
            "✅ <b>Murojaat javob berildi</b>",
            "",
            "🔖 <b>Tracking:</b> {$appeal->tracking_code}",
            "👤 <b>Ariza beruvchi:</b> {$appeal->full_name}",
            "",
            "💬 <b>Javob:</b>",
            $preview,
        ]);

        $this->sendMessage($this->chatId, $text);
    }

    /**
     * Answer a callback query to remove the Telegram loading indicator.
     */
    public function answerCallbackQuery(string $callbackQueryId, string $text = ''): void
    {
        $this->call('answerCallbackQuery', [
            'callback_query_id' => $callbackQueryId,
            'text'              => $text,
            'show_alert'        => false,
        ]);
    }

    /**
     * Edit the inline keyboard on a previously sent message to reflect the new status.
     */
    public function updateMessageKeyboard(string $chatId, int $messageId, int $appealId): void
    {
        $this->call('editMessageReplyMarkup', [
            'chat_id'      => $chatId,
            'message_id'   => $messageId,
            'reply_markup' => json_encode($this->buildStatusKeyboard($appealId)),
        ]);
    }

    private function sendMessage(string $chatId, string $text, array $replyMarkup = []): void
    {
        $payload = [
            'chat_id'    => $chatId,
            'text'       => $text,
            'parse_mode' => 'HTML',
        ];

        if (!empty($replyMarkup)) {
            $payload['reply_markup'] = json_encode($replyMarkup);
        }

        $this->call('sendMessage', $payload);
    }

    /**
     * Build inline keyboard with all actionable statuses for an appeal.
     *
     * Callback data format: "appeal_status:{appeal_id}:{status}"
     */
    private function buildStatusKeyboard(int $appealId): array
    {
        $buttons = [
            ['text' => '🔍 Reviewing',  'callback_data' => "appeal_status:{$appealId}:reviewing"],
            ['text' => '✅ Responded',  'callback_data' => "appeal_status:{$appealId}:responded"],
            ['text' => '🔒 Closed',     'callback_data' => "appeal_status:{$appealId}:closed"],
            ['text' => '❌ Rejected',   'callback_data' => "appeal_status:{$appealId}:rejected"],
        ];

        // Two buttons per row
        return [
            'inline_keyboard' => array_chunk($buttons, 2),
        ];
    }

    private function call(string $method, array $payload): void
    {
        try {
            $response = Http::post("{$this->apiBase}/{$method}", $payload);

            if (!$response->successful()) {
                Log::warning("Telegram API [{$method}] failed", [
                    'status'   => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error("Telegram API [{$method}] exception: {$e->getMessage()}");
        }
    }
}
