<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReturnIncomeDetail extends Model
{
    protected $fillable = [
        'return_id', 'salary_income', 'business_income',
        'property_income', 'capital_gain', 'other_income'
    ];
    public function return() { return $this->belongsTo(IncomeTaxReturn::class, 'return_id'); }
}