<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'alias',
        'status',
    ];

    public function Projects()
    {
        return $this->hasMany(Project::class);
    }
}
