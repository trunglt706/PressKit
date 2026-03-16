<?php

namespace App\Repositories;

use App\Models\ContactSubmission;

class ContactSubmissionRepository
{
    /**
     * Find an existing submission from the same day that shares any of:
     * IP address, email, or phone — used to detect duplicate submissions.
     *
     * @param  string  $submittedDate  Date string (Y-m-d) of the submission.
     * @param  string  $ipAddress      Client IP address.
     * @param  string  $email          Submitted email address.
     * @param  string  $phone          Submitted phone number.
     */
    public function findDailyDuplicate(string $submittedDate, string $ipAddress, string $email, string $phone): ?ContactSubmission
    {
        return ContactSubmission::query()
            ->where('submitted_date', $submittedDate)
            ->where(function ($query) use ($ipAddress, $email, $phone): void {
                $query
                    ->where('ip_address', $ipAddress)
                    ->orWhere('email', $email)
                    ->orWhere('phone', $phone);
            })
            ->first();
    }

    /**
     * Create a new contact submission record with the given attributes.
     */
    public function create(array $data): ContactSubmission
    {
        return ContactSubmission::query()->create($data);
    }
}
