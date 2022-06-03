<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'company_id',
        'title',
        'alias',
        'target',
        'deadline',
        'content',
        'price',
        'status',
    ];

    public function Comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function Donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function Plans()
    {
        return $this->hasMany(Plan::class);
    }

    public function Company()
    {
        return $this->belongsTo(Company::class);
    }

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }

    public function Galleries()
    {
        return $this->hasMany(Gallery::class);
    }
}
