<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'adresse',
    ];
    use HasFactory;

    
    public function prescriptions()
{
    return $this->hasMany(Prescription::class);
}

}
