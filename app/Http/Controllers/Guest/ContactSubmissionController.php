<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\StoreContactSubmissionRequest;
use App\Services\ContactSubmissionService;
use Illuminate\Http\RedirectResponse;

class ContactSubmissionController extends Controller
{
    public function __construct(
        private readonly ContactSubmissionService $contactSubmissionService
    ) {
    }

    /**
     * Store a new contact submission with daily duplicate constraints.
     */
    public function store(StoreContactSubmissionRequest $request): RedirectResponse
    {
        return $this->contactSubmissionService->handleGuestSubmission(
            $request->validated(),
            (string) $request->ip()
        );
    }
}
