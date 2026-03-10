<?php

namespace App\Http\Controllers;

use App\Services\AppealService;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function __construct(private readonly AppealService $appealService) {}

    public function index()
    {
        return view('tracking.index');
    }

    public function track(Request $request)
    {
        $request->validate([
            'tracking_code' => ['required', 'string', 'regex:/^APP-\d{4}-\d{5}$/'],
        ], [
            'tracking_code.required' => 'Tracking kodni kiriting.',
            'tracking_code.regex'    => 'Format noto\'g\'ri. Masalan: APP-2025-00142.',
        ]);

        $code = strtoupper(trim($request->input('tracking_code')));

        // Check existence here so we can show a friendly message instead of a bare 404.
        if ($this->appealService->findByTrackingCode($code) === null) {
            return back()
                ->withInput()
                ->with('not_found', $code);
        }

        return redirect()->route('appeals.show', ['code' => $code]);
    }
}
