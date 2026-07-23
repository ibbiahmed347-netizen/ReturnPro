<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeTaxReturn extends Model
{
    protected $fillable = [
        'client_id', 'tax_year_id', 'return_type', 'return_status',
        'published_by', 'published_date', 'notes'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function taxYear()
    {
        return $this->belongsTo(TaxYear::class);
    }

    public function incomeDetails()
    {
        return $this->hasOne(ReturnIncomeDetail::class, 'return_id');
    }

    public function taxCredits()
    {
        return $this->hasMany(ReturnTaxCredit::class, 'return_id');
    }

    public function taxDeducted()
    {
        return $this->hasMany(ReturnTaxDeducted::class, 'return_id');
    }

    public function wealthStatement()
    {
        return $this->hasOne(WealthStatement::class, 'return_id');
    }

    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function getTotalIncomeAttribute()
    {
        $d = $this->incomeDetails;
        if (!$d) return 0;
        return $d->salary_income + $d->business_income + $d->property_income + $d->capital_gain + $d->other_income;
    }
}   