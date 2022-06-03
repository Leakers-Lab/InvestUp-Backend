<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'plan_id',
        'user_id',
        'amount',
        'status',
    ];

    public function Project()
    {
        return $this->belongsTo(Project::class);
    }

    public function Plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
