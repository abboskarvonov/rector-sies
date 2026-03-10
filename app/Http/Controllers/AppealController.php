<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppealRequest;
use App\Mail\AppealConfirmation;
use App\Models\Category;
use App\Services\AppealService;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Mail;

class AppealController extends Controller
{
    public function __construct(
        private readonly AppealService   $appealService,
        private readonly TelegramService $telegram,
    ) {}

    /**
     * Home page.
     */
    public function index()
    {
        return view('appeals.index');
    }

    /**
     * Show the appeal submission form.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();

        return view('appeals.create', compact('categories'));
    }

    /**
     * Validate, persist the appeal, notify operators via Telegram.
     */
    public function store(AppealRequest $request)
    {
        $data = $request->validated();
        $data['ip_address'] = $request->ip();
        $data['files']      = $request->hasFile('files') ? $request->file('files') : [];

        $appeal = $this->appealService->create($data);

        $this->telegram->sendNewAppealNotification($appeal);

        if ($appeal->email) {
            Mail::to($appeal->email)->send(new AppealConfirmation($appeal));
        }

        return view('appeals.submitted', [
            'tracking_code' => $appeal->tracking_code,
        ]);
    }

    /**
     * Show the status page for a specific appeal.
     */
    public function show(string $code)
    {
        $appeal = $this->appealService->findByTrackingCode($code);

        abort_if($appeal === null, 404, 'Murojaat topilmadi.');

        return view('appeals.show', compact('appeal'));
    }
}
