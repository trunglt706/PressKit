<?php

namespace App\Enums;

enum ContactSubmissionStatus: string
{
    case PENDING = 'pending';
    case PROCESSED = 'processed';
}
