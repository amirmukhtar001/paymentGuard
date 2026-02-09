<?php

namespace App\Enums;

enum ReconciliationStatus: string
{
    case PendingReview = 'pending_review';
    case Acknowledged = 'acknowledged';
    case Disputed = 'disputed';
    case Resolved = 'resolved';
}
