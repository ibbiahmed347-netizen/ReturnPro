<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TaxYear extends Model
{
    protected $fillable = ['tax_year', 'start_date', 'end_date', 'status'];
}