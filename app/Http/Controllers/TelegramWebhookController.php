<?php

namespace App\Http\Controllers;

use App\Models\Appeal;
use App\Services\AppealService;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    public function __construct(
        private readonly AppealService   $appealService,
        private readonly TelegramService $telegram,
    ) {}

    /**
     * Receive and process Telegram webhook updates.
     *
     * Telegram expects a 200 response quickly — heavy work should be queued in production.
     */
    public function handle(Request $request): Response
    {
        $update = $request->json()->all();

        if (isset($update['callback_query'])) {
            $this->handleCallbackQuery($update['callback_query']);
        }

        // Always return 200 so Telegram does not retry the update.
        return response('OK', 200);
    }

    /**
     * Handle an inline-button press from an operator.
     *
     * Expected callback_data format: "appeal_status:{appeal_id}:{status}"
     */
    private function handleCallbackQuery(array $callbackQuery): void
    {
        $queryId = $callbackQuery['id'];
        $data    = $callbackQuery['data'] ?? '';
        $chatId  = (string) ($callbackQuery['message']['chat']['id'] ?? '');
        $msgId   = $callbackQuery['message']['message_id'] ?? null;

        if (!str_starts_with($data, 'appeal_status:')) {
            $this->telegram->answerCallbackQuery($queryId, 'Unknown action.');
            return;
        }

        // Parse: appeal_status:{appeal_id}:{status}
        $parts = explode(':', $data);
        if (count($parts) !== 3) {
            $this->telegram->answerCallbackQuery($queryId, 'Malformed callback data.');
            return;
        }

        [, $appealId, $status] = $parts;

        if (!in_array($status, Appeal::STATUSES, true)) {
            $this->telegram->answerCallbackQuery($queryId, 'Invalid status.');
            return;
        }

        $appeal = Appeal::find((int) $appealId);

        if ($appeal === null) {
            $this->telegram->answerCallbackQuery($queryId, 'Appeal not found.');
            return;
        }

        if ($appeal->status === $status) {
            $this->telegram->answerCallbackQuery($queryId, "Status is already \"{$status}\".");
            return;
        }

        try {
            $operatorName = $callbackQuery['from']['first_name'] ?? 'Operator';

            $this->appealService->changeStatus(
                $appeal,
                $status,
                "Status changed to \"{$status}\" by {$operatorName} via Telegram.",
            );

            $this->telegram->answerCallbackQuery($queryId, "Status updated to \"{$status}\".");

            if ($msgId !== null) {
                $this->telegram->updateMessageKeyboard($chatId, $msgId, $appeal->id);
            }
        } catch (\Throwable $e) {
            Log::error('Telegram webhook: failed to update appeal status', [
                'appeal_id' => $appealId,
                'status'    => $status,
                'error'     => $e->getMessage(),
            ]);

            $this->telegram->answerCallbackQuery($queryId, 'Failed to update status. Please try again.');
        }
    }
}
