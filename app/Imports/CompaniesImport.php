<?php

namespace App\Imports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;


class CompaniesImport implements ToModel, WithHeadingRow, WithProgressBar
{

    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
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
