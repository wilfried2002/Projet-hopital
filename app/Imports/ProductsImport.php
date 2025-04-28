<?php
namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class ProductsImport implements ToModel, WithHeadingRow
{
    /**
     * Convertir une ligne du fichier Excel en modèle Eloquent
     */
    public function model(array $row)
    {
        return new Product([
            'name' => $row['name'], // Assurez-vous que les noms de colonnes correspondent à ceux du fichier Excel
            'description' => $row['description'],
            'quantite' => $row['quantite'],
            'price' => $row['price'],
        ]);
    }
}
