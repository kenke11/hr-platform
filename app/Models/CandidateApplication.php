<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'vacancy_id',
        'full_name',
        'email',
        'phone',
        'position',
        'linkedin_url',
        'github_url',
        'portfolio_url',
        'cv_path',
        'cover_letter',
        'status',
    ];

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
