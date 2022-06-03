<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'content',
        'status',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Project()
    {
        return $this->belongsTo(Project::class);
    }
}
