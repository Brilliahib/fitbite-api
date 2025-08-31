<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityChatAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'community_chat_id',
        'user_id',
        'message',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function communityChat()
    {
        return $this->belongsTo(CommunityChat::class);
    }
}
