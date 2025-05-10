<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    protected $fillable = [
        'user_id', 
        'message', 
        'status', 
        'response',
        'agent_id'
    ];

    public function user() { 
        return $this->belongsTo(User::class); 
    }

    public function agent() { 
        return $this->belongsTo(User::class, 'agent_id'); 
    }
}
