<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Ticket extends Model
{
    use HasFactory, Searchable;

    public $fillable = [
        'ticket_type_id',
        'content',
        'user_id',
        'comment',
        'status',
        'images'
    ];

    public function toSearchableArray()
    {
        return [
            'content' => $this->content,
            'comment' => $this->comment,
            'status' => $this->status
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
