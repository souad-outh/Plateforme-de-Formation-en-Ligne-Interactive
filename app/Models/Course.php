<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'creator_id',
        'score',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function contents()
    {
        return $this->hasMany(Content::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}