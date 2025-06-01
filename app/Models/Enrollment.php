<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'amount_paid',
        'payment_method',
        'payment_status',
        'transaction_id',
        'payment_details',
        'enrolled_at',
        'completed_at',
        'progress_percentage'
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
        'amount_paid' => 'decimal:2',
        'progress_percentage' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function isCompleted()
    {
        return !is_null($this->completed_at);
    }

    public function isPaid()
    {
        return $this->payment_status === 'completed';
    }
}
