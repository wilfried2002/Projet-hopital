<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function show()
{
    $patients = Patient::all(); // Récupérer tous les patients
    return view('your_view', compact('patients')); // Passer les patients à la vue
}
}
