<?php

namespace App\Observers;

use App\Enums\ContactSubmissionStatus;
use App\Models\ContactSubmission;

class ContactSubmissionObserver
{
    /**
     * Handle the ContactSubmission "creating" event.
     */
    public function creating(ContactSubmission $contactSubmission): void
    {
        if (blank($contactSubmission->status)) {
            $contactSubmission->status = ContactSubmissionStatus::PENDING;
        }

        if (blank($contactSubmission->ip_address)) {
            $ipAddress = request()->ip();

            if (is_string($ipAddress) && $ipAddress !== '') {
                $contactSubmission->ip_address = $ipAddress;
            }
        }

        if (blank($contactSubmission->submitted_date)) {
            $contactSubmission->submitted_date = now()->toDateString();
        }
    }
}
