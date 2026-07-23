<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SalesTaxReturn extends Model
{
    protected $fillable = ['client_id', 'month', 'year', 'status', 'notes'];

    public function client() { return $this->belongsTo(Client::class); }
    public function sales() { return $this->hasMany(SalesTaxSale::class, 'return_id'); }
    public function purchases() { return $this->hasMany(SalesTaxPurchase::class, 'return_id'); }

    public function getMonthNameAttribute()
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }

    public function getTotalSalesAttribute()
    {
        return $this->sales->sum('amount');
    }

    public function getTotalSalesTaxAttribute()
    {
        return $this->sales->sum('sales_tax');
    }

    public function getTotalPurchasesAttribute()
    {
        return $this->purchases->sum('amount');
    }

    public function getTotalInputTaxAttribute()
    {
        return $this->purchases->sum('input_tax');
    }

    public function getTaxPayableAttribute()
    {
        return max(0, $this->total_sales_tax - $this->total_input_tax);
    }
}