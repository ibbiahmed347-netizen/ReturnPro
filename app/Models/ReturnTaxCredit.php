<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReturnTaxCredit extends Model
{
    protected $fillable = ['return_id', 'description', 'amount'];
    public function return() { return $this->belongsTo(IncomeTaxReturn::class, 'return_id'); }
}