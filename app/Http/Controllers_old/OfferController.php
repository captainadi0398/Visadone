<?php

namespace App\Http\Controllers;

use App\Models\VisaOffers;
use DB;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Response;

class OfferController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        // dd($user);
        if ($user->branch_id == null || $user->user_type == "S") {
            $data['country'] = DB::table('country_info')->get();
        } else {
            $hasPermission = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->where('roles.id', $user->role)
                ->where('permissions.name', 'Offers list')
                ->exists();


            if ($hasPermission) {
                // Fetch data based on user's group_id and user_type 'O'
                $data['country'] = DB::table('country_info')->get();
            } else {
                abort(403, 'You Don\'t Have Access To this Page Currently');
            }
        }

        // dd($data);
        return view("offers.index", $data);
    }

    public function create()
    {
        // try {
        $user = Auth::user();
        // dd($user);
        if ($user->branch_id == null || $user->user_type == "S") {
            $data['country'] = DB::table('country_info')->get();
        } else {
            $hasPermission = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->where('roles.id', $user->role)
                ->where('permissions.name', 'Offers add')
                ->exists();


            if ($hasPermission) {
                // Fetch data based on user's group_id and user_type 'O'
                $data['country'] = DB::table('country_info')->get();
            } else {
                abort(403, 'You Don\'t Have Access To this Page Currently');
            }
        }
        return view("offers.create", $data);
        // } catch (\Exception $e) {
        //     return view("layout.500");
        // }

    }

    public function edit($id)
    {
        try {
            $data['offer'] = DB::table('visa_offer_data')->where('id', $id)->get();
            $data['country'] = DB::table('country_info')->get();
            return view("offers.edit", $data);
        } catch (\Exception $e) {
            return view("layout.500");
        }
    }

    public function getoffers($destination, $nationality)
    {
        // $data = VisaOffers::where('destination', $destination)->get();
        $data = VisaOffers::where('destination', 'LIKE', '%' . $destination . '%')->where('entry_fees', 'single_entry')->orwhere('entry_fees', 'Single Entry')->get();
        return response()->json($data)->setEncodingOptions(JSON_PRETTY_PRINT);
    }


    public function getoffers_multiple($destination)
    {
        // $data = VisaOffers::where('destination', $destination)->get();
        $data = VisaOffers::where('destination', 'LIKE', '%' . $destination . '%')->where('entry_fees', 'multi_entry')->get();
        return response()->json($data)->setEncodingOptions(JSON_PRETTY_PRINT);
    }

    public function store(Request $request)
    {

        try {

            $id = VisaOffers::create(['nationality' => $request->nationality, 'destination' => $request->destination, 'visa_category' => $request->visa_category, 'visa_type' => $request->visa_type, 'status' => 'active'])->id;
            $user = VisaOffers::find($id);

            $user->entry_fees = $request->entery_type;
            $user->visa_description = $request->visa_description;
            $user->processing_time = $request->processing_time;
            $user->visa_validity = $request->visa_validity;
            $user->stay_validity = $request->stay_validity;
            $user->base_rate_adult = $request->base_rate_adult;
            $user->base_rate_child = $request->base_rate_child;
            $user->base_rate_Infant = $request->base_rate_Infant;
            $user->govt_fees_adult = $request->govt_fees_adult;
            $user->govt_fees_child = $request->govt_fees_child;
            $user->govt_fees_infant = $request->govt_fees_infant;
            $user->save();

            $data['success'] = "1";
            $data['message'] = "Offer Created";
            $data['offerinfo'] = [
                'Nationality' => $request->nationality,
                'Destination' => $request->destination,
                'Visa Type' => $request->visa_type,
                'Visa Category' => $request->visa_category,
                'Visa Validity' => $request->visa_validity,
                'offer_id' => $id,
            ];

            return $data;
        } catch (\Expection $e) {
            // dd($e);
            // return view("layout.500");
            $data['success'] = "0";
            $data['message'] = "Offer not Created!";
            $data['offerinfo'] = [];
        }
    }

    public function getOffer_view(Request $request)
    {

        try {
            $data['offers'] = VisaOffers::where('nationality', 'LIKE', '%' . $request->nationality . '%')
                ->where('destination', 'LIKE', '%' . $request->destination . '%')

                ->orderBy('created_at')
                ->get();

            $data['country'] = DB::table('country_info')->get();

            return view("offers.index", $data);
        } catch (\Exception $e) {
            // dd($e);
            return view("layout.500");
        }
    }

    public function store_offer(Request $request)
    {
        // dd($request);
        try {
            $nationality = collect($request->nationality)->implode(',');
            $destination = collect($request->destination)->implode(',');
            $id = VisaOffers::create([
                'nationality' => $nationality,
                'destination' => $destination,
                'visa_category' => $request->visa_category,
                'visa_type' => $request->visa_type,
                'status' => $request->status_offer
            ])->id;
            $user = VisaOffers::find($id);

            $user->entry_fees = $request->entry_type;
            $user->visa_description = $request->visa_description;
            $user->processing_time = $request->processing_time;
            $user->visa_validity = $request->visa_validity;
            $user->stay_validity = $request->stay_validity;
            $user->base_rate_adult = $request->base_rate_adult;
            $user->base_rate_child = $request->child_fees;
            $user->base_rate_Infant = $request->infant_fees;
            $user->govt_fees_adult = $request->govt_fees_adult;
            $user->govt_fees_child = $request->govt_fees_child;
            $user->govt_fees_infant = $request->govt_fees_infant;
            $user->currency_value = $request->currency_data;
            $user->save();

            $data['success'] = "1";
            $data['message'] = "Offer Created";
            $data['offerinfo'] = [
                'Nationality' => $request->nationality,
                'Destination' => $request->destination,
                'Visa Type' => $request->visa_type,
                'Visa Category' => $request->visa_category,
                'Visa Validity' => $request->visa_validity,
                'offer_id' => $id,
            ];
            $data['country'] = DB::table('country_info')->get();
            return redirect('/visa/offers');
        } catch (\Expection $e) {
            // dd($e);
            return view("layout.500");
        }
    }

    public function getcurrency($country)
    {
        $data = DB::table('usd_currency_converter')->where('country_name', $country)->get();

        return response()->json($data);
    }

    public function update(Request $request)
    {
        // dd($request);
        try {
            $nationality = collect($request->nationality)->implode(',');
            $destination = collect($request->destination)->implode(',');
            DB::table('visa_offer_data')
                ->where('id', $request->id) // Specify the condition to select the record(s) to update
                ->update([
                    'nationality' => $nationality,
                    'destination' => $destination,
                    'visa_category' => $request->visa_category,
                    'visa_type' => $request->visa_type,
                    'status' => $request->status_offer,
                    'entry_fees' => $request->entry_type,
                    'visa_description' => $request->visa_description,
                    'processing_time' => $request->processing_time,
                    'visa_validity' => $request->visa_validity,
                    'stay_validity' => $request->stay_validity,
                    'base_rate_adult' => $request->base_rate_adult,
                    'base_rate_child' => $request->child_fees,
                    'base_rate_Infant' => $request->infant_fees,
                    'govt_fees_adult' => $request->govt_fees_adult,
                    'govt_fees_child' => $request->govt_fees_child,
                    'govt_fees_infant' => $request->govt_fees_infant,
                    'currency_value' => $request->currency_data,

                ]); // Set the new values for the columns

            return redirect('/visa/offers');
        } catch (\Exception $e) {
            dd($e);
            return view("layout.500");
        }
    }

    public function getnation($id)
    {
        $data['nation'] = VisaOffers::where('id', $id)->get();

        return response()->json($data);
    }


    public function update_country(Request $request, $id, $nation)
    {
        // dd($request);
        VisaOffers::where('id', $id)->update(['nationality' => $nation]);
        $data['data'] = "Updated Successfully";
        return response()->json($data);
    }


    public function exportData(Request $request)
    {
        // Perform your query to retrieve the data to export
        $data = DB::table('visa_offer_data')->get();

        // Extract column names from the first row of data
        $columns = array_keys((array)$data[0]);

        // Create a CSV file with column names as the first row
        $csv = $this->arrayToCsv([$columns]);

        // Append the data rows to the CSV
        $csv .= $this->arrayToCsv($data);

        // Create a response with the CSV data and appropriate headers
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="export.csv"',
        ];

        return Response::make($csv, 200, $headers);
    }

    private function arrayToCsv($array)
    {
        $output = fopen('php://temp', 'w');
        foreach ($array as $row) {
            fputcsv($output, (array)$row);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        return $csv;
    }
}
