<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'date',
        'check_in_at',
        'check_out_at',
        'is_absent',
        'absence_reason',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
    ];

    /* =======================
     | Relations
     |======================= */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /* =======================
     | Helpers
     |======================= */

    public function isCheckedIn(): bool
    {
        return ! is_null($this->check_in_at);
    }

    public function isCheckedOut(): bool
    {
        return ! is_null($this->check_out_at);
    }
}
