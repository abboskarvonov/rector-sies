<?php

namespace App\Services;

use App\Models\Appeal;
use App\Models\AppealResponse;
use App\Models\AppealStatus;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AppealService
{
    private const ALLOWED_MIMES = ['pdf', 'jpg', 'jpeg', 'png', 'docx'];
    private const MAX_FILE_SIZE  = 5 * 1024 * 1024; // 5 MB in bytes

    /**
     * Create a new appeal, upload attached files, and persist everything atomically.
     *
     * @param  array{
     *     category_id: int,
     *     full_name: string,
     *     phone: string,
     *     email?: string|null,
     *     subject: string,
     *     body: string,
     *     ip_address?: string|null,
     *     files?: UploadedFile[]|null,
     * } $data
     */
    public function create(array $data): Appeal
    {
        $uploadedPaths = [];

        if (!empty($data['files'])) {
            $uploadedPaths = $this->uploadFiles($data['files']);
        }

        return DB::transaction(function () use ($data, $uploadedPaths) {
            /** @var Appeal $appeal */
            $appeal = Appeal::create([
                'category_id'   => $data['category_id'],
                'full_name'     => $data['full_name'],
                'phone'         => $data['phone'],
                'email'         => $data['email'] ?? null,
                'subject'       => $data['subject'],
                'body'          => $data['body'],
                'status'        => Appeal::STATUS_PENDING,
                'files'         => $uploadedPaths ?: null,
                'ip_address'    => $data['ip_address'] ?? null,
            ]);

            AppealStatus::create([
                'appeal_id'  => $appeal->id,
                'status'     => Appeal::STATUS_PENDING,
                'comment'    => 'Appeal submitted.',
                'changed_by' => null,
            ]);

            return $appeal;
        });
    }

    /**
     * Change the status of an appeal and record the change in appeal_statuses.
     */
    public function changeStatus(Appeal $appeal, string $status, string $comment, ?int $changedBy = null): void
    {
        if (!in_array($status, Appeal::STATUSES, true)) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        DB::transaction(function () use ($appeal, $status, $comment, $changedBy) {
            $appeal->update(['status' => $status]);

            AppealStatus::create([
                'appeal_id'  => $appeal->id,
                'status'     => $status,
                'comment'    => $comment,
                'changed_by' => $changedBy,
            ]);
        });
    }

    /**
     * Add a response to an appeal and move its status to "responded".
     */
    public function respond(Appeal $appeal, string $response, int $userId): void
    {
        DB::transaction(function () use ($appeal, $response, $userId) {
            AppealResponse::create([
                'appeal_id'    => $appeal->id,
                'response_text' => $response,
                'responded_by'  => $userId,
            ]);

            $this->changeStatus(
                $appeal,
                Appeal::STATUS_RESPONDED,
                'Response added.',
                $userId
            );
        });
    }

    /**
     * Find an appeal by its tracking code, eager-loading related data.
     */
    public function findByTrackingCode(string $code): ?Appeal
    {
        return Appeal::with(['category', 'statuses', 'responses'])
            ->where('tracking_code', $code)
            ->first();
    }

    /**
     * Validate and store multiple uploaded files.
     *
     * Files are saved to storage/app/public/appeals/{year}/{month}/
     * and the method returns their public-relative paths.
     *
     * @param  UploadedFile[]  $files
     * @return string[]
     *
     * @throws ValidationException
     */
    private function uploadFiles(array $files): array
    {
        $this->validateFiles($files);

        $directory = sprintf(
            'appeals/%d/%02d',
            now()->year,
            now()->month
        );

        $paths = [];

        foreach ($files as $file) {
            $paths[] = $file->store($directory, 'public');
        }

        return $paths;
    }

    /**
     * @param  UploadedFile[]  $files
     *
     * @throws ValidationException
     */
    private function validateFiles(array $files): void
    {
        $errors = [];

        foreach ($files as $index => $file) {
            $key = "files.{$index}";

            if (!$file instanceof UploadedFile || !$file->isValid()) {
                $errors[$key][] = 'The file is invalid or could not be uploaded.';
                continue;
            }

            if ($file->getSize() > self::MAX_FILE_SIZE) {
                $errors[$key][] = 'Each file must not exceed 5 MB.';
            }

            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, self::ALLOWED_MIMES, true)) {
                $errors[$key][] = 'Allowed file types: ' . implode(', ', self::ALLOWED_MIMES) . '.';
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }
}
