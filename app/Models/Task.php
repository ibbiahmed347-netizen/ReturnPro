<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'client_id', 'assigned_to', 'assigned_by',
        'title', 'description', 'due_date', 'priority', 'status'
    ];

    public function client() { return $this->belongsTo(Client::class); }
    public function assignedTo() { return $this->belongsTo(User::class, 'assigned_to'); }
    public function assignedBy() { return $this->belongsTo(User::class, 'assigned_by'); }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'Urgent' => 'danger',
            'High'   => 'warning',
            'Medium' => 'info',
            default  => 'secondary',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Completed'   => 'success',
            'In Progress' => 'warning',
            default       => 'secondary',
        };
    }
}