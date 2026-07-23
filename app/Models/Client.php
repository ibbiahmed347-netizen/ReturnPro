<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'client_code', 'case_number', 'ntn', 'cnic', 'name',
        'father_name', 'business_name', 'mobile', 'whatsapp',
        'email', 'address', 'city', 'fbr_username', 'fbr_password',
        'registration_date', 'annual_fee', 'status',
    ];

    public function businesses()
    {
        return $this->hasMany(ClientBusiness::class);
    }

    public function contacts()
    {
        return $this->hasMany(ClientContact::class);
    }

    public function notes()
    {
        return $this->hasMany(ClientNote::class);
    }

    public function incomeTaxReturns()
    {
        return $this->hasMany(IncomeTaxReturn::class);
    }

    public function salesTaxReturns()
    {
        return $this->hasMany(SalesTaxReturn::class);
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    public function documents()
    {
        return $this->hasMany(ClientDocument::class);
    }

    public function notices()
    {
        return $this->hasMany(Notice::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}