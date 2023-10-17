<?php

namespace App\Http\Controllers;

use App\Http\Controllers\OCRController;
use App\Models\Visa;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use App\Exceptions\Exception;
use Auth;


class VisaController extends Controller
{
    //
    public function __construct()
    {

        // $this->middleware(['role:Admin']);
        // $this->middleware('permission:Users add',['only' => ['create']]);
        // $this->middleware('permission:Users edit',['only' => ['edit']]);
        // $this->middleware('permission:Users delete',['only' => ['bulk_delete', 'destroy']]);
        // $this->middleware('permission:Users list');
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->user_type == "S") {

            $index['data'] = DB::table('visa_data')
                ->join('users', 'visa_data.user_id', '=', 'users.id')
                ->select('users.*', 'visa_data.*')
                ->where('visa_data.visa_status', '=', 'active')
                ->orderby('visa_data.id', 'desc')
                ->get();
            $index['country'] = DB::table('country_info')->get();
        } else {
            $hasPermission = DB::table('role_has_permissions')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
                ->where('roles.id', $user->role)
                ->where('permissions.name', 'Visa list')
                ->exists();


            if ($hasPermission) {
                // Fetch data based on user's group_id and user_type 'O'
                $index['data'] = DB::table('visa_data')
                    ->join('users', 'visa_data.user_id', '=', 'users.id')
                    ->select('users.*', 'visa_data.*')
                    ->where('visa_data.visa_status', '=', 'active')
                    ->where('branch_id', $user->branch_id)
                    ->orderby('visa_data.id', 'desc')
                    ->get();
            } else {
                abort(403, 'You Don\'t Have Access To this Page Currently');
            }
        }

        // dd($index);
        return view("visa.index", $index);
    }

    public function create()
    {
        $index['country'] = DB::table('country_info')->get();
        $userType = session('user_type');

        switch ($userType) {
            case 'S':
                $index['currency'] = DB::table('currency_converter')
                    ->get();
                break;

            case 'BB':
                $index['currency'] = DB::table('currency_converter')
                    ->where('country_name', session('country_origin'))
                    ->orWhere('country_name', 'United States')
                    ->get();
                break;

            case 'CC':
                $index['currency'] = DB::table('currency_converter')
                    ->where('country_name', session('country_origin'))
                    ->orWhere('country_name', 'United States')
                    ->get();
                break;

            case 'C':
                $index['currency'] = DB::table('currency_converter')
                    ->where('country_name', 'United States')
                    ->get();
                break;

            default:
                $index['currency'] = DB::table('currency_converter')
                    ->get();
                break;
        }

        // dd($index['currency']);
        return view("visa.create", $index);
    }

    public function edit($id)
    {
        $index['country'] = DB::table('country_info')->get();
        $userType = session('user_type');

        switch ($userType) {
            case 'S':
                $index['currency'] = DB::table('currency_converter')
                    ->get();
                break;

            case 'BB':
                $index['currency'] = DB::table('currency_converter')
                    ->where('country_name', session('country_origin'))
                    ->orWhere('country_name', 'United States')
                    ->get();
                break;

            case 'CC':
                $index['currency'] = DB::table('currency_converter')
                    ->where('country_name', session('country_origin'))
                    ->orWhere('country_name', 'United States')
                    ->get();
                break;

            case 'C':
                $index['currency'] = DB::table('currency_converter')
                    ->where('country_name', 'United States')
                    ->get();
                break;

            default:
                $index['currency'] = DB::table('currency_converter')
                    ->get();
                break;
        }

        // dd($index['currency']);
        return view("visa.edit", $index);
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     $request->documents_name => 'max:5120', // 1 MB in kilobytes
        // ]);
        // dd($request->dataFile);
        if ($request->has('offer_secrate_field') && $request->input('offer_secrate_field') === null) {
            return redirect()->back()->with('message', 'The Offer is not Selected.');
        }

        // dd($request);
        try {

            $id = Visa::create(['user_id' => session('id'), 'nationality' => $request->nationality, 'destination' => $request->destination, 'offer_id' => $request->offer_secrate_field, 'visa_status' => 'active'])->id;
            $user = Visa::find($id);
            $data = DB::table('visa_offer_data')->where('id', $request->selected_offer_id)->get();
            $visa_type = $data[0]->visa_category;

            // dd($data);
            // $user->branch_id = $request->branch_id;
            $user->travel_date_from = $request->from_date ?? date('Y-m-d');
            $user->travel_date_to = $request->to_date ?? date('Y-m-d');
            $user->visa_type_selected = $request->{'switch-one-1'};
            $user->visa_fees = $request->visa_fees;
            $user->visa_offer = $data[0]->visa_validity;
            $user->visa_fees = $data[0]->base_rate_adult;

            $user->save(); // Save Data in Visa_data table  Files are remaining.

            if (empty($request->dataFile)){
                return redirect()->back()->with('message', 'Documents Not Uploaded');
            }


            // $files_name = $request->documents_name;
            $files_name = $request->dataFile;

            $filesSaved = [];
            $response_json = [];
            // foreach ($files_name as $data) {
            $fileCount = count($request->dataFile);
            //     // print_r($fileCount);
            // if ($request->hasFile($data)) {

                foreach ($request->dataFile as $file) {

                    $originalName = $file->getClientOriginalName();
                    $mimeType = $file->getClientMimeType();
                    $error = $file->getError();
                    $randomString = Str::random(6);
                    $newFileName = 'VISA_' . $id . '_' . $randomString . '.' . $file->getClientOriginalExtension();


                    // // Store the file in the storage/app/public directory with the new filename
                    // // The 'public' disk is configured to point to storage/app/public
                    $path = $file->storeAs('public/documents/' . $id . '/', $newFileName);


                    $filesSaved[] = [
                        'originalName' => $originalName,
                        'mimeType' => $mimeType,
                        'newFileName' => $newFileName,
                        'path' => $path,
                        'visa_id' => $id,
                    ];

                    $object1 = new OCRController();
                    // $imgurl = "https://carsinafrica.in/img/sheetal_passport.jpg";
                    $imgurl = $originalName;
                    $data_response = $object1->yourControllerFunction($imgurl);
                    // $data_response = str_replace('\\', '', $data_response);
                    // dd($data_response);
                    // Using OCR response Store the Values
                    $data_valid = json_encode($data_response);
                    $offer_id = $request->offer_secrate_field;

                    $responses = $object1->OCR_data_response($id, $data_valid,$newFileName,$offer_id);
                    $responseData = $responses->getData();
                    // dd(empty($responseData));
                    $response_json[] = $responses;
                }
                // }
                // }




            DB::table('visa_data')->where('id', $id)->update([
                'files_JSON' => $filesSaved,
            ]);
            // End

            // OCR Calling
            $responseCount = count($response_json);

            // OCR END
            $data_response_array = (array) $response_json;
            $destination = $request->destination;

            $encoded_data_response = base64_encode(serialize($data_response_array));
            // dd($encoded_data_response);
            // Merge the array with the 'destination' parameter
            $parameters = array_merge(['destination' => $destination, 'type' => $visa_type, 'id' => $id, 'count' => $fileCount, 'data_response' => $encoded_data_response]);
            // dd($parameters);
            // Redirect with the merged parameters
            return redirect()->route('getDocument_visa', $parameters);
        } catch (\Exception $e) {
            dd($e);
            return view("layout.500");
        }
    }

    public function get_required_documents()
    {
    }
}
