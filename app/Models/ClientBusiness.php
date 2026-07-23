<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ClientBusiness extends Model
{
    protected $fillable = ['client_id', 'business_name', 'business_type', 'strn', 'status'];
    public function client() { return $this->belongsTo(Client::class); }
}