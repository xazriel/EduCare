<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionnaireResponse extends Model
{
    protected $fillable = [
        'user_id', 'sdq_score', 'psc17_score',
        'sassv_score', 'status', 'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'sdq_score'    => 'integer',
        'psc17_score'  => 'integer',
        'sassv_score'  => 'integer',
    ];

    public function riskClassification()
    {
        return $this->hasOne(RiskClassification::class, 'response_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sdqAnswers()
    {
        return $this->hasMany(SdqAnswer::class, 'response_id');
    }

    public function psc17Answers()
    {
        return $this->hasMany(Psc17Answer::class, 'response_id');
    }

    public function sassvAnswers()
    {
        return $this->hasMany(SassvAnswer::class, 'response_id');
    }
}