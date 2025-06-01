<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model  {

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    protected $fillable = [
        'course_id',
        'user_id',
        'degree',
        'appeal_status',
        'appeal_reason',
        'appealed_at',
        'appeal_response',
        'appeal_responded_at',
    ];

    protected $casts = [
        'appealed_at' => 'datetime',
        'appeal_responded_at' => 'datetime',
    ];
}