<?php

namespace App\Http\Controllers;

use App\Models\FormData;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;



class FormsController extends Controller
{
    //
    public function getFields($country)
    {
        $data = DB::table('forms_data')->where('country', $country)->where('is_active', 0)->get();

        return response()->json($data);
    }

    public function getFields_view($country)
    {
        $data['data'] = DB::table('forms_data')->where('country', $country)->get();
        $data['country'] = DB::table('country_info')->get();
        $data['country_name'] = $country;

        return view("Dynamic_form.forms_country", $data);
    }


    // public function getFields_visa($country,$responses)
    // {
    //     $data['data'] = DB::table('forms_data')->where('country', $country)->get();
    //     $data['country'] = DB::table('country_info')->get();
    //     $data['country_name'] = $country;
    //     $data['responses'] = $responses;

    //     return view("Dynamic_form.forms_country", $data);
    // }

    public function getFields_visa($type, $country1, Request $request, $id, $count)
    {

        $data['json'] = [];
        $requesting_files = [];

        $ret = $this->storeFiles($request, $id);



        foreach ($request->allFiles() as $key => $value) {
            $requesting_files[$key] = $value;
        }


        // dd($requesting_files);

        $data_response_array = unserialize(base64_decode(session('processedDataArray')));
        $data['json'] = $data_response_array;

        $arrayData = $data_response_array;


        $visa_data = DB::table('visa_data')->where('id', $id)->get();
        $filenamesArray = [];

        foreach ($visa_data as $item) {
            $filesArray = json_decode($item->files_JSON, true); // Convert JSON to associative array

            foreach ($filesArray as $file) {
                $filenamesArray[] = $file['newFileName'];
            }
        }


        // Assuming the required parameters are part of the $data_response object,
        $data = $arrayData;

        // Initialize an empty array to store results
        $results = [];
        $documents_needed = DB::table('document_rule_data')->where('offer_id', 'like', '%' . $visa_data[0]->offer_id . '%')->pluck('document_name');

        // Iterate through the $data array using a foreach loop
        foreach ($data as $i => $item) {
            // Extract individual data elements from $item
            $data = $item['data'];
            $country = $item['country'];
            // $country_name = $item['country_name'];
            $Code = $item['Code'];
            $Passport_Number = $item['Passport_Number'];
            $Surname = $item['Surname'];
            $First_Name = $item['First_Name'];
            $Nationality = $item['Nationality'];
            $Sex = $item['Sex'];
            $Date_of_Birth = $item['Date_of_Birth'];
            $Place_of_birth = $item['Place_of_birth'];
            $Authority = $item['Authority'];
            $Date_of_Issue = $item['Date_of_Issue'];
            $Date_of_expiry = $item['Date_of_expiry'];
            $MRZ = $item['MRZ'];
            $table = $item['table'];



            foreach ($documents_needed as $document_name) {
                foreach ($requesting_files as $requestKey => $requestValue) {
                    // You can access $requestKey and $requestValue here
                    $parts = explode("$i-", $requestKey);

                    if (count($parts) >= 2 && $document_name === $parts[1]) {
                        // Access the original file name using getClientOriginalName()
                        $originalFileName = $requestValue->getClientOriginalName();

                        // Update the value in $item['doc'] with the original file name
                        $item['doc'][$document_name] = $originalFileName;
                    }
                }
            }



            $files = $item['doc'];

            // Create an associative array with extracted values for this $item
            $extractedData = [
                'Code' => $Code,
                'Passport_Number' => $Passport_Number,
                'Surname' => $Surname,
                'First_Name' => $First_Name,
                'Nationality' => $Nationality,
                'Sex' => $Sex,
                'Date_of_Birth' => $Date_of_Birth,
                'Place_of_birth' => $Place_of_birth,
                'Authority' => $Authority,
                'Date_of_Issue' => $Date_of_Issue,
                'Date_of_expiry' => $Date_of_expiry,
                'MRZ' => $MRZ,
                'table' => $table,
                'files_mapping' => $files,

            ];

            // Add the extracted data to the $results array
            $results[] = $extractedData;
        }




        // Fetch data from the database based on the country
        $countryString = is_string($country) ? $country : '';
        $typeString = is_string($type) ? $type : '';

        $data['country_info'] = DB::table('forms_data')
            ->where('country', $country1)
            ->get();


        $data['country'] = DB::table('country_info')->get();
        $data['country_name'] = $country;


        $data['files'] = $filenamesArray;
        $data['visa_id'] = $id;
        $data['visa_nationality'] = $visa_data[0]->nationality;
        $data['visa_destination'] = $visa_data[0]->destination;
        $data['visa_travel_date_from'] = $visa_data[0]->travel_date_from;
        $data['visa_travel_date_to'] = $visa_data[0]->travel_date_to;
        $data['visa_visa_type_selected'] = $visa_data[0]->visa_type_selected;
        $data['visa_visa_offer'] = $visa_data[0]->visa_offer;
        $data['count'] = $count;
        $data['results'] = $results;

        $data_response_array = unserialize(base64_decode(session('processedDataArray')));
        $data['json'] = $data_response_array;
        // dd($data['results'][1]['files_mapping']);
        return view("Dynamic_form.forms_country", $data);
    }



    public function getDocuments_visa($type, $country, Request $request, $id, $count, $data_response)
    {
        $data_response_array = unserialize(base64_decode($data_response));


        $visa_data = DB::table('visa_data')->where('id', $id)->get();

        $filenamesArray = [];
        foreach ($visa_data as $item) {
            $filesArray = json_decode($item->files_JSON, true); // Convert JSON to associative array

            foreach ($filesArray as $file) {
                $filenamesArray[] = $file['newFileName'];
            }
        }


        $commonVisaData = [
            'visa_id' => $id,
            'visa_nationality' => $visa_data[0]->nationality,
            'visa_destination' => $visa_data[0]->destination,
            'visa_travel_date_from' => $visa_data[0]->travel_date_from,
            'visa_travel_date_to' => $visa_data[0]->travel_date_to,
            'visa_visa_type_selected' => $visa_data[0]->visa_type_selected,
            'visa_visa_offer' => $visa_data[0]->offer_id,
            'count' => $count,
            'files' => $filenamesArray,
            'visa_type' => $type
        ];

        $offerId = is_array($visa_data[0]->offer_id) ? $visa_data[0]->offer_id : [$visa_data[0]->offer_id];


        $documents_needed = DB::table('document_rule_data')->where('offer_id', 'like', '%' . $visa_data[0]->offer_id . '%')->get();


        $documents_array = [];
        foreach ($documents_needed as $document) {
            $documents = [
                'document_name' => $document->document_name,
                // Add more fields as needed
            ];
            $documents_array[] = $documents;
        }


        // dd($data_response_array);
        $processedDataArray = [];

        foreach ($data_response_array as $data) {
            $jsonString = $data->original;
            $arrayData = json_decode($jsonString, true);



            $data = $arrayData;

            $Code = isset($data['data'][0]['Code']) ? $data['data'][0]['Code'] : null;
            $Passport_Number = isset($data['data'][1]['Passport_Number']) ? $data['data'][1]['Passport_Number'] : null;
            $Surname = isset($data['data'][2]['Surname']) ? $data['data'][2]['Surname'] : null;
            $First_Name = isset($data['data'][3]['First_Name']) ? $data['data'][3]['First_Name'] : null;
            $Nationality = isset($data['data'][4]['Nationality']) ? $data['data'][4]['Nationality'] : null;
            $Sex = isset($data['data'][5]['Sex']) ? $data['data'][5]['Sex'] : null;
            $Date_of_Birth = isset($data['data'][6]['Date_of_Birth']) ? $data['data'][6]['Date_of_Birth'] : null;
            $Place_of_birth = isset($data['data'][7]['Place_of_birth']) ? $data['data'][7]['Place_of_birth'] : null;
            $Authority = isset($data['data'][8]['Authority']) ? $data['data'][8]['Authority'] : null;
            $Date_of_Issue = isset($data['data'][9]['Date_of_Issue']) ? $data['data'][9]['Date_of_Issue'] : null;
            $Date_of_expiry = isset($data['data'][10]['Date_of_expiry']) ? $data['data'][10]['Date_of_expiry'] : null;
            $MRZ = isset($data['data'][11]['MRZ']) ? $data['data'][11]['MRZ'] : null;
            $table = isset($data['data'][12]['table']) ? $data['data'][12]['table'] : null;



            // Fetch data from the database based on the country
            $data['data'] = DB::table('forms_data')->where('country', $country)->where('visa_type', $type)->get();
            $data['country'] = DB::table('country_info')->get();
            $data['country_name'] = $country;


            // Pass the extracted parameters to the view
            $data['Code'] = $Code;
            $data['Passport_Number'] = $Passport_Number;
            $data['Surname'] = $Surname;
            $data['First_Name'] = $First_Name;
            $data['Nationality'] = $Nationality;
            $data['Sex'] = $Sex;
            $data['Date_of_Birth'] = $Date_of_Birth;
            $data['Place_of_birth'] = $Place_of_birth;
            $data['Authority'] = $Authority;
            $data['Date_of_Issue'] = $Date_of_Issue;
            $data['Date_of_expiry'] = $Date_of_expiry;
            $data['MRZ'] = $MRZ;
            $data['table'] = $table;
            $data['files'] = $filenamesArray;
            $data['visa_id'] = $id;
            foreach ($documents_needed as $mapp) {
                // $data['doc1'][] = $mapp->document_name;
                if ($arrayData['document_name'] == $mapp->document_name) {
                    $data['doc'][$mapp->document_name] = $arrayData['document_file'];
                } else {
                    $data['doc'][$mapp->document_name] = '';
                }
            }




            $processedDataArray[] = $data;
        }

        //     $data['final_json']=$processedDataArray;

        // $data_final = array($commonVisaData, $processedDataArray);

        // $data_final = array_merge($commonVisaData,$data['final_json']);
        $data['final_json'] = $processedDataArray;

        $data_final = [
            'commonVisaData' => $commonVisaData,
            'processedDataArray' => $data['final_json'],
            'documents_per_visa' => $documents_array
        ];
        // dd($data_final);

        $encodedData = base64_encode(serialize($processedDataArray));
        session([
            'processedDataArray' => $encodedData,
        ]);



        return view("visa.document_list_visa", $data_final);
        // return view("visa.document_list_visa", ['data_final' => $data_final]);

    }


    public function index()
    {
        $data['country'] = DB::table('country_info')->get();

        return view("Dynamic_form.index", $data);
    }

    public function store(Request $request)
    {
        // dd($request);
        try {
            $id = FormData::create([
                'country' => $request->country,
                'field_name' => $request->field_name,
                'is_dropdown' => $request->is_dropdown,
                'is_required' => $request->is_required,
                'default_values' => $request->default_values
            ])->id;
            $user = FormData::find($id);
            $data['country'] = DB::table('country_info')->get();

            return redirect('/form_fields');
        } catch (\Expection $e) {
            // dd($e);
            return view("layout.500");
        }
    }

    public function getStatus(Request $request)
    {
        // dd($ids);
        FormData::whereIn('id', $request->input('selectedIds'))->update(['is_active' => 1]);
        $message = 'Fields status retrieved successfully';
        return response()->json(['message' => $message]);
    }

    public function getStatus_enable(Request $request)
    {
        // dd($ids);
        FormData::whereIn('id', $request->input('selectedIds'))->update(['is_active' => 0]);
        $message = 'Fields status retrieved successfully';
        return response()->json(['message' => $message]);
    }


    public function storeFiles($request, $id)
    {
        try {
            $filesSaved = [];

            foreach ($request->allFiles() as $key => $file) {
                // Split the key to extract information
                $parts = explode('file-upload-', $key);

                if (count($parts) === 2) { // Assuming key format is 'file-upload-X'
                    $documentName = $parts[1]; // Extract the document name
                    $fileName = $file->getClientOriginalName(); // Get the original file name

                    // Generate a new file name
                    $randomString = Str::random(6);
                    $newFileName = 'VISA_' . $id . '_' . $randomString . '.' . $file->getClientOriginalExtension();

                    // Store the file in the storage/app/public directory with the new filename
                    $path = $file->storeAs('public/documents/' . $id . '/', $fileName);

                    // Save information about the uploaded file in your database or use it as needed
                    $filesSaved[] = [
                        'originalName' => $fileName,
                        'newFileName' => $newFileName,
                        'path' => $path,
                        'visa_id' => $id,
                        'documentName' => $documentName,
                    ];
                }
            }

            // You can return or use $filesSaved as needed

            return $filesSaved;
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
