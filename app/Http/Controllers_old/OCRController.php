<?php

namespace App\Http\Controllers;

use App\Models\Applications;
use GuzzleHttp\Client;
use Carbon\Carbon;
use DB;

class OCRController extends Controller
{
    // public function yourControllerFunction($img_url)
    // {

    //     $image_url = $img_url;
    //     // $ocr_model_id = "37cd19a1-7a2a-4406-ac18-39703aec03dc";

    //     // $new_url = 'https://app.nanonets.com/api/v2/OCR/Model/' . $ocr_model_id;
    //     $url = 'https://carsinafrica.in/backend_2_0/api/yourControllerFunction';

    //     // $api_key = 'b84bc39c-2232-11ee-a9e9-d2d8ad94c5df'; // Replace with your API key
    //     // $base_url = 'https://app.nanonets.com/api/v2/OCR/Model/';
    //     // $url = $base_url . $ocr_model_id . '/LabelUrls/';

    //     // $username = $api_key; // Replace with your API username
    //     // $password = $api_key; // Replace with your API password

    //     $postData = [
    //         'urls' => $image_url, // Replace with your POST data
    //         // Add any additional POST data as needed
    //     ];

    //     // $client = new Client();

    //     $client = new Client([
    //         'verify' => 'C:/wamp64/www/visaDone/cacert.pem', // Update the path to the cacert.pem file
    //     ]);

    //     try {
    //         $response = $client->request('POST', $url, [
    //             // 'auth' => [$username, $password],
    //             // 'headers' => [
    //                 'Content-Type' => 'application/json',
    //             //     // Add any additional headers you need
    //             // ],
    //             'form_params' => $postData,
    //         ]);

    //         echo "<pre>"; print_r($response); exit;
    //         return response()->json($response);
    //         // Handle the response data as needed
    //     } catch (\Exception $e) {
    //         // Handle the error if the request fails
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }use GuzzleHttp\Client;

    public function yourControllerFunction($img_url)
    {
        $image_url = $img_url;

        $ocr_model_id = "dcc24153-7930-41d9-874b-03d5879a562a";
        $api_key = 'a534d407-222b-11ee-9be8-f20ad52326ad'; // Replace with your API key

        $base_url = 'https://app.nanonets.com/api/v2/OCR/Model/';
        $url = $base_url . $ocr_model_id . '/LabelUrls/';

        $username = $api_key; // Replace with your API username
        $password = $api_key; // Replace with your API password

        $client = new \GuzzleHttp\Client([
            'verify' => 'C:/wamp64/www/visaDone/cacert.pem', // Update the path to the cacert.pem file
        ]);

        $payload = [
            'urls' => $image_url,
        ];


        // Image using URL
        // try {
        //     $response = $client->request('POST', $url, [
        //         'auth' => [$username, $password],  // Basic Authentication
        //         'headers' => [
        //             'Accept' => 'application/x-www-form-urlencoded',
        //             'Content-Type' => 'application/x-www-form-urlencoded',
        //             // Add any additional headers you need
        //         ],
        //         'form_params' => $payload,  // Send the payload as x-www-form-urlencoded data
        //     ]);

        //     $responseData = json_decode($response->getBody(), true);
        //     return response()->json($responseData);
        //     // Handle the response data as needed
        // } catch (\Exception $e) {
        //     // Handle the error if the request fails
        //     return response()->json(['error' => $e->getMessage()], 500);
        // }


        //Image using path from download
        $imageFilePath = 'D:/Aditya.Yadav/Downloads/' . $img_url;
        try {
            $response = $client->request('POST', $url, [
                'auth' => [$api_key, $api_key], // Basic Authentication
                'headers' => [
                    'Accept' => 'application/json',
                    // Add any additional headers you need
                ],
                'multipart' => [
                    [
                        'name' => 'file',  // Update the name to 'file'
                        'contents' => file_get_contents($imageFilePath),
                        'filename' => 'image.jpg', // Change this to your desired filename
                    ],
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);
            // dd($responseData);
            return $responseData;
            // Handle the response data as needed
        } catch (\Exception $e) {
            // Handle the error if the request fails
            dd($e);
            return ['error' => $e->getMessage()];
        }
    }

    public function OCR_data_response($id, $response,$filename,$visa_id)
    {
        $document_data = DB::table('document_rule_data')->where('offer_id', 'LIKE', '%' . $visa_id . '%')->get();

        $data = json_decode($response);
        // dd($data);
        if ($data === null) {
            $error = json_last_error();
            // Handle the error based on the value of $error
            dd(gettype(json_last_error()));
        }

        $response = str_replace("\n", '', $response);

        // Now you can decode the cleaned JSON string

        // dd($response);
        $responseData = json_decode($response, true);
        // dd($responseData);

        $JSON_final = [];

        $code = null;
        $passport = null;
        $surname = null;
        $firstname = null;
        $nationality = null;
        $sex = null;
        $date_of_birth = null;
        $place_of_birth = null;
        $authority = null;
        $date_of_issue = null;
        $date_of_expire = null;
        $MRZ = null;


        foreach ($responseData['result'][0]['prediction'] as $prediction) {
        // foreach ($responseData['result'][0]['prediction'] as $prediction) {
            $predictionData = $prediction['label'];
            $predictionData_value = $prediction['ocr_text'];
            $predictionData_value = stripslashes($predictionData_value);


            $JSON_final['data'][] = [
                $predictionData => $predictionData_value,
            ];

            if ($predictionData === 'Code') {
                $code = $predictionData_value;
            } else if ($predictionData === 'Passport_Number') {
                $passport = $predictionData_value;
            } else if ($predictionData === 'Surname') {
                $surname = $predictionData_value;
            } else if ($predictionData === 'First_Name') {
                $firstname = $predictionData_value;
            } else if ($predictionData === 'Nationality') {
                $nationality = $predictionData_value;
            } else if ($predictionData === 'Sex') {
                $sex = $predictionData_value;
            } else if ($predictionData === 'Date_of_Birth') {
                $date_of_birth = $predictionData_value;
            } else if ($predictionData === 'Authority') {
                $authority = $predictionData_value;
            } else if ($predictionData === 'Date_of_Issue') {
                $date_of_issue = $predictionData_value;
            } else if ($predictionData === 'Date_of_expiry') {
                $date_of_expire = $predictionData_value;
            } else if ($predictionData === 'MRZ') {
                $MRZ = $predictionData_value;
            } else if ($predictionData === 'Place_of_birth') {
                $place_of_birth = $predictionData_value;
            }


        }
        $JSON_final['document_name'] = $document_data[0]->document_name;
        $JSON_final['document_file'] = $filename;
        $JSON_final[$document_data[0]->document_name] = $filename;
        $jsonData = json_encode($JSON_final);

        // $checkpoint = Applications::where('passport_number', $passport)->take(1)->get();
        // if ($checkpoint) {
        //     $JSON_final['previous']= $checkpoint;
        // } else {
        Applications::create([
            'visa_id' => $id,
            'code' => $code,
            'passport_number' => $passport,
            'surname' => $surname,
            'firstname' => $firstname,
            'nationality' => $nationality,
            'sex' => $sex,
            'date_of_birth' => $date_of_birth,
            'place_of_birth' => $place_of_birth,
            'authority' => $authority,
            'date_of_issue' => $date_of_issue,
            'date_of_expire' => $date_of_expire,
            'MRZ' => $MRZ,
            'application_JSON' => $jsonData,

        ]);

        // Encode the $JSON_final array as a JSON string without white spaces
        $jsonResponse = json_encode($JSON_final, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // Check if JSON encoding was successful
        if ($jsonResponse === false) {
            // Handle the JSON encoding error here
            return response()->json(['error' => 'Invalid JSON data']);
        }

        // Return the JSON response
        // dd($jsonResponse);
        return response()->json($jsonResponse);
    }

    public function yourControllerFunction_response($img_url)
    {
        $image_url = $img_url;
        $ocr_model_id = "dcc24153-7930-41d9-874b-03d5879a562a";
        $api_key = 'a534d407-222b-11ee-9be8-f20ad52326ad'; // Replace with your API key

        $base_url = 'https://app.nanonets.com/api/v2/OCR/Model/';
        $url = $base_url . $ocr_model_id . '/LabelUrls/';

        $username = $api_key; // Replace with your API username
        $password = $api_key; // Replace with your API password

        $client = new \GuzzleHttp\Client([
            'verify' => 'C:/wamp64/www/visaDone/cacert.pem', // Update the path to the cacert.pem file
        ]);

        $payload = [
            'urls' => $image_url,
        ];

        try {
            $response = $client->request('POST', $url, [
                'auth' => [$username, $password],  // Basic Authentication
                'headers' => [
                    'Accept' => 'application/x-www-form-urlencoded',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    // Add any additional headers you need
                ],
                'form_params' => $payload,  // Send the payload as x-www-form-urlencoded data
            ]);

            $responseData = json_decode($response->getBody(), true);
            return response()->json($responseData);
            // Handle the response data as needed
        } catch (\Exception $e) {
            // Handle the error if the request fails
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
