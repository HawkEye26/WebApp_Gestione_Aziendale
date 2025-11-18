<?php

namespace App\Imports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

// Classe che legge e converte il file in istanze del modello Company
class CompaniesImport implements ToModel, WithHeadingRow, WithProgressBar
{

    // Trait del pacchetto Maatwebsite
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {
        // row è un'array associativo che creare una chiave=>valore
        return new Company([
            'company_name' => $row['company_name'],
            'address' => $row['address'],
            'zip_code' => $row['zip_code'],
            'city' => $row['city'],
            'province' => $row['province'],
            'region' => $row['region'],
            'email' => $row['email'],
        ]);
    }
   
}
