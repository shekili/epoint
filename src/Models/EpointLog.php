<?php

namespace Shekili\Epoint\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EpointLog extends Model
{
    protected $table = 'epoint_logs';

    protected $fillable = [
        'status',
        'transaction',
        'code',
        'message',
        'order_id',
        'bank_transaction',
        'bank_response',
        'card_name',
        'card_mask',
        'rrn',
        'other_attr',
        'trace_id',
        'amount',
        'model_type',
        'model_id',
        'description',
        'reviewed',
    ];

    // -----------------------------------------------------------------------
    // İlişkiler
    // -----------------------------------------------------------------------

    /** Polimorfik: hansı modelə bağlıdır (User, Order, vs.) */
    public function loggable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }

    // -----------------------------------------------------------------------
    // Scopes
    // -----------------------------------------------------------------------

    public function scopeStatusSuccess($query)
    {
        return $query->where('code', '000');
    }
}
