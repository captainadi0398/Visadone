<?php

namespace App\Http\Controllers;

use DB;

class CurrencyController extends Controller
{
    //
    public function convert_currency($country)
    {
        $index = DB::table('currency_converter')->where('country_currency', $country)->get();
        $data['success'] = "1";
        $data['message'] = "Currency Convert";
        $data['offerinfo'] = $index;

        return response()->json($data);
    }

    public function update_rate($id, $rate)
    {

        try {
            $index['exchange_rate'] = DB::table('currency_configuration_data')->where('id', $id)->update([
                'exchange_rate' => $rate]);
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
        $index['currency'] = DB::table('currency_configuration_data')->get();
        return view("currency_config.index", $index);
    }

}
