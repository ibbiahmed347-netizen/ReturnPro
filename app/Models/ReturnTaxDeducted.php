<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReturnTaxDeducted extends Model
{
    protected $table = 'return_tax_deducted';
    
    protected $fillable = ['return_id', 'source_name', 'amount'];
    
    public function incomeTaxReturn() 
    { 
        return $this->belongsTo(IncomeTaxReturn::class, 'return_id'); 
    }
}