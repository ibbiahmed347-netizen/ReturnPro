<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SalesTaxPurchase extends Model
{
    protected $fillable = ['return_id', 'invoice_no', 'invoice_date', 'amount', 'input_tax'];
    public function return() { return $this->belongsTo(SalesTaxReturn::class, 'return_id'); }
}