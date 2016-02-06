<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

/**
 * BibleBowl\OrderStatus
 *
 * @property string $code
 * @property string $name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class OrderStatus extends Model
{
    const IN_CREATION   = 'in_creation';
    const PENDING       = 'pending';
    const IN_PROCESS    = 'in_process';
    const COMPLETED     = 'completed';
    const FAILED        = 'failed';
    const CANCELED      = 'canceled';
}