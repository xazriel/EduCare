<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';

    protected $fillable = ['name', 'grade', 'academic_year'];

    public function students()
    {
        return $this->hasMany(User::class, 'class_id');
    }
}
