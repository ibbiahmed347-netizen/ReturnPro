<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'receipt_no', 'voucher_id', 'client_id', 'receipt_date',
        'amount', 'payment_method', 'bank_name', 'cheque_no',
        'notes', 'received_by'
    ];
    public function voucher() { return $this->belongsTo(Voucher::class); }
    public function client() { return $this->belongsTo(Client::class); }
}