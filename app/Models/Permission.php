<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'client_id', 'notice_number', 'notice_date',
        'subject', 'description', 'attachment', 'status'
    ];

    public function client() { return $this->belongsTo(Client::class); }
    public function replies() { return $this->hasMany(NoticeReply::class); }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Closed'      => 'success',
            'In Progress' => 'warning',
            default       => 'danger',
        };
    }
}