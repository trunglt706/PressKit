<?php

namespace App\Models;

use App\Enums\ContactSubmissionStatus;
use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'ip_address',
        'submitted_date',
    ];

    protected function casts(): array
    {
        return [
            'status' => ContactSubmissionStatus::class,
            'submitted_date' => 'date',
        ];
    }
}
