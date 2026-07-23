<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class NoticeReply extends Model
{
    protected $fillable = ['notice_id', 'reply_date', 'remarks', 'attachment', 'replied_by'];
    public function notice() { return $this->belongsTo(Notice::class); }
    public function repliedBy() { return $this->belongsTo(User::class, 'replied_by'); }
}