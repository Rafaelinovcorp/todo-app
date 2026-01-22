<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
    'title',
    'description',
    'due_date',
    'start_time',
    'end_time',
    'priority',
    'status',
    'user_id',
    ];

    public function lists()
    {
        return $this->belongsToMany(TaskList::class, 'list_task');
    }



    public function getColorClassAttribute(): string
    {
        if ($this->status === 'completed') {
            return 'bg-green-200 border-green-400';
        }
    
        if ($this->due_date && Carbon::parse($this->due_date)->isPast()) {
            return 'bg-purple-200 border-purple-400';
        }
    
        return match ($this->priority) {
            'low' => 'bg-yellow-200 border-yellow-400',
            'medium' => 'bg-orange-200 border-orange-400',
            'high' => 'bg-red-200 border-red-400',
            default => 'bg-gray-200 border-gray-400',
        };
    }
}
