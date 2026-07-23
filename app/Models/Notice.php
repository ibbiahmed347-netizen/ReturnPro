<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = ['client_id', 'notice_number', 'notice_date', 'subject', 'description', 'attachment', 'status'];
    public function client() { return $this->belongsTo(Client::class); }
}