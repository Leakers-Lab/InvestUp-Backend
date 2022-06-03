<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'company_id',
        'title',
        'content',
        'price',
        'status',
    ];

    public function Donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function Company()
    {
        return $this->belongsTo(Company::class);
    }

    public function Project()
    {
        return $this->belongsTo(Project::class);
    }
}
