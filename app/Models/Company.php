<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'alias',
        'address',
        'phone',
        'image',
        'bg-image',
        'status',
    ];

    public function Plans()
    {
        return $this->hasMany(Plan::class);
    }

    public function Projects()
    {
        return $this->hasMany(Project::class);
    }
}
