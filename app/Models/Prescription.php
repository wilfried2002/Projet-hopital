<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model

{
    use HasFactory;
    
    protected $fillable = [
        'code', 'medicament', 'posologie', 'duree', 'instruction', 'patient', 'medecin_id'
    ];

    public function medecin()
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }
}
