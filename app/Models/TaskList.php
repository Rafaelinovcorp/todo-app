<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * Lista pertence a um utilizador
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lista tem várias tarefas
     */
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'list_task');
    }

}
