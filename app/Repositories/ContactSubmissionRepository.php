<?php

namespace App\Repositories;

use App\Models\ContactSubmission;

class ContactSubmissionRepository
{
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

    public function create(array $data): ContactSubmission
    {
        return ContactSubmission::query()->create($data);
    }
}
