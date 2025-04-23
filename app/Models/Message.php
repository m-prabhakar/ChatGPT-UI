<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender',   // 'user' or 'assistant'
        'message',  // message content
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
