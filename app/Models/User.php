<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'gender',
        'class_id',
        'nis',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function studentClass()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function questionnaireResponses()
    {
        return $this->hasMany(QuestionnaireResponse::class);
    }

    public function chatbotLogs()
    {
        return $this->hasMany(ChatbotLog::class);
    }
}