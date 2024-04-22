<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'counter_id',
        'number',
        'status',
    ];

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
    ];

    protected $appends = [
        'diff_now'
    ];

    protected static function booted()
    {
        static::created(function ($model) {
            Counter::query()
                ->where('id', $model->counter_id)
                ->first()
                ->update([
                    'next_ticket_number' => $model->number + 1,
                ]);
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function counter(): BelongsTo
    {
        return $this->belongsTo(Counter::class);
    }

    public function getDiffNowAttribute()
    {
        return $this->created_at->diffForHumans(now());
    }
}
