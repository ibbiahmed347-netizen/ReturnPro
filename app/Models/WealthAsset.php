<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WealthAsset extends Model
{
    protected $fillable = ['wealth_statement_id', 'asset_type', 'description', 'amount'];
    public function wealthStatement() { return $this->belongsTo(WealthStatement::class); }
}