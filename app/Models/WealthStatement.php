<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WealthStatement extends Model
{
    protected $fillable = [
        'return_id', 'opening_assets', 'closing_assets',
        'opening_liabilities', 'closing_liabilities'
    ];
    public function return() { return $this->belongsTo(IncomeTaxReturn::class, 'return_id'); }
    public function assets() { return $this->hasMany(WealthAsset::class, 'wealth_statement_id'); }
    public function liabilities() { return $this->hasMany(WealthLiability::class, 'wealth_statement_id'); }
}