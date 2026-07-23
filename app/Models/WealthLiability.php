<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WealthLiability extends Model
{
    protected $fillable = ['wealth_statement_id', 'liability_type', 'description', 'amount'];
    public function wealthStatement() { return $this->belongsTo(WealthStatement::class); }
}