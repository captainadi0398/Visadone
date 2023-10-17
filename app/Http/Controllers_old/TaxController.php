<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use DB;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    //
    public function update_Tax($id, $rate, $tax_name)
    {

        try {
            $index['tax_update'] = DB::table('tax_data')->where('id', $id)->update([
                'tax_percentage' => $rate], ['tax_name' => $tax_name]);
            $data['success'] = "1";
            $data['message'] = "Value Updated";

        } catch (\Exception $e) {
            $data['success'] = "0";
            $data['message'] = "Something went wrong";
        }

        return response()->json($data);
    }

    public function index()
    {
        if (session('user_type') == 'S') {
            $index['country'] = DB::table('country_info')->get();

            $index['tax'] = DB::table('tax_data')->get();
        } else {
            $index['country'] = DB::table('country_info')->get();

            $index['tax'] = DB::table('tax_data')->get();
        }
        return view("taxes.index", $index);
    }

    public function store(Request $request)
    {
        // dd($request);
        try {

            $id = Tax::create(['country_name' => $request->country,
                'country_code' => $request->country_code,
                'tax_name' => $request->tax_name,
                'tax_percentage' => $request->tax_per,
            ])->id;

            return redirect('/tax');
        } catch (\Expection $e) {
            // dd($e);
            return view("layout.500");
        }
    }
}
