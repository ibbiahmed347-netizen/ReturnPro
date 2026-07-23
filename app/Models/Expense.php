<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'category_id', 'expense_date', 'amount', 'description', 'attachment', 'created_by'
    ];

    public function category() { return $this->belongsTo(ExpenseCategory::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}