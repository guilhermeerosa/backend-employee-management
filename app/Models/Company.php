<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    const ROUTE_API = 'company';

    protected $fillable = ['name', 'cnpj', 'address'];

    public function users(){
        return $this->belongsToMany(User::class, 'company_users');
    }
}
