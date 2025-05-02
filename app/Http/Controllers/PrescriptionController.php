<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Prescription;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;

class PrescriptionController extends Controller
{
    public function index()
{
    $prescriptions = Prescription::with('medecin')->latest()->paginate(10); // 10 prescriptions par page

    return view('products.prescriptions', compact('prescriptions'));

}

    public function create()
    {
        return view('prescriptions.create');
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:prescriptions',
            'medicament' => 'required',
            'posologie' => 'required',
            'duree' => 'required',
            'patient' => 'required',
        ]);

        Prescription::create([
            'code' => $request->code,
            'medicament' => $request->medicament,
            'posologie' => $request->posologie,
            'duree' => $request->duree,
            'instruction' => $request->instruction,
            'patient' => $request->patient,
            'medecin_id' => auth()->id(),
        ]);

        return redirect()->route('prescriptions.index')->with('success', 'Prescription enregistrée avec succès.');
    }

    public function destroy(Prescription $prescription)
    {
        $prescription->delete();
        return back()->with('success', 'Prescription supprimée.');
    }
}


