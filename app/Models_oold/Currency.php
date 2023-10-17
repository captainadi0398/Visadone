<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    public function import(Request $request)
    {
        $file = $request->file('import_file');

        Excel::import(new CurrencyImporter, $file);

        return redirect()->back()->with('success', 'Data imported successfully.');
    }
}
