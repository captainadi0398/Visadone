<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportController extends Controller
{
    //
    public function export()
    {
        $data = User::all();
        $csvData = (new Collection($data))->map(function ($row) {
            return [
                'ID' => $row->id,
                'Name' => $row->name,
                'Email' => $row->email,
            ];
        });

        $csv = $csvData->prepend(['ID', 'Name', 'Email']) // Add header once
            ->implode("\n", $csvData->map(function ($row) {
                return implode(',', $row);
            }));

        // Convert CSV string back to array
        $csvArray = array_map('str_getcsv', explode("\n", $csv));

        // Dump the CSV array for debugging
        // dd($csvArray);

        // Return CSV response
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="users.csv"');
    }
}
