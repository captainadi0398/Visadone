<?php

namespace App\Http\Controllers;

use App\Models\DocumentRules;
use App\Models\Users;
use App\Models\VisaOffers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class FrontEndController extends Controller
{
    //User Register
    public function user_register(Request $request)
    {
        try {
            $messages = [
                'email.unique' => 'User already exists.',
                'mobile_number.unique' => 'User already exists.',
            ];
            $validation = Validator::make($request->all(), [
                'mobno' => 'required',
                'password' => 'required|same:confirm_password',
                'email' => 'required|unique:users,email',
                'first_name' => 'required',
                'last_name' => 'required',
            ], $messages);

            $errors = $validation->errors();

            if (count($errors) > 0) {
                $data['success'] = "0";
                $data['message'] = implode(", ", $errors->all());
                $data['userinfo'] = "";

            } else {

                $id = Users::create(['first_name' => $request->first_name . " " . $request->last_name, 'email' => $request->email, 'password' => bcrypt($request->password), 'user_type' => 'C', 'status' => 1])->id;
                $user = Users::find($id);

                // $user->login_status = 1;
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->mobile_number = $request->mobno;
                $user->Country_of_residences = $request->country;
                $user->country = $request->country;
                $user->api_token = bin2hex(random_bytes(60));
                $user->status = 1;
                $user->save();
                $data['success'] = "1";
                $data['message'] = "You have Registered Successfully!";
                $data['userinfo'] = array('user_id' => "$user->id", 'api_token' => $user->api_token, 'user_name' => $user->first_name . " " . $user->last_name, 'mobno' => $user->mobile_number, 'emailid' => $user->email, 'country' => "$user->country", 'password' => $user->password, 'status' => "$user->status", 'timestamp' => date('Y-m-d H:i:s', strtotime($user->created_at)), 'Country of Residences' => $user->country);

            }

            $data1 = array('name' => "Cars In Africa");
            $message = $data['message'];
            $to = $request->emailid;
            $name = $request->first_name . $request->last_name;

            //For Sending Mail After register.
            // $obj = new MailController();
            // $obj->basic_email($to, $name, $message);

            return response()->json($data);

        } catch (\Exception $e) {
            //if Exception Happens 500 Error.
            $data['success'] = "0";
            $data['message'] = "Some Thing Went Wrong Try Again";
            $data['userinfo'] = "null";
            return response()->json($data);
        }

    }

    public function user_login(Request $request)
    {
        $credentials = $request->only('mobile_number', 'password');

        if (auth()->attempt($credentials)) {
            // Authentication Passed
            $user = auth()->user(); // Retrieve the authenticated user

            $data['success'] = true;
            $data['message'] = "You have signed in successfully!";
            $data['userinfo'] = [
                "user_id" => $user->id,
                "api_token" => $user->api_token,
                "user_name" => $user->first_name,
                "user_type" => $user->user_type,
                "mobno" => $user->mobile_number,
                "emailid" => $user->email,
                "gender" => $user->gender,
                "type" => $user->user_type,
                "status" => $user->status,
                "timestamp" => $user->created_at->format('Y-m-d H:i:s'),
            ];
        } else {
            // Authentication failed
            $data['success'] = false;
            $data['message'] = "Invalid login credentials";
            $data['userinfo'] = null;
        }

        return response()->json($data);
    }

    public function getOffer_view($nationality, $destination)
    {
        try {

            // $data['success'] = "1";
            // $data['message'] = "Offers List";
            // $data['offerinfo'] = $data['offers'];
            $data = VisaOffers::where('nationality', 'LIKE', '%' . $nationality . '%')
                ->where('destination', 'LIKE', '%' . $destination . '%')
                ->where('entry_fees','single_entry')
                ->get();

        } catch (\Exception $e) {
            // dd($e);
            $data['success'] = "0";
            $data['message'] = "internal Server Error";
            $data['offerinfo'] = [];
        }

        return response()->json($data);
    }

    public function getOffer_documentrules($id)
    {
        try {

            $data = DocumentRules::where('offer_id', 'like', '%' . $id . '%')->get();

        } catch (\Exception $e) {
            // dd($e);
            $data['success'] = "0";
            $data['message'] = "internal Server Error";
            $data['offerinfo'] = [];
        }

        return response()->json($data);
    }

    public function getDocuments($id)
    {
        try {
            $data = DocumentRules::where('offer_id', 'like', '%' . $id . '%')->get();

        } catch (\Exception $e) {
            $data = "Internal Server Error";
        }

        return response()->json($data);
    }

    public function store(Request $request)
    {

        try {

            $id = DocumentRules::create(['nationality' => $request->nationality,
                'destination' => $request->destination,
                'offer_id' => $request->offer_id,
                'document_type' => $request->document_type,
                'document_name' => $request0->document_name])->id;

            $user = DocumentRules::find($id);

            $user->document_needed = $request->document_mandatory;
            $user->document_description = $request->message;
            $user->visa_type = $request->document_requiredments;

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

        } catch (\Expection $e) {
            // dd($e);
            // return view("layout.500");
            $data['success'] = "0";
            $data['message'] = "Offer not Created!";
            $data['offerinfo'] = [];

        }
        return $data;
    }

    public function offerstore(Request $request)
    {
        // printf($request);
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

    public function getOffer_json($nationality, $destination)
    {
        try {
            // $data['success'] = "1";
            // $data['message'] = "Offers List";
            // $data['offerinfo'] = $data['offers'];
            $data = DocumentRules::join('visa_offer_data', 'visa_offer_data.id', '=', 'document_rule_data.offer_id')
            ->select('visa_offer_data.id as vid','document_rule_data.*')
                ->where('document_rule_data.nationality', 'LIKE', '%' . $nationality . '%')
                ->where('document_rule_data.destination', 'LIKE', '%' . $destination . '%')
                ->get();

        } catch (\Exception $e) {
            // dd($e);
            $data['success'] = "0";
            $data['message'] = "internal Server Error";
            $data['offerinfo'] = [];
        }
        // dd($data);
        return response()->json($data);
    }

    public function createJson($document_id, Request $request)
    {
        $document_offer = null;
        $jsonArray = [
            'message' => 'Success',
            'result' => [],
        ];

        $documents = DocumentRules::where('id', $document_id)->get();

        $countries = DB::table('country_info')->pluck('country_name')->toArray();

        foreach ($countries as $country) {
            // echo $country.':\n';
            $documentArray = [
                'message' => 'Success',
                'document_name' => '',
            ];

            foreach ($documents as $document) {
                $documentArray['document_name'] = $document->document_type;
                $document_offer = $document->offer_id;
            }

            // $offers = VisaOffers::whereIn('id', [3,4,20,21])->whereIn('nationality',[$country])->get();
            $offers = DB::table('visa_offer_data')
                ->whereIn('id', [$document_offer])
                ->where('nationality', 'LIKE', '%' . $country . '%')
                ->get();
            $offersArray = [];
            // dd($offers);
            foreach ($offers as $offer) {
                $offerArray = [
                    'offer_id' => $offer->id,
                    'offer_price' => $offer->entry_fees,
                    'nationality' => $offer->nationality,
                    'destination' => $offer->destination,
                    'offer_type' => $offer->visa_type,
                    'offer_category' => $offer->visa_category,
                    'country' => $country,
                ];

                $offersArray[] = $offerArray;
            }

            $documentArray[$country] = $offersArray;
            $jsonArray['result'][] = $documentArray;
        }

        return response()->json($jsonArray);


    }

    public function createnationJson($destination){
        $data = DB::table('visa_offer_data')->where('destination',$destination)->get();
        return response()->json($data);
    }

    public function getTaxJson(Request $request)
    {
        try {

            $ip = '49.35.41.195'; //For static IP address get
            //$ip = request()->ip(); //Dynamic IP address get
            $data = \Location::get($ip);

            $country = $data->countryName;
            $jsonArray = [
                'message' => 'Success',
                'result' => [],
            ];

            $taxData = DB::table('tax_data')->where('country_name', $country)->get();

            $offersArray = [];
            foreach ($taxData as $offer) {
                $offerArray = [
                    'country' => $offer->country_name,
                    'Key' => $offer->tax_key,
                    'Value' => $offer->tax_value,
                ];

                $offersArray[] = $offerArray;
            }

            $documentArray = $offersArray;
            $jsonArray['result'] = $documentArray;
        } catch (\Exception $e) {
            // dd($e);
            $jsonArray = [
                'message' => 'error',
                'result' => 'something went wrong',
            ];
        }

        return response()->json($jsonArray);
    }



    public function getbranches($country){
        $data = DB::table('branches')->where('country_name',$country)->get();
        return response()->json($data);
    }

    public function getagency($country,$branch){
        $data = DB::table('agency_data')->where('country',$country)->orWhere('branch_name',$branch)->get();
        return response()->json($data);
    }
}
