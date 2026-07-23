<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'voucher_no', 'client_id', 'tax_year_id', 'voucher_date',
        'due_date', 'amount', 'discount', 'net_amount',
        'status', 'notes', 'created_by'
    ];

    public function client() { return $this->belongsTo(Client::class); }
    public function taxYear() { return $this->belongsTo(TaxYear::class); }
    public function items() { return $this->hasMany(VoucherItem::class); }
    public function receipts() { return $this->hasMany(Receipt::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}